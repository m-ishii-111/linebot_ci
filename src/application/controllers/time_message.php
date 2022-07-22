<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_message extends CI_Controller
{
    function index()
    {
        $data['title'] = '時刻メッセージ';
        $data['now_time'] = date("H時i分s秒");
        $now_hour = date("H");
        if ($now_hour == 12)
        {
            $data['message'] = 'お昼です。';
        }
        elseif ($now_hour == '3')
        {
            $data['message'] = '3時のピーナッツバーのおやつです。';
        }
        else
        {
            $data['message'] = '今日も頑張って！';
        }

        $this->load->view('time_message_view', $data);
    }
}