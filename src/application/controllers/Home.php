<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
    }

    public function index()
    {
        $data['users'] = $this->users_model->getUsers();
        
        return $this->load->view('home', $data);
    }
}