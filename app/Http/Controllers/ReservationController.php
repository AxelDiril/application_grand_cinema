<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Screening;
use App\Models\Room;
use App\Models\Movie;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ReservationController extends Controller
{
    
    /**
     * Récupère la liste des réservations avec le nom de l'utilisateur,
     * le film, la date de séance, la salle et la place réservée.
     * Des filtres peuvent être appliqués par nom, identifiant de réservation,
     * date, film et salle.
     *
     * @param Request $request Les paramètres de filtres de la requête.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse La vue affichant les
     * réservations ou un retour sur la page d'accueil si l'utilisateur n'a pas le rôle requis.
     */
    public function getReservationsList(Request $request)
    {
        // Vérifie que l'utilisateur soit connecté et possède le rôle d'administrateur ou
        // d'agent de contrôle.
        $objAuthUser = Auth::user();

        if (!$objAuthUser || ($objAuthUser->role_code != 'A' && $objAuthUser->role_code != 'C')) {
            return redirect('/');
        }

        $strName = $request->query('name');
        $iReservationId = $request->query('reservation_id');
        $strDate = $request->query('screening_date');
        $iMovieId = $request->query('movie_id');
        $iRoomId = $request->query('room_id');

        $arReservations = Reservation::with([
            'user',
            'seat',
            'screening.movie',
            'screening.room'
        ]);

        // Filtre par nom de l'utilisateur
        if (!empty($strName) && $strName != "all") {
            $arReservations->whereHas('user', function ($query) use ($strName) {
                $query->where('name', 'LIKE', '%' . $strName . '%');
            });
        }

        // Filtre par identifiant de réservation
        if (!empty($iReservationId)) {
            $arReservations->where('reservation_id', $iReservationId);
        }

        // Filtre par date de séance
        if (!empty($strDate)) {
            $arReservations->whereHas('screening', function ($query) use ($strDate) {
                $query->whereDate('screening_date', $strDate);
            });
        }

        // Filtre par film
        if (!empty($iMovieId) && $iMovieId != "all") {
            $arReservations->whereHas('screening', function ($query) use ($iMovieId) {
                $query->where('movie_id', $iMovieId);
            });
        }

        // Filtre par salle
        if (!empty($iRoomId) && $iRoomId != "all") {
            $arReservations->whereHas('screening', function ($query) use ($iRoomId) {
                $query->where('room_id', $iRoomId);
            });
        }

        // Récupération des réservations après application des filtres
        $arReservations = $arReservations
            ->orderBy('reservation_id', 'asc')
            ->get();

        // Récupération des films et des salles pour les filtres
        $arMovies = Movie::get();
        $arRooms = Room::get();

        return view('pages/reservations_list', compact(
            'arReservations',
            'arMovies',
            'arRooms',
            'strName',
            'iReservationId',
            'strDate',
            'iMovieId',
            'iRoomId'
        ));
    }

    /**
     * Enregistre la réservation de l'utilisateur en base de données.
     *
     * @param Request $request Les données du formulaire.
     * @param int $screening_id Identifiant de la séance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Retourne un message de
     * confirmation à l'utilisateur ou renvoie l'utilisateur non-connecté vers la page d'accueil.
     */
    public function addReservation(Request $request, $screening_id)
    {
        // Message de confirmation par défaut.
        $strMessage = 'Votre réservation a bien été enregistrée. Vous pouvez télécharger votre billet dans la section "Mes billets".';
        $strLink = "/my_tickets";
        $strLinkMessage = "Mes billets";

        // Vérifie que l'utilisateur est connecté.
        $objAuthUser = Auth::user();

        if (!$objAuthUser) {
            return redirect('/');
        }

        // Récupère la séance et sa salle.
        $objScreening = Screening::with('room')->find($screening_id);

        if (!$objScreening) {
            return redirect('/');
        }

        // Vérifie la place sélectionnée.
        $arData = $request->validate([
            'seat_id' => 'required|integer|exists:seats,seat_id'
        ]);

        // Vérifie que la place appartient bien à la salle de la séance.
        $objSeat = Seat::where('seat_id', $arData['seat_id'])
            ->where('room_id', $objScreening->room_id)
            ->first();

        if (!$objSeat) {
            return redirect('/make_reservation/' . $screening_id);
        }

        try {
            // Démarre une transaction pour sécuriser la création de la réservation.
            $bReservationCreated = DB::transaction(function () use (
                $objScreening,
                $objSeat,
                $objAuthUser,
                $screening_id
            ) {
                // Vérifie si la place est déjà réservée pour cette séance.
                $bSeatReserved = $objScreening->reservations()
                    ->where('seat_id', $objSeat->seat_id)
                    ->exists();

                if ($bSeatReserved) {
                    return false;
                }

                // Crée la réservation.
                Reservation::create([
                    'screening_id' => $screening_id,
                    'id' => $objAuthUser->id,
                    'seat_id' => $objSeat->seat_id
                ]);

                return true;
            });

            // Si la place était déjà réservée, redirige vers le choix des places.
            if (!$bReservationCreated) {
                return redirect('/make_reservation/' . $screening_id);
            }
        } catch (QueryException $exception) {
            // Vérifie si l'erreur vient de la contrainte d'unicité.
            $bDuplicateReservation =
                ($exception->errorInfo[1] ?? null) == 1062
                || str_contains(
                    strtolower($exception->getMessage()),
                    'unique constraint failed'
                );

            // Si la place vient d'être réservée par un autre utilisateur.
            if ($bDuplicateReservation) {
                // Message d'erreur.
                $strMessage = "Cette place vient d'être réservée par quelqu'un d'autre. Veuillez choisir une autre place.";
                $strLink = '/make_reservation/' . $screening_id;
                $strLinkMessage = "Retour à la réservation";

                return view('pages/message', compact(
                    'strMessage',
                    'strLink',
                    'strLinkMessage'
                ));
            }

            // Propage les autres erreurs SQL.
            throw $exception;
        }

        return view('pages/message', compact(
            'strMessage',
            'strLink',
            'strLinkMessage'
        ));
    }

    /**
     * Affiche les réservations de l'utilisateur connecté. Il pourra y télécharger ses billets sous
     * format PDF.
     *
    * @return \Illuminate\Http\Response|\Illuminate\View\View Télécharge le billet ou renvoie
     * un message d'erreur si l'utilisateur n'est pas connecté.
     */
    public function getMyTickets()
    {
        $objAuthUser = Auth::user();

        // Vérifie que l'utilisateur est connecté
        if (!$objAuthUser) {
            $strMessage = "Vous devez vous connecter pour consulter vos réservations.";
            $strLink = "/login";
            $strLinkMessage = "Connexion";

            return view('pages/message', compact(
                'strMessage',
                'strLink',
                'strLinkMessage'
            ));
        }

        // Récupère uniquement les réservations de l'utilisateur connecté
        $arReservations = Reservation::with([
            'screening.movie',
            'screening.room'
        ])
            ->where('id', $objAuthUser->id)
            ->orderBy('reservation_id', 'desc')
            ->get();

        return view('pages/my_tickets', compact('arReservations'));
    }

    /**
     * Génère et télécharge le billet PDF correspondant à une réservation.
     *
     * @param int $reservation_id Identifiant de la réservation.
     * @return \Illuminate\Http\Response|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * Télécharge le billet, renvoie un message d'erreur si l'utilisateur n'est pas connecté ou renvoie
     * à la page d'accueil si il tente de télécharger une réservation qui n'existe pas.
     */
    public function downloadTicket($reservation_id)
    {
        // Vérifie que l'utilisateur est connecté.
        $objAuthUser = Auth::user();

        if (!$objAuthUser) {
            $strMessage = "Vous devez vous connecter pour télécharger vos billets.";
            $strLink = "/login";
            $strLinkMessage = "Connexion";

            return view('pages.message', compact(
                'strMessage',
                'strLink',
                'strLinkMessage'
            ));
        }

        // Récupère la réservation et ses relations.
        $objReservation = Reservation::with([
            'user',
            'screening.movie',
            'screening.room'
        ])->find($reservation_id);

        // Vérifie que la réservation existe
        if (!$objReservation) {
            return redirect('/');
        }

        // Génère le PDF à partir de la vue ticket.
        $objPdf = Pdf::loadView('pages.ticket', compact('objReservation'));

        // Télécharge le billet PDF.
        return $objPdf->download(
            'billet_reservation_' . $objReservation->reservation_id . '.pdf'
        );
    }
}
