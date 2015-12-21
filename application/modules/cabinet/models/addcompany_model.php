<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addcompany_Model extends CI_Model {
    
    private $polya, $mname, $u_types, $company_id=null;
    
    function __construct()
    {
        parent::__construct(); 
        $this->polya=array('title'=>'','category'=>'','adres'=>'','description'=>'','phone'=>'','logo'=>'');
        $this->u_types=array(1,2);      
        $this->load->library('form_validation');
        $this->tp->add_js("tinymce/tinymce.min.js");
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
		  
		    $this->load_categoryes();
		    $this->add_company_form();
		}
		else
		{
		  $this->polya['title']=$this->input->post('title');
          $this->polya['adres']=$this->input->post('adres');         
          $this->polya['description']=$this->input->post('description');
          $this->polya['category']=$this->input->post('category');
          $this->polya['phone']=$this->input->post('phone');          
          $this->polya['logo']="no_company_logo.png";
          
	      $this->create_company();
            
		}        
        
        
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
           
             return 'no_company_logo.png';
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
    
    function load_categoryes()
    {
       $query=$this->db->get('categoryes');
       $result='';
       
       foreach ($query->result() as $row)
       {
        $result.='<option value ="'.$row->category_id.'">'.$row->category_name.'</option>';        
       }
       
        $this->tp->assign('categoryes_value',$result);
       
    }
    
     function add_company_form()
    {
        $this->tp->parse('ADDCOMPANY_FORM', $this->mname.'/addcompany_form.tpl'); 
    }
    
    
    function insert_companydata($data)
    {
    $this->db->trans_start();
    
    $result=$this->db->insert('companyes', $data);
    
    if($result)
    {
        $id=$this->db->insert_id();
        $this->company_id=$id;
        $mydata= array('company_id'=>$id,'user_id'=>$this->session->userdata('user_id'));
        $this->db->insert('users_companyes',$mydata);
        
        $this->db->trans_complete();
        return true;
         
    }
    else
    {
        $this->db->trans_complete();
        return false;
    }   
    

    
    }
    
    function create_company()
    {        
        
         $data=array('company_name'=>$this->polya['title'],'category_id'=>strtolower($this->polya['category']),'company_logo'=>$this->polya['logo'],
        'company_adress'=>$this->polya['adres'], 'company_phone'=>$this->polya['phone'], 'company_description'=>$this->polya['description']);
        

    
          if ($this->insert_companydata($data)) 
          {       
              if($this->company_id!=null)
             {
                
                $company_path='uploads/'.$this->company_id.'/';
                 mkdir($company_path, 0777, true);
                 $this->generateQR($this->company_id,"печеньки");
                 $logo_result=iconv("windows-1251","utf-8", $this->do_upload('img/companyes/'));
               
                 
                
                 if($logo_result!=$this->polya['logo']){
                 
                 $company_data = array('company_logo' => $logo_result);

                 $this->db->where('company_id', $this->company_id);
                 $this->db->update('companyes', $company_data); 
                 
                
                 }
                 
                
                 $cookie = array(
                   'name'   => 'company_id',
                   'value'  => $this->company_id,
                   'expire' => '999999',
                   'domain' => '.test1.ru',
                   'path'   => '/',
                   'prefix' => 'my_',
               );

                set_cookie($cookie);
               
             }                                                              
             header('location: /cabinet/company/'.$this->company_id);
          }
         else
          {
            
           $this->tp->show_msg('Произошла ошибка при добавлении в базу данных', 'myerror_msg','myerror_msg');
           $this->load_categoryes();
           $this->add_company_form(); 
         
          }
       
      
    }
    
    function generateQR($company_id, $word="печеньки")
    {
        $this->load->library('ciqrcode');
        $params['data'] = $company_id."|*|".$word;
        $params['level'] = 'L';
        $params['size'] = 4;
        $params['savename'] = FCPATH.'uploads/'.$company_id.'/qr.png';
        $this->ciqrcode->generate($params);
        
    }
    
    
}
