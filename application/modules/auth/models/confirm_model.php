<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Confirm_Model extends CI_Model {

    public function index($mname)
    {
        $this->mname=$mname;
        if ($c=$this->uri->segment(4))
        {
            $this->confirm_user($c);
        }
    }    

    function confirm_user($c)
    {
        $ar=explode('_',$c);
        if (isset($ar[1]))
        {
            $id_user=(int)substr($ar[1],13);
            $where=array('id_user'=>$id_user,'confirm'=>$c);
            $q=$this->db->select('users.status as status')
                     ->from('confirmations')
                     ->where($where)
                     ->join('users','users.id=confirmations.id_user')
                     ->limit(1)
                     ->get();
            if ($q->num_rows())
            {
                $row=$q->row_array();
                if ($this->check_user_status($row['status']))
                {
                    $where=array('id'=>$id_user);
                    $data=array('status'=>1);
                    if ($this->db->update('users',$data,$where))
                    {
                        $this->common->auth_user($where);
                        $this->tp->show_msg('Вы успешно активировали свой аккаунт','msg_ok');  
                        $this->tp->show_msg('<a href="'.URL.'/cabinet/edit">Перейдите в кабинет и отредактируйте личные данные</a>','msg_a');   
                    }
                    else
                    {
                        $this->tp->show_msg('При активации аккаунта возникла ошибка');
                    }
                }
            }
            else
            {
                $this->tp->show_msg('Ошибочная ссылка!'); 
            }
        } 
        else
        {
            $this->tp->show_msg('Ошибочная ссылка!');
        }
    }
    
    function check_user_status($s)
    {
        $r=false;
        switch ($s)
        {
            case 1: $this->tp->show_msg('Ваш аккаунт уже активирован. Используйте свой логин (e-mail) и пароль для входа'); break;  
            case 2: $r=true; break; 
            case 3: $this->tp->show_msg('Ваш аккаунт и все ваши объявления заблокированы администрацией сайта'); break;  
        }
        return $r;
    }
    
}
