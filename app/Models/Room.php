<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Room
 * 
 * @property int $room_id
 * @property string $room_name
 *
 * @package App\Models
 */
class Room extends Model
{
	protected $table = 'rooms';
	protected $primaryKey = 'room_id';
	public $timestamps = false;
	protected $fillable = [
		'room_name'
	];

	public function seats()
	{
		return $this->hasMany(Seat::class, 'room_id', 'room_id');
	}
}
