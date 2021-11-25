//Menggabungkan dengan file koneksi yang telah kita buat
include 'koneksi.php';
 
// Load library phpspreadsheet
require('vendor/autoload.php');
 
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// End load library phpspreadsheet
 
$spreadsheet = new Spreadsheet();
 
// Set document properties
$spreadsheet->getProperties()->setCreator('Dewan Komputer')
->setLastModifiedBy('Dewan Komputer')
->setTitle('Office 2007 XLSX Dewan Komputer')
->setSubject('Office 2007 XLSX Dewan Komputer')
->setDescription('Test document for Office 2007 XLSX Dewan Komputer.')
->setKeywords('office 2007 openxml php Dewan Komputer')
->setCategory('Test result file Dewan Komputer');
 
$spreadsheet->getActiveSheet()->mergeCells('A1:G1');
$spreadsheet->setActiveSheetIndex(0)->setCellValue('A1', 'Cara Ekspor Laporan/Data dari Database MySQL ke dalam Excel (.xlsx) dengan plugin PHPOffice pada PHP');
 
 
//Font Color
$spreadsheet->getActiveSheet()->getStyle('A3:E3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
 
// Background color
    $spreadsheet->getActiveSheet()->getStyle('A3:E3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFFF0000');
 
 
// Header Tabel
$spreadsheet->setActiveSheetIndex(0)
->setCellValue('A3', 'NO')
->setCellValue('B3', 'NAMA MAHASISWA')
->setCellValue('C3', 'ALAMAT')
->setCellValue('D3', 'JENIS KELAMIN')
->setCellValue('E3', 'TANGGAL MASUK')
;
 
$i=4; 
$no=1; 
$query = "SELECT * FROM tbl_mahasiswa ORDER BY nama_mahasiswa ASC";
$dewan1 = $db1->prepare($query);
$dewan1->execute();
$res1 = $dewan1->get_result();
while ($row = $res1->fetch_assoc()) {
	$spreadsheet->setActiveSheetIndex(0)
	->setCellValue('A'.$i, $no)
	->setCellValue('B'.$i, $row['nama_mahasiswa'])
	->setCellValue('C'.$i, $row['alamat'])
	->setCellValue('D'.$i, $row['jenis_kelamin'])
	->setCellValue('E'.$i, $row['tgl_masuk']);
	$i++; $no++;
}
 
 
// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Report Excel '.date('d-m-Y H'));
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$spreadsheet->setActiveSheetIndex(0);
 
// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Report Excel.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
 
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
 
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');