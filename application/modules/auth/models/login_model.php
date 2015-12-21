<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {
    
    private $polya, $mname, $table, $uid;
    
    function __construct()
    {
        $this->table='users';
    }

    public function index($mname)
    {
        $this->mname=$mname;
        if ($this->check_login_params())
        {
            // login
            $this->check_logining();
        }
        else
        { 
            $this->show_login_form();
            
        }
    }    
    
    function check_login_params()
    {
        if ($this->input->post('userlogin') && $this->input->post('userpass'))
        {
            return true;
        }
        else
        {
            //$this->tp->show_msg('Извините, вы не указали данные для авторизации', 'login_msg','LOGMSG');
            return false;
        }
    }
    
    function check_logining()  // proverka pri avtorizacii
    {       
            
            $this->load->helper('security');
            
            $u=$this->input->post('userlogin', TRUE);
            $p=$this->input->post('userpass', TRUE);
           
            if ($u=='admin' && $p=='123123')
            {
                echo('Здарова Админ4ег!');
            }
            else
            {
               
                if ($row=$this->is_user_exist($u))
                {
                                     
                    if ($this->check_pass($row, $p))
                    {
                       /* if ($this->check_user_status($row->status))
                        {
                            $array=array('id'=>$row->id);
                            $this->common->auth_user($array); 
                            $redirect=str_replace(SITEURL,'',$_SERVER['HTTP_REFERER']);
                            redirect($redirect);  
                            //redirect('/'.LANG.'/cabinet');  
                        } */
                        
                       
                        $this->session->set_userdata('login',$u);
                        $this->session->set_userdata('user_id',$row->user_id);
                      
                          $this->tp->show_msg('<h2>Добро Пожаловать</h2><br/>Спасибо, что зашли <span>'.$u.'</span>! <br/> Сейчас вы будете направлены в личный кабинет..<br/> Если этого не произошло то пройдите поссылке <a href="/cabinet">Личный кабинет<a/>', 'welcome_msg','WELCOME_MSG');
                          header( 'Refresh: 2; url=/cabinet' );
                    }
                    else
                    {
                        $this->tp->show_msg('Авторизация не удалась :( Неправильно указан пароль', 'login_msg','LOGMSG');
                        $this->show_login_form();
                        
                    }
                }  
                else
                {
                    $this->tp->show_msg('Авторизация не удалась :( Такого пользователя не существует!', 'login_msg','LOGMSG');
                        $this->show_login_form();
                }
            }
       
    }
    
    function show_login_form()
    {       
        $this->tp->parse('REGISTER_FORM', $this->mname.'/login_form.tpl');    
        //$this->tp->parse('REGISTER_FORM', $this->mname.'/social.tpl'); 
    }
    
    function check_pass($row, $p)
    {
        $str = do_hash($p, 'md5');         
        if ($str==$row->password)
        return TRUE;
        else
        return FALSE;
    }
    
    function is_user_exist($login)
    {
        $this->db->select('user_id, password');

        $query = $this->db->get_where('users',array('login' => $login));
        
        return $query->row();
    }
    
    function check_user_status($s)
    {
        $r=false;
        switch ($s)
        {
            case 1: $r=true; break;
            case 2: $this->tp->show_msg('На ваш e-mail было выслано письмо с ссылкой для подтверждения регистрации. Перейдите по ссылке, указанной в письме, чтобы активировать ваш аккаунт'); 
                    $this->tp->show_msg('<a href="'.URL.'/auth/resend">Выслать письмо еще раз</a>','msg_a');
                    $io['resend_email']=$this->input->post('userlogin', TRUE);
                    $io['resend_id']=$this->uid;
                    //unset($_SESSION['resend_email']);
                    $_SESSION['resend_info']=$io;
                    break;  
            case 3: $this->tp->show_msg('Ваш аккаунт и все ваши объявления заблокированы администрацией сайта'); break;  
        }
        return $r;
    }
    
}
