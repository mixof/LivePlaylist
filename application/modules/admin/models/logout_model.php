<?php
class Logout_Model extends CI_Model {
    
    private $mname;

    public function __construct() {
        parent::__construct(); 
       
    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        $this->session->sess_destroy();
        redirect(SITEURL.'/admin');
      //$this->tp->parse('404', 'notfound/notfound.tpl');
       
    }
    
    
}