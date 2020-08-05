<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //check if the user login or not
        //applicalble in all controller except auth
        $this->model->auth();
    }


    function index()
    {
        $data['content'] = 'Laporan';
        $data['pagetitle'] = 'Laporan';
        $data['datatables'] = '1';
        $data['bulan'] = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        $data['laporan'] = [
            "Transaksi Penjualan", "Transaksi Pembelian", "Laba Rugi"
        ];
        if ($this->input->post('submit')) {
            $bulan = $this->input->post('bulan');
            $jenis = $this->input->post('jenis');
            $input = [
                "bulan" => $bulan,
                "jenis" => $jenis,
                "cetak" => 1
            ];
            $dataOutput = $this->model->getLaporan($input);
            $data['inJenis'] = $jenis;
            $data['inBulan'] = $bulan;
            if ($jenis == 1) {
                $data['jenis'] = 'penjualan';
            } else if ($jenis == 2) {
                $data['jenis'] = 'pembelian';
            } else {
                $data['jenis'] = 'Laba / Rugi';
                $dataPembelian = $this->model->getPembelian($input);
                $dataOutput = array_merge($dataOutput,$dataPembelian);
            }
            $data['dataOutput'] = $dataOutput;
        }
        $this->load->view('Template', $data);
    }


    function export()
    {
        $type = $this->input->get('type');
        $bulan = $this->input->get('m');
        $jenis = $this->input->get('j');
        $bulanIndo = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        $input = [
            "bulan" => $bulan,
            "jenis" => $jenis,
            "cetak" => 0
        ];
        $dataOutput = $this->model->getLaporan($input);
      
        if ($jenis == 1) {
            $j = 'penjualan';
        } else if ($jenis == 2) {
            $j = 'pembelian';
        } else {
            $j = 'laba / rugi';
        }

        if ($type == "xlsx") {
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            if ($jenis == 3) {

                $inputFileName = FCPATH . 'assets/template/template_laporan_laba_rugi.xlsx';

                /** Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                // $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $dataPembelian = $this->model->getPembelian($input);
                $dataOutput = array_merge($dataOutput,$dataPembelian);
                $sheet->setCellValue('E5',$dataOutput['item_pembelian']);
                $sheet->setCellValue('E6',$dataOutput['total_pembelian']);
                $sheet->setCellValue('E9',$dataOutput['item_penjualan']);
                $sheet->setCellValue('E10',$dataOutput['total_penjualan']);
                $sheet->setCellValue('E13',($dataOutput['total_penjualan'] - $dataOutput['total_pembelian']));
            } else {
                $inputFileName = FCPATH . 'assets/template/template_laporan_penjualan_pembelian.xlsx';

                /** Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                // $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // $sheet->setCellValue('A1', 'Hello World !');
                $i = 4;
                $no = 1;
                foreach ($dataOutput as $key => $val) {
                    $sheet->setCellValue('A' . $i, $no);
                    $sheet->setCellValue('B' . $i, date_format(date_create($val[$j . '_tanggal']), 'd/m/Y'));
                    $sheet->setCellValue('C' . $i, $val[$j . "_id_transaksi"]);
                    $sheet->setCellValue('D' . $i, $val["obat_nama"]);
                    $sheet->setCellValue('E' . $i, $val["detail_jumlah"]);
                    $sheet->setCellValue('F' . $i, $val["detail_harga"]);
                    $sheet->setCellValue('G' . $i, $val[$j . "_subtotal"]);
                    $i++;
                    $no++;
                }
                $i = $i - 1;
                $spreadsheet->getActiveSheet()->getStyle("A4:G" . $i)->applyFromArray($styleArray);
            }
            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Laporan_' . $j . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');
            // $writer = new Xlsx($spreadsheet);
            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        } else if ($type == "pdf") {
            $data['jenis'] = $j;
            if($jenis == 3){
                $dataPembelian = $this->model->getPembelian($input);
                $dataOutput = array_merge($dataOutput,$dataPembelian);
            }
            $data['dataOutput'] = $dataOutput;
            $data['bulan'] = $bulanIndo[$bulan-1];
            //die(json_encode($data));
            $this->load->library('PdfGenerator');
            $this->pdfgenerator->setPaper('A4', 'potrait');
            $this->pdfgenerator->filename = "laporan_".$j."_bulanan.pdf";
           // <td><?= date_format(date_create($val[$jenis . '_tanggal']), 'd/m/Y') //></td>
        //    <td><?= $val[$jenis . "_id_transaksi"]></td>
        //    echo json_encode($data);
        //    die();
            if($jenis != 3){
                $this->pdfgenerator->load_view('cetakLaporanPdf', $data);
            }else{
                $this->pdfgenerator->load_view('cetakLaporanLabaRugi', $data);
            }
        }
    }
}
