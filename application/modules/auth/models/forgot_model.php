<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot_Model extends CI_Model {

    public function index($mname)
    {
        $this->mname=$mname;
        $this->check_post();
    }   
    
    function check_post()
    {
        if ($email=mb_strtolower($this->input->post('user_email',true),'UTF-8'))  // esli uje postnuli e-mail
        {
            if (@$_SESSION['forgot_email']!=$email) // 4tob ne zaprashival po 100 raz otpravku pis'ma
            {
                $where=array('email'=>$email,'password !='=>'no','status'=>1);
                $q=$this->db->get_where('users',$where,1);
                if ($q->num_rows())
                {
                    $row=$q->row_array();
                    if ($this->common->send('forgot_password',$row['email'],$row['password'])) // otpravlyaet pismo sklirozniku
                    {
                        $this->tp->show_msg('Письмо с напоминанием пароля успешно отправлено на ваш e-mail','msg_ok');
                        $_SESSION['forgot_email']=$email;
                    }
                    else
                    {
                        $this->tp->show_msg('Произошла ошибка с отправкой письма на ваш e-mail');
                    }
                } 
                else
                {
                    $this->tp->show_msg('Зарегистрированного пользователся с таким e-mail-ом не найдено');
                }   
            }
            else
            {
                $this->tp->show_msg('Мы уже отправили письмо на указанный e-mail'); 
            }
        }
        else
        {
            $a['mail_submodule']='forgot';
            $a['mail_message']='Введите сюда свой e-mail. На него будет отправлено письмо с вашим паролем';
            $this->tp->assign($a);
            $this->tp->parse('REGISTER_FORM',$this->mname.'/forgot_form.tpl');
        }
    } 
}
