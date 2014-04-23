<?php
require APPPATH.'/libraries/REST_Controller.php';

/**
 * Handles signup.
 *
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class Signup extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User');
    }
    
    public function index_put() {
        $params = $this->_args; //get PUT params
        $user = new User;
        $user->setName($params['name']);
        $user->setPassword($params['password']);
        $user->setEmail($params['email']);
        $user->setCreatedDate(); //set created date to current date
        $id = $user->save();
        
        log_message('debug', "[ SIGNUP ]------------> " . print_r($params, true));
        $this->response(array("_id" => $id), 201);
    }
}
