<?php

namespace dcms\pin\includes;

use dcms\pin\includes\Database;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Class for the operations of plugin
class Export{

    public function __construct(){
        add_action('admin_post_process_export_pin_sent', [$this, 'process_export_data']);
    }

    // Export data
    public function process_export_data(){
        $db = new Database();

        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];

        $spreadsheet = new Spreadsheet();
        $writer = new Xlsx($spreadsheet);

        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'Identificativo');
        $sheet->setCellValue('B1', 'PIN');
        $sheet->setCellValue('C1', 'Correo');
        $sheet->setCellValue('D1', 'Número');
        $sheet->setCellValue('E1', 'Referencia');
        $sheet->setCellValue('F1', 'NIF');
        $sheet->setCellValue('G1', 'Fecha');
        $sheet->setCellValue('H1', 'Aceptar Terminos');

        // Get data from table
        $data = $db->select_log_table($date_start, $date_end);

        $i = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A'.$i, $row->identify);
            $sheet->setCellValue('B'.$i, $row->pin);
            $sheet->setCellValue('C'.$i, $row->email);
            $sheet->setCellValue('D'.$i, $row->number);
            $sheet->setCellValue('E'.$i, $row->reference);
            $sheet->setCellValue('F'.$i, $row->nif);
            $sheet->setCellValue('G'.$i, $row->date);
            $sheet->setCellValue('H'.$i, $row->terms);
            $i++;
        }

        $filename = 'pin_sent.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='. $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

}