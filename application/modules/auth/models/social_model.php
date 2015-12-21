<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social_Model extends CI_Model {
    
    private $mname;

    public function index($mname)
    {
        $this->mname=$mname;
        $this->tp->parse('WELCOME', $this->mname.'/social.tpl');  
    }    
    
}
