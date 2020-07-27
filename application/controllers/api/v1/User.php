<?php

defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Model');
       
        // $this->methods['komentar_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['komentar_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['komentar_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    public function auth_post(){
    
        $jsonArray = json_decode(file_get_contents('php://input'),true);
        $data = array(
            'user_name' => $jsonArray['user_name'],
            'user_password' => md5($jsonArray['user_password'])
        );

        if ($jsonArray['user_name'] === NULL || $jsonArray['user_password'] === NULL)
        {
       
                return $this->response(array(
                    "status"                => false,
                    "response_code"         => REST_Controller::HTTP_EXPECTATION_FAILED,
                    "response_message"      => "Gagal Mendapatkan Data",
                    "data"                  => null,
                ), REST_Controller::HTTP_OK);
    
        }else {
            
       
           
            $data = $this->model->getById('tb_user', $data);
            if($data->num_rows() >= 1){
                return $this->response(array(
                    "status"                => true,
                    "response_code"         => REST_Controller::HTTP_OK,
                    "response_message"      => "Berhasil",
                    "data"                  => $data->result(),
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
    public function user_get()
    {
        $id = $this->get('id');
        if ($id === NULL)
        {
            $data = $this->model->getAll('tb_user');
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
            
            $data = $this->model->getById('tb_user',array('user_id'=>$id));
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
   
    public function user_put(){

        $jsonArray = json_decode(file_get_contents('php://input'),true);
        $bukti = "";
        
        $date = new DateTime();
        if(!empty($jsonArray['user_foto'])){
            

            $image = base64_decode($jsonArray['user_foto']);
            $bukti = $date->getTimestamp().".jpg";
            file_put_contents("assets/img/$bukti",$image);

            $data = array(
                "user_nama"=> $jsonArray['user_nama'],
                "user_foto" => $bukti,
            );

            $data = $this->model->update(array("user_id"=>$jsonArray['user_id']),$data,'tb_user');
        
     
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
        }else{
            $data = array(
                "user_nama"=> $jsonArray['user_nama'],
            );
            $data = $this->model->update(array("user_id"=>$jsonArray['user_id']),$data,'tb_user');
     
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
  


}