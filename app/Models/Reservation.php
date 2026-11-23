<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reservation
 * 
 * @property int $reservation_id
 * @property int $id
 * @property int $screening_id
 * @property int $seat_id
 *
 * @package App\Models
 */
class Reservation extends Model
{
	protected $table = 'reservations';
	protected $primaryKey = 'reservation_id';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'screening_id' => 'int',
		'seat_id' => 'int',
	];

	protected $fillable = [
		'id',
		'screening_id',
		'seat_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id');
	}

	public function screening()
	{
		return $this->belongsTo(Screening::class, 'screening_id');
	}

	public function seat()
	{
		return $this->belongsTo(Seat::class, 'seat_id', 'seat_id');
	}
}
