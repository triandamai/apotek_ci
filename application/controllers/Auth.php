<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        
        
    }
    
    //view login page
    public function index()
    {
        $this->load->view('login');
        
    }
    //check login
    function check_login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $data = array(
            'user_name' => $username,
            'user_password' => md5($password)
        );

        $check = $this->model->getById('tb_user', $data)->num_rows();
        $rows = $this->model->getById('tb_user', $data)->row();
        //if data found
        if($check > 0) {
            $data_session = array(
                'nama' => $rows->user_nama,
                'foto' => $rows->user_foto,
                'status' => "login"
                );
    
            $this->session->set_userdata($data_session);
    
            redirect(base_url("home"));
        }else{
            //if data not found
            echo "<script>alert('Username / Password Salah !'); window.history.back();</script>";
        }

    }
    //logout
    function logout() 
    {
        $this->session->sess_destroy();
        redirect(base_url('auth'));
    }
    //view profie page
    public function profile()
    {
        $this->model->auth();
        $data['content'] = 'profile';
        $data['pagetitle'] = 'Profile';
        $data['profile'] = $this->model->getById('tb_user', ['user_nama' => $this->session->userdata('nama')])->row();
        $this->load->view('template',$data);
        
    }
    //update profile page
    public function update()
    {
        $this->model->auth();
        //config library upload
        $config['upload_path']          = './assets/img/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 1024;
     
        $this->load->library('upload', $config);

        $id = $this->input->post('user_id');
        $user_nama = $this->input->post('user_nama');
        //if user updated ther photo
        if(!empty($_FILES['user_foto']['tmp_name']!='')) {
            $data = array(
                'user_nama' => $user_nama,
                'user_foto' => $_FILES['user_foto']['name']
                );
                $this->upload->do_upload('user_foto');
                $this->model->update(['user_id' => $id], $data,'tb_user');
                //if user not update their photo
        }elseif(empty($_FILES['user_foto']['tmp_name']!='')) {
            $data = array(
                'user_nama' => $user_nama
                );
                $this->model->update(['user_id' => $id], $data,'tb_user');
            }
            //unset session
            $this->session->unset_userdata('status');
            echo "<script>alert('Silahkan Login Kembali !'); window.history.back();</script>";
        
    }

}

    

/* End of file Auth.php */

?>