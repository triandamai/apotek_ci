<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Penjualan extends REST_Controller {

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

    public function penjualan_temp_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {

            
        $data = $this->DataModel->select('*');
        $data = $this->DataModel->getJoin('tb_pembelian_detail as stok','stok.detail_id = detail.temp_id_stok','INNER');
        $data = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = stok.detail_obat_id','INNER');

        $data = $this->DataModel->order_by("detail.temp_id","ASC");
        $data = $this->DataModel->getData('tb_penjualan_temp AS detail');
            if($data && $data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
                ), REST_Controller::HTTP_OK);
            }else{
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
    
        }else {
            
            $data = $this->DataModel->select('*');
            $data = $this->DataModel->getJoin('tb_pembelian_detail as stok','stok.detail_id = detail.temp_id_stok','INNER');
            $data = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = stok.detail_obat_id','INNER');
            $data = $this->db->where("detail.detail_id_transaksi ",$id);
            $data = $this->DataModel->order_by("detail.temp_id","ASC");
            $data = $this->DataModel->getData('tb_penjualan_temp AS detail');    

            if($data && $data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
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

    public function penjualan_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {

            $data = $this->model->getAll('tb_penjualan');
            if($data && $data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
                ), REST_Controller::HTTP_OK);
            }else{
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
    
        }else {
            
            $data = $this->model->getById('tb_penjualan',array('penjualan_id'=>$id));
            if($data && $data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
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
    public function penjualan_detail_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {

            $data = $this->DataModel->select('*');
            $data = $this->DataModel->getJoin('tb_pembelian_detail as p','p.detail_id = c.detail_id_stok','INNER');
            $data = $this->DataModel->getJoin('tb_obat as o','o.obat_id = p.detail_obat_id','INNER');
            $data = $this->DataModel->order_by("c.detail_id","ASC");
            $data = $this->DataModel->getData('tb_penjualan_detail AS c');
            if($data && $data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
                ), REST_Controller::HTTP_OK);
            }else{
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
    
        }else {
            $data = $this->DataModel->select('*');
            $data = $this->DataModel->getJoin('tb_pembelian_detail as p','p.detail_id = c.detail_id_stok','INNER');
            $data = $this->DataModel->getJoin('tb_obat as o','o.obat_id = p.detail_obat_id','INNER');
            $data = $this->db->where("c.detail_id_transaksi ",$id);
            $data = $this->DataModel->order_by("c.detail_id","ASC");
            $data = $this->DataModel->getData('tb_penjualan_detail AS c');
            if($data && $data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
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
    public function penjualan_temp_post(){

         $jsonArray = json_decode(file_get_contents('php://input'),true);

        
        //if data not exist, insert new data
        
        if($jsonArray['temp_id'] != null){
            $check = $this->model->getById('tb_penjualan_temp', ['temp_id' => $jsonArray['temp_id']])->num_rows();
            if($check && $check >= 0){
                $ambilharga = $this->model->getSingleValue('tb_pembelian_detail', 'detail_harga_jual', ['detail_id' => $jsonArray['temp_id_stok']]);
                $jumlah = $this->model->getSingleValue('tb_penjualan_temp', 'temp_jumlah', ['temp_id' => $jsonArray['temp_id']]);

                $new = $jsonArray['temp_jumlah'] + $jumlah->temp_jumlah;
                $harga = $ambilharga->detail_harga_jual * $new ;
 
		        $data = array(
                    'temp_id_stok' => $jsonArray['temp_id_stok'],
			        'temp_jumlah' => $new,
                    'temp_totalharga' => $harga,
                );
                $simpan = $this->model->update(['temp_id' =>$jsonArray['temp_id']], $data,'tb_penjualan_temp');
                
                
                if($simpan){
                    return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => null,
                    ), REST_Controller::HTTP_OK);
                }else{
                    return $this->response(array(
                    "status"                => false,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Menyimpan Data code:0",
                    "data"                  => null,
                    ), REST_Controller::HTTP_OK);
                }
            }else{
                $ambilharga = $this->model->getSingleValue('tb_pembelian_detail', 'detail_harga_jual', ['detail_id' => $jsonArray['temp_id_stok']]);
                $harga = $ambilharga->detail_harga_jual * $jsonArray['temp_jumlah'];
 
		        $data = array(
                'temp_id_stok' => $jsonArray['temp_id_stok'],
			    'temp_jumlah' => $jsonArray['temp_jumlah'],
                'temp_totalharga' => $harga,
                );
                $simpan = $this->model->save($data,'tb_penjualan_temp');
                if($simpan){
                    return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => null,
                    ), REST_Controller::HTTP_OK);
                }else{
                    return $this->response(array(
                    "status"                => false,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Menyimpan Data code : 1",
                    "data"                  => null,
                    ), REST_Controller::HTTP_OK);
                }
            }
        }else{
            $ambilharga = $this->model->getSingleValue('tb_pembelian_detail', 'detail_harga_jual', ['detail_id' => $jsonArray['temp_id_stok']]);
            $harga = $ambilharga->detail_harga_jual * $jsonArray['temp_jumlah'];
 
		    $data = array(
                'temp_id_stok' => $jsonArray['temp_id_stok'],
			    'temp_jumlah' => $jsonArray['temp_jumlah'],
                'temp_totalharga' => $harga,
            );
            $simpan = $this->model->save($data,'tb_penjualan_temp');
        //   var_dump($simpan);
            if($simpan){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }else{
                return $this->response(array(
                    "status"                => false,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Menyimpan Data code : 2",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
        }
     
     

    }
    public function penjualan_post(){

        $jsonArray = json_decode(file_get_contents('php://input'),true);
       

    
            //get user input
            $subtotal = $jsonArray['subtotal'];
            //generate id transaksi
            $idtransaksi = 'PNJ-'.date('YmdHis');
            $tanggal = date('Y-m-d');
            //insert data in array
            $data = array(
                'penjualan_id_transaksi' => $idtransaksi,
                'penjualan_tanggal' => $tanggal,
                'penjualan_subtotal' => $subtotal,
            );
                //save the data
            if($this->model->save($data,'tb_penjualan')){
                $insertid =  $this->db->insert_id();
                //insert batch data from temporary to penjualan_detail table
                $data_insert = array();
                $query = $this->db->get('tb_penjualan_temp');
                foreach ($query->result() as $row) {
                    $dataloop = array(
                        'detail_id_transaksi' => $insertid,
                        'detail_id_stok' => $row->temp_id_stok,
                        'detail_jumlah' => $row->temp_jumlah,
                        'detail_harga' => $subtotal
                    );
                    array_push($data_insert,$dataloop);
              
                }
                //empty the temporary table
                    $simpan = $this->DataModel->save_batch("tb_penjualan_detail",$data_insert);
                    if($simpan){
                        $this->db->empty_table('tb_penjualan_temp'); 
                        return $this->response(array(
                            "status"                => true,
                            "response_code"         => REST_Controller::HTTP_OK,
                            "response_message"      => "Berhasil",
                            "data"                  => null,
                        ), REST_Controller::HTTP_OK);
                    }else{
                        return $this->response(array(
                            "status"                => false,
                            "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                            "response_message"      => "Gagal Mendapatkan Data",
                            "data"                  => null,
                        ), REST_Controller::HTTP_OK);
                    }
            }else{
                return $this->response(array(
                    "status"                => false,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
                //get the last insert id
   }
   public function penjualan_temp_hapus_post(){

    $jsonArray = json_decode(file_get_contents('php://input'),true);
    $data = $this->model->delete(array("temp_id"=>$jsonArray['id']),'tb_penjualan_temp');
 
   if($data){
       return $this->response(array(
           "status"                => true,
           "response_code"         => REST_Controller::HTTP_OK,
           "response_message"      => "Berhasil",
           "data"                  => null,
       ), REST_Controller::HTTP_OK);
   }else{
       return $this->response(array(
           "status"                => false,
           "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
           "response_message"      => "Gagal Mendapatkan Data",
           "data"                  => null,
       ), REST_Controller::HTTP_OK);
   }

}
public function penjualan_temp_ubah_post(){

    $jsonArray = json_decode(file_get_contents('php://input'),true);
    $post = array('temp_jumlah'=>$jsonArray['temp_jumlah'],"temp_totalharga" => $jsonArray['temp_total']);
    $data = $this->model->update(array("temp_id"=>$jsonArray['id']),$post,'tb_penjualan_temp');
 
   if($data){
       return $this->response(array(
           "status"                => true,
           "response_code"         => REST_Controller::HTTP_OK,
           "response_message"      => "Berhasil",
           "data"                  => null,
       ), REST_Controller::HTTP_OK);
   }else{
       return $this->response(array(
           "status"                => false,
           "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
           "response_message"      => "Gagal Mendapatkan Data",
           "data"                  => null,
       ), REST_Controller::HTTP_OK);
   }

}
   public function obat_delete(){

    $jsonArray = json_decode(file_get_contents('php://input'),true);
    $data = $this->model->delete(array("obat_id"=>$jsonArray['obat_id']),'tb_obat');
 
   if($data){
       return $this->response(array(
           "status"                => true,
           "response_code"         => REST_Controller::HTTP_OK,
           "response_message"      => "Berhasil",
           "data"                  => null,
       ), REST_Controller::HTTP_OK);
   }else{
       return $this->response(array(
           "status"                => false,
           "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
           "response_message"      => "Gagal Mendapatkan Data",
           "data"                  => null,
       ), REST_Controller::HTTP_OK);
   }

}
}