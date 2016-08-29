<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
	public function create()
	{
		return view('cadastro');
	}

	public function store(Request $request)
	{
		$data = $request->only('email', 'password');

		return view('cadastro');
	}
}
