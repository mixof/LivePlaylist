<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {

    
    private $mname, $tag, $social, $authmode=true;
    public $tpl; 
    
    function __construct()
    {
      
        $this->mname=strtolower(get_class());
        $this->funcs=array('stat','logout','users','companyes','comments'); 
        
        $this->load->library('Session');  
        
        //если существует сессия администратора - то грузим админ панель
        if($this->isadmin($this->session->userdata('user_id')))
        {            
             $this->tpl='admin_default.tpl';      
             $this->authmode=false; 
             $this->tp->add_js('admin/bootbox.min.js');   
             $this->tp->add_js('admin/admin.js');     
        }             
        else //иначе показываем форму авторизации
        {
            $this->tpl='admin_auth.tpl';  
            $this->authmode=true;
        }
        
        
        
                        
        
        
    } 

    public function index()
    {  
        if($this->authmode) // если необходимо авторизоваться
        $this->check_login();
        else // если уже авторизован
        {
            $this->load->model($this->mname.'/'.$this->mname.'_model');
            $this->tp->assign('new_comments',$this->admin_model->get_new_comments_count());
             $this->tp->assign("admin_login"," ".$this->session->userdata('login'));
             $this->check_function();
        }        
        
       
       
    }
    
    
    
     function check_function()
    {
        if ($f=$this->uri->segment(2))
        {
            if (in_array($f, $this->funcs)) // esli dostupnaya funkciya
            {
                
               $this->common->load_model($this->mname, $f);   
                 
            }
            else
            {
              $this->tp->parse('404', 'notfound/notfound.tpl');
             
            }
        }   
        else
        {
             $this->common->load_model($this->mname, 'admin');   
        }          
    }
      
   function check_login()
   {
    $this->load->library('form_validation');
        
        $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean');   
        $this->form_validation->set_rules('password', 'Пароль', 'trim|required|md5');  
      
        $this->check_submit();
    
   }   
   
   function check_submit()// проверяет была-ли отправлена форма
   {
     if($this->input->post('commit')) // если был сабмит
       {
		if ($this->form_validation->run() == FALSE) // если форма не прошла валидацию
		{		    
            $this->tp->assign("validation","<div class='alert alert-danger'>".validation_errors()."</div>");
		}
		else // если прошла
		{
		 
          $this->check_logining($this->input->post('login',true),$this->input->post('password'));
            
		}
        
        }
   }
   
   
      function check_logining($login, $password)  // proverka pri avtorizacii
    {       
        
            $u=$login;
            $p=$password; 
               //если пользователь существует
                if ($row=$this->is_user_exist($u))
                {
                       //если совпадает пароль и role="admin"                                     
                    if ($this->check_pass($row, $p) && $this->check_role($row))
                    {
                       
                        
                       //Перезаписываем данные в сессию и делаем рефреш страницы
                       $this->session->set_userdata('login',$u);
                       $this->session->set_userdata('user_id',$row->user_id);
                      
                       redirect(SITEURL.'/admin');
                    }
                    else $this->tp->assign("validation","<div class='alert alert-danger'>Неверный пароль! Проверьте правильность ввода данных..</div>");
                    
                }  
                else  $this->tp->assign("validation","<div class='alert alert-danger'>Возможно логин введён неверно! Проверьте правильность ввода данных..</div>");
                                        
               
            
       
    }
    
   
    function check_pass($row, $p)
    {
           
        if ($p==$row->password)
        return TRUE;
        else
        return FALSE;
    }
    
    function check_role($row)
    {
        if($row->role=="admin")return true;
        else return false;
    }
    
    function is_user_exist($login)
    {
        $this->db->select('user_id, password, role');

        $query = $this->db->get_where('users',array('login' => $login));
        
        return $query->row();
    }
    
    
     
    //Проверка роли пользователя в бд
    function isadmin($user_id)
    {
        $result=false;
        if($this->session->userdata('login'))
        {        
        
        $this->db->select('role');
        $this->db->from('users');
        $this->db->where('user_id',$user_id);
        $query=$this->db->get();
        
        foreach ($query->result() as $row)
        {
            if($row->role=="admin")
            {
                $result=true;
            }
        }
        
        }

        return $result;

    }
    
    function show_login_page()
    {     
            //$this->auth_model->load_login_form();            
    }
    
    
  
    
}
