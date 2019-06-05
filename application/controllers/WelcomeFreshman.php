<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WelcomeFreshman extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('file');
    }

    public function index()
    {
        $this->session->unset_userdata('google_data');
        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('gmail');
        $this->session->sess_destroy();
        $this->load->view('welcome/welcomeFreshman');
    }

    public function markdownLearn()
    {
        $this->load->view('markdownLearn.php');
    }

    public function markdownData()
    {
        $data = file_get_contents('./md/markdownlearn.md');
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
