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
 * @property int $seats_quantity
 * @property int $id
 * @property int $screening_id
 *
 * @package App\Models
 */
class Reservation extends Model
{
	protected $table = 'reservations';
	protected $primaryKey = 'reservation_id';
	public $timestamps = false;

	protected $casts = [
		'seats_quantity' => 'int',
		'id' => 'int',
		'screening_id' => 'int'
	];

	protected $fillable = [
		'seats_quantity',
		'id',
		'screening_id'
	];
}
