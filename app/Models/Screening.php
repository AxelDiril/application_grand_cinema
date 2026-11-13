<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Screening
 * 
 * @property int $screening_id
 * @property Carbon $screening_date
 * @property int $room_id
 * @property int $movie_id
 *
 * @package App\Models
 */
class Screening extends Model
{
	protected $table = 'screenings';
	protected $primaryKey = 'screening_id';
	public $timestamps = false;

	protected $casts = [
		'screening_date' => 'datetime',
		'room_id' => 'int',
		'movie_id' => 'int'
	];

	protected $fillable = [
		'screening_date',
		'room_id',
		'movie_id'
	];

	public function movie(){
		return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
	}
}
