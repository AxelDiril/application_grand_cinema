# application_grand_cinema
Application de consultation de séances et de réservation de places pour le Grand Cinéma.
L'application est testable à cette adresse : https://application-grand-cinema-production.laravel.cloud/

# Convention de nommage des branches 
- `main` : branche contenant les versions stables du projet prêtes à être déployées.
- `develop` : branche principale de développement.
- `feature/nom-fonctionnalite` : branche dédiée au développement d'une nouvelle fonctionnalité.
- `fix/nom-correction` : branche dédiée à la correction d'un bug, amélioration de la qualité du code ou des tests.

Les noms des branches sont écrits en minuscules et les mots sont séparés par des tirets.

Les branches `feature` et `fix` sont créées à partir de `develop`, puis fusionnées dans `develop` une fois le travail terminé.

# Guide d'installation
* Importer le script `application_grand_cinema.sql` dans votre base de données
* A la racine du projet, ouvrir le terminal
* Ecrire `composer install` pour installer les dépendances de PHP
* Ecrire `npm install` puis `npm run build` pour installer les paquets de Node.js
* Renommer le fichier `.env.example` en `.env` (afficher les fichiers cachés si il n'est pas visibile)
* Dans le fichier `.env`, aller à cette section  :
```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
* Remplir ces lignes avec les informations de votre base de données ainsi que le préfixe de vos tables
* Pour démarrer le projet, écrire `php artisan serve` dans le terminal