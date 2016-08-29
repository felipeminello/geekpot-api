<?php

namespace App\Http\Middleware;

use App\User;
use Authorizer;
use Closure;

class CheckAdmin
{
	/**
	 * @var User
	 */
	private $user;

	public function __construct(User $user) {
		$this->user = $user;
	}

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$user = $this->user->find(Authorizer::getResourceOwnerId());

		$accessType = $user->access_type;

		if ($accessType == 1)
			return $next($request);

        return response()->json('Access Denied', 403);
    }
}
