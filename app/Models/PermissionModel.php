<?php namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model 
{
    
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db      = \Config\Database::connect();
    }
	public function cek_permission($module = '')
	{
		//cek apakah sudah login atau belum
		if($this->session->get('logged_in') == true)
		{
            $data = $this->db->table('permission')
                                ->select('*')
                                ->join('module','module.id=permission.module_id')
                                ->where('module.name',$module)
                                ->where('permission.level_id',$this->session->get('level_id'))
                                ->countAllResults();
			return $data;
		}
		
    }
    public function get_data()
    {
        return "hai";
    }
}
