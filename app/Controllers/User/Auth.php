<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\AuthModel;

class Auth extends BaseController
{
	protected $model;

	public function __construct()
	{
		$this->model   = new AuthModel;
		$this->session = \Config\Services::session();
	}
	public function index()
	{
		return view_blade('user.auth');
	}
	public function forgot_password()
	{
		return view_blade('user.forgot');
	}
	public function reset_password()
	{
		return view_blade('user.reset_password',['email'=>$_GET['email'], 'token'=>$_GET['token']]);
	}
	public function check_validation()
	{
		$requestEmail    = $this->request->getPost('email');
		$requestPassword = sha1($this->request->getPost('password'));
		$data            = $this->model->check_validation($requestEmail, $requestPassword);
		echo json_encode($data);
	}
	public function update_password()
	{
		$requestEmail    = $this->request->getPost('email');
		$requestToken    = $this->request->getPost('token');
		$requestPassword = sha1($this->request->getPost('password'));
		$data            = $this->model->update_password($requestEmail,$requestToken, $requestPassword);
		echo json_encode($data);
	}

	public function logout()
	{
		$this->session->destroy();
		return redirect()->to(base_url('auth'));
	}

	//--------------------------------------------------------------------

}