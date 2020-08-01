<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Notifikasi extends REST_Controller {

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
                $hasil = array();

                

                $data['pembelian'] = $this->DataModel->getData('tb_pembelian')->num_rows();
                $data['penjualan'] = $this->DataModel->getData('tb_penjualan')->num_rows();
                $data['nama_apotek'] = $d->nama_apotek;
                $data['alamat_apotek'] = $d->alamat_apotek;
                $data['notifikasi_expired'] = $d->notifikasi_expired;
                $data['stok_minimal'] = $d->stok_minimal;
                
                            
            $data = $this->DataModel->select('*');
            $data = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = detail.detail_obat_id','INNER');
            $data = $this->DataModel->getJoin('tb_pembelian as pembelian','pembelian.pembelian_id = detail.detail_id_transaksi','INNER');
            $data = $this->DataModel->getJoin('tb_supplier as suplier','suplier.supplier_id = pembelian.pembelian_id_supplier','INNER');
           
            $data = $this->DataModel->getData('tb_pembelian_detail AS detail');

            foreach($data->result() as $obat){
                $waktuawal  = date_create($obat->detail_expired); //waktu di setting

                $waktuakhir = date_create(); //2019-02-21 09:35 waktu sekarang
                
                $diff  = date_diff($waktuawal, $waktuakhir);

                
                if($diff->d < $d->notifikasi_expired){
                    $ar = array(
                        'id_notif'=> "expired_".$obat->obat_id,
                        'expired_day'=> $diff->d,
                        'expired_date'=> $obat->detail_expired,
                        'stok_obat'=> $obat->obat_stok,
                        'stok_minimal'=> $d->stok_minimal,
                        'obat' => $obat->obat_nama,
                        'obat_id'=> $obat->obat_id,
                        'supplier'=> $obat->supplier_nama,
                        'status' => 'Obat Expired',
                        'message' =>  'Obat <b>'.$obat->obat_nama.'</b> hampir kadaluarsa nih,Segera ganti dengan yang baru'
                    );

                    array_push($hasil,$ar);
                }
                if($obat->detail_jumlah < $d->stok_minimal){
                    $ar = array(
                        'id_notif'=> "stok_".$obat->obat_id,
                        'expired_day'=> $diff->d,
                        'expired_date'=> $obat->detail_expired,
                        'stok_obat'=> $obat->obat_stok,
                        'stok_minimal'=> $d->stok_minimal,
                        'obat' => $obat->obat_nama,
                        'obat_id'=> $obat->obat_id,
                        'supplier'=> $obat->supplier_nama,
                        'status'=> 'Update Stok Obat',
                        'message'=> 'Obat <b>'.$obat->obat_nama.'</b> hampir habis, Segera tabahkan persediaan !'
                    );

                    array_push($hasil,$ar);
                }
            }

                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $hasil,
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