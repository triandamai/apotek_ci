<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

    
    function __construct(){
		parent::__construct();
    
        //check if the user login or not
        //applicalble in all controller except auth
        $this->model->auth();
	}
    

    public function index()
    {
        $data['content'] = 'Obat';
        $data['pagetitle'] = 'Obat';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get data obat
        $data['obats'] = $this->model->getAll('tb_obat')->result();
		$this->load->view('template',$data);   
    }

    public function tambah()
    {
        $data['content'] = 'tambahobat';
        $data['pagetitle'] = 'Tambah Obat';
		$this->load->view('template',$data);   
    }

    public function save() {
        //get the input
        $namaobat = $this->input->post('namaobat');
		$stokobat = $this->input->post('stokobat');
        $hargabeli = $this->input->post('hargabeli');
        $hargajual = $this->input->post('hargajual');
        //insert data in array
		$data = array(
			'obat_nama' => $namaobat,
			'obat_stok' => $stokobat,
            'obat_beli' => $hargabeli,
            'obat_jual' => $hargajual
            );
            //save the data
            $this->model->save($data,'tb_obat');
            //set flashdata
            $this->session->set_flashdata('stat','sukses');
		    redirect('obat/index');
    }

    public function edit($id)
    {
        $data['content'] = 'editobat';
        $data['pagetitle'] = 'Edit Obat';
        //get data obat based on id
        $data['obats'] = $this->model->getById('tb_obat', ['obat_id' => $id])->row();
		$this->load->view('template',$data);   
    }

    public function update() {
        //get the input
        $id = $this->input->post('id');
        $namaobat = $this->input->post('namaobat');
		$stokobat = $this->input->post('stokobat');
        $hargabeli = $this->input->post('hargabeli');
        $hargajual = $this->input->post('hargajual');
        //insert data in array
		$data = array(
			'obat_nama' => $namaobat,
			'obat_stok' => $stokobat,
            'obat_beli' => $hargabeli,
            'obat_jual' => $hargajual
            );
            //update the data based on id
            $this->model->update(['obat_id' => $id], $data,'tb_obat');
            //set flash data
            $this->session->set_flashdata('stat','sukses');
		    redirect('obat/index');
    }

    public function delete($id) {
        //delete the data based on id
        $this->model->delete(['obat_id' => $id],'tb_obat');
        //set flash data
        $this->session->set_flashdata('stat','sukses');
	    redirect('obat/index');
    }
    

}

/* End of file Obat.php */

?>