<?php
require APPPATH.'/libraries/REST_Controller.php';

class Collaboration extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Sum');
        $this->load->model('User');
    }
    
    /**
     * Returns requested collaboration.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function get_get() {
        $sumId = $this->uri->segment(3);
        $collaborationId = $this->uri->segment(4);
        $sum = new Sum($sumId);
        if (!$sum->wasLoaded()) {
            //unable to load sum
            log_message('debug', '[ GET COLLABORATION ]--> unable to load SUM: ' . $sumId);
            $this->response(null, 404);
        }
        
        //get collaboration
        $params = array(
            'userId' => null, //TODO: get user id from session
            'collaborationId' => $collaborationId
        );
        $collaboration = $sum->getCollaboration($params);
        
        //return collaboration
        $this->response($collaboration, 200);
    }
    
    /**
     * Adds new collaboration to sum.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_put() {
        $params = $this->_args; //get PUT params
        //log_message('debug', print_r($params, true)); return false;
        $sumId = $params['sumId'];
        
        //load sum
        $sum = new Sum($sumId);
        
        //add collaboration and save it
        $sum->addCollaboration($params);
        $sum->save();
        
        //response
        $this->response(null, 204);
    }
    
    /**
     * Updates existing collaboration on passed sum.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_post() {
        $params = $this->_args; //get PUT params
        //log_message('debug', print_r($params, true)); return false;
        $sumId = $params['sumId'];
        
        //load sum
        $sum = new Sum($sumId);
        
        //add collaboration and save it
        $sum->updateCollaboration($params);
        $sum->save();
        
        //response
        $this->response(null, 204);
    }
}