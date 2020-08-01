<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

    
    function __construct(){
		parent::__construct();
    
        //check if the user login or not
        //applicalble in all controller except auth
        $this->model->auth();
        $this->load->model('DataModel');
	}
    

    public function index()
    {
        $data['content'] = 'Stok';
        $data['pagetitle'] = 'Stok Obat';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get data obat
            $data['obats']  = $this->DataModel->select('*');
    
            $data['obats']  = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = detail.detail_obat_id','INNER');
            $data['obats']  = $this->DataModel->getJoin('tb_pembelian as pembelian','pembelian.pembelian_id = detail.detail_id_transaksi','INNER');
            $data['obats']  = $this->db->where("detail.detail_jumlah > ",0);
            $data['obats']  = $this->DataModel->getJoin('tb_supplier as suplier','suplier.supplier_id = pembelian.pembelian_id_supplier','INNER');
            $data['obats']  = $this->DataModel->order_by("detail.detail_id","ASC");
            
            $data['obats']  = $this->DataModel->getData('tb_pembelian_detail AS detail')->result();
        
        // var_dump($data);
        // die();
		$this->load->view('template',$data);   
    }

    public function tambah()
    {
        $data['content'] = 'TambahObat';
        $data['pagetitle'] = 'Tambah Obat';
		$this->load->view('template',$data);   
    }

    public function save() {
        //get the input
        $namaobat = $this->input->post('namaobat');
		$stokobat = $this->input->post('stokobat');
        // $hargabeli = $this->input->post('hargabeli');
        // $hargajual = $this->input->post('hargajual');
        //insert data in array
		$data = array(
			'obat_nama' => $namaobat,
			'obat_stok' => $stokobat,
            // 'obat_beli' => $hargabeli,
            // 'obat_jual' => $hargajual
            );
            //save the data
            $this->model->save($data,'tb_obat');
            //set flashdata
            $this->session->set_flashdata('stat','sukses');
		    redirect('obat/index');
    }

    public function edit($id)
    {
        $data['content'] = 'EditObat';
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
        // $hargabeli = $this->input->post('hargabeli');
        // $hargajual = $this->input->post('hargajual');
        //insert data in array
		$data = array(
			'obat_nama' => $namaobat,
			'obat_stok' => $stokobat,
            // 'obat_beli' => $hargabeli,
            // 'obat_jual' => $hargajual
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