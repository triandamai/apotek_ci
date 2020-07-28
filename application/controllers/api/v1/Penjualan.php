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
       
        // $this->methods['komentar_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['komentar_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['komentar_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function penjualan_temp_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {

            $data = $this->model->getAll('tb_penjualan_temp');
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
            
            $data = $this->model->getById('tb_penjualan_temp',array('temp_id'=>$id));
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

            $data = $this->model->getAll('tb_penjualan_detail');
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
            
            $data = $this->model->getById('tb_penjualan_detail',array('penjualan_id'=>$id));
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
                $ambilharga = $this->model->getSingleValue('tb_obat', 'obat_jual', ['obat_id' => $jsonArray['temp_obat_id']]);
                $jumlah = $this->model->getSingleValue('tb_penjualan_temp', 'temp_jumlah', ['temp_id' => $jsonArray['temp_id']]);

                $new = $jsonArray['temp_jumlah'] + $jumlah->temp_jumlah;
                $harga = $ambilharga->obat_jual * $new ;
 
		        $data = array(
                    'temp_nama' => $jsonArray['temp_nama'],
                    'temp_obat_id' => $jsonArray['temp_obat_id'],
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
                    "response_message"      => "Gagal Menyimpan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
            }else{
                return $this->response(array(
                    "status"                => false,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Menyimpan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
            }
        }else{
            $ambilharga = $this->model->getSingleValue('tb_obat', 'obat_jual', ['obat_id' => $jsonArray['temp_obat_id']]);
            $harga = $ambilharga->obat_jual * $jsonArray['temp_jumlah'];
 
		    $data = array(
                'temp_nama' => $jsonArray['temp_nama'],
                'temp_obat_id' => $jsonArray['temp_obat_id'],
			    'temp_jumlah' => $jsonArray['temp_jumlah'],
                'temp_totalharga' => $jsonArray['temp_totalharga'],
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
                    "response_message"      => "Gagal Menyimpan Data",
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
                        'detail_obat' => $row->temp_nama,
                        'detail_obat_id' => $row->temp_obat_id,
                        'detail_jumlah' => $row->temp_jumlah,
                        'detail_harga' => $row->temp_totalharga
                    );
                    array_push($data_insert,$dataloop);
              
                }
                //empty the temporary table
                    $simpan = $this->model->save_batch("tb_penjualan_detail",$data_insert);
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