<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller {
    
    /**
     * Dlya glavnyh modulei ukazyvaem shablon i parsim v kontent
     */
    
    private $mname, $tag, $social;
    public $tpl; 
    
    function __construct()
    {
        $this->tpl='p_default.tpl'; // shablon stranicy
        $this->mname=strtolower(get_class());// 'auth' imya modulya
        $this->funcs=array('register','login','logout'); // dostupnye funkcii - 3 parametr v urle
        $this->load->library('Session');               
    } 

    public function index()
    {  
        
        if( $this->session->userdata('login') && $this->uri->segment(2)!='logout')
        {
              header("location: /cabinet");
        }
        else
        {
        $this->load->model($this->mname.'/'.$this->mname.'_model');
        $this->auth_model->index($this->mname);
  
        $this->check_function();
        
        $this->tp->add_doc_ready($this->mname); // zapisyvaet js
        $this->tp->parse('CONTENT', $this->mname.'/'.$this->mname.'.tpl');
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
                $this->common->show404();
            }
        }
        else
        {
            $this->show_login_page();
        }      
    }
    
    function show_login_page()
    {     
            $this->auth_model->load_login_form();            
    }
    
    
    function show_welcome_page()
    {     
            $this->auth_model->load_private_form();            
    }
    
}
