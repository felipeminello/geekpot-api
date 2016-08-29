<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistration;
use App\User;
use Hash;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

use App\Http\Requests;
use Mail;
use Route;

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
			$data['access_type'] = 2;
			$data['api_key'] = str_random(20);
			$data['api_secret'] = str_random(20);

			$user = $this->user->create($data);

			Mail::to($user->email)->send(new UserRegistration($user));

			return response()->json(
				[
					'error' => false,
					'message' => 'Usuário cadastrado',
					'api_key' => $user->api_key,
					'api_secret' => $user->api_secret
				]);
		} catch (ValidationException $e) {
			return response()->json(
				[
					'error' => $e->getMessage()
				]);
		}
	}

	public function lookup()
	{
		$routes = [
			['method' => 'POST', 'route' => '/api/posts', 'description' => 'Create new Post', 'params' => [
				'title' => 'string',
				'text' => 'string',
			]],
			['method' => 'GET', 'route' => '/api/posts', 'description' => 'View Posts'],
			['method' => 'GET', 'route' => '/api/posts/{id}', 'description' => 'View select post'],
			['method' => 'PUT', 'route' => '/api/posts/{id}', 'description' => 'Update the post', 'params' => [
				'title' => 'string',
				'text' => 'string',
			]],
			['method' => 'DELETE', 'route' => '/api/posts/{id}', 'description' => 'Destroy the post'],

			['method' => 'POST', 'route' => '/oauth/access_token', 'description' => 'Get Token', 'params' => [
				'grant_type' => 'password',
				'client_id' => '6e1ftdtwr80ty9zfkqzj',
				'client_secret' => '9vpczvqmkndob6doqjqa',
				'username' => '{API_KEY}',
				'password' => '{API_SECRET}',
			]],

			['method' => 'POST', 'route' => '/oauth/access_token', 'description' => 'Get Refresh Token', 'params' => [
				'grant_type' => 'refresh_token',
				'client_id' => '6e1ftdtwr80ty9zfkqzj',
				'client_secret' => '9vpczvqmkndob6doqjqa',
				'refresh_token' => '{REFRESH_TOKEN}',
			]],
		];

		return response()->json($routes);
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
