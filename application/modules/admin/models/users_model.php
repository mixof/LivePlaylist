<?php
class Users_Model extends CI_Model {
    
    private $mname;

    public function __construct() {
        parent::__construct(); 
        
        $this->polya=array('login'=>'','email'=>'','password'=>'','role'=>'','username'=>'');
      
        $this->tp->add_js('admin/users.js');
    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        if($this->input->post())
        {
        if($this->input->post('act')=='add')
        {
            $this->add_user();
            
        }
        else if($this->input->post('act')=="del")
        {
            
         
           $user_id=$this->input->post('id');
           $this->delete_companyes_and_user($user_id);
        
        }        
         else if($this->input->post('act')=="edit")
        {
           $this->load_update_data();          
           
        
        }
        
       
        }
        else{
        $this->get_all_users("users_list");
        $this->tp->parse('CONTENT', $this->mname.'/users_view.tpl');  
        
        }         
        
    }
    
    
    
    function add_user()
    {
        $this->load->library('form_validation');
        
           $this->form_validation->set_rules('login', 'Логин', 'trim|required|min_length[3]|max_length[12]xss_clean');   
           $this->form_validation->set_rules('password', 'Пароль', 'trim|required|min_length[6]|alpha_numeric|md5');  
           $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');  
           $this->form_validation->set_rules('username', 'Имя', 'trim|required|min_length[4]|max_length[15]|cyr_alpha');      
            
           if ($this->form_validation->run() == FALSE)
	     	{
	     	  $json['status']= "error";
              $json['msg']= validation_errors();  
		      echo(json_encode($json));
              die;
		   }
           else
           {
            
            $this->polya['username']=$this->input->post('username');
            $this->polya['login']=$this->input->post('login');
            $this->polya['email']=$this->input->post('email');
            if($this->input->post('role')=='1') $this->polya['role']='admin';
            else $this->polya['role']='user';            
            $this->polya['password']=$this->input->post('password');
            
            $this->register_new();
          
           }
    }
    //получить общее количество пользователей в базе
    function count_all_users()
    {
        return $this->db->count_all('users');
    }
    
    //получаем из базы всех пользователей и заносим их в метку шаблона переданную в параметре
    function get_all_users($label)
    {
        $query=$this->db->get('users');
        
        $table_rows='';
        
        foreach ($query->result() as $row)
        {
            $user_img='<img src="'.SITEURL.'/img/admin/user_icon.png" title="Пользователь" alt="Пользователь"/>';
            if($row->role!='user')$user_img='<img src="'.SITEURL.'/img/admin/admin_icon.png" title="Администратор" alt="Администратор"/>';
            
          $table_rows.="<tr>
           <td class='login'>$row->login</td>          
           <td>$row->user_name</td>
           <td>$row->user_email</td>
           <td style='text-align: center;'>$user_img</td>
           <td ><div class='doing'><a href='#edit'><img src='".SITEURL."/img/admin/edit.png' title='Изменить' alt='Изменить'/></a><a href='#delete'><img src='".SITEURL."/img/admin/delete.png' title='Удалить' alt='Удалить'/></a></div>  <input name='u_id' id='e_uid' type='hidden' value=".$row->user_id."></td>
           </tr>";    
           
           
        }
        
        $this->tp->assign($label,$table_rows);  

    }
    
    
      function register_new()
    {        
        
        $data=array('login'=>$this->polya['login'],'user_email'=>strtolower($this->polya['email']),'password'=>$this->polya['password'],
        'user_name'=>$this->polya['username'],'role'=>$this->polya['role'] );
        
        $user_exist=false;
        try
        {        
          if($this->db->select()->from('users')->where('login',$data['login'])->count_all_results()>0)
          { 
            
            $user_exist=true;          
            $json['status']= "error"; 
            $json['msg']= "Пользователь с таким логином уже существует!";  
		    echo(json_encode($json));
            die;
             
          }
          else  if($this->db->select()->from('users')->where('user_email',$data['user_email'])->count_all_results()>0)
          {
          
            $user_exist=true;
            $json['status']= "error";         
            $json['msg']= "Пользователь с таким email уже зарегистрирован!";  
		    echo(json_encode($json));
            die;         
             
          } 
       
        }
        catch(exception $e)
        {
            $json['status']= "error"; 
            $json['msg']= "Произошла ошибка при подключении к базе данных!";  
		    echo(json_encode($json));
            die;     
             
             
        }
       
       
       if(!$user_exist)
       {         
          if ($this->db->insert('users', $data)) 
          { 
            $user_id=$this->db->insert_id();
            try
            {
                
                $json['status']= "success"; 
                $json['login']= $this->polya['login'];
                $json['email']= $this->polya['email'];
                $json['username']= $this->polya['username'];
                $json['role']= $this->input->post('role');
                $json['id']=$user_id;
                $json['msg']= "ok!";  
		        echo(json_encode($json));
                die;              
            
            }
            catch(exception $ex)
            {
              $json['status']= "error"; 
              $json['msg']= "Не удалось создать папку пользователя..";  
		      echo(json_encode($json));
              die;                     
             
            }
            
             
          }
         else
          {
            $json['status']= "error"; 
            $json['msg']= "Произошла ошибка при добавлении в базу данных..";  
	        echo(json_encode($json));
            die;            
           
          }
       }
      
    }
    
