<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegistration extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * Create a new message instance.
	 *
	 * @param User $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->from('felipeminello@gmail.com')
					->view('emails.user-register')
					->with([
							'email' => $this->user->email
						   ]);
	}
}
