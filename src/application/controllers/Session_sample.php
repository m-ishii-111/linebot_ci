<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Session_sample extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        header('Content-Type: text/html; charset=UTF-8');
        $this->load->library('session');
    }

    function index()
    {
        if (!$this->session->userdata('count'))
        {
            $this->session->set_userdata('count', 1);
        }
        else
        {
            $count = $this->session->userdata('count');
            $count++;
            $this->session->set_userdata('count', $count);
        }
        echo '訪問回数:' . $this->session->userdata('count');
    }

    function destroy()
    {
        $this->session->sess_destroy();
        echo 'セッションをクリアしました。';
    }
}