    function load_update_data()
    {
        
         $this->load->library('form_validation');        
            
           $this->form_validation->set_rules('e_password', 'Пароль', 'trim|min_length[6]|alpha_numeric|md5');  
           $this->form_validation->set_rules('e_email', 'Email', 'trim|required|valid_email');  
           $this->form_validation->set_rules('e_username', 'Имя', 'trim|required|min_length[4]|max_length[15]|cyr_alpha');      
            
           if ($this->form_validation->run() == FALSE)
	     	{
	     	  $json['status']= "error";
              $json['msg']= validation_errors();  
		      echo(json_encode($json));
              die;
		   }
           else
           {
            
         $role='';
         $uid=$this->input->post('form_uid');
            if($this->input->post('e_role')=='1') $role='admin';
            else $role='user'; 
            
            $data;
            
            if(strlen($this->input->post('e_password'))>0)
            {           
            
             $data = array(
               'user_name' => $this->input->post('e_username'),
               'password' => $this->input->post('e_password'),
               'user_email' => $this->input->post('e_email'),
               'role' => $role
            );
            
            }else
            {
                $data = array(
               'user_name' => $this->input->post('e_username'),               
               'user_email' => $this->input->post('e_email'),
               'role' => $role
            );
            }
                         
           if(!$this->update_user($uid,$data))
           {
            $json['status']= "error"; 
            $json['msg']= "Ошибка при обновлении в базе данных..";  
	        echo(json_encode($json));
            die;   
           }
           else
           {
            $json['status']= "success";                 
                $json['email']= $data['user_email'];
                $json['username']= $data['user_name'];
                $json['role']= $this->input->post('e_role');
                $json['id']=$uid;
                $json['msg']= "ok!";  
		        echo(json_encode($json));
                die;        
           }
            
            }
            
        
    }
    
    function update_user($user_id,$data)
    {
       $this->db->trans_start();
       $this->db->where('user_id',$user_id);
       $this->db->update('users', $data); 
       $this->db->trans_complete();
       
       return $this->db->trans_status();
    }
    
    
    function delete_user($user_id)
    {
         $this->db->where('user_id',$user_id);
         $this->db->delete('users');               
         $res=$this->db->affected_rows(); 
                    
                 if($res) return true;  
                   else return false;
    }
    
    
    function delete_company_tracks($company_id)
    { 
      try
     {
            
      $this->db->query('delete from tracks where track_id in (select track_id from tracks_companyes where company_id='.$company_id.')');
      $this->db->query('delete from tracks_companyes where company_id='.$company_id);
       
        
        return true;
     
      }
       catch(Exception $e)
      {
        return false;
      }
        
    }
    
       function delete_company($company_id, $logo=null)
    {
        if($logo=='no_company_logo.png')$logo=null;
        $tables = array('companyes', 'users_companyes');
        $this->db->where('company_id', $company_id);
       
       $this->db->delete($tables);
       
        $this->load->helper('file');
        $path='uploads/'.$company_id.'/';
       if(delete_files($path, TRUE))
        {
            
           @rmdir($path);
       }
        if($logo!=null)
        {
             
            @unlink('img/companyes/'.$logo);
        }
       
        
    }
    
    //Получает из базы список компаний пользователя, возвращает массив их id
    function get_user_companyes($user_id)
    {
        $query=$this->db->query('select * from companyes where company_id in (select company_id from users_companyes where user_id='.$user_id.')');
              
        $companyes=array();
        
        foreach($query->result() as $row)
        {
            array_push($companyes,array('id'=>$row->company_id,'logo'=>$row->company_logo));
        }
        
        return $companyes;
    }
    
  
  function delete_companyes_and_user($user_id)
  {      
              
    
    $companyes=$this->get_user_companyes($user_id);
  
    foreach($companyes as $key =>$value)
    {
        if($this->delete_company_tracks($companyes[$key]['id']))
        {
         $this->delete_company($companyes[$key]['id'],$companyes[$key]['logo']);  
        
        }         
    }
    
     if($this->delete_user($user_id))
      {
         $json['status']= "success"; 
         $json['msg']= "Пользователь успешно удалён!";  
         echo(json_encode($json));
         die;
      } 
      else
      {
         $json['status']= "error"; 
         $json['msg']= "Не удалось удалить пользователя.."; 
         echo(json_encode($json));
         die; 
      }
    
  }
    
    
    
}