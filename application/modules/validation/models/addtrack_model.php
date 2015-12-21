<?php
class Addtrack_Model extends CI_Model {
    
    private $table, $mname, $polya, $userfolder, $company;

    public function __construct() {
        parent::__construct();
        $this->polya=array('track_title'=>'','track_url'=>'');
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->library('Getid3');
   
    }
    
    public function index($mname)
    {
        $this->userfolder=$this->session->userdata('login');
        $this->company=get_cookie('my_company_id');
        $this->upload();
      /*  $this->form_validation->set_rules('track_title', 'Название', 'min_length[3]|max_length[70]|required|xss_clean');   
        $this->form_validation->set_rules('track_url', 'Адрес', 'required|trim|xss_clean');  
        
        $json=array();
        
        if ($this->form_validation->run() == FALSE)
		{ 
		   
           $this->output->set_header('HTTP/1.1 403 Forbiden');
           $json['error']= validation_errors();
           echo(json_encode($json));
         
        }
        else
        {        
           $data=array('track_name'=>$this->input->post('track_title'),'track_url'=>  $this->input->post('track_url'));
            
            if($this->insert_track($data))
            {  
            $json['title']= $this->polya['track_title'];
            $json['url']=$this->polya['track_url'];
             echo(json_encode($json));
            }
            else
            {
              $this->output->set_header('HTTP/1.1 403 Forbiden');
              $json['error']= "Ошибка при добавлении в базу данных..";
              echo(json_encode($json));
            }
        }*/
           
     
       
    }
    
    
    /*
    
    function insert_track($data)
    {
                        
    $this->db->trans_start();
    
    $result=$this->db->insert('tracks', $data);
    
    if($result)
    {        
        $id=$this->db->insert_id();
        $mydata= array('company_id'=>get_cookie('company_id', TRUE),'track_id'=>$id,'rating'=>0);
        $this->db->insert('tracks_companyes',$mydata);     
    }
    else
    {
        $this->db->trans_complete();
        return false;
    }   
    
    $this->db->trans_complete();
    return true;
    
    }*/
    
    
    function upload(){
        
        $upload_path="uploads/".$this->company;

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = "mp3";
        $config['max_size']	= 21000;     
        $config['encrypt_name'] = FALSE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('upl') == false) {          
    
    
           
          
       echo '{"status":"error","msg":"'.$this->upload->display_errors('','').'"}';
     
      
        }else{
            
            $data = $this->upload->data();
            
            $file_title=$data['raw_name'];
            
            $filename=iconv ('windows-1251', 'utf-8',$data['file_name']);
            
            $fullpath= $data['full_path'];
            
            
            $ThisFileInfo = $this->getid3->analyze($fullpath); 
            
             getid3_lib::CopyTagsToComments($ThisFileInfo);
            
             $artist_id=1;
             $artist=strtolower($ThisFileInfo['comments_html']['artist'][0]);             
             $title=strtolower($ThisFileInfo['comments_html']['title'][0]);
             
             $artist=html_entity_decode($artist, ENT_COMPAT,'utf-8'); 
             $title=html_entity_decode($title, ENT_COMPAT,'utf-8');                          
             
           //  $artist=mb_convert_encoding($artist,'UTF-8');
             //$title=mb_convert_encoding($title,'UTF-8');
             
            
              $this->db->trans_start();// открываем транзакцию
            
              if(strlen($artist)==0) $artist='unknown'; //если от клиента не был получен исполнитель
              else// если был получен
              {
                $find = false;
               //проверяем его наличие в бд и записываем id, если был найден
               $query=$this->db->get_where('artists',array('artist_name' => $artist));
               foreach ($query->result() as $row)
               {
                 $artist_id=$row->artist_id;
                 $find=true;
               }
               
               if(!$find)//если не найден
               {
                 $data=array('artist_name'=>$artist);
                 $artist_result=$this->db->insert('artists', $data);
                 if($artist_result)
                 $artist_id=$this->db->insert_id(); 
                 else 
                 {
                    echo '{"status":"error","msg":"'.$artist.'"}';
                  die;
                 }
                 
               }
              }
              
              // если не удалось извлеч из название из тега - то будет использовано имя файла                          
              if(strlen($title)==0) $title= $file_title;              
              
              
              
               //добавляем загрженный трек в базу 
                 $mydata= array('artist_id'=>$artist_id,'track_name'=>$title,'track_url'=>SITEURL."/".$upload_path."/".$filename);
               
                 $track_result=$this->db->insert('tracks',$mydata);
              
                 
                 if($track_result)
                 {
                   
                     $track_id=$this->db->insert_id();     
                     $tc_data= array('track_id'=>$track_id,'company_id'=>$this->company);
                     $tc_result=$this->db->insert('tracks_companyes',$tc_data);
                   
                     
                     if(!$tc_result){                        
                     echo '{"status":"error","msg":"Не удалось добавить трек в выбраную компанию.."}';
                      die;
                     }
                     else   echo '{"status":"success"}';
                 }
                 else
                 {
                    echo '{"status":"error","msg":"Не удалось добавить трек.."}';
                    die;
                 }
                 
        $this->db->trans_complete();//завершаем транзакцию
            
        }
        

    }
  
    
}