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

        $spreadsheet = new Spreadsheet();
        $writer = new Xlsx($spreadsheet);

        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'Identificador');
        $sheet->setCellValue('B1', 'PIN');
        $sheet->setCellValue('C1', 'Correo');
        $sheet->setCellValue('D1', 'Fecha');

        // Get data from table
        $data = $db->select_log_table(0, 'ASC');

        $i = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A'.$i, $row->identify);
            $sheet->setCellValue('B'.$i, $row->pin);
            $sheet->setCellValue('C'.$i, $row->email);
            $sheet->setCellValue('D'.$i, $row->date);
            $i++;
        }

        $filename = 'pin_sent.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='. $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

}