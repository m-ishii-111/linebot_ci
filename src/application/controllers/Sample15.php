<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sample15 extends CI_Controller
{
    public function index()
    {
        log_message('debug', 'logtest');
        echo 'ログテスト中';
    }
}