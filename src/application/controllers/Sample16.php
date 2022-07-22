<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sample16 extends CI_Controller
{
    public function index()
    {
        $this->load->helper('sample');
        echo hr(1);
    }
}