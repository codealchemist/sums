<?php
require APPPATH.'/libraries/REST_Controller.php';

class Sums extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Sum');
    }
    
    /**
     * Returns requested sum.
     * Used to display sum to collaborators/viewers with a shared link.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function get_get() {
        log_message('debug', '+ GET SUM');
        $id = $this->uri->segment(3);
        $sum = new Sum($id);
        if ($sum->wasLoaded()) {
            $this->response($sum->toArray(), 200);
        }
        
        $this->response(null, 404);
    }
    
    /**
     * Creates a new sum.
     * Collaborators already using the app will be added by user id.
     * The ones not using the app will be invited using their email address.
     * Server answers with the id of the newly created sum.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_put() {
        log_message('debug', '+ PUT SUM');
        $params = $this->_args; //get PUT params
        //log_message('debug', print_r($params, true)); return false;
        
        $sum = new Sum;
        $sum->fill($params);
        $id = $sum->save();
        
        $response = array("_id" => $id);
        $this->response($response, 201);
    }
    
    /**
     * Updates existing sum.
     * Will update "updatedDate" to current date.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_post() {
        log_message('debug', '+ POST SUM');
        $params = $this->_args; //get POST params
        //log_message('debug', print_r($params, true)); return false;
        
        $sum = new Sum;
        $sum->fill($params);
        $id = $sum->save();
        
        $response = array("_id" => $id);
        $this->response($response, 201);
    }
}