<?php
class Deletetrack_Model extends CI_Model 
{
     private $table, $mname, $polya, $tracks;

    public function __construct() {
        parent::__construct();
        $this->polya=array('track_id'=>'','company_id'=>'');        
       
       
   
    }
    
    public function index($mname)
    {
        if($this->input->post())
        {
       
        $this->tracks=json_decode($this->input->post('tracks'));        
        
        if(count($this->tracks)>0) // если пришедший массив с индексами не пуст
        {
           
              $pathes=$this->get_pathes($this->tracks); // получаю пути к файлам
              
              if(count($pathes)>0) // если получены
              {
                
                 $tables = array('tracks', 'tracks_companyes');
                 $this->db->where_in('track_id',$this->tracks);
                 $this->db->delete($tables);                 
                 $res=$this->db->affected_rows();                 
                 
            
                 if($res){ // если удаление выполнено  
                        
                     foreach($pathes as $row) // перебираю пути к файлам
                      {
                        $tmp=str_replace(SITEURL."/",'',$row->track_url);
                        $path=iconv("UTF-8", "CP1251",$tmp);
                        if(file_exists($path))
                        {
                        @unlink($path);     // удаляю файл    
                          
                        }                     
                      }       
                                 
               $json['status']= "ok";               
               echo(json_encode($json));
                
                }
                else //если не удалось удалить из базы 
                {
                  $json['status']= "error";
                 $json['msg']= "Ошибка при удалении..";                
                 echo(json_encode($json));
                }
                
                
              }
              else // если не найдены
              {
                 $json['status']= "error";
                 $json['msg']= "Не удалось обнаружить выбранные композиции..";                
                 echo(json_encode($json));
              }             
           
                
       
        }else{   
        
         $json['status']= "none";
          echo(json_encode($json));}        
             
        
        } else  redirect(SITEURL);   // если массив post пуст - делаю редирект на домашнюю страницу
      
     
    }
    
    function get_pathes($tracks_ids)
    {
        $this->db->select('track_url');           
        $this->db->where_in('track_id',$tracks_ids);
        $query=$this->db->get('tracks');
                     
          return $query->result();
    }
    
}

?>