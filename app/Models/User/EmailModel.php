<?php namespace App\Models\User;

use CodeIgniter\Model;
use App\Models\User\AuthModel;

class EmailModel extends Model
{
    protected $table         = 'account_verify';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['email', 'token', 'expired'];

    

    function cek_email_user($email_to)
    {
        $db  = db_connect();
        $cek = $db->table('user')
                    ->select('*')
                    ->where('email',$email_to)
                    ->countAllResults();
        return $cek;
    }

    function justToken($email_to)
    {
        date_default_timezone_set('Asia/Jakarta');
        $token  = sha1(date('Y-m-d H:i:s'));
        $date   = date('Y-m-d');
        $time   = date("Y-m-d H:i:s", strtotime("+1 hours"));

        //cek email di table user
        if($this->cek_email_user($email_to) > 0)
        {
            //cek email di database verify
            $cek_email = $this->table('account_verify')
                                ->select('*')
                                ->where(['email'=>$email_to])
                                ->countAllResults();
            //set builder save to account verify
            $builder   = $this->table('account_verify');
            if($cek_email > 0)
            {
                $builder->set('token', $token)
                        ->set('expired', $time)
                        ->where('email', $email_to)
                        ->update();
            } else {
                $data   = array(
                'email'  => $email_to,
                'token'  => $token,
                'expired'=> $time,
                );
                $builder->insert($data);
            }

            return $token;

        } else {

            return FALSE;
        }
    }
}