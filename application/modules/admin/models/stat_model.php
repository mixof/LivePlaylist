<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stat_Model extends MX_Controller {

    
    private $mname, $tag, $social, $authmode=true, $polya;
    
    public $tpl; 
    
    function __construct()
    {
      
        $this->mname=strtolower(get_class());
       
        $this->polya=array('company_name'=>'','company_adress'=>'','company_phone'=>'','company_description'=>'','company_logo'=>'');  
    } 

    public function index($mname)
    {  
        $this->mname=$mname;
        
         if($this->input->post())
        {
            
        if($this->input->post('act')=="edit")
        {
                    
            $this->load_update_data(); 
        
        }
        
       
        }
        else{
      
         $this->tp->assign('companyes_count',$this->get_companyes_count()); 
         $this->tp->assign('users_count',$this->get_users_count());  
         $this->tp->assign('comments_count',$this->get_comments_count());  
         
         $this->tp->parse('CONTENT', $this->mname.'/stat_view.tpl');  
        }
       
    }
    
    
  
    
   function get_companyes_count()
   {
    
    return $this->db->count_all_results('companyes');
        
   }
    
    
    function get_users_count()
    {
        
      return $this->db->count_all_results('users');
        
    }
    
    function get_comments_count()
    {
        
        return $this->db->count_all_results('comments');
    }
    

    
    
    function get_all_companyes($label)
    {
        $query=$this->db->get('companyes');
        
        $table_rows='';
        
        foreach ($query->result() as $row)
        {
            $img='<img class="table_preview" src="'.SITEURL.'/img/companyes/'.$row->company_logo.'"/>';
           
            $cat_name= $this->get_category_name($row->category_id);
            $owner= $this->get_company_owner($row->company_id);
            if($cat_name==null)$cat_name='unknown';
                       
           $table_rows.="<tr>
           <td>$img</td> 
           <td>$cat_name</td>           
           <td>$row->company_name</td>
           <td>$row->company_description</td>
           <td>$row->company_adress</td>
           <td>$row->company_phone</td>
           <td><a href='#$owner[id]'>$owner[login]</a></td>
           <td><div class='doing'><a href='#edit'><img src='".SITEURL."/img/admin/edit.png' title='Изменить' alt='Изменить'/></a><a href='#delete'><img src='".SITEURL."/img/admin/delete.png' title='Удалить' alt='Удалить'/></a></div><input name='cat_id' id='cat_id' type='hidden' value=".$row->category_id.">  <input name='c_id' id='c_uid' type='hidden' value=".$row->company_id."></td>
           </tr>";    
           
           
        }
        
        $this->tp->assign($label,$table_rows);  

    }
    
    
    
    
    function get_category_name($cat_id)
    {
        $this->db->where('category_id',$cat_id);
        $query=$this->db->get('categoryes');
        
        $name=null;
        
        foreach($query->result() as $row)
        {
          $name=$row->category_name;    
        }
        
        return $name;    
        
    }
    
    function get_company_owner($comp_id)
    {        
        $this->db->where('company_id',$comp_id);
        $query=$this->db->get('users_companyes');  
        $user_id=null;   
        $user_name=null;
        foreach($query->result() as $row)
        {
            
          $user_id=$row->user_id; 
          $this->db->where('user_id',$user_id);
          $query2=$this->db->get('users'); 
          
          foreach($query2->result() as $row)
          {
            $user_name=$row->login;
          
          }
        
       
        }
        
        $user=array('id'=>$user_id,'login'=>$user_name);
         return $user; 
    }
         

    
}
