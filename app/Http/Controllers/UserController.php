<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
	public function getSignup() {
		return view('user.signup');
	}

	public function postSignup(Request $request) {
		$this->validate($request, [
			'email' => 'email|required|unique:users',
			'password' => 'required|min:4'
		]);

		$user = new User([
			'email' => $request->input('email'),
			'password' => bcrypt($request->input('password'))
		]);

		$user->save();

		Auth::login($user);

		return redirect()->route('user.profile');
	}

	public function getSignin() {
		return view('user.signin');
	}

	public function postSignin(Request $request) {
		$this->validate($request, [
			'email' => 'email|required',
			'password' => 'required|min:4'
		]);

		if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
			return redirect()->route('user.profile');
		} else {
			return redirect()->back()->with('error', 'Wrong email or password');
		}
	}

	public function getProfile() {
		return view('user.profile');
	}

	public function getLogout() {
		Auth::logout();

		return redirect()->route('user.signin');
	}
}
