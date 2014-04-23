<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ipwhitelist
 *
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class Ipwhitelist {
    public function __construct() {
        $this->CI =& get_instance();
        
        //get ip whitelist flipping the array to get IPs as keys
        $whitelist = $this->CI->config->item('ipWhitelist');
        if (empty($whitelist)){
            $this->bye();
        }
            
        $whitelist = array_flip($whitelist);
        $clientIp = $this->getClientIp();
        
        if (!array_key_exists($clientIp, $whitelist)) {
            $this->bye();
        }
        
        $who = $whitelist[$clientIp];
        log_message('debug', __METHOD__ . ": ---> WHITELISTED IP: $clientIp ($who)");
    }
    
    /**
     * Returns client IP Address.
     * 
     * @author Alberto Miranda <alberto@glyder.co>
     * @return string
     */
    private function getClientIp() {
        if (!empty($_SERVER ["HTTP_X_FORWARDED_FOR"])) {
            $source_ips = explode(',', $_SERVER ["HTTP_X_FORWARDED_FOR"]);
            $source_ip = $source_ips [0];
        } else {
            $source_ip = $_SERVER ['REMOTE_ADDR'];
        }

        return $source_ip;
    }
    
    /**
     * The way is shut.
     * No access to the Admin. Exits.
     * 
     * @author Alberto Miranda <alberto@glyder.co>
     */
    private function bye() {
        die('<html><h1 style="font:normal normal normal 24px arial">THE WAY IS SHUT.</h1></html>');
    }
}
