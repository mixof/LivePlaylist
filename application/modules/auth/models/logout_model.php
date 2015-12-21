<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout_Model extends CI_Model {
    
    private $mname;
    
    function __construct()
    {
        
    }

    public function index($mname)
    {
        $this->mname=$mname;
        $this->session->sess_destroy();
        header( 'Location: /auth/login' );
    }
    
}