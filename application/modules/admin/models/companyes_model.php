<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companyes_Model extends MX_Controller {

    
    private $mname, $tag, $social, $authmode=true, $polya;
    
    public $tpl; 
    
    function __construct()
    {
      
        $this->mname=strtolower(get_class());
        $this->tp->add_js('admin/companyes.js');
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
        else if($this->input->post('act')=="del")
        {
            $img=$this->input->post('img');
            if($img=='no_company_logo.png'|| strlen($img)<1) $img=null; 
                    
          $this->delete_company($this->input->post('id'), $img);
        
        }
        
       
        }
        else{
        $this->get_all_companyes('companyes_list');
        $this->tp->parse('CONTENT', $this->mname.'/companyes_view.tpl');  
        }
       
    }
    
    
    function load_update_data()
    {
                
        $this->load->library('form_validation');        
            
        $this->form_validation->set_rules('c_name', 'Название', 'trim|min_length[3]|max_length[50]|required|xss_clean');   
        $this->form_validation->set_rules('c_adress', 'Адрес', 'required|min_length[5]|max_length[50]|trim|xss_clean');        
        $this->form_validation->set_rules('description', 'Описание', 'min_length[20]|max_length[200]|required|trim|xss_clean');  
        $this->form_validation->set_rules('c_phone', 'Телефон', 'trim|xss_clean');            
            
           if ($this->form_validation->run() == FALSE)
	     	{
	     	  $json['status']= "error";
              $json['msg']= validation_errors();  
		      echo(json_encode($json));
              die;
		   }
           else
           {
            
            $this->polya['company_name']=$this->input->post('c_name');
            $this->polya['company_adress']=$this->input->post('c_adress');
            $this->polya['company_phone']=$this->input->post('c_phone');
            $this->polya['company_description']=$this->input->post('description');
            $this->polya['company_logo']=$this->input->post('logo');  
            $company_id=$this->input->post('form_uid');  
            
            
            
           if(!$this->update_company($company_id, $this->polya))
            {
                $json['status']= "error"; 
                $json['msg']= "Ошибка при обновлении в базе данных..";  
	            echo(json_encode($json));
                die;  
            }
            else
            {     
                              
                 $logo_result=$this->do_upload('./img/companyes/');
                 
                if($logo_result!=$this->polya['company_logo']){
                    
                 
                $company_data = array('company_logo' => $logo_result);

                $this->db->where('company_id',$company_id);
                $this->db->update('companyes', $company_data); 
                 
                }
               
               
                $json['status']= "success";                 
                $json['name']= $this->polya['company_name'];
                $json['adress']=$this->polya['company_adress'];
                $json['phone']= $this->polya['company_phone'];
                $json['description']=$this->polya['company_description'];
                $json['id']=$company_id;
                $json['msg']= "ok!";  
		        echo(json_encode($json));
                die;     
            }
            
            
           }
                     
        
        
    }
    
       
    function delete_company($company_id, $logo=null)
    {
        $tables = array('companyes', 'users_companyes','tracks_companyes');
         $this->db->trans_start();
        $this->db->where('company_id', $company_id);       
        $this->db->delete($tables);
        $this->db->trans_complete();
        
        if($this->db->trans_status())
        {
        $this->load->helper('file');
        $path='uploads/'.$company_id.'/';
        if(delete_files($path, TRUE))@rmdir($path);
        if($logo!=null)
        {
            @unlink('img/companyes/'.$logo);
        }
        
        $json['status']= "success";
		      echo(json_encode($json));
              die;
        
        
        }
        else
        {
              $json['status']= "error";
		      echo(json_encode($json));
              die;     
        }
       
        
    }
    
    function update_company($company_id, $data)
    {
       $this->db->trans_start();
       $this->db->where('company_id',$company_id);
       $this->db->update('companyes', $data); 
       $this->db->trans_complete();
       
       return $this->db->trans_status();
    }
    
    
     function do_upload($path)
	{
	  
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2048';
		$config['max_width']  = '2000';
		$config['max_height']  = '2000';

		$this->load->library('upload', $config);

   	   if(!$this->upload->do_upload())
          {	
           
             return  $this->polya['company_logo'];
          }
          else
          {
            $array=$this->upload->data();
            
           if($array['image_width']>150 && $array['image_height']>150)
           {
            $config['image_library'] = 'gd2';
            $config['source_image'] = $path.$array['file_name'];          
            $config['width'] = 150;
            $config['height'] = 150;

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            }
            
            return $array['file_name'];
          } 
     
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
