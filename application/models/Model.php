<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Model extends CI_Model
{


  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Jakarta");
  }


  public function Auth()
  {
    if ($this->session->userdata('status') != "login") {
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

  public function save($data, $table)
  {
    return $this->db->insert($table, $data);
  }

  public function update($where, $data, $table)
  {
    $this->db->where($where);
    return $this->db->update($table, $data);
  }

  public function delete($where, $table)
  {
    $this->db->where($where);
    return $this->db->delete($table);
  }
  public function getSingleValue($table, $column, $where)
  {
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
    $this->db->where('DAY(penj.penjualan_tanggal)', $day);
    $this->db->where('MONTH(penj.penjualan_tanggal)', $month);
    return $this->db->get()->row();
  }

  public function getjumlahpembelian($month, $day)
  {
    $this->db->select('*');
    $this->db->from('tb_pembelian as penj');
    $this->db->join('tb_pembelian_detail as det', 'penj.pembelian_id  = det.detail_id_transaksi');
    $this->db->select_sum('det.detail_jumlah');
    $this->db->where('DAY(penj.pembelian_tanggal)', $day);
    $this->db->where('MONTH(penj.pembelian_tanggal)', $month);
    return $this->db->get()->row();
  }

  public function getLaporan($data)
  {
    if ($data['jenis'] != 3) {
      if ($data['jenis'] == 1) {
        $table = "tb_penjualan";
      } else if ($data['jenis'] == 2) {
        $table = "tb_pembelian";
      }
 
     
      if($data['jenis'] == 1){
        $this->db->select('
        jual.detail_jumlah as jumlah_beli,
        jual.detail_harga as harga_beli,
        penjualan.penjualan_id_transaksi as id_transaksi,
        penjualan.penjualan_tanggal as penjualan_tanggal,
        penjualan.penjualan_subtotal as penjualan_subtotal, 
        penjualan_id as penjualan_id,
        obat_nama as obat_nama,
        jual.detail_jumlah as detail_jumlah,
        jual.detail_harga as detail_harga');
        $this->db->where("MONTH(" . substr($table, 3) . "_tanggal)", $data['bulan']);
        $this->db->from($table." as penjualan");
        $this->db->join($table . "_detail AS jual", "jual.detail_id_transaksi = " . substr($table, 3) . "_id", "left");
       $this->db->join("tb_pembelian_detail AS beli", "beli.detail_id = jual.detail_id_stok", "left");
       $this->db->join("tb_obat as obat","obat.obat_id = beli.detail_obat_id","left");
       return $this->db->get()->result_array();
      }else{
        
        if($data['cetak'] == 1){
         
          $this->db->select('
        tb_pembelian.pembelian_id_transaksi as id_transaksi,
        tb_pembelian.pembelian_tanggal_masuk as pembelian_tanggal,
        tb_pembelian.pembelian_subtotal as pembelian_subtotal,
        tb_pembelian.pembelian_id as pembelian_id,
');
        $this->db->where("MONTH(" . substr($table, 3) . "_tanggal)", $data['bulan']);
        $this->db->from($table);
        }else{
          $this->db->select('
        tb_pembelian.pembelian_id_transaksi as id_transaksi,
        tb_pembelian.pembelian_tanggal_masuk as pembelian_tanggal,
        tb_pembelian.pembelian_subtotal as pembelian_subtotal,
        tb_pembelian.pembelian_id as pembelian_id,
        tb_pembelian_detail.detail_jumlah as detail_jumlah,
        tb_pembelian_detail.detail_harga_beli as detail_harga_beli,
        tb_pembelian_detail.detail_diskon as detail_diskon,
        tb_pembelian_detail.detail_harga as detail_harga,
        tb_obat.obat_nama as obat_nama');
        $this->db->where("MONTH(" . substr($table, 3) . "_tanggal)", $data['bulan']);
        $this->db->from($table);
        $this->db->join($table . "_detail", $table . "_detail.detail_id_transaksi = " . substr($table, 3) . "_id", "left");
        $this->db->join("tb_obat","tb_obat.obat_id = ".$table."_detail.detail_obat_id","left");
        }
        return $this->db->get()->result_array();

      }
     
      
    } else {
      $this->db->select('SUM(detail_jumlah) as item_penjualan,SUM(penjualan_subtotal) as total_penjualan');
      $this->db->where("MONTH(penjualan_tanggal)", $data['bulan']);
      $this->db->from("tb_penjualan");
      $this->db->join("tb_penjualan_detail", "tb_penjualan_detail.detail_id_transaksi = penjualan_id", "left");
      return $this->db->get()->row_array();
    }
  }

  public function getPembelian($data)
  {
    $this->db->select('SUM(detail_jumlah) as item_pembelian,SUM(pembelian_subtotal) as total_pembelian');
    $this->db->where("MONTH(pembelian_tanggal)", $data['bulan']);
    $this->db->from("tb_pembelian");
    $this->db->join("tb_pembelian_detail", "tb_pembelian_detail.detail_id_transaksi = pembelian_id", "left");
    $this->db->join("tb_obat", "tb_obat.obat_id = tb_pembelian_detail.detail_obat_id", "left");
    
    
    return $this->db->get()->row_array();
  }

  public function getPenjualan($data)
  {
    $this->db->select('SUM(detail_jumlah) as item_penjualan,SUM(penjualan_subtotal) as total_penjualan');
    $this->db->where("MONTH(penjualan_tanggal)", $data['bulan']);
    $this->db->from("tb_penjualan");
    $this->db->join("tb_penjualan_detail", "tb_penjualan_detail.detail_id_transaksi = penjualan_id", "left");
    $this->db->join("tb_pembelian_detail", "tb_pembelian_detail.detail_id_pembelian = tb_penjualan_detail.detail_ad_stok", "left");
    $this->db->join("tb_obat", "tb_obat.obat_id = tb_pembelian_detail.detail_obat_id", "left");
    
    
    return $this->db->get()->row_array();
  }
}

/* End of file Model.php */
