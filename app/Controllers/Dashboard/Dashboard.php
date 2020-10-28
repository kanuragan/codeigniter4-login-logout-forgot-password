<?php namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Dashboard extends BaseController
{
	
	public function __construct()
	{
		$this->permission = new PermissionModel;
	}

	public function index()
	{
		$data = array(
			'name_app' => 'VVP',
			'heading'  => 'Dashboard',
			'cek_m'    => $this->permission,
		);

		return view_blade('dashboard.dashboard', $data);
	}
	//--------------------------------------------------------------------

}
