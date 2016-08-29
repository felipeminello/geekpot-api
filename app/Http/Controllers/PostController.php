<?php

namespace App\Http\Controllers;

use App\Post;
use Authorizer;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
	/**
	 * @var Post
	 */
	private $post;

	public function __construct(Post $post)
	{
		$this->post = $post;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$userId = Authorizer::getResourceOwnerId();

		$posts = $this->post->where(['user_id' => $userId])->get();

		return response()->json(
			[
				'posts' => $posts
			]);
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
		$userId = Authorizer::getResourceOwnerId();

		$data = $request->only('title', 'text');

		try {
			$this->validate($request, [
				'title'    => 'required|max:150',
				'text' => 'required',
			]);

			$data['user_id'] = $userId;

			$this->post->create($data);

			return response()->json(
				[
					'error' => false,
					'message' => 'Post Criado com sucesso',
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
		$userId = Authorizer::getResourceOwnerId();

		try {
			$post = $this->post->where(
				['user_id' => $userId, 'id' => $id]
			)->first();

			return response()->json(
				[
					'error' => false,
					'post' => $post,
				]);
		} catch (ValidationException $e) {
			return response()->json(
				[
					'error' => $e->getMessage()
				]);
		}

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
		$userId = Authorizer::getResourceOwnerId();

		$data = $request->only('title', 'text');

		try {
			$this->validate($request, [
				'title'    => 'required|max:150',
				'text' => 'required',
			]);

			$data['user_id'] = $userId;

			$this->post->where(
				['user_id' => $userId, 'id' => $id]
			)->update($data);

			return response()->json(
				[
					'error' => false,
					'message' => 'Post Atualizado com sucesso',
				]);
		} catch (ValidationException $e) {
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
		$userId = Authorizer::getResourceOwnerId();

		try {
			$this->post->where(
				['user_id' => $userId, 'id' => $id]
			)->delete();

			return response()->json(
				[
					'error' => false,
					'message' => 'Post Excluído com sucesso',
				]);
		} catch (ValidationException $e) {
			return response()->json(
				[
					'error' => $e->getMessage()
				]);
		}
	}
}
