<?php
/**
 * Allows reusing a single connection to MongoDB thru MongoQB.
 * 
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class MongoDBAdapter {
    private static $instance = null;
    private function __clone() {} //disallow cloning
    public function __construct() { //called when autoloading by CI
        return self::getInstance(); //ensure reusing
    }
    
    /**
     * Returns an instance of MongoQB.
     * 
     * @return \MongoQB\Builder
     */
    public static function getInstance() {
        if (self::$instance) return self::$instance;
        
        $CI =& get_instance();
        $config = $CI->config->item('mongodb');
        $username = $config['username'];
        $password = $config['password'];
        $hostname = $config['hostname'];
        $port = $config['port'];
        $database = $config['database'];
        $dsn = "mongodb://$username:$password@$hostname:$port/$database";
        
        $qb = new \MongoQB\Builder(array(
            'dsn' => $dsn
        ));
        
        self::$instance = $qb;
        return self::$instance;
    }
}
