<?php
class Index extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $output = $this->twig->render('index.twig', array(
            'appName' => $this->config->item('appName')
        ));
        die($output);
    }
}