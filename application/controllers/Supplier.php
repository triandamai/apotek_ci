<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

    function __construct(){
		parent::__construct();
        //check if the user login or not
        //applicalble in all controller except auth
		$this->model->auth();
	}
    

    public function index()
    {
        $data['content'] = 'supplier';
        $data['pagetitle'] = 'Supplier';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get suppliers data
        $data['suppliers'] = $this->model->getAll('tb_supplier')->result();
		$this->load->view('template',$data);   
    }

    public function tambah()
    {
        $data['content'] = 'tambahsupplier';
        $data['pagetitle'] = 'Tambah Supplier';
		$this->load->view('template',$data);   
    }

    public function save() {
        //get user input
        $namasupplier = $this->input->post('namasupplier');
		$alamatsupplier = $this->input->post('alamatsupplier');
        $telpsupplier = $this->input->post('telpsupplier');
        //insert data on array
		$data = array(
			'supplier_nama' => $namasupplier,
			'supplier_alamat' => $alamatsupplier,
            'supplier_telp' => $telpsupplier,
            );
            //save the data
            $this->model->save($data,'tb_supplier');
            //set flashdata
            $this->session->set_flashdata('stat','sukses');
		    redirect('supplier/index');
    }

    public function edit($id)
    {
        $data['content'] = 'editsupplier';
        $data['pagetitle'] = 'Edit Supplier';
        //get suppliers data based on id
        $data['supplier'] = $this->model->getById('tb_supplier', ['supplier_id' => $id])->row();
		$this->load->view('template',$data);   
    }

    public function update() {
        //get user input
        $id = $this->input->post('id');
        $namasupplier = $this->input->post('namasupplier');
		$alamatsupplier = $this->input->post('alamatsupplier');
        $telpsupplier = $this->input->post('telpsupplier');
        //insert data in array
		$data = array(
			'supplier_nama' => $namasupplier,
			'supplier_alamat' => $alamatsupplier,
            'supplier_telp' => $telpsupplier,
            );
            //update the data
            $this->model->update(['supplier_id' => $id], $data,'tb_supplier');
            //set flashdata
            $this->session->set_flashdata('stat','sukses');
		    redirect('supplier/index');
    }

    public function delete($id) {
        //delete the data based on id
        $this->model->delete(['supplier_id' => $id],'tb_supplier');
        //set flashdata
        $this->session->set_flashdata('stat','sukses');
	    redirect('supplier/index');
    }

}

/* End of file Supplier.php */

?>