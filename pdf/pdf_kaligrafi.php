<?php
require_once('../config.php');
require('../function/fpdf/html_table.php');
require_once ("../function/fungsi_formatdate.php");
require_once ("../function/fungsi_convertNumberToWord.php");
$pdf=new PDF();
$html="";
$pdf->AddPage();
$pdf->SetMargins(17, 0, 10, true);
$pdf->Ln(1);
//HEADER        
$tgl = '';
$noSph = ($_GET["noSph"]);
$q = "SELECT s.*,dk.*,u.nama,p.name as pname,k.name as kname ";
$q.= "FROM aki_sph s right join aki_dkaligrafi dk on s.noSph=dk.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
$q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph."'";
$q.= " ORDER BY s.noSph asc ";
$rs = mysql_query($q, $dbLink);
$hasil = mysql_fetch_array($rs);
$pdf->SetFont('helvetica', '', 11);
$tgl = $hasil['tanggal'];
$no = $hasil['noSph'];
$tbl = '
<div id="noSph">No : '.$no.'</div><br>
Hal : <b><u>Penawaran Harga Kubah Masjid</u></b><br>
';
$pdf->writeHTML($tbl);
$nama_cust = $hasil['nama_cust'];
$masjid = $hasil['masjid'];
$pdf->SetFont('helvetica', '', 11); 
$alamat = $hasil['kname'].', '.$hasil['pname'];
$tbl = '<br>
Kepada Yth<br><b>'.$nama_cust.'</b><br>
'.$masjid.'
<br>'.$alamat.'<br><br>
<ol>Di Tempat</ol><br><br>
Dengan Hormat,<br>
Sehubungan dengan pembangunan Masjid, Kami selaku kontraktor Kubah Masjid mengajukan penawaran harga untuk Plafon Kubah.<br><br>
<b>KALIGRAFI PLAFON</b><br><br>
';

$pdf->writeHTML($tbl);
$q2 = 'SELECT * FROM `aki_dkaligrafi` WHERE MD5(noSph)="' . $noSph.'"';
$rs2 = mysql_query($q2, $dbLink);
$jml=0;
while (  $hasil2 = mysql_fetch_array($rs2)) {
	$pdf->writeHTML('Spesifikasi :<br>');
	$pdf->Cell(10,5,'',0,0,'R',0);
	$pdf->MultiCell(130,6,'  -    Diameter '.$hasil2['d'].' meter dan Tinggi '.$hasil2['t'].' meter
		-    Plafon Motif Kaligrafi '.$hasil2['motif'].' (Motif Terlampir)
		-    Finishing menggunakan Cat Tembok Merk "Mowilex"'
		,0,'B',0);
	$ppn ='';
	if ($hasil['ppn'] == '1') {
		$ppn ='sudah';
	}else{
		$ppn ='belum';
	}
	$transport ='';
	if ($hasil['transport'] == '1') {
		$transport ='sudah';
	}else{
		$transport ='belum';
	}
	$tbl = '<br>
	<b>Harga untuk Plafon kaligrafi tersebut adalah Rp. '.number_format($hasil['harga']).',-<br><br></b>';
	$pdf->writeHTML($tbl);
	$jml++;
}
$tbl = '<b>NB :<br>
	Harga diatas '.$ppn.' termasuk PPn 10%<br>
	Harga diatas '.$transport.' termasuk transport team kaligrafi.<br><br>Sistem pembayaran :</b><br>
<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(10,5,'',0,0,'R',0);
$pdf->MultiCell(190,7,chr(149).'   Pembayaran pertama sebesar 30 % sebagai uang muka. 
'.chr(149).'   Pembayaran kedua 25% diberikan saat kubah selesai pabrikasi dan akan dikirimkan.
'.chr(149).'   Pembayaran ketiga 35 % diberikan saat barang & tukang pemasang sampai di lokasi.
'.chr(149).'   Pembayaran keempat 10% diberikan saat makara siap terpasang.'
,0,'B',0);
if ($jml>1) {
	$pdf->addpage();
	$pdf->Ln(10);
}
/*$tbl = '<br>
Dibayarkan melalui :<br>
<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(10,5,'',0,0,'R',0);
$pdf->Cell(55,6,'- Bank Rakyat Indonesia (BRI)',0,0,'L',0);
$pdf->Cell(65,6,'a/n PT. Anugerah Kubah Indonesia',0,0,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(55,6,'0033 - 01 - 003664 - 30 - 5',0,1,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(10,5,'',0,0,'R',0);
$pdf->Cell(55,6,'- Bank Central Asia (BCA)',0,0,'L',0);
$pdf->Cell(65,6,'a/n PT. Anugerah Kubah Indonesia',0,0,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(55,6,'033 - 330 - 2508',0,1,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(10,5,'',0,0,'R',0);
$pdf->Cell(55,6,'- Bank Mandiri',0,0,'L',0);
$pdf->Cell(65,6,'a/n PT. Anugerah Kubah Indonesia',0,0,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(55,6,'171 - 00 - 2558002 - 2',0,1,'L',0);*/

$pdf->addpage();
$pdf->SetMargins(17, 0, 10, true);
$pdf->Ln(10);
$tbl = '
<b><U>MOTIF PLAFON KALIGRAFI</U></b><br>
<br>
';
$pdf->writeHTML($tbl);
if ($hasil['kaligrafi']!='' && $hasil['kaligrafi']!='empty') {
    $pdf->image('../../uploads/'.$hasil['kaligrafi'],25,55,170,130);
}else{
	$pdf->image('../dist/img/IMGNOTFOUND.jpg',25,55,170,130);
}
$tbl = '
Demikian penawaran harga Plafon kubah dari kami, atas perhatian dan kerjasamanya kami sampaikan terima kasih.
<br>
';
$pdf->Ln(135);
$pdf->writeHTML($tbl);
$pdf->image('../dist/img/ttd.jpg',122,212);
$arr = explode('-', $hasil['tanggal']);
$newDate = $arr[2].' '.namaBulan_id($arr[1]).' '.$arr[0];
$pdf->Ln(5);

$pdf->Cell(120,6,'',0,0,'C',0);
$pdf->Cell(50,6,'Kediri, '.$newDate,0,1,'R',0);
$pdf->Ln(36);
$pdf->SetFont('helvetica', 'BU', 11);
$pdf->Cell(120,6,'',0,0,'C',0);
$pdf->Cell(50,6,'ANDIK NUR SETIAWAN',0,1,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(120,6,'',0,0,'C',0);
$pdf->Cell(50,6,'Direktur PT. Anugerah Kubah Indonesia',0,1,'C',0);

$pdf->Output(str_replace('/', '.', $no).'-'.$nama_cust.'-'.$alamat.'.pdf','I');
?>