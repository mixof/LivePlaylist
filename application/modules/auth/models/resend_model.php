<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resend_Model extends CI_Model {
    
    private $mname;

    public function index($mname)
    {
        $this->mname=$mname;
        $this->check_post();
         /*
        if (isset($_SESSION['resend_info']))
        {
            $a=$_SESSION['resend_info'];
            if (isset($_SESSION['resend_email']))
            {
                if ($a['resend_email']==$_SESSION['resend_email'])
                {
                    $this->tp->show_msg('Мы уже выслали вам повторно письмо. Возможно, оно попало в "СПАМ". Если письмо так и не дошло, попробуйте зарегистрироваться с другим е-мэйлом');
                }
                else
                {
                    $this->resend(); 
                } 
            }
            else
            {
                $this->resend();
            }        
        }
        else
        {
            redirect(LANG.'/auth/login');
        }
           */ 
    }
    
    function check_post()
    {
        if ($email=mb_strtolower($this->input->post('user_email',true),'UTF-8'))  // esli uje postnuli e-mail
        {
            if (@$_SESSION['resend_email']!=$email) // 4tob ne zaprashival po 100 raz otpravku pis'ma
            {
                $where=array('email'=>$email,'password !='=>'no','status'=>2);
                $q=$this->db->select('c.confirm as conf')
                            ->from('users as u')
                            ->join('confirmations as c','c.id_user = u.id')
                            ->where($where)
                            ->limit(1)
                            ->get();
                if ($q->num_rows())
                {
                    $row=$q->row_array();
                    if ($this->common->send('confirmation',$email,$row['conf'])) // otpravlyaet pismo sklirozniku
                    {
                        $this->tp->show_msg('На ваш e-mail было выслано ПОВТОРНО письмо с ссылкой для подтверждения регистрации. Перейдите по ссылке, указанной в письме, чтобы активировать ваш аккаунт','msg_ok');
                        $_SESSION['resend_email']=$email;
                    }
                    else
                    {
                        $this->tp->show_msg('Произошла ошибка с отправкой письма на ваш e-mail');
                    }
                } 
                else
                {
                    $this->tp->show_msg('Зарегистрированного пользователся с таким e-mail-ом не найдено, либо пользователь уже активировал свой аккаунт');
                }   
            }
            else
            {
                $this->tp->show_msg('Мы уже выслали вам повторно письмо. Возможно, оно попало в "СПАМ". <br>Если письмо так и не дошло, попробуйте зарегистрироваться с другим е-мэйлом :('); 
            }
        }
        else
        {
            $a['mail_submodule']='resend';
            $a['mail_message']='Введите сюда свой e-mail. На него будет отправлено письмо с подтверждением регистрации';
            $this->tp->assign($a);
            $this->tp->parse('REGISTER_FORM',$this->mname.'/forgot_form.tpl');
        }
    } 
    
    function resend()
    {
        $a=$_SESSION['resend_info'];
        $_SESSION['resend_email']=$a['resend_email']; 
        $this->tp->show_msg('На ваш e-mail было выслано ПОВТОРНО письмо с ссылкой для подтверждения регистрации. Перейдите по ссылке, указанной в письме, чтобы активировать ваш аккаунт'); 
        //$this->tp->show_msg('<a href="'.URL.'/auth/resend">Выслать письмо еще раз</a>','msg_a');
        $confirm=$this->tp->get_value('confirmations','confirm',$a['resend_id'],'id_user');
        $this->common->send('confirmation',$a['resend_email'],$confirm);
    }
    
}
