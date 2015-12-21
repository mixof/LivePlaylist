<?php
class Upload_Model extends CI_Model {
    
    private $table, $mname;

    public function __construct() {
        parent::__construct();
        $this->load->helper('cookie');     
        $this->tp->add_css('jquery.fileupload.css');
        $this->tp->add_js('jquery.knob.js'); 
        $this->tp->add_js('jquery.ui.widget.js');
        $this->tp->add_js('jquery.iframe-transport.js');
        $this->tp->add_js('jquery.fileupload.js');
       
             
        $this->tp->add_js('script.js');
    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        $this->check_company();
     
    }
    
       function check_company()
    {
        if ($f=$this->uri->segment(3))
        {
          if($this->is_mycompany($f))
          { 
          
           $this->write_company_cookie('company_id',$f);
           $this->show_company_info();
          
           
          }
           else  $this->notfound();
           
        }
        else
        {
          
          $this->notfound();
        }      
    }
    
    
    function show_company_info()
    {
        $this->tp->parse('COMPANY', $this->mname.'/upload_view.tpl'); 
    }
    
    function notfound()
    {
        $this->tp->parse('COMPANY', $this->mname.'/company_notfound.tpl'); 
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