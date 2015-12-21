<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Footer extends MX_Controller {

  private $mname, $tag;
    public $tpl, $funcs; 

 function __construct()
    {
        $this->tpl='p_default.tpl'; // shablon stranicy
        $this->mname=strtolower(get_class());// 'cabient' imya modulya
        $this->funcs=array('auth'); // dostupnye funkcii - 3 parametr v urle
    } 

 public function index()
    {
        $this->load->model($this->mname.'/'.$this->mname.'_model'); // zagrujaem model modulya
        $m=$this->mname.'_model';
        $this->$m->index($this->mname); 
        
         if ($f=$this->uri->segment(1))
        {
            if (in_array($f, $this->funcs)) // esli dostupnaya funkciya
            {
               $this->tp->parse('FOOTER', $this->mname.'/footer1.tpl');
            }
            else
            {
                $this->tp->parse('FOOTER', $this->mname.'/'.$this->mname.'.tpl');
            }
        }
        else
        {
            $this->tp->parse('FOOTER', $this->mname.'/'.$this->mname.'.tpl');
        }                 
                
        
    }
}
