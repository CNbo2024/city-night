<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
	protected $table = 'promotions';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
		'code',
		'discount'
	];
}