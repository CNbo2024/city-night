<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
	protected $table = 'tips';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
		'title',
		'content'
	];
}