<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    function __construct(){
		parent::__construct();
        //check if the user login or not
        //applicalble in all controller except auth
		$this->model->auth();
	}

    public function index()
    {
        $data['content'] = 'home';
        $data['pagetitle'] = 'Home';
        $bulan = date('m');
        $day = date('d');
        //get data penjualan and pembelian based on the day and month now
        $data['query'] = $this->model->getjumlahpenjualan($bulan, $day);
        $data['query1'] = $this->model->getjumlahpembelian($bulan, $day);
        $this->load->view('template',$data);
        
    }

}

/* End of file Home.php */

?>