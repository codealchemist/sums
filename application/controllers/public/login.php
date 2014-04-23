<?php
class Login extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $output = $this->twig->render('login.twig', array(
            'appName' => $this->config->item('appName'),
            'version' => $this->config->item('version')
        ));
        die($output);
    }
}