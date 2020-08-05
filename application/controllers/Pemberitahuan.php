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
   
            
                $d = $this->DataModel->getData('tb_pengaturan')->row();
        
                if($d){
                    $hasil = array();
    
                    
    
                    $data['pembelian'] = $this->DataModel->getData('tb_pembelian')->num_rows();
                    $data['penjualan'] = $this->DataModel->getData('tb_penjualan')->num_rows();
                    $data['nama_apotek'] = $d->nama_apotek;
                    $data['alamat_apotek'] = $d->alamat_apotek;
                    $data['notifikasi_expired'] = $d->notifikasi_expired;
                    $data['stok_minimal'] = $d->stok_minimal;
                    
                                
                $obats = $this->DataModel->select('*');
                $obats = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = detail.detail_obat_id','INNER');
                $obats = $this->DataModel->getJoin('tb_pembelian as pembelian','pembelian.pembelian_id = detail.detail_id_transaksi','INNER');
                $obats = $this->DataModel->getJoin('tb_supplier as suplier','suplier.supplier_id = pembelian.pembelian_id_supplier','INNER');
               
                $obats = $this->DataModel->getData('tb_pembelian_detail AS detail');
    
                foreach($obats->result() as $obat){
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
                $data['notifikasi'] = $hasil;
          
                $this->load->view('template',$data);   
        
          
        }
		
    }

 
}