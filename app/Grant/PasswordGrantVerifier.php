<?php

namespace App\Grant;

use App\User;
use Illuminate\Support\Facades\Auth;

class PasswordGrantVerifier
{
	public function verify($username, $password)
	{
		$model = new User();

		$credentials = [
			'api_key'    => $username,
			'api_secret' => $password,
		];

		$user = $model->where($credentials)->first();

		if (!empty($user)) {
			Auth::setUser($user);

			return $user->id;
		}

		return false;
	}
}