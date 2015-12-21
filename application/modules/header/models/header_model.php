<?php
class Header_Model extends CI_Model {
    
    private $table, $mname;

    public function __construct() {
        parent::__construct();
    }
    
    public function index($mname)
    {
        $this->mname=$mname;
        $this->load->library('session');
      
        if($this->session->userdata('login')) // если залогинился
        { 
            /*Проверяем, если пользователь не админ - то выводим один header, иначе выводим другой*/
            $cab="<i class='icon-briefcase icon-2x pull-left'></i>";
            $ext="<i class='icon-remove-sign icon-2x pull-left'></i>";
            if(!$this->isadmin($this->session->userdata('user_id'))) 
            $this->tp->show_msg('<i class="icon-briefcase icon-2x"></i> <span><a href="/cabinet" data-step="1" data-intro="'.$cab.' В личном кабинете находятся данные о ваших компаниях и инструменты для работы сними." data-position="bottom">Личный кабинет</a>  <a id="out" href="/auth/logout" data-step="2" data-intro="'.$ext.' Воспользуйтесь данной ссылкой чтобы выйти из своего аккаунта." data-position="bottom">Выход</a></span>', '','LOGIN_INFO');
            else
            $this->tp->show_msg('<i class="icon-briefcase icon-2x"></i> <span><a href="/admin">Админ панель</a> <a href="/cabinet">Личный кабинет</a>  <a href="/auth/logout">Выход</a></span>', '','LOGIN_INFO');
                      
        }
        else{
            
            $this->tp->show_msg('<i class="icon-briefcase icon-2x"></i> <span><a href="/auth/login">Войти</a></span>', '','LOGIN_INFO');
        }
        
    }
    
    //Проверка роли пользователя в бд
    function isadmin($user_id)
    {
        
        $result=false;
        $this->db->select('role');
        $this->db->from('users');
        $this->db->where('user_id',$user_id);
        $query=$this->db->get();
        
        foreach ($query->result() as $row)
        {
            if($row->role=="admin")
            {
                $result=true;
            }
        }

        return $result;

    }
    
}