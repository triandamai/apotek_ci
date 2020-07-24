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
       
        // $this->methods['komentar_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['komentar_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['komentar_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function obat_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {

            $data = $this->model->getAll('tb_obat');
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
            
            $data = $this->model->getById('tb_obat',array('obat_id'=>$id));
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
                "status"                => false,
                "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                "response_message"      => "Gagal Mendapatkan Data",
                "data"                  => null,
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
               "status"                => false,
               "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
               "response_message"      => "Gagal Mendapatkan Data",
               "data"                  => null,
           ), REST_Controller::HTTP_OK);
       }

   }
}