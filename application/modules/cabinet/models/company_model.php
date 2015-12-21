<?php
class Company_Model extends CI_Model {
    
    private $table, $mname;

    public function __construct() {
        parent::__construct();
        $this->load->helper('cookie');         
        $this->tp->add_css('bar-ui.css');
        $this->tp->add_css('jquery.jscrollpane.css');
        $this->tp->add_css('jquery.confirm.css');  
        $this->tp->add_css('bootstrap-editable.css');                 
        $this->tp->add_js('soundmanager2.js');
        $this->tp->add_js('bar-ui.js');        
        $this->tp->add_js('jquery.jscrollpane.min.js');
        $this->tp->add_js('jquery.mousewheel.js');
        $this->tp->add_js('dialog_script.js');
        $this->tp->add_js('jquery.confirm.js'); 
        $this->tp->add_js('checkbox.js');             

    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        
        if($this->input->post('del'))
        {            
            $c_id=$this->input->post('company');
            $img=$this->input->post('image');
             if($img=='no_company_logo.png')$img=null;
             
            if($this->is_mycompany($c_id))
            {
               
               $this->delete_company($c_id,$img);
               
            }
            
            
        }else
        $this->check_company();
     
    }
    
       function check_company()
    {
        if ($f=$this->uri->segment(3))
        {
          if($this->is_mycompany($f))
          { 
            if($this->input->post())
            {
           
          
            
            if($this->input->post('value'))
            {    
               $this->updateConnectionData($this->input->post('value'),$f);          
            }
            else if($this->input->post('act')=="reset")
            {
                $id=$this->input->post("id");
                if($id)
                {
                    $this->resetTrackRating($id);
                }
                else
                {
                    $response=array('status'=>'error','msg'=>'не удалось получить id..');
                    echo json_encode($response);
                    die;
                }
            }
            else if($this->input->post('act')=="refresh")
            {
             
                echo $this->load_tracks($f);
                die;
            }
            
            
            } // end if isset post           
            else
            {
            
              $this->tp->assign('this_company',$f);
              $this->load_company($f);        
              $this->show_company_info();
           
           }
          
           
          }
           else  $this->notfound();
           
        }
        else
        {
          
          $this->notfound();
        }      
    }
    
    
    function show_company_info()
    {
        $this->tp->parse('COMPANY', $this->mname.'/company_view.tpl'); 
    }
    
    function notfound()
    {
        $this->tp->parse('COMPANY', $this->mname.'/company_notfound.tpl'); 
    }
    
       function load_tracks($company_id)
        {
            $this->db->select('tracks.track_id, tracks.artist_id, tracks.track_name, tracks.track_url, rating, artist_name')->from('tracks')->join('artists','artists.artist_id=tracks.artist_id')->join('tracks_companyes',
            'tracks_companyes.track_id=tracks.track_id AND tracks_companyes.company_id='.$company_id)->order_by("rating", "desc");
            $query=$this->db->get();
            
            $result='';
            foreach ($query->result() as $row)
            {
                $result.="<li> 
                <div class='sm2-row'>
       <div class='sm2-col sm2-wide' rt='$row->rating'>
         
           <a href='$row->track_url' data-id='$row->track_id'><b>$row->artist_name</b> - $row->track_name</a>
       </div>     
          <span><input type='checkbox' value='$row->track_id'></span>
       <div class='sm2-col'>
       
      <span class='track_rate' data-placement='left' title='Rating: $row->rating'>$row->rating</span>
      
      </div>     
   </div>
       
   
    </li>";
                
            }
            $result.="<span class='lastelement'/>";
            if(strlen($result)>0) return $result;
            else return "<i>Список песен пуст..</i>";

        }   
    
    
     function load_company($company_id)
    {  
          
      
        
          $this->write_company_cookie('company_id',$company_id);
         
          $this->db->select()->from('companyes')->where('company_id',$company_id);
          $query=$this->db->get();
          
          foreach ($query->result() as $row)
          {
              $this->tp->assign('company_name',$row->company_name);
              $this->tp->assign('company_description',$row->company_description);
              $this->tp->assign('company_adress',$row->company_adress);
              $this->tp->assign('company_phone',$row->company_phone);
              $this->tp->assign('company_logo',$row->company_logo);
              
              $this->db->select('category_name')->from('categoryes')->where('category_id',$row->category_id);
              $query=$this->db->get();
              $com_categor=$query->row();
              $this->tp->assign('company_category',$com_categor->category_name);
              $this->tp->assign('code_word',$row->code_word);
          }
           // <li><a href=""><b>SonReal</b> - People Asking</a></li>
           $tracks=$this->load_tracks($company_id);
           $this->tp->assign('tracklist',$tracks);     
           $this->tp->assign('qr_code','<img id="qr_code" src='.SITEURL.'/uploads/'.$company_id.'/qr.png />');      
           $this->tp->parse('TRACKS', $this->mname.'/company_tracks.tpl');           
       
        
         
       
    }
    
