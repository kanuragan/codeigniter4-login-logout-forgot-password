<?php namespace App\Models\User;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table         = 'user';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['email', 'token', 'expired'];

    function check_validation($requestEmail, $requestPassword)
    {
        $session = \Config\Services::session();
        $cek_user = $this->table('user')
                            ->select('*')
                            ->where(['email'=>$requestEmail,'password'=>$requestPassword])
                            ->countAllResults();
        if($cek_user > 0)
        {
            $builder   = $this->table('user');
            $data_user = $builder->getWhere(['email'=>$requestEmail,'password'=>$requestPassword])->getRow();

            $newdata = [
                'user_id'   => $data_user->id,
                'level_id'  => $data_user->level_id,
                'email'     => $data_user->email,
                'logged_in' => TRUE
            ];
            
            $session->set($newdata);

            return [
                'response' => 'success',
                'msg'      => $data_user->email,
                'url'      => base_url('dashboard'),
            ];
        } else {
            return [
                'response' => 'failed',
                'msg'      => 'data tidak ditemukan',
            ];
        }
    }
    function justToken($email_to)
    {
        $token  = sha1(date('Y-m-d H:i:s'));
        $date   = date('Y-m-d');
        $time   = time() + 60*60;

        //cek email di database
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
    }
    function update_password($requestEmail, $requestToken, $requestPassword)
    {
        $db  = db_connect();
        $cek_token = $db->table('account_verify')
                        ->select('*')
                        ->where(['email'=>$requestEmail,'token'=>$requestToken]);
        if($cek_token->countAllResults() > 0)
        {
            if($cek_token->get()->getRow()->expired < date('Y-m-d H:i:s'))
            {
                return [
                    'response' => 'failed',
                    'msg'      => 'token tidak valid, silahkan resend token',
                ];
            } else {
                $builder = $db->table('user');
                $builder->where('email',$requestEmail);
                $builder->set('password',$requestPassword);
                $update = $builder->update();
                if($update)
                {
                    $builder2 = $db->table('account_verify');
                    $builder2->where('email',$requestEmail);
                    $builder2->delete();
                    return [
                        'response' => 'success',
                        'msg'      => 'berhasil reset password',
                        'url'      => base_url('auth')
                    ];
                } else {
                    return [
                        'response' => 'failed',
                        'msg'      => 'token tidak valid, silahkan resend token',
                    ];
                }
            }
        } else {
            return [
                'response' => 'failed',
                'msg'      => 'token tidak valid, silahkan resend token',
            ];
        }
    }
}