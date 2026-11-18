<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Movie
 * 
 * @property int $movie_id
 * @property string $movie_name
 * @property string $director
 * @property int $duration_minutes
 * @property string|null $description
 * 
 * @property Collection|Genre[] $genres
 *
 * @package App\Models
 */
class Movie extends Model
{
	protected $table = 'movies';
	protected $primaryKey = 'movie_id';
	public $timestamps = false;

	protected $casts = [
		'duration_minutes' => 'int'
	];

	protected $fillable = [
		'movie_name',
		'director',
		'duration_minutes',
		'description'
	];

	public function genres()
	{
		return $this->belongsToMany(
			Genre::class,
			'movies_genres',
			'movie_id',
			'genre_id',
			'movie_id',
			'genre_id'
		);
	}
}
