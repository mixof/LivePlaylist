<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{
    
    
    
	function company_get()
    {
        
        $this->load->model('api/api_model');
     
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }
        
        $company = $this->api_model->get_company($this->get('id'));
       
		
    	
    	
        if($company)
        {
            $this->response($company, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('status' => false,'error' => 'Компания не найдена'), 404);
            
        }
    }
    
    
    function tracks_get()
    {
         $this->load->model('api/api_model');
     
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }
          
         $tracks = $this->api_model->get_tracks($this->get('id'));
       
		
    	
    	
        if($tracks)
        {
            $this->response($tracks, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('status' => false,'error' => 'Треки не найдены'), 404);
        }
    }
    
    
    function track_put()
    {
        $this->load->model('api/api_model');
        
        if(!$this->put('id'))
        {
        	$this->response(NULL, 400);
        }
        
        $track= $this->api_model->update_track_rate($this->put('id'));
        
        if($track)
        {
            $this->response('success!', 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('status' => false,'error' => 'Не удалось изменить рейтинг'), 404);
        }
        
    }
    
}