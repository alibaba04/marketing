<?php
//Menggabungkan dengan file koneksi yang telah kita buat
include '../config_pdf.php';

// Load library phpspreadsheet
require('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet

$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('sikubah.com')
->setLastModifiedBy('sikubah.com')
->setTitle('Office sikubah.com')
->setSubject('Office sikubah.com')
->setDescription('Document for Office sikubah.com')
->setKeywords('Office sikubah.com')
->setCategory('Result file sikubah.com');

$spreadsheet->getActiveSheet()->mergeCells('A1:G1');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Export Data SPH Bulan '.$_GET["month"]);


//Font Color
$spreadsheet->getActiveSheet()->getStyle('A3:L3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

// Background color
    $spreadsheet->getActiveSheet()->getStyle('A3:L3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ADD8E6');


// Header Tabel
$spreadsheet->setActiveSheetIndex(0)
->setCellValue('A3', 'No')
->setCellValue('B3', 'Nomer SPH')
->setCellValue('C3', 'Tanggal')
->setCellValue('D3', 'Diameter')
->setCellValue('E3', 'Tinggi')
->setCellValue('F3', 'Diameter Tengah')
->setCellValue('G3', 'Kelengkapan')
->setCellValue('H3', 'Kabupaten/Kota')
->setCellValue('I3', 'Provinsi')
->setCellValue('J3', 'Klien')
->setCellValue('K3', 'Operator')
->setCellValue('L3', 'Affiliate')
;

$i=4; 
$no=1;
$filter='';
if (isset($_GET["month"])) {
	if ($_GET["month"] != '0') {
		$filter='and month(tanggal)="'.$_GET["month"].'"';
	}
}
$q = "SELECT s.*,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,k.name as kn ";
$q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
$q.= "WHERE 1=1 ".$filter." group by s.noSph ";
$q.= " ORDER BY s.noSph desc "; 
$result = $dbLink->prepare($q);
$result->execute();
$res1 = $result->get_result();
while ($row = $res1->fetch_assoc()) {
	$kel='';
	if ($row["plafon"] == 0){
		$kel = 'Full';
	}else if ($row["plafon"] == 1){
		$kel = 'Tanpa Plafon';
	}else{
		$kel = 'Waterproof';
	}
	$spreadsheet->setActiveSheetIndex(0)
	->setCellValue('A'.$i, $no)
	->setCellValue('B'.$i, $row['noSph'])
	->setCellValue('C'.$i, $row['tanggal'])
	->setCellValue('D'.$i, $row['d'])
	->setCellValue('E'.$i, $row['t'])
	->setCellValue('F'.$i, $row['dt'])
	->setCellValue('G'.$i, $kel)
	->setCellValue('H'.$i, $row['kn'])
	->setCellValue('I'.$i, $row['pn'])
	->setCellValue('J'.$i, $row['nama_cust'])
	->setCellValue('K'.$i, $row['nama'])
	->setCellValue('L'.$i, $row['affiliate']);
	$i++; $no++;
}

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);

// We'll be outputting an excel file
// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Report Excel-.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$write = IOFactory::createWriter($spreadsheet, 'Xlsx');
$write->save('php://output');

?>
