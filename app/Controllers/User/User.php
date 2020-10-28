<?php namespace App\Controllers\User;

use App\Controllers\BaseController;

class User extends BaseController
{
	public function index()
	{
		return view_blade('user.user', ['nama'=>'<script>alert("jancok")</script>']);
	}

	public function register()
	{
		return view_blade('user.register',['nama'=>'ini register']);
	}

	//--------------------------------------------------------------------

}
