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
        $data['suppliers'] = $this->model->getAll('tb_supplier')->result();
        //get user input, later this become flashdata 
        $data['asd'] = $this->input->post('nama_supplier');
        //get data obat
        $data['obats'] = $this->model->getAll('tb_obat')->result();
        //get data temporary pembelian
        $data['temp'] = $this->model->getAll('tb_pembelian_temp')->result();
		$this->load->view('template',$data);   
    }
}