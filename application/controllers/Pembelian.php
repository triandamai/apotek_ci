<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {

    function __construct(){
		parent::__construct();
        //check if the user login or not
        //applicalble in all controller except auth
        $this->model->auth();
        //set default timezone
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('DataModel');
	}
    

    public function index()
    {
        $data['content'] = 'Pembelian';
        $data['pagetitle'] = 'Pembelian';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get suppliers data
        $data['suppliers'] = $this->model->getAll('tb_supplier')->result();
        //get user input, later this become flashdata 
        $data['asd'] = $this->input->post('nama_supplier');
        //get data obat
        $data['obats'] = $this->model->getAll('tb_obat')->result();
        //get data temporary pembelian
        $data['temp'] = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = temp.temp_obat_id','INNER');
        $data['temp'] = $this->DataModel->getData('tb_pembelian_temp AS temp')->result();
       
		$this->load->view('template',$data);   
    }

    public function save() {
        //get user input
        $faktur = $this->input->post('faktur');
        $supplier = $this->input->post('supplier');
        $subtotal = $this->input->post('subtotal');
        //generate id transaksi
        $idtransaksi = 'PMB-'.date('YmdHis');
        $tanggal = date('Y-m-d');
        //insert data in array
		$data = array(
            'pembelian_faktur' => $faktur,
			'pembelian_id_transaksi' => $idtransaksi,
            'pembelian_tanggal' => $tanggal,
            'pembelian_id_supplier' => $supplier,
            'pembelian_subtotal' => $subtotal,
            );
            //save the data
            $this->model->save($data,'tb_pembelian');
            //get the last insert id
            $insertid =  $this->db->insert_id();

           
    //insert batch from temporary table to detail_pembelian table
    $query = $this->db->get('tb_pembelian_temp');
    foreach ($query->result() as $row) {
        $data1 = array(
            'detail_id_transaksi' => $insertid,
            'detail_obat_id' => $row->temp_obat_id,
            'detail_harga_beli'=> $row->temp_harga_beli,
            'detail_harga_jual'=>$row->temp_harga_jual,
            'detail_jumlah' => $row->temp_jumlah,
            'detail_harga' => $row->temp_totalharga,
            'detail_satuan_beli' =>$row->temp_satuan_beli,
            'detail_diskon'=>$row->temp_diskon,
            'detail_expired'=>$row->temp_expired,
            'detail_tanggal_terima'=>$row->temp_tanggal_terima,
            );
          $this->db->insert('tb_pembelian_detail',$data1);
    }
    //empty the temporary table
    $this->db->empty_table('tb_pembelian_temp'); 

            //set flashdata
            $this->session->set_flashdata('stat','sukses');
		    redirect('pembelian/index');
    }

    public function save_temp() {
        //get user input
        $nama_obat = $this->input->post('nama_obat');
        $faktur = $this->input->post('no_faktur');
     
        $tanggal_masuk = $this->input->post('tanggal_faktur');
        $satuan_beli = $this->input->post('satuan_beli');
        $harga_beli = $this->input->post('harga_beli');
        $harga_jual = $this->input->post('harga_jual');
       
        $expired = $this->input->post('expired');
        $diskon = $this->input->post('diskon');
        $id_supplier = $this->input->post('ids');
        $jumlah = $this->input->post('jumlah');
        //check if temporary data exist with the same nama obat
        $check = $this->model->getById('tb_pembelian_temp', ['temp_obat_id' => $nama_obat])->num_rows();
        //if data not exist, insert new data
    
        if($check == 0) {
        $hargaa = $this->model->getById('tb_obat', ['obat_id' => $nama_obat])->row();
       
        $harga = $harga_beli * $jumlah;
        $nama = $hargaa->obat_nama;
        $total_final = $nilai=($diskon/100)*$harga;
		$data = array(
            'temp_faktur' => $faktur,
            'temp_obat_id' => $nama_obat,
            'temp_satuan_beli' =>$satuan_beli,
            'temp_harga_beli'=>$harga_beli,
            'temp_harga_jual'=>$harga_jual,
            'temp_diskon'=>$diskon,
            'temp_expired'=>$expired,
            'temp_tanggal_terima'=>$tanggal_masuk,
			'temp_jumlah' => $jumlah,
            'temp_totalharga' => $total_final,
            );
            $this->model->save($data,'tb_pembelian_temp');
            $this->session->set_flashdata('id_supplier',$id_supplier);
            redirect('pembelian/index');
            //if data exist, update the data
        }elseif($check > 0) {
            $hargaa = $this->model->getById('tb_obat', ['obat_id' => $nama_obat])->row();
            $jumlahh = $this->model->getSingleValue('tb_pembelian_temp', 'temp_jumlah', ['temp_obat_id' => $nama_obat]);

            $new = $jumlah + $jumlahh->temp_jumlah;
            $harga = $harga_beli * $new ;  
            $nama = $hargaa->obat_id;
            $total_final = $nilai=($diskon/100)*$harga;
            $data = array(
            'temp_faktur' => $faktur,
            'temp_nama' => $nama,
            'temp_obat_id' => $nama_obat,
            'temp_satuan_beli' =>$satuan_beli,
            'temp_harga_beli'=>$harga_beli,
            'temp_harga_jual'=>$harga_jual,
            'temp_diskon'=>$diskon,
            'temp_expired'=>$expired,
            'temp_tanggal_terima'=>$tanggal_masuk,
			'temp_jumlah' => $jumlah,
            'temp_totalharga' => $total_final,
            );
            $this->model->update(['temp_nama' => $nama], $data,'tb_pembelian_temp');
            $this->session->set_flashdata('id_supplier',$id_supplier);
            redirect('pembelian/index');
        } 
    }

    public function delete_temp($id,$id2) {
        //delete the data in temporary table
        $this->model->delete(['temp_id' => $id],'tb_pembelian_temp');
        //decode the url
        $url = urldecode($id2);
        $this->session->set_flashdata('id_supplier',$url);
		    redirect('pembelian/index');
    }

    public function daftar()
    {
        $data['content'] = 'RiwayatPembelian';
        $data['pagetitle'] = 'Riwayat Pembelian';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get data pembelian
        $data['pembelians'] = $this->DataModel->getJoin('tb_supplier as s','s.supplier_id = p.pembelian_id_supplier','INNER');
        $data['pembelians'] = $this->DataModel->getData('tb_pembelian AS p')->result();
       
		$this->load->view('template',$data);   
    }

    public function detail($id) 
    {
        $data['content'] = 'DetailPembelian';
        $data['pagetitle'] = 'Detail Pembelian';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        $data['id'] = $id;
        //get the detail pembelian based on id
        // $data['pembelians'] = $this->model->getById('tb_pembelian', ['pembelian_id' => $id])->row();

      //  $data['details'] = $this->model->getById('tb_pembelian_detail', ['detail_id_transaksi' => $id])->result();
        $data['details'] = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = p.detail_obat_id','INNER');
        $data['details'] = $this->db->where("p.detail_id_transaksi ",$id);
        $data['details'] = $this->DataModel->getData('tb_pembelian_detail AS p')->result();

        $data['pembelians'] = $this->DataModel->getJoin('tb_supplier as s','s.supplier_id = p.pembelian_id_supplier','INNER');
        $data['pembelians'] = $this->db->where("p.pembelian_id ",$id);
        $data['pembelians'] = $this->DataModel->getData('tb_pembelian AS p')->row();
        
		$this->load->view('template',$data);   
    }

    public function print($id,$id2) 
    {
 
        $data['details'] = $this->DataModel->getJoin('tb_obat as obat','obat.obat_id = p.detail_obat_id','INNER');
        $data['details'] = $this->db->where("p.detail_id_transaksi ",$id);
        $data['details'] = $this->DataModel->getData('tb_pembelian_detail AS p')->result();

        $data['pembelians'] = $this->DataModel->getJoin('tb_supplier as s','s.supplier_id = p.pembelian_id_supplier','INNER');
        $data['pembelians'] = $this->db->where("p.pembelian_id ",$id);
        $data['pembelians'] = $this->DataModel->getData('tb_pembelian AS p')->row();
        //load the dompdf library
        $this->load->library('pdfgenerator');
        //set the configuration
        $this->pdfgenerator->setPaper('A4', 'potrait');
        $this->pdfgenerator->filename = $id2;
        //print the file
        $this->pdfgenerator->load_view('reportpembelian', $data);
    }
    public function deleted ()
    {
        //empty the temporary table
        $this->db->empty_table('tb_pembelian_temp'); 
		    redirect('pembelian/index');
    }

}

/* End of file Pembelian.php */
 
?>