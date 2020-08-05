<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class Obat extends REST_Controller {

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

    public function obats_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {

            $data = $this->DataModel->select('*');
    
            $data = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = detail.detail_obat_id','INNER');
            $data = $this->DataModel->getJoin('tb_pembelian as pembelian','pembelian.pembelian_id = detail.detail_id_transaksi','INNER');
            $data = $this->db->where("detail.detail_jumlah > ",0);
            $data = $this->DataModel->getJoin('tb_supplier as suplier','suplier.supplier_id = pembelian.pembelian_id_supplier','INNER');
            $data = $this->DataModel->order_by("detail.detail_id","ASC");
            
            $data = $this->DataModel->getData('tb_pembelian_detail AS detail');
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
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => array(),
                ), REST_Controller::HTTP_OK);
            }
    
        }else {
            
            $data = $this->DataModel->select('*');
            $data = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = detail.detail_obat_id','INNER');
            $data = $this->DataModel->getJoin('tb_pembelian as pembelian','pembelian.pembelian_id = detail.detail_id_transaksi','INNER');
            $data = $this->DataModel->getJoin('tb_supplier as suplier','suplier.supplier_id = pembelian.pembelian_id_supplier','INNER');
            $data = $this->db->where("detail.detail_id_transaksi ",$id);
            $data = $this->db->where("detail.detail_jumlah > ",0);
            $data = $this->DataModel->order_by("detail.detail_id","ASC");
            $data = $this->DataModel->getData('tb_pembelian_detail AS detail');
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
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => array(),
                ), REST_Controller::HTTP_OK);
            }
    
        }
    }
    public function obat_post(){

         $jsonArray = json_decode(file_get_contents('php://input'),true);
         $data = $this->model->save($jsonArray,'tb_obat');
      
        if($data){
            return $this->response(array(
                "status"                => true,
                "response_code"         => REST_Controller::HTTP_OK,
                "response_message"      => "Berhasil",
                "data"                  => $jsonArray,
            ), REST_Controller::HTTP_OK);
        }else{
            return $this->response(array(
                "status"                => true,
                "response_code"         => REST_Controller::HTTP_OK,
                "response_message"      => "Gagal Mendapatkan Data",
                "data"                  => array(),
            ), REST_Controller::HTTP_OK);
        }

    }
    public function obat_put(){

        $jsonArray = json_decode(file_get_contents('php://input'),true);
        $data = $this->model->update(array("obat_id"=>$jsonArray['obat_id']),$jsonArray,'tb_obat');
     
       if($data){
           return $this->response(array(
               "status"                => true,
               "response_code"         => REST_Controller::HTTP_OK,
               "response_message"      => "Berhasil",
               "data"                  => null,
           ), REST_Controller::HTTP_OK);
       }else{
        return $this->response(array(
            "status"                => true,
            "response_code"         => REST_Controller::HTTP_OK,
            "response_message"      => "Gagal Mendapatkan Data",
            "data"                  => array(),
        ), REST_Controller::HTTP_OK);;
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