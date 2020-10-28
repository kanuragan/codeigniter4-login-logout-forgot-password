<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\User\EmailModel;

class Email extends BaseController
{
	protected $model;

	public function __construct()
	{
		$this->model   = new EmailModel;
	}
	public function send_verification()
	{
		$this->email = \Config\Services::email();
		$email_to = $this->request->getPost('email');
		$token    = $this->model->justToken($email_to);

		if($token === FALSE)
		{
			$data = [
				'response' => 'failed',
				'msg'	   => 'email tidak valid'
			];
		} else {
			$body     = "<html>";
			$body    .= "<body>";
			$body    .= "<h3>VVP BitCoin</h3>";
			$body    .= "<p>Akun anda baru saja meminta untuk Reset Password. Klik link Dibawah ini untuk melanjutkan</p> <br><br>";
			$body    .= "<a href='". base_url('user/auth/reset_password?email='.$email_to.'&token='.$token) ."'> Klik Disini</a>";

			//data
			$this->email->setFrom('huda.alfarizi.it@gmail.com', 'Huda');
			$this->email->setTo($email_to);
			$this->email->setSubject('Reset Password');
			$this->email->setMessage($body);
			if(!$this->email->send())
			{
				$data = [
					'response' => 'failed',
					'msg'	   => 'gagal kirim'
				];
			} else {
				$data = [
					'response' => 'success',
					'msg'	   => 'Silahkan Cek Email Anda',
					'url'      => base_url('user/auth'),
				];
			}
		}
		echo json_encode($data);
	}

	//--------------------------------------------------------------------

}
