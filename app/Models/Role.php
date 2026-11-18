<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * 
 * @property string $role_code
 * @property string $role_label
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';
	protected $primaryKey = 'role_code';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
		'role_code',
		'role_label'
	];

	public function users()
	{
		return $this->hasMany(User::class, 'role_code');
	}
}
