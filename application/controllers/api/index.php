<?php
require APPPATH.'/libraries/REST_Controller.php';

class Index extends REST_Controller {
    public function index_get() {
        return $this->response('[ GET ]--> Welcome to SUMS API!');
    }
    
    public function index_post() {
        return $this->response('[ POST ]--> Welcome to SUMS API!');
    }
    
    public function index_put() {
        return $this->response('[ PUT ]--> Welcome to SUMS API!');
    }
    
    public function index_delete() {
        return $this->response('[ DELETE ]--> Welcome to SUMS API!');
    }
}