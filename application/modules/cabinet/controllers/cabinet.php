<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabinet extends MX_Controller {

  private $mname, $tag;
    public $tpl, $funcs; 

 function __construct()
    {
        $this->tpl='p_default.tpl'; // shablon stranicy
        $this->mname=strtolower(get_class());// 'cabient' imya modulya
        $this->funcs=array('addcompany','editcompany','company','upload'); // dostupnye funkcii - 3 parametr v urle
        $this->load->library('session');      
        $this->load->helper('cookie');
      
       

    } 

 public function index()
    {
        
        if( $this->session->userdata('login'))
        {
        
        $this->load->model($this->mname.'/'.$this->mname.'_model'); // zagrujaem model modulya
        $m=$this->mname.'_model';
        $this->$m->index($this->mname);      
        $this->check_function();     
        $this->tp->parse('CONTENT', $this->mname.'/'.$this->mname.'.tpl');
        }
        else
        {
            header("location:".SITEURL."/auth/login");
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
       
        }      
    }
}
