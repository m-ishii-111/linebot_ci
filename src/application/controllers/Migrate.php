<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request())
        {
            show_404();
            exit;
        }
        $this->load->library('migration');
    }

    public function current()
    {
        if ($this->migration->current())
        {
            log_message('error', 'Migration Success.');
        }
        else
        {
            log_message('error', $this->migration->error_string());
        }
    }

    public function rollback($version)
    {
        if ($this->migration->version($version))
        {
            log_message('error', 'Migration Success.');
        }
        else
        {
            log_message('error', $this->migration->error_string());
        }
    }

    public function latest()
    {
        if ($this->migration->latest())
        {
            log_message('error', 'Migration Success.');
        }
        else
        {
            log_message('error', $this->migration->error_string());
        }
    }
}