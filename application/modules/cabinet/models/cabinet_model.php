<?php
class Cabinet_Model extends CI_Model {
    
    private $table, $mname, $contains_companyes=false;

    public function __construct() {
        parent::__construct(); 
        $this->tp->add_css('introjs.css');
        $this->tp->add_js('intro.js');
        $this->tp->add_js('mydiagram.js');        
        $this->tp->add_js_ie('excanvas.js');
        
    }
    
    public function index($mname)
    {
         if($this->input->post('act')=='add_comment')
        {
            $this->submit_comment();
        }else
        {
        $this->mname=$mname;
        $this->load_companyes();
        }
    }
    
     function load_companyes()
    {
       
       
        
        $this->db->select('companyes.company_id, company_name');
        $this->db->from('companyes');
        $this->db->join('users_companyes', 'users_companyes.company_id = companyes.company_id and users_companyes.user_id='.$this->session->userdata('user_id'));

        $query=$this->db->get();
        $result='';
        
        $company_ids=array();
       
       foreach ($query->result() as $row)
       {
        
        $result.='<li><a href="/cabinet/company/'.$row->company_id.'">'.$row->company_name.'</a></li>';   
        array_push($company_ids,array ('id'=>$row->company_id, 'name'=> $row->company_name));     
       }
       if(strlen($result)<1) $result=' <span id="emptylist">Список компаний пуст..</span>';
       else $this->contains_companyes=true;
       
       
        $this->tp->assign('user_companyes',$result);
        
        if(count($this->uri->segment_array())==1)// если в адресной строке указанно только "cabinet"
        {
          if($this->contains_companyes) // если есть созданные компании
          {
              if($this->get_tracks_count($company_ids)>0) // если есть треки в компаниях
              {
                $this->tp->assign('companyes_info',$this->load_companyes_info($company_ids));   
                $this->tp->assign('top_artists',$this->load_top_authors($company_ids));
                $this->show_diagram();
              }
              else
              {
                $this->tp->assign('COMPANY','<div class="alert alert-warning"><strong>Внимание!</strong> На данный момент в ваших компаниях нет ни одного трека..</br> Вы сможете увидеть вашу статистику только после добавления треков.</div>');  
              }
        
         }
         else
         {
           $this->tp->assign('COMPANY','<div class="alert alert-warning"><strong>Внимание!</strong> На данный момент вы не создали не одной компании..</div>                         <div class="jumbotron">
        <h2>Используете наш сервис впервые?</h2>
        <p id="turne">Мы проведём для вас небольшой тур по системме.</p>
        <a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Начать тур</a>
      </div>  ');  
         }
        
        
        }
        
       
    }
    
    
    //получить суммарное количество треков по всем компаниям || принимает массив ид компаний
    function get_tracks_count($ids)
    {
        $count=0;
       foreach($ids as $key => $value)
      {
        $this->db->where('company_id',$ids[$key]['id']);
        $this->db->from('tracks_companyes');       
        $count+=$this->db->count_all_results();   
      }
      
      return $count;
      
    }
    
    
    function get_user_data($user_id)
    {
        $this->db->select('user_name,user_email');
        $this->db->where('user_id',$user_id);
        $query=$this->db->get('users');
        
        
      $user_data=null;
        foreach($query->result() as $row)
        {
             
            $user_data=array('name'=>$row->user_name,'email'=>$row->user_email);            
        }
        
       
        return $user_data;
    }
    
    function insert_comment($data)
    {
       $this->db->trans_start();
       $this->db->insert('comments', $data);
       $this->db->trans_complete();
       
      return $this->db->trans_status();
    }
    
    function submit_comment()
    {
             
        $this->load->library('form_validation');        
            
        $this->form_validation->set_rules('message', 'текст отзыва', 'min_length[10]|max_length[120]|required|xss_clean');   
           
            
           if ($this->form_validation->run() == FALSE)
	     	{
	     	  $json['status']= "error";
              $json['msg']= validation_errors();  
		      echo(json_encode($json));
              die;
		   }
           else
           {          
            
           $user_data=$this->get_user_data($this->session->userdata('user_id'));
           
             if($user_data!=null)
            {
              
               $data=array('comment_text'=>$this->input->post('message'),'author_fname'=>$user_data['name'],'email'=>$user_data['email'],'post_date'=>date("Y-m-d H:i:s"));
               if($this->insert_comment($data))
               {
                  $json['status']= "success";
                  $json['msg']= "Успешно отправлено!";  
		          echo(json_encode($json));
                  die;
               }
               else
               {
                 $json['status']= "error";
                 $json['msg']= 'Не удалось добавить запись в бд..';  
		         echo(json_encode($json));
                 die; 
               }
            }
            else
            {
              $json['status']= "error";
              $json['msg']= 'Не удалось получить данные отправителя..';  
		      echo(json_encode($json));
              die;  
            }
                
         }
    }
     
    //Получить количество треков для каждой компании || принимает массив ид компаний
    function load_companyes_info($ids)
    {
        $result='';
        $red=13;
        $green=160;
      foreach($ids as $key => $value)
      {
        $this->db->where('company_id',$ids[$key]['id']);
        $this->db->from('tracks_companyes');       
        $count=$this->db->count_all_results();
        $result.="<tr style='color:rgb(".$red.",".$green." , 104)'><td>".$ids[$key]['name']."</td><td>".$count."</td></tr>";
        $red+=30;
        $green-=30;
      }
      
      return $result;
    }
    
     // компаратор для сравнения количества треков у исполнителей  
    function cmp($a, $b)
    {
      return ($a['count'] < $b['count']);
    }
    
    // Получить список авторов, у которых количество треков по всем компаниям >1  || принимает массив ид компаний
    function load_top_authors($ids)
    {
       $result='';
       $artists=array();
       
        
        foreach($ids as $key => $value)
        {         
              
         $query1=$this->db->query("select * from artists where artist_id in (select artist_id from tracks where track_id in (select track_id from tracks_companyes where company_id=".$ids[$key]['id']."))");
        
         foreach($query1->result() as $row)
        {
            $myquery=$this->db->query("select * from tracks where artist_id=".$row->artist_id." and track_id in (select track_id from tracks_companyes where company_id =".$ids[$key]['id'].")");
         
           if($myquery->num_rows()>0)
           {
            if(isset($artists[$row->artist_id]))
            {           
              $cnt=$artists[$row->artist_id]['count'];
              $artists[$row->artist_id]['count']=$cnt+$myquery->num_rows();
            }
            else
            {
               $artists[$row->artist_id]=array('name'=>$row->artist_name,"count"=>$myquery->num_rows());
            }
           
           }
           
        }
       
        
        }
        
        
        usort($artists, array( $this, 'cmp' ) ); // сортируем массив по убыванию с помощью функции компаратора
        
        foreach($artists as $key=>$value)
        {
            if($artists[$key]['count']>1)
             $result.="<tr><td>".$artists[$key]['name']."</td><td>".$artists[$key]['count']."</td></tr>";
        }
        
        
        
        return $result;
    }
    

    
    function show_diagram()
    {
        $this->tp->parse('COMPANY', $this->mname.'/'.'mydiagram.tpl'); 
    }
    
}