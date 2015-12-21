<?php
class Api_Model extends CI_Model {
    
    private $table, $mname, $provider, $uid, $fullname, $email, $id_user, $avatar;

    public function __construct() {
        parent::__construct(); 
        $this->table='users';
    }
    
    public function index($mname)
    {
        $this->mname=$mname; 
    }
    
    
    function get_company($id)
    {
        $this->db->where('company_id',$id);
        $query=$this->db->get('companyes');
        
       return $query->result_array();
    }
    
    
    function get_tracks($company_id, $limit=null)
    {
        $this->db->select('*');
        $this->db->from('tracks');
        $this->db->join('tracks_companyes','tracks_companyes.track_id=tracks.track_id and tracks_companyes.company_id='.$company_id);
        $query=$this->db->get();
        
        return $query->result_array();
    }
    
    
    function update_track_rate($track_id)
    {
        $this->db->query('UPDATE `tracks_companyes` SET `rating` = `rating` + 1 where track_id='.$track_id);
        return $this->db->affected_rows();
    }
  
    
}
