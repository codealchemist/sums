<?php
/**
 * Handles access control to the application.
 *
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class Access {
    private $publicControllers = array('login');
    
    public function __construct() {
        $this->CI =& get_instance();
        
        //force not using cache
        $this->forceNoCache();
        
        //check if login is required
        $controller = $this->CI->uri->segment(1);
        $app = APPLICATION;
        if (!$this->isLoginRequired($app, $controller)) return false;
        
        //check if it's a public controller
        //for example, login will always be a public controller, so users can
        //login
        if ($this->isPublicController($controller)) return false;
        
        //login required: check if logged in
        $this->log("acccessing PROTECTED area '$app/$controller'");
        if (!$this->isLoggedIn()) {
            $this->log("LOGIN REQUIRED!");
            return $this->showLogin();
        }
        
        //OK, already logged in
        $this->log("USER ACCESS: ALREADY LOGGED IN");
    }
    
    /**
     * Logs passed message to default log.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $message
     */
    private function log($message) {
        log_message('debug', "[ ACCESS ]--> $message");
    }
    
    /**
     * Returns true if we have a logged in User.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @return boolean
     */
    private function isLoggedIn() {
        $sessionId = $this->CI->session->userdata('session_id');
        $email = $this->CI->session->userdata('email');
        $app = $this->CI->session->userdata('app');
        
        if ($email) {
            $this->log("USER ACCESS: Session ID: $app/$sessionId");
            $this->log("USER ACCESS: $app/$email");
            return true;
        }
        return false;
    }
    
    /**
     * Shows login view.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    private function showLogin() {
        header("Location: /login");
    }
    
    /**
     * Returns true if passed controller inside passed app requires login.
     * False if not, meaning it's public.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com> 
     * @param string $app
     * @param string $controller
     * @return boolean
     */
    private function isLoginRequired($app, $controller) {
        $config = $this->CI->config->item('access');
        if (empty($config)) {
            $this->log('WARNING: NO ACCESS config found.');
            return false;
        }
        
        if (!array_key_exists('protectedApps', $config)) {
            $this->log('WARNING: "protectedApps" not defined in ACCESS config.');
            return false;
        }
        $protectedApps = $config['protectedApps'];
        //echo "APP: $app / CONTROLLER: $controller"; print_r($protectedApps); exit;
        
        //check if app requires login
        if (!in_array($app, $protectedApps)) {
            $this->log("APP '$app' is not protected. Access granted.");
            return false;
        }
        
        return true;
    }
    
    /**
     * Returns true if passed controller name is public (accessible without login).
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com> 
     * @param string $controllerName
     * @return boolean
     */
    private function isPublicController($controllerName) {
        return in_array($controllerName, $this->publicControllers);
    }
    
    /**
     * Sets headers to force browser to NOT using cache.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    private function forceNoCache() {
        // Date in the past
        header("Expires: Mon, 17 May 1998 05:00:00 GMT");
        // always modified
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        // HTTP/1.1
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        // HTTP/1.0
        header("Pragma: no-cache");
    }
}