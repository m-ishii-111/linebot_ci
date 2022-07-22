<?php

class MakeTable extends CI_Controller
{
    function index()
    {
        $this->load->library('table');

        $tmpl = ['table_open' => '<table border="1" cellpadding="4" cellspacing="0">'];
        $this->table->set_template($tmpl);

        $data = [
            ['名前', '色', 'Size'],
            ['フレッド', 'ブルー', 'Small'],
            ['マリー', '赤', 'Large'],
            ['ジョン', '緑', 'Medium']
        ];

        echo $this->table->generate($data);
    }
}