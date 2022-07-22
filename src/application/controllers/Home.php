<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        $this->load->library('hotpepper');
    }

    public function index()
    {
        $data['users'] = $this->users_model->getUsers();

        $lat = '35.68503';
        $lng = '139.78271';
        
        return $this->load->view('home', $data);
    }
}