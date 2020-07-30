<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Home extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Model');
        $this->load->model('DataModel');
       
        // $this->methods['komentar_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['komentar_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['komentar_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        
            $d = $this->DataModel->getData('tb_pengaturan')->row();
    
            if($d){
                $data['pembelian'] = $this->DataModel->getData('tb_pembelian')->num_rows();
                $data['penjualan'] = $this->DataModel->getData('tb_penjualan')->num_rows();
                $data['nama_apotek'] = $d->nama_apotek;
                $data['alamat_apotek'] = $d->alamat_apotek;
                $data['notifikasi_expired'] = $d->notifikasi_expired;
                $data['stok_minimal'] = $d->stok_minimal;
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data,
                ), REST_Controller::HTTP_OK);
            }else{
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
    
      
    }

}