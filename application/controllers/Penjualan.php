<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

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
        $data['content'] = 'penjualan';
        $data['pagetitle'] = 'Penjualan';
        //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get data obat and temporary
        $data['obats'] = $this->model->getAll('tb_obat')->result();
        $data['temp'] = $this->model->getAll('tb_penjualan_temp')->result();
		$this->load->view('template',$data);   
    }


    public function save() {
        //get user input
        $subtotal = $this->input->post('subtotal');
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
            $this->model->save($data,'tb_penjualan');
            //get the last insert id
            $insertid =  $this->db->insert_id();

           
    //insert batch data from temporary to penjualan_detail table
    $query = $this->db->get('tb_penjualan_temp');
    foreach ($query->result() as $row) {
        $data1 = array(
            'detail_id_transaksi' => $insertid,
			'detail_obat' => $row->temp_nama,
            'detail_jumlah' => $row->temp_jumlah,
            'detail_harga' => $row->temp_totalharga
            );
          $this->db->insert('tb_penjualan_detail',$data1);
    }
    //empty the temporary table
    $this->db->empty_table('tb_penjualan_temp'); 

            //set flashdata
            $this->session->set_flashdata('stat','sukses');
		    redirect('penjualan/index');
    }

    public function save_temp() {
        //get user input
        $nama = $this->input->post('nama_obat');
        $jumlah = $this->input->post('jumlah');
        //check if teemporary data exist with the same nama obat
        $check = $this->model->getById('tb_penjualan_temp', ['temp_nama' => $nama])->num_rows();
        //if data not exist, insert new data
        if($check == 0) {
        $hargaa = $this->model->getSingleValue('tb_obat', 'obat_jual', ['obat_nama' => $nama]);
        $harga = $hargaa->obat_jual * $jumlah;
 
		$data = array(
			'temp_nama' => $nama,
			'temp_jumlah' => $jumlah,
            'temp_totalharga' => $harga,
            );
            $this->model->save($data,'tb_penjualan_temp');
            redirect('penjualan/index');
            //if data exist, update the data
        }elseif($check > 0) {
            $hargaa = $this->model->getSingleValue('tb_obat', 'obat_jual', ['obat_nama' => $nama]);
            $jumlahh = $this->model->getSingleValue('tb_penjualan_temp', 'temp_jumlah', ['temp_nama' => $nama]);

        $new = $jumlah + $jumlahh->temp_jumlah;
        $harga = $hargaa->obat_jual * $new ;
 
		$data = array(
			'temp_nama' => $nama,
			'temp_jumlah' => $new,
            'temp_totalharga' => $harga,
            );
            $this->model->update(['temp_nama' => $nama], $data,'tb_penjualan_temp');
            redirect('penjualan/index');
        }
    }

    public function delete_temp($id) {
        //delete the temporary table
        $this->model->delete(['temp_id' => $id],'tb_penjualan_temp');
		    redirect('penjualan/index');
    }

    public function daftar()
    {
        $data['content'] = 'riwayatpenjualan';
        $data['pagetitle'] = 'Riwayat Penjualan';
         //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
        //get data detail penjualan
        $data['penjualans'] = $this->model->getAll('tb_penjualan')->result();
		$this->load->view('template',$data);   
    }

    public function detail($id) 
    {
        $data['content'] = 'detailpenjualan';
        $data['pagetitle'] = 'Detail Penjualan';
         //if datatatables = 1 apply the javascript for datatable
        $data['datatables'] = '1';
         //get detail penjualan based on id
        $data['id'] = $id;
        $data['penjualans'] = $this->model->getById('tb_penjualan', ['penjualan_id' => $id])->row();
        $data['details'] = $this->model->getById('tb_penjualan_detail', ['detail_id_transaksi' => $id])->result();
		$this->load->view('template',$data);   
    }

    public function print($id,$id2) 
    {
        //get data detail penjualan based on id
		$data['penjualans'] = $this->model->getById('tb_penjualan', ['penjualan_id' => $id])->row();
        $data['details'] = $this->model->getById('tb_penjualan_detail', ['detail_id_transaksi' => $id])->result();
        //load the dompdf library
        $this->load->library('pdfgenerator');
        //set the configuration
        $this->pdfgenerator->setPaper('A4', 'potrait');
        $this->pdfgenerator->filename = $id2;
        //print the file
        $this->pdfgenerator->load_view('reportpenjualan', $data);
    }

    public function deleted ()
    {
        //empty the temporary table
        $this->db->empty_table('tb_penjualan_temp'); 
		    redirect('penjualan/index');
    }

}

/* End of file Penjualan.php */

?>