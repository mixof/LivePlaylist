<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation extends MX_Controller {

  private $mname, $tag;
    public $funcs; 

 function __construct()
    {
      
        $this->mname=strtolower(get_class());// 'cabient' imya modulya
        $this->funcs=array('addtrack','deletetrack'); // dostupnye funkcii - 3 parametr v urle
      

    } 

 public function index()
    {
        $this->check_function();
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
              redirect(SITEURL, 'location');
            }
        }
        else
        {
           redirect(SITEURL, 'location');
        }      
    }
    
}
