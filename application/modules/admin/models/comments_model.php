<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments_Model extends MX_Controller {

    
    private $mname, $tag, $social, $authmode=true, $polya;
    
    public $tpl; 
    
    function __construct()
    {
      
        $this->mname=strtolower(get_class());
        $this->tp->add_js('admin/comments.js');
        $this->polya=array('author_fname'=>'','email'=>'','comment_text'=>'','post_date'=>'','moderate'=>'');  
    } 

    public function index($mname)
    {  
        $this->mname=$mname;
        
        if($this->input->post('act')=='del')
        {
            $this->delete_comment($this->input->post('id'));                
            
        }else if($this->input->post('act')=='accept')
        {
            $this->accept_comment($this->input->post('id'));                
           
        }else if($this->input->post('act')=='edit')
        {
            $comment_id=$this->input->post('form_uid');
            $text= $this->input->post('comment_text');
            $this->edit_comment($comment_id, $text);
           
        }
        else
        {
       
        $this->get_all_comments('comments_list');
        $this->tp->parse('CONTENT', $this->mname.'/comments_view.tpl');  
        }
       
    }
    
    
    function delete_comment($comment_id)
    {
        $this->db->where('comment_id',$comment_id);
        $this->db->delete('comments');
        
        if($this->db->affected_rows())
        {
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
    
    function accept_comment($comment_id)
    {
       $this->db->where('comment_id',$comment_id);
      $this->db->update('comments', array('moderate' => 1));
       
       if($this->db->affected_rows())
       {
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
    
    
    function edit_comment($comment_id, $text)
    {
       $this->load->library('form_validation');        
            
       
        $this->form_validation->set_rules('comment_text', 'Текст комментария', 'min_length[10]|max_length[200]|required|trim|xss_clean');  
          
           if ($this->form_validation->run() == FALSE)
	     	{
	     	  $json['status']= "error";
              $json['msg']= validation_errors();  
		      echo(json_encode($json));
              die;
		   }
           else
           {             
        
      $this->db->where('comment_id',$comment_id);
      $this->db->update('comments', array('comment_text' => $text));
       
       if($this->db->affected_rows())
       {
           $json['status']= "success";
           $json['msg']="Изменения Сохранены";   
           $json['text']=$text;  
           $json['id']=$comment_id;           
            echo(json_encode($json));
            die;
       }
       else
       {
           $json['status']= "error"; 
           $json['msg']="Изменений не обнаруженно..";                  
           echo(json_encode($json));
           die; 
       }
       
       }
        
    }
    
    
    function get_all_comments($label)
    {
       
        $this->db->order_by('post_date desc, moderate desc');       
        $query=$this->db->get('comments');
       
        $data='';
        foreach($query->result() as $row)
        {
            $img;
            $doing;
            if($row->moderate==0){
                
                $img='<img src="{SITEURL}/img/wait.png" alt="Ожидает проверки" title="Ожидает проверки"></img>';
                $doing="<li><a href='#accept'><img src='".SITEURL."/img/postit.png' title='Опубликовать' alt='Опубликовать'/> Опубликовать</a></li>                
                <li><a href='#edit'><img src='".SITEURL."/img/admin/edit.png' title='Изменить' alt='Изменить'/> Изменить</a></li>
                <li><a href='#delete'><img src='".SITEURL."/img/admin/delete.png' title='Удалить' alt='Удалить'/> Удалить</a></li>";
                
                }
            else {
                $img='<img src="{SITEURL}/img/ok.png" alt="Опубликован" title="Опубликован"></img>';
                 $doing="<li><a href='#edit'><img src='".SITEURL."/img/admin/edit.png' title='Изменить' alt='Изменить'/> Изменить</a></li>
                <li><a href='#delete'><img src='".SITEURL."/img/admin/delete.png' title='Удалить' alt='Удалить'/> Удалить</a></li>";
                }
            $data.="<tr>
           <td>$row->author_fname</td> 
           <td>$row->email</td>           
           <td>$row->comment_text</td>
           <td>$row->post_date</td>          
           <td style='text-align: center;'>$img</td>         
           <td style='text-align: center;'><div class='dropdown'><a id='dLabel' role='button' data-toggle='dropdown' data-target='#' href='#'><i class='fa fa-navicon'></i></a>
           <ul class='dropdown-menu' role='menu' aria-labelledby='dLabel'>           
          $doing
          </ul>
           </div><input name='com_id' id='com_id' type='hidden' value=".$row->comment_id."> </td>
           </tr>";    
        }
        
        
        
        $this->tp->assign($label,$data);
        
    }
    
}
