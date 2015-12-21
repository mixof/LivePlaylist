<?php
class Main extends MX_Controller {
    
    private $modules;
    public $tpl;
    
    function __construct()
    {
        $this->modules=array('home','news','auth', 'empty_module_url','cabinet','admin'); // dostupnye moduli iz urla           
    }

    function index()
    {
         
      //  @session_start();  
     if($this->uri->segment(1)=='validation') $this->common->load_module('validation'); 
     else if($this->uri->segment(1)=='api')$this->load->module('api');       
     else{
        
        $this->check_module();  // proveryaet modul v urle
      
        $this->tp->load_tpl($this->tp->tpl); // zagruzhaet shablon i proveryaet na moduli
        $this->tp->print_page(); // vyvodit shablon s prorabotannymi modulami na 
        
        }
        
        

     }
    
        
    function check_module()
    {
        if ($m=$this->uri->segment(1))
        {
            if (in_array($m,$this->modules))
            {
                $this->common->load_module($m);
                $this->tp->tpl=$this->$m->tpl;
            }
            else
            {
              $this->common->show404();
            }
        }   
        else
        {
            $this->load_main_page(); // esli net vtorogo segmenta, to zagruzhaet glavnuyu stranicu
        }
    }
    
    function load_main_page()
    {
       $m='home';
       $this->common->load_module($m);
       $this->tp->tpl=$this->$m->tpl; 
    }   
    
}
?>
