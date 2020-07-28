<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller {

    function __construct(){
		parent::__construct();
        //check if the user login or not
        //applicalble in all controller except auth
        $this->model->auth();
        //set default timezone
        date_default_timezone_set("Asia/Jakarta");
	}
    

    public function index()
    {
        $data['content'] = 'Pengaturan';
        $data['pagetitle'] = 'Pengaturan';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '0';
        //get suppliers data
        $data['pengaturan'] = $this->model->getAll('tb_pengaturan')->row();
		$this->load->view('template',$data);   
    }

    public function simpan_data_apotek()
    {
        $nama_apotek = $this->input->post();
        $alamat_apotek = $this->input->post();
    }
}