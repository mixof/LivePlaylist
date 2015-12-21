<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_Model extends CI_Model {
    
    private $polya, $mname, $u_types;
    
    function __construct()
    {
        parent::__construct(); 
        $this->polya=array('username'=>'','email_adress'=>'','password'=>'','firstname'=>'','lastname'=>'');
        $this->u_types=array(1,2);
    }

    public function index($mname)
    {
      
        $this->mname=$mname;  
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('username', 'Логин', 'trim|required|min_length[3]|max_length[12]xss_clean');   
        $this->form_validation->set_rules('password', 'Пароль', 'trim|matches[password_confirm]|required|min_length[6]|alpha_numeric|md5');  
        $this->form_validation->set_rules('password_confirm', 'Пароль подтверждение', 'trim|required');  
        $this->form_validation->set_rules('email_adress', 'Email', 'trim|required|valid_email');  
        $this->form_validation->set_rules('firstname', 'Фамилия', 'trim|min_length[3]|max_length[15]|cyr_alpha');  
        $this->form_validation->set_rules('lastname', 'Имя', 'trim|required|min_length[4]|max_length[15]|cyr_alpha');      
				
		if ($this->form_validation->run() == FALSE)
		{
		    $this->auth->show_welcome_page(); 
		}
		else
		{
		  $this->polya['username']=$this->input->post('username');
          $this->polya['password']=$this->input->post('password');         
          $this->polya['email_adress']=$this->input->post('email_adress');
          $this->polya['firstname']=$this->input->post('firstname');
          $this->polya['lastname']=$this->input->post('lastname');
          
	     	$this->register_new();
            
		}
              
     
        
    }
    
    
    
    function register_new()
    {        
        
        $data=array('login'=>$this->polya['username'],'user_email'=>strtolower($this->polya['email_adress']),'password'=>$this->polya['password'],
        'user_name'=>$this->polya['lastname']);
        
        $user_exist=false;
        try
        {        
          if($this->db->select()->from('users')->where('login',$data['login'])->count_all_results()>0)
          { 
            
            $user_exist=true;
            $this->tp->show_msg('Пользователь с таким логином уже существует!', 'myerror_msg');
             $this->auth->show_welcome_page();
          }
          else  if($this->db->select()->from('users')->where('user_email',$data['user_email'])->count_all_results()>0)
          {
          
            $user_exist=true;
            $this->tp->show_msg('Пользователь с таким email уже зарегистрирован!', 'myerror_msg');
             $this->auth->show_welcome_page();
          } 
       
        }
        catch(exception $e)
        {
             
              $this->tp->show_msg('Произошла ошибка при подключении к базе данных', 'myerror_msg');
              $this->auth->show_welcome_page();
        }
       
       
       if(!$user_exist)
       {         
          if ($this->db->insert('users', $data)) 
          { 
            try
            {
                mkdir(APPPATH."uploads/".$this->polya['username']);
                $this->tp->show_msg('Вы успешно Зарегистриовались и сейчас будете перенаправлены на страницу авторизации..', 'myerror_msg','CONFIRM_MSG');
            
            
            }
            catch(exception $ex)
            {
              $this->tp->show_msg('Не удалось создать папку пользователя..', 'myerror_msg');          
             
            }
            
             header( 'Refresh: 3; url=/auth/login' );
          }
         else
          {
            
            $this->tp->show_msg('Произошла ошибка при добавлении в базу данных', 'myerror_msg');
            $this->auth->show_welcome_page();
          }
       }
      
    }
    
}
