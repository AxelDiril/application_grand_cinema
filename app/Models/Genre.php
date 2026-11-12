<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Genre
 * 
 * @property int $genre_id
 * @property string $genre_label
 * 
 * @property Collection|Movie[] $movies
 *
 * @package App\Models
 */
class Genre extends Model
{
	protected $table = 'genres';
	protected $primaryKey = 'genre_id';
	public $timestamps = false;

	protected $fillable = [
		'genre_label'
	];

	public function movies()
	{
		return $this->belongsToMany(Movie::class, 'movies_genres');
	}
}
