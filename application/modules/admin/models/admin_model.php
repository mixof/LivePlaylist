<?php
class Admin_Model extends CI_Model {
    
    private $mname;

    public function __construct() {
        parent::__construct(); 
       
    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        
         $this->common->load_model('admin', 'stat');   
       
    }
    
        function get_new_comments_count()
    {
        $this->db->where('moderate',0);       
       $res=$this->db->count_all_results('comments');
       if($res>0)return '<li><a href="{SITEURL}/admin/comments" class="dropdown-toggle" title="Новые комментарии"><i class="fa fa-comment"></i> 
                  комментарии <span class="badge badge-success" id="new_comments" title="Новые комментарии">'.$res.'</span> </a></li>';
       else return '';
    }
}