<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistration;
use App\User;
use Hash;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

use App\Http\Requests;
use Mail;

class UserController extends Controller
{
	/**
	 * @var User
	 */
	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('cadastro');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$data = $request->only('email', 'password');

		try {
			$this->validate($request, [
				'email'    => 'required|email|unique:users|max:255',
				'password' => 'required|min:3|max:8',
			]);

			$data['password'] = Hash::make($data['password']);
			$data['api_key'] = str_random(20);
			$data['api_secret'] = str_random(20);

			$user = $this->user->create($data);

			Mail::to($user->email)->send(new UserRegistration($user));

			return response()->json(
				[
					'error' => false,
					'message' => 'Usuário cadastrado, dados de acesso'
				]);
		} catch (ValidationException $e) {
			return response()->json(
				[
					'error' => $e->getMessage()
				]);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int                      $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
