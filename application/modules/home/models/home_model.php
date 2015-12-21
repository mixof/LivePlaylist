<?php
class Home_Model extends CI_Model {
    
    private $table, $mname;

    public function __construct() {
        parent::__construct();
    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        $this->get_coments('users_comments');
    }
    
    
    function get_coments($label)
    {
        $this->db->where('moderate',1);
        $query=$this->db->get('comments');
        
        $data='';
        foreach($query->result() as $row)
        {
           $data.='<li class="testimonial">
                                    <div class="text">
                                        <p>'.
                                          $row->comment_text
                                        .'</p>
                                    </div>
                                    <div class="author">
                                        <div class="image-with-transparent-border pull-left">                                          
                                            <img class="img-circle" src="img/lp.png" alt="ava">
                                        </div>
                                        <h4>'.$row->author_fname.'</h4>
                                        <p class="muted">'.$row->email.'</p>
                                    </div>
                                </li>';
            
        }
        
        $this->tp->assign($label,$data);
        
    }
    
}