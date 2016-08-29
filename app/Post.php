<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
	protected $fillable = ['user_id', 'title', 'text'];

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}
}
