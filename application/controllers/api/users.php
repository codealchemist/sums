<?php
require APPPATH.'/libraries/REST_Controller.php';

class Users extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User');
    }
    
    /**
     * User login.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function login_post() {
        $user = new User("533ab6c7f263a0174d0d5db0");
        $this->response($user->toArray());
    }
    
    public function index_get() {
        //$this->response('welcome to Users API endpoint', 200);
        $this->response('ok', 200);
    }
    
    public function index_options() {
        $this->response(null, 200);
    }
    
    /**
     * Update user.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_put() {
        $this->response('user PUT, yeah!');
    }
    
    /**
     * Create user.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_post() {
        $params = $this->_args; //get PUT params
        log_message('debug', "[ SIGNUP ]------------> " . print_r($params, true));
        $user = new User;
        $user->setName($params['name']);
        $user->setPassword($params['password']);
        $user->setEmail($params['email']);
        $user->setCreatedDate(); //set created date to current date
        $id = $user->save();
        
        //response
        $this->response(array("_id" => $id), 201);
    }
    
    public function index_delete() {
        $this->response('user DELETE, yeah!');
    }
    
    public function get_get() {
        //receive id param from GET request
        if(!$this->get('id')) {
            $this->response(NULL, 400);
        }
        
        $user = array(
            'name' => 'Kirk',
            'rank' => 'Captain',
            'ship' => 'USS Enterprise'
        );
        
        $this->response($user, 200);
    }
}