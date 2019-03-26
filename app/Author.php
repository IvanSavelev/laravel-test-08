<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
	protected $fillable = ['first_name', 'last_name'];
	protected $dates = ['deleted_at'];

    //
}
