<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MoviesGenre
 * 
 * @property int $movie_id
 * @property int $genre_id
 * 
 * @property Genre $genre
 * @property Movie $movie
 *
 * @package App\Models
 */
class MoviesGenre extends Model
{
	protected $table = 'movies_genres';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'movie_id' => 'int',
		'genre_id' => 'int'
	];

	public function genre()
	{
		return $this->belongsTo(Genre::class);
	}

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}
}
