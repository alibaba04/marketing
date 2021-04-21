<?php
require_once('../config.php');
require('../function/fpdf/html_table.php');
require_once ("../function/fungsi_formatdate.php");
require_once ("../function/fungsi_convertNumberToWord.php");
$pdf=new PDF();
$html="";
$pdf->AddPage();
//HEADER        
$tgl = '';
$noKk = ($_GET["noKK"]);
$q = "SELECT s.*,u.nama,p.name as pname,k.name as kname ";
$q.= "FROM aki_kk s left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
$q.= "WHERE 1=1 and MD5(s.noKk)='" . $noKk."'";
$q.= " ORDER BY s.noKk asc ";
$rs = mysql_query($q, $dbLink);
$hasil = mysql_fetch_array($rs);
$no = $hasil['noKk'];
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(190,8,'PERJANJIAN JUAL BELI DAN PEMASANGAN KUBAH MASJID',0,1,'C',0);
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(190,6, $no,0,0,'C',0);

$pdf->SetFont('helvetica', '', 11);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->SetFont('helvetica', '', 11); 
$pdf->Ln(5);
$tbl = '<br>
Pada hari ini '.( $hasil['tanggal']).' tanggal ............... bulan ........... tahun ................. kami yang bertanda tangan dibawah ini: <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,10,'1.',0,0,'C',0);
$pdf->Cell(1,10,'Nama',0,0,'C',0);
$pdf->Cell(52,10,':',0,0,'C',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(-1,10,'ANDIK NUR SETIAWAN',0,1,'C',0); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(25,2,'',0,0,'C',0);
$pdf->Cell(12,2,'No. Identitas',0,0,'C',0);
$pdf->Cell(30,2,':',0,0,'C',0);
$pdf->Cell(23,2,'3571020710760001 (KTP)',0,1,'C',0); 
$pdf->Cell(25,9,'',0,0,'C',0);
$pdf->Cell(2,9,'Alamat',0,0,'C',0);
$pdf->Cell(50,9,':',0,0,'C',0);
$pdf->Cell(89,9,'Ngadirejo Gg. I Buntu RT/RW 004/009 Kel/Desa Ngadirejo Kecamatan Kota ',0,1,'C',0);
$pdf->Cell(25,2,'',0,0,'C',0);
$pdf->Cell(1,2,'',0,0,'C',0);
$pdf->Cell(50,2,'',0,0,'C',0);
$pdf->Cell(1,2,' Kota Kediri, Jawa Timur.',0,1,'C',0); 
$pdf->Cell(25,8,'',0,0,'C',0);
$pdf->Cell(1,8,'Jabatan',0,0,'C',0);
$pdf->Cell(52,8,':',0,0,'C',0);
$pdf->Cell(24,8,'Direktur PT. Anugerah Kubah Indonesia',0,1,'C',0); 
$tbl = '<br>
Dalam hal ini bertindak untuk dan atas nama Direksi PT. Anugerah Kubah Indonesia selaku pihak yang akan menjadi Pemborong Kerja dan Pemasangan Kubah Masjid, selanjutnya disebut sebagai <b>Pihak Pertama .</b><br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,10,'2.',0,0,'C',0);
$pdf->Cell(1,10,'Nama',0,0,'C',0);
$pdf->Cell(52,10,':',0,0,'C',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(-1,10,'ANDIK NUR SETIAWAN',0,1,'C',0); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(25,2,'',0,0,'C',0);
$pdf->Cell(12,2,'No. Identitas',0,0,'C',0);
$pdf->Cell(30,2,':',0,0,'C',0);
$pdf->Cell(23,2,'3571020710760001 (KTP)',0,1,'C',0); 
$pdf->Cell(25,9,'',0,0,'C',0);
$pdf->Cell(2,9,'Alamat',0,0,'C',0);
$pdf->Cell(50,9,':',0,0,'C',0);
$pdf->Cell(89,9,'Ngadirejo Gg. I Buntu RT/RW 004/009 Kel/Desa Ngadirejo Kecamatan Kota ',0,1,'C',0);
$pdf->Cell(25,2,'',0,0,'C',0);
$pdf->Cell(1,2,'',0,0,'C',0);
$pdf->Cell(50,2,'',0,0,'C',0);
$pdf->Cell(1,2,' Kota Kediri, Jawa Timur.',0,1,'C',0); 
$pdf->Cell(25,8,'',0,0,'C',0);
$pdf->Cell(1,8,'Jabatan',0,0,'C',0);
$pdf->Cell(52,8,':',0,0,'C',0);
$pdf->Cell(24,8,'Direktur PT. Anugerah Kubah Indonesia',0,1,'C',0);
$tbl = '<br>
Dalam hal ini bertindak untuk dan atas nama Panitia Pembangunan Masjid [**] , selanjutnya disebut <b>Pihak Kedua .</b><br>
';
$pdf->writeHTML($tbl);
$pdf->Ln(1);
$tbl = '
Selanjutnya <b>Pihak Pertama </b>dan <b>Pihak Kedua</b> secara bersama-sama disebut "Para Pihak". Bahwa Para Pihak sepakat untuk membuat dan mengikatkan diri dalam Perjanjian Jual Beli dan Pemasangan Kubah Masjid <b>("Perjanjian")</b> ini dan terlebih dahulu menjelaskan hal-hal sebagai berikut :
<br>
';
$pdf->writeHTML($tbl); 
$pdf->Cell(25,10,'1).',0,0,'C',0);
$pdf->MultiCell(90,8,"It is a long text\nwhich is left aligned",1,'L',1);
$pdf->MultiCell(1,10,'Bahwa Pihak Pertama  adalah suatu Perseroan Terbatas  yang berdiri berdasarkan hukum Republik Indonesia, berkedudukan di Kediri, Jawa Timur yang memproduksi kubah masjid.',0,1,'C',0);
$pdf->Cell(25,10,'1).',0,0,'C',0);
$pdf->MultiCell(1,10,'Bahwa Pihak Pertama  adalah suatu Perseroan Terbatas  yang berdiri berdasarkan hukum Republik Indonesia, berkedudukan di Kediri, Jawa Timur yang memproduksi kubah masjid.',0,1,'C',0);

$pdf->Output('s.pdf','I');
?>