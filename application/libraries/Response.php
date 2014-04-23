<?php
//ElServer uses FastCGI, which has this bug with getallheaders() (users for CORS)
//https://bugs.php.net/bug.php?id=62596


/**
 * Contains methods to facilitate responses to clients.
 *
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class Response {
    private $cors = false;
    
    public function __construct() {
        $this->log('CONSTRUCT');
        $this->CI =& get_instance();
        
        //force not using cache
        //$this->forceNoCache();
     
        $cors = $this->CI->config->item('cors');
        if ($cors === true) $this->cors = true;
        if ($cors === false) {
            $this->log('CORS is DISABLED.');
            return true;
        }
        
        //handle CORS preflighted request
        if ($this->isPreflighted()) { 
            $this->log('________________________ C O R S ________________________');
            $origin = $_SERVER['HTTP_ORIGIN'];
            
            //check if it's an allowed domain origin
            if ($this->isAllowedOrigin($origin)) {
                //ok! send a repsonse allowing it
                $this->log("CORS: ORIGIN ALLOWED: $origin");
                $this->allowPreflightedRequest($origin);
            } else {
                $this->log("CORS: BAD BOY, ORIGIN NOT ALLOWED: $origin");
                die('ORIGIN NOT ALLOWED');
            }
        } else {
            $this->log('not preflighted');
        }
    }
    
    /**
     * Returns true if current request is a CORS preflighted one.
     * 
     * "Unlike simple requests (discussed above), "preflighted" requests first 
     * send an HTTP OPTIONS request header to the resource on the other domain, 
     * in order to determine whether the actual request is safe to send."
     * 
     * Read more here: 
     * https://developer.mozilla.org/en-US/docs/HTTP/Access_control_CORS
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @return boolean
     */
    private function isPreflighted() {
        $this->log('is preflighted?');
        //----------------------------------------------------------------------
        //getallheaders() polyfill
        if( !function_exists('getallheaders') ) {
            //$this->log('--------> FULL HEADERS: ' . print_r($_SERVER, true));
            
            function getallheaders() {
                $arh = array();
                $rx_http = '/\AHTTP_/';
                foreach($_SERVER as $key => $val) {
                    if( preg_match($rx_http, $key) ) {
                        $arh_key = preg_replace($rx_http, '', $key);
                        $rx_matches = array();
                        // do some nasty string manipulations to restore the original letter case
                        // this should work in most cases
                        $rx_matches = explode('_', $arh_key);

                        if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                            foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                            $arh_key = implode('-', $rx_matches);
                        }
                        $arh[$arh_key] = $val;
                    }
                }
                return( $arh );
            }
        }
        //----------------------------------------------------------------------
        
        $requestHeaders = getallheaders();
        //$this->log('REQUEST HEADERS: ' . print_r($requestHeaders, true));
        if (
            array_key_exists('Access-Control-Request-Method', $requestHeaders) or
            array_key_exists('Access-Control-Request-Headers', $requestHeaders)
        ) { 
            return true;
        }
        
        return false;
    }
    
    /**
     * Returns true if current request origin is allowed in CORS config.
     * False if not.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $origin
     * @return boolean
     */
    private function isAllowedOrigin($origin) {
        $cors = $this->CI->config->item('cors');
        if (in_array($origin, $cors['domains'])) {
            return true;
        }
        return false;
    }
    
    /**
     * Allows CORS preflighted request by forcing a response with propper
     * headers.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $origin
     */
    private function allowPreflightedRequest($origin) {
        $output = $this->CI->output;
        $finalOutput = $output->set_header("Access-Control-Allow-Origin: $origin")
               ->set_header("Access-Control-Allow-Methods: *")
               ->set_header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Options");
        exit;
    }
    
    /**
     * Logs passed message to default log.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param string $message
     */
    private function log($message) {
        log_message('debug', "[ RESPONSE ]--> $message");
    }
    
    /**
     * Sets headers to force browser to NOT using cache.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     */
    private function forceNoCache() {
        $this->CI->output
            ->set_header("Expires: Mon, 17 May 1998 05:00:00 GMT")
            ->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT")
            ->set_header("Cache-Control: no-store, no-cache, must-revalidate")
            ->set_header("Cache-Control: post-check=0, pre-check=0", false)
            ->set_header("Pragma: no-cache");
    }
    
    /**
     * Outputs a JSON response.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param array $data
     */
    public function json($data, $type='success', $message='') {
        $response = array(
            'type' => $type,
            'message' => $message,
            'data' => $data
        );
        
        //send response
        $this->send($response);
    }
    
    /**
     * Sends passed response AS IS.
     * 
     * @author Alberto Miranda <alberto.php@gmail.com>
     * @param mixed $response
     */
    public function send($response) {
        //get the output object
        $output = $this->CI->output;
        
        //if CORS is enabled check origin
        if ($this->cors) {
            //CORS: check if origin is allowed
            $origin = $_SERVER['HTTP_ORIGIN'];
            if (!$this->isAllowedOrigin($origin)) {
                die('ORIGIN NOT ALLOWED');
            }
        
            //CORS: set allowed domain origin
            $output->set_header("Access-Control-Allow-Origin: $origin")
                   ->set_header("Access-Control-Allow-Methods: *")
                   ->set_header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Options");
        }
        
        //send output
        $output->set_content_type('application/json')
               ->set_output( json_encode($response) );
    }
    
}

/**
 * ResponseType enum
 * 
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
final class ResponseType {
    const SUCCESS = 'success';
    const ERROR = 'error';
}