<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pemberitahuan extends CI_Controller {

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
        $data['content'] = 'Pemberitahuan';
        $data['pagetitle'] = 'Pemberitahuan';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '0';
        //get suppliers data
        $data['pengaturan'] = $this->model->getAll('tb_pengaturan')->row();
		$this->load->view('template',$data);   
    }

    public function simpan_data_apotek()
    {
        $nama_apotek = $this->input->post('nama_apotek');
        $alamat_apotek = $this->input->post('alamat_apotek');
        

         //insert data in array
            $data = array(
             'nama_apotek' => $nama_apotek,
             'alamat_apotek' => $alamat_apotek,
             );
             //save the data
             $impan =$this->model->update(['id_pengaturan' => 1], $data,'tb_pengaturan');
             
             if($simpan){
                $this->session->set_flashdata('stat','sukses');
                redirect('pengaturan/index');
             }else{
                $this->session->set_flashdata('stat','gagal');
                redirect('pengaturan/index');
             }
    }
    public function simpan_notifikasi()
    {
        $exp= $this->input->post('expired');
        $stok = $this->input->post('minimal_stok');


         //insert data in array
         $data = array(
             'notifikasi_expired' => $exp,
             'stok_minimal' => $stok,
             );
             $impan =$this->model->update(['id_pengaturan' => 1], $data,'tb_pengaturan');
             
             if($simpan){
                $this->session->set_flashdata('stat','sukses');
                redirect('pengaturan/index');
             }else{
                $this->session->set_flashdata('stat','gagal');
                redirect('pengaturan/index');
             }
    }
}