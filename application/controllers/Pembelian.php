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
        $data['temp'] = $this->model->getAll('tb_pembelian_temp')->result();
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
            'pembelian_supplier' => $supplier,
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
			'detail_obat' => $row->temp_nama,
            'detail_jumlah' => $row->temp_jumlah,
            'detail_harga' => $row->temp_totalharga,
            'detail_satuan_beli' =>$row->temp_satuan_beli,
            'detail_satuan_jual'=>$row->temp_satuan_jual,
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
        $nama = $this->input->post('nama_obat');
        $faktur = $this->input->post('faktur');
        $tanggal_masuk = $this->input->post('tanggal_faktur');
        $satuan_beli = $this->input->post('satuan_beli');
        $satuan_jual = $this->input->post('satuan_jual');
        $expired = $this->input->post('expired');
        $diskon = $this->input->post('diskon');
        $id_supplier = $this->input->post('ids');
        $jumlah = $this->input->post('jumlah');
        //check if temporary data exist with the same nama obat
        $check = $this->model->getById('tb_pembelian_temp', ['temp_nama' => $nama])->num_rows();
        //if data not exist, insert new data
        if($check == 0) {
        $hargaa = $this->model->getSingleValue('tb_obat', 'obat_beli', ['obat_nama' => $nama]);
        $harga = $hargaa->obat_beli * $jumlah;
		$data = array(
            'temp_faktur' => $faktur,
            'temp_nama' => $nama,
            'temp_satuan_beli' =>$satuan_beli,
            'temp_satuan_jual'=>$satuan_jual,
            'temp_diskon'=>$diskon,
            'temp_expired'=>$expired,
            'temp_tanggal_terima'=>$tanggal_masuk,
			'temp_jumlah' => $jumlah,
            'temp_totalharga' => $harga,
            );
            $this->model->save($data,'tb_pembelian_temp');
            $this->session->set_flashdata('id_supplier',$id_supplier);
            redirect('pembelian/index');
            //if data exist, update the data
        }elseif($check > 0) {
            $hargaa = $this->model->getSingleValue('tb_obat', 'obat_beli', ['obat_nama' => $nama]);
            $jumlahh = $this->model->getSingleValue('tb_pembelian_temp', 'temp_jumlah', ['temp_nama' => $nama]);

            $new = $jumlah + $jumlahh->temp_jumlah;
        $harga = $hargaa->obat_beli * $new ;
        $data = array(
            'temp_faktur' => $faktur,
            'temp_nama' => $nama,
            'temp_satuan_beli' =>$satuan_beli,
            'temp_satuan_jual'=>$satuan_jual,
            'temp_diskon'=>$diskon,
            'temp_expired'=>$expired,
            'temp_tanggal_terima'=>$tanggal_masuk,
			'temp_jumlah' => $jumlah,
            'temp_totalharga' => $harga,
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
        $data['pembelians'] = $this->model->getAll('tb_pembelian')->result();
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
        $data['pembelians'] = $this->model->getById('tb_pembelian', ['pembelian_id' => $id])->row();
        $data['details'] = $this->model->getById('tb_pembelian_detail', ['detail_id_transaksi' => $id])->result();
		$this->load->view('template',$data);   
    }

    public function print($id,$id2) 
    {
 
		$data['pembelians'] = $this->model->getById('tb_pembelian', ['pembelian_id' => $id])->row();
        $data['details'] = $this->model->getById('tb_pembelian_detail', ['detail_id_transaksi' => $id])->result();
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