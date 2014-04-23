<?php
require APPPATH.'/libraries/REST_Controller.php';

class Token extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User');
    }
    
    /**
     * Validates passed login token for passed user.
     * Will return an HTTP code for each possible state:
     * 204 - valid
     * 401 - invalid / expired
     * 
     * @author Alberto Mirana <alberto.php@gmail.com>
     */
    public function validateLogin_post() {
        $params = $this->_args; //get POST params
        log_message('debug', 'validate login: params: ' . print_r($params, true));
        
        $id = $params['_id'];
        $token = $params['token'];
        try {
            $user = new User($id);
        } catch(Exception $e) {
            //INVALID TOKEN
            $this->response(null, 401);
        }
        
        $storedToken = $user->getLoginToken();
        if ($storedToken == $token) {
            //VALID TOKEN
            $this->response(null, 204);
        }
        
        //INVALID TOKEN
        $this->response(null, 401);
    }
}