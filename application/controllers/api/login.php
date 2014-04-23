<?php
require APPPATH.'/libraries/REST_Controller.php';

/**
 * User login.
 *
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class login extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User');
    }
    
    /**
     * Verifies passed login data.
     * If correct creates a new session for user.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    public function index_post() {
        $params = $this->_args;
        $user = new User;
        $user->setEmail($params['email']);
        $user->setPassword($params['password']);
        
        //check login
        $isValidLogin = $user->login();
        if ($isValidLogin) {
            //login OK!
            log_message('debug', "LOGIN OK: {$params['email']}");
            $user->setLoginToken();
            $user->setLastLoginDate();
            $user->save();
            
            return $this->response($user->toArray(), 200);
        } 
        
        //INVALID login
        return $this->response(null, 401);
    }
}
