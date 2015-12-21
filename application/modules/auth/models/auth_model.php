<?php
class Auth_Model extends CI_Model {
    
    private $table, $mname, $provider, $uid, $fullname, $email, $id_user, $avatar;

    public function __construct() {
        parent::__construct(); 
        $this->table='users';
    }
    
    public function index($mname)
    {
        $this->mname=$mname; 
    }
    
    function load_private_form()
    {
        
        $this->tp->parse('REGISTER_FORM', $this->mname.'/register_form.tpl');  
        //$this->tp->parse('REGISTER_FORM', $this->mname.'/social.tpl');
    }
    
    function load_login_form()
    {
        $this->tp->parse('REGISTER_FORM', $this->mname.'/login_form.tpl'); 
    }
    
}
