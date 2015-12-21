<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editcompany_Model extends CI_Model {
    
    private $polya, $mname, $u_types, $company_id=null;
    
    function __construct()
    {
        parent::__construct(); 
        $this->polya=array('title'=>'','category'=>'','adres'=>'','description'=>'','phone'=>'','logo'=>'');
        $this->u_types=array(1,2);      
        $this->load->library('form_validation');
        $this->tp->add_js("tinymce/tinymce.min.js");
        $this->tp->add_js("editcompany.js");
        $this->load->helper('cookie');
    }

    public function index($mname)
    {
      
        $this->mname=$mname;  
        
     
        
        $this->form_validation->set_rules('title', 'Название', 'trim|min_length[3]|max_length[50]|required|xss_clean');   
        $this->form_validation->set_rules('adres', 'Адрес', 'required|min_length[5]|max_length[50]|trim|xss_clean');        
        $this->form_validation->set_rules('description', 'Описание', 'min_length[20]|max_length[200]|required|trim|xss_clean');  
        $this->form_validation->set_rules('category', 'Категория', 'xss_clean');  
        $this->form_validation->set_rules('phone', 'Телефон', 'trim|xss_clean');      
				
		if ($this->form_validation->run() == FALSE)
		{
		    $this->check_company();
		  
		}
		else
		{
		  $this->polya['title']=$this->input->post('title');
          $this->polya['adres']=$this->input->post('adres');         
          $this->polya['description']=$this->input->post('description');
          $this->polya['category']=$this->input->post('category');
          $this->polya['phone']=$this->input->post('phone');          
          $this->polya['logo']=$this->input->post('logo');                   
          $this->company_id=get_cookie('my_company_id');
          
	      $this->update_company();
            
		}        
        
        
    }
    
    
       function check_company()
    {
        if ($f=$this->uri->segment(3))
        {
          if($this->is_mycompany($f))
          { 
          
           $this->write_company_cookie('company_id',$f);  
           $this->company_id=$f;
           $this->init_company_data($f);
           $this->add_company_form();
          
           
          }
           else  $this->notfound();
           
        }
        else
        {
          
          $this->notfound();
        }      
    }
    
    
        function is_mycompany($company_id)
    {
        $this->db->select('company_id')->from('users_companyes')->where('user_id',$this->session->userdata('user_id'));
        $query=$this->db->get();
        
        $compahi=$query->result_array();
        $query->free_result();
        $is_my_company=false;
        
         foreach ($compahi as $s)
          { 
          
              if(in_array($company_id,$s))
              {
                $is_my_company=true;
             
                break;
              }
             
              
          }
          
          
      return $is_my_company;
    }
    
    
      function notfound()
    {
        $this->tp->parse('COMPANY', $this->mname.'/company_notfound.tpl'); 
    }
    
    
    
    function do_upload($path)
	{
	  
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2048';
		$config['max_width']  = '2000';
		$config['max_height']  = '2000';

		$this->load->library('upload', $config);

   	   if(!$this->upload->do_upload())
          {	
           
             return  $this->polya['logo'];
          }
          else
          {
            $array=$this->upload->data();
            
           if($array['image_width']>150 && $array['image_height']>150)
           {
            $config['image_library'] = 'gd2';
            $config['source_image'] = $path.$array['file_name'];          
            $config['width'] = 150;
            $config['height'] = 150;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            }
            
            return $array['file_name'];
          } 
     
	}
    
    function load_categoryes($cat_id)
    {
       $query=$this->db->get('categoryes');
       $result='';
       
       foreach ($query->result() as $row)
       {
     
        if($row->category_id==$cat_id)
        {
         $result.='<option selected="true" value ="'.$row->category_id.'" >'.$row->category_name.'</option>';   
        }            
        else
        $result.='<option value ="'.$row->category_id.'">'.$row->category_name.'</option>';  
        
            
       }
       
        $this->tp->assign('categoryes_value',$result);
       
    }
    
     function add_company_form()
    {
        $this->init_company_data(0);
        $this->tp->parse('ADDCOMPANY_FORM', $this->mname.'/editcompany_form.tpl'); 
    }
    
    
    function update_companydata($data)
    {
    $this->db->trans_start();
    $this->db->where("company_id",$this->company_id);
    $result=$this->db->update('companyes', $data);
    
    if($result)
    {   
        
        $this->db->trans_complete();
        return true;
         
    }
    else
    {
        $this->db->trans_complete();
        return false;
    }   
    

    
    }
    
    function init_company_data($company_id)
    {
        $this->db->where('company_id',$company_id);
        $query=$this->db->get('companyes');
               
        
       foreach ($query->result() as $row)
       {
        
        $this->tp->assign('company_logo',$row->company_logo); 
        $this->load_categoryes($row->category_id);
        $this->tp->assign('company_title', htmlspecialchars($row->company_name)); 
        $this->tp->assign('company_adres',$row->company_adress); 
        $this->tp->assign('company_phone',$row->company_phone); 
        $this->tp->assign('company_description',$row->company_description); 
          
       }  
        
        
    }
    
    function update_company()
    {        
        
        $data=array('company_name'=>$this->polya['title'],'category_id'=>strtolower($this->polya['category']),
        'company_adress'=>$this->polya['adres'], 'company_phone'=>$this->polya['phone'], 'company_description'=>$this->polya['description']);
        

    
          if ($this->update_companydata($data)) 
          {    
             if($this->company_id!=null)
             { 
               
                            
                 $logo_result=$this->do_upload('./img/companyes/');
                 if($logo_result!=$this->polya['logo']){
                    
                 
                 $company_data = array('company_logo' => iconv("windows-1251","utf-8", $logo_result));

                 $this->db->where('company_id', $this->company_id);
                 $this->db->update('companyes', $company_data); 
                 
                
                 
                 redirect('/cabinet/company/'.$this->company_id);
                 
              }             
                 
                 redirect('/cabinet/company/'.$this->company_id);
               
             }                                                              
            
          }
         else
          {
            
           $this->tp->show_msg('Произошла ошибка при обновлении в базе данных', 'myerror_msg','myerror_msg');
           $this->init_company_data($this->company_id);
           $this->add_company_form(); 
         
          }
       
      
    }
    
    function write_company_cookie($cookie_name, $value)
    {
         $cookie = array(
                   'name'   => $cookie_name,
                   'value'  => $value,
                   'expire' => '999999',
                   'domain' => '.test1.ru',
                   'path'   => '/',
                   'prefix' => 'my_',
               );

          set_cookie($cookie);  
    
    }
    
}