    function is_mycompany($company_id)
    {
        $this->db->select('company_id')->from('users_companyes')->where('user_id',$this->session->userdata('user_id'));
        $query=$this->db->get();
        
        $compahi=$query->result_array();
        $query->free_result();
        $is_my_company=false;
        
         foreach ($compahi as $s)
          {
              if(in_array($company_id,$s))
              {
                $is_my_company=true;
                break;
              }
              
          }
          
          
      return $is_my_company;
    }
    
    
    function delete_company($company_id, $logo=null)
    {
        $tables = array('companyes', 'users_companyes','tracks_companyes');
        $this->db->where('company_id', $company_id);
       
        $this->db->delete($tables);
       
        $this->load->helper('file');
        $path='uploads/'.$company_id.'/';
        if(delete_files($path, TRUE))@rmdir($path);
        if($logo!=null)
        {
            @unlink('img/companyes/'.$logo);
        }
       
        
    }
    
    function editCodeWord($code_word, $company_id)
    {
        $data = array('code_word' => $code_word);
       
      $query=$this->db->update('companyes', $data, array('company_id'=>$company_id));
      
      if($this->db->affected_rows()) return true;
      else return false;

    }
    
    function generateQR($company_id, $word="печеньки")
    {
        $this->load->library('ciqrcode');
        $params['data'] = $company_id."|*|".$word;
        $params['level'] = 'L';
        $params['size'] = 4;
        $params['savename'] = FCPATH.'uploads/'.$company_id.'/qr.png';
        $this->ciqrcode->generate($params);
        
    }
    
    function updateConnectionData($word, $f)
    {      
                if($this->editCodeWord($word,$f))
                {
                    $this->generateQR($f,$word);                    
                    $response=array('status'=>'ok','msg'=>SITEURL.'/uploads/'.$f.'/qr.png?');
                    echo json_encode($response);
                    die;
                }
                else
                {
                    $response=array('status'=>'error','msg'=>'Ошибка БД, не удалось изменить значение..');
                    echo json_encode($response);
                    die;
                }                      
    }
    
    
    function resetTrackRating($id)
    {
       $data = array('rating' => 0);
       
      $query=$this->db->update('tracks_companyes', $data, array('track_id'=>$id));
      
      if($this->db->affected_rows()){
        
        $response=array('status'=>'ok','msg'=>'reset complete!');
        echo json_encode($response);
        die;
      }
      else 
      {
        $response=array('status'=>'error','msg'=>'не удалось сбросить рейтинг..');
        echo json_encode($response);
        die;
      }
        
    }
    
    
    function write_company_cookie($cookie_name, $value)
    {
         $cookie = array(
                   'name'   => $cookie_name,
                   'value'  => $value,
                   'expire' => '999999',
                   'domain' => '.test1.ru',
                   'path'   => '/',
                   'prefix' => 'my_',
               );

          set_cookie($cookie);  
    
    }
    
      
}