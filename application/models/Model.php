<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model {

  
  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Jakarta");
  }
  

    public function Auth() 
    {
        if($this->session->userdata('status') != "login"){
			redirect(base_url("auth"));
		}
    }

    public function getAll($table)
    {
        return $this->db->get($table);
    }
    
    public function getById($table, $column)
    {
        return $this->db->get_where($table, $column);
    }

    public function save($data,$table)
    {
		    return $this->db->insert($table,$data);
    }
    
    public function update($where,$data,$table){
		    $this->db->where($where);
        return $this->db->update($table,$data);
       
    }
    function save_batch($table,$data)
    {
        return $this->db->insert_batch($table,$data);
       
    }
    public function delete($where,$table){
		    $this->db->where($where);
		    return $this->db->delete($table);
	  }
    public function getSingleValue($table, $column, $where){
      $this->db->select($column);
      $this->db->from($table);
      $this->db->where($where);
      return $this->db->get()->row();
    }

    public function getjumlahpenjualan($month, $day)
    {
      $this->db->select('*');
      $this->db->from('tb_penjualan as penj');
      $this->db->join('tb_penjualan_detail as det', 'penj.penjualan_id  = det.detail_id_transaksi');    
      $this->db->select_sum('det.detail_jumlah');
      $this->db->where('DAY(penj.penjualan_tanggal)',$day);
      $this->db->where('MONTH(penj.penjualan_tanggal)',$month);
      return $this->db->get()->row();
    }

    public function getjumlahpembelian($month, $day)
    {
      $this->db->select('*');
      $this->db->from('tb_pembelian as penj');
      $this->db->join('tb_pembelian_detail as det', 'penj.pembelian_id  = det.detail_id_transaksi');    
      $this->db->select_sum('det.detail_jumlah');
      $this->db->where('DAY(penj.pembelian_tanggal)',$day);
      $this->db->where('MONTH(penj.pembelian_tanggal)',$month);
      return $this->db->get()->row();
    }
}

/* End of file Model.php */

?>