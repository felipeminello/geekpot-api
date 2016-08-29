<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistration;
use App\User;
use Authorizer;
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
		$users = $this->user->get();

		return response()->json($users);
	}

	/**
	 * Display all users
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function indexAll()
	{
		$users = $this->user->withTrashed()->get();

		return response()->json($users);
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
					'error'      => false,
					'message'    => 'Usuário cadastrado',
					'api_key'    => $user->api_key,
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
		$user = $this->user->find(Authorizer::getResourceOwnerId());

		$adminRoutes = [];

		$routes = [
			['method' => 'POST', 'route' => '/api/post', 'description' => 'Create new Post', 'params' => [
				'title' => 'string',
				'text'  => 'string',
			]],
			['method' => 'GET', 'route' => '/api/post', 'description' => 'View Posts'],
			['method' => 'GET', 'route' => '/api/post/{id}', 'description' => 'View selected post'],
			['method' => 'PUT', 'route' => '/api/post/{id}', 'description' => 'Update the post', 'params' => [
				'title' => 'string',
				'text'  => 'string',
			]],
			['method' => 'DELETE', 'route' => '/api/post/{id}', 'description' => 'Destroy the post'],

			['method' => 'POST', 'route' => '/oauth/access_token', 'description' => 'Get Token', 'params' => [
				'grant_type'    => 'password',
				'client_id'     => '6e1ftdtwr80ty9zfkqzj',
				'client_secret' => '9vpczvqmkndob6doqjqa',
				'username'      => '{API_KEY}',
				'password'      => '{API_SECRET}',
			]],

			['method' => 'POST', 'route' => '/oauth/access_token', 'description' => 'Get Refresh Token', 'params' => [
				'grant_type'    => 'refresh_token',
				'client_id'     => '6e1ftdtwr80ty9zfkqzj',
				'client_secret' => '9vpczvqmkndob6doqjqa',
				'refresh_token' => '{REFRESH_TOKEN}',
			]],
		];

		// Se usuário logado for admin
		if ($user->access_type == 1) {
			$adminRoutes = [
				['method' => 'GET', 'route' => '/api/user', 'description' => 'View Users'],
				['method' => 'GET', 'route' => '/api/user/all', 'description' => 'View active Users and deleted users'],
				['method' => 'GET', 'route' => '/api/user/{id}', 'description' => 'View selected user'],
				['method' => 'PUT', 'route' => '/api/user/{id}', 'description' => 'Update the user', 'params' => [
					'email' => 'string',
					'password'  => 'string',
					'api_key'  => 'string',
					'api_secret'  => 'string',
				]],
				['method' => 'DELETE', 'route' => '/api/user/{id}', 'description' => 'Destroy the user'],
			];
		}

		$result = array_merge($routes, $adminRoutes);

		return response()->json($result);
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
		$user = $this->user->find($id);

		if (empty($user)) {
			return response()->json(['error' => 'User not found']);
		}

		return response()->json($user);
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
		$data = $request->only('email', 'password', 'api_key', 'api_secret');

		$user = $this->user->find($id);

		if (empty($user)) {
			return response()->json(['error' => 'User not found']);
		}

		try {
			$this->validate($request, [
				'email'      => 'required|email|unique:users,email,'.$user->id.'|max:255',
				'password'   => 'required|min:3|max:8',
				'api_key'    => 'required',
				'api_secret' => 'required',
			]);

			$data['password'] = Hash::make($data['password']);

			$user->update($data);

			return response()->json(
				[
					'message' => 'Usuário atualizado',
					'user'    => $user
				]);
		} catch (ValidationException $e) {
			var_dump($e->getTraceAsString());
			return response()->json(
				[
					'error' => $e->getMessage()
				]);
		}
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
		try {
			$this->user->where(
				['id' => $id]
			)->delete();

			return response()->json(
				[
					'error' => false,
					'message' => 'Usuário excluído com sucesso',
				]);
		} catch (ValidationException $e) {
			return response()->json(
				[
					'error' => $e->getMessage()
				]);
		}
	}
}
