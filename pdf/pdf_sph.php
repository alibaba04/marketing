<?php
require_once('../config.php');
require('../function/fpdf/html_table.php');
require_once ("../function/fungsi_formatdate.php");
require_once ("../function/fungsi_convertNumberToWord.php");
$pdf=new PDF();
$html="";
$pdf->AddPage();
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(1);
//HEADER        
$tgl = '';
$noSph = ($_GET["noSph"]);
$q = "SELECT s.*,u.nama,p.name as pname,k.name as kname ";
$q.= "FROM aki_sph s left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
$q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph."'";
$q.= " ORDER BY s.noSph asc ";
$rs = mysql_query($q, $dbLink);
$hasil = mysql_fetch_array($rs);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetAutoPageBreak(TRUE, 0);
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
Panitia Pembangunan '.$masjid.'
<br>'.$alamat.'<br><br>
<ol>Di Tempat</ol><br><br>
Dengan Hormat,<br><br>
Sehubungan dengan pembangunan Masjid, Kami selaku kontraktor Kubah Masjid mengajukan penawaran harga untuk Kubah.
';
$pdf->writeHTML($tbl);
$pdf->SetFont('helvetica', '', 11); 
$q2 = "SELECT ds.gold,ds.luas,ds.biaya_plafon,ds.bahan,ds.idDsph,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport FROM aki_dsph ds WHERE 1=1 and MD5(ds.noSph)='".$noSph."' order by idDsph asc";
$rs2 = mysql_query($q2, $dbLink);
$nourut = 1;
$rincian = '';
$rincian2 = '';
$tharga1 = 0;
$tharga2 = 0;
$tharga3 = 0;
$model ='';
$bplafon ='';
$ongkir =0;
$plafon='';
while (  $hasil = mysql_fetch_array($rs2)) {
    $plafon =$hasil['plafon'];
    $bahan = $hasil['bahan'];
    if ($hasil['ket'] == 'Kubah Utama') {
        $model = $hasil['model'];
    }else{
        $model = $hasil['model'];
    }
    $bplafon =$hasil['biaya_plafon'];;
    $ketkubah = $hasil['ket'];
    $jumlah = $hasil['jumlah'];
    $luas = '';
    $ongkir += $hasil['transport'];
    $txtrangka1=$txtrangka2=$txtrangka3='';
    if ($hasil['model'] =='custom') {
        $luas = $hasil['luas'];
        
    }else{
        $luas = luas($hasil['d'],$hasil['t'],$hasil['dt']);
    }
    $luas = round($luas,2);
    $harga1 = ($hasil['harga']);
    $harga2 = ($hasil['harga2']);
    $harga3 = ($hasil['harga3']);
    
    $d = $hasil['d'];
    $t = $hasil['t'];
    $dt = $hasil['dt'];
    $rangka2 ='';
    $rangka2a ='';
    if ($dt != 0){
        $rangka = cekrangka($hasil['dt']);
    }else{
        $rangka = cekrangka($hasil['d']);
    }
    
//if bahan ga 
if ($bahan == '1' or $bahan == '4' or $bahan == '6' or $bahan == '0') {
    if ($ketkubah == 'Atap') {
        $pdf->SetMargins(74, 10, 10, true);
        $pdf->Ln(10);
        $pdf->Write(5,'Dengan Luas Atap '.$luas.' meter');
        $pdf->subWrite(5,'2','',6,4);
    }else{
        if ($dt != 0){
            $pdf->SetMargins(34, 10, 10, true);
            $pdf->Ln(10);
            $pdf->Write(5,'Diameter '.$d.' meter dan Tinggi '.$t.' meter Diameter Tengah '.$dt.' dengan Luas '.$luas.' meter');
            $pdf->subWrite(5,'2','',6,4);
        }else{
            $pdf->SetMargins(50, 10, 10, true);
            $pdf->Ln(7);
            $pdf->Write(5,'Diameter '.$d.' meter dan Tinggi '.$t.' meter dengan Luas '.$luas.' meter');
            $pdf->subWrite(5,'2','',6,4);
        }
    }
    
    $pdf->Ln(2);
    $pdf->SetMargins(13, 10, 10, true);
    $tbl = '<br>
    <i><b>'.$nourut.'. SPESIFIKASI KUBAH ORNAMEN BERBAHAN GALVALUME ( '.$ketkubah .') </b></i>
    ';
    $pdf->writeHTML($tbl);
    $pdf->SetMargins(18, 10, 10, true);
    
    $pdf->Ln(10);  
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(8,6,'No',1,0,'C',0);
    $pdf->Cell(32,6,'Item',1,0,'C',0);
    $pdf->Cell(143,6,'Spesifikasi','1',1,'C',0);  
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'1.','LR',0,'C',0);
    $pdf->Cell(32,6,'Rangka Kubah','LR',0,'C',0);
    if ($hasil['model']=='custom') {
        $q3 = "SELECT * FROM aki_rangka WHERE 1=1 and aktif=1 and MD5(noSph)='".$noSph."'";
        $rs3 = mysql_query($q3, $dbLink);
        $jumrangka=0;
        while (  $hasil2 = mysql_fetch_array($rs3)) {
            if ($jumrangka!=0 && $hasil2['rangka']!='') {
                $pdf->Cell(8,6,'','LR',0,'C',0);
                $pdf->Cell(32,6,'','LR',0,'C',0);
            }
            if ($hasil2['rangka']!='') {
                $pdf->Cell(143,6,'   ~ '.$hasil2['rangka'],'LR',1,'L',0);
                $jumrangka++;
            }
        }
    }else{
        $pdf->Cell(143,6,'   ~ Rangka primer Pipa Galvanis dengan ukuran '.$rangka,'LR',1,'L',0);
        if ($hasil['d']>=6) {
            $pdf->Cell(8,6,'','LR',0,'C',0);
            $pdf->Cell(32,6,'','LR',0,'C',0);
            $pdf->Cell(35,6,'   ~ System Rangka ','L',0,'L',0);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->Cell(108,6,'Double Frame (Kremona)','R',1,'L',0);
            $pdf->SetFont('helvetica', '', 11);
        }
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Rangka Pendukung Hollow 1,5 x 3,5 cm, tebal 0,7  mm','LR',1,'L',0);
    }
    $pdf->Cell(8,6,'','LTR',0,'C',0);
    $pdf->Cell(32,6,'','LTR',0,'C',0);
    $pdf->Cell(40,6,'   ~ Bahan terbuat dari','LT',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(31,6,'Plat Galvalume','T','L',0);
    $pdf->SetFont('helvetica', '', 11);
    if ($hasil['d']>=1 ) {
        $pdf->Cell(72,6,'0,4 - 0,5 mm','TR',1,'L',0);
    }else{
        $pdf->Cell(72,6,'0,4  mm','TR',1,'L',0);
    }
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(22,6,'   ~ Finishing ','L',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(15,6,' Cat PU ',0,0,'L',0);
    $pdf->SetFont('helvetica', '', 11);
    $gold='';
    $gold1='';
    if ($hasil['gold']==1 ) {
        $gold = '4';
        $gold1=' Primer';
    }else{
        $gold = '2';
    }
    $pdf->Cell(106,6,'dengan '.$gold.' komponen pengecatan :','R',1,'L',0);
    $pdf->Cell(8,6,'2.','LR',0,'C',0);
    $pdf->Cell(32,6,'Atap Kubah','LR',0,'C',0);
    $pdf->Cell(143,6,'         - Epoxy'.$gold1,'LR',1,'L',0);
    
    if ($hasil['gold']==1 ) {
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(55,6,'         - Cat Dasar warna kuning',0,0,'L',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(88,6,'Solid PU','R',1,'LR',0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(54,6,'         - Cat Dasar warna pokok','L',0,'L',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(89,6,'Xiralic Gold','R',1,'LR',0);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(57,6,'         - Pelapis anti gores / Clear','L',0,'L',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(86,6,'MS PU','R',1,'LR',0);
        $pdf->SetFont('helvetica', '', 11);
    }else{
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(143,6,'         - Cat PU 2 Komponen','LR',1,'L',0);
    }
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Garansi warna dan konstruksi 5 tahun, bisa di cat ulang','LR',1,'L',0);
    
    if ($d >= '0.5' and $d<'3'){
        $rangka2 = '1 inch tebal 1,6  mm, hollow';
        $rangka2a = '1,5 x 3,5 cm';
    }
    elseif ($d >= '3' and $d<'8' ) {
        $rangka2 = '1,25 inch tebal 1,6 mm, Hollow';
        $rangka2a = '1,5 x 3,5 cm';
    }
    elseif ($d >= '8' and $d<'10') {
        $rangka2 = '1,25 inch tebal 1,6 mm, Hollow';
        $rangka2a = '1,5 x 3,5 cm dan 3,5 x 3,5 cm';
    }
    elseif ($d >= '10' and $d<'21') {
        $rangka2 = '1,25 inch tebal 1,6 mm, Hollow';
        $rangka2a = '3,5 x 3,5 cm';
    }
    elseif ($d >= '21' and $d<'31') {
        $rangka2 = '1,5 inch tebal 1,6 mm, Hollow';
        $rangka2a = '3,5 x 3,5 cm';
    }
if ($hasil['plafon'] == 0){
    $pdf->Cell(8,6,'','LTR',0,'C',0);
    $pdf->Cell(32,6,'','LTR',0,'C',0);
    $pdf->Cell(110,6,'   ~ Bahan Kalsiboard 3 mm finishing cat dinding dengan lukisan','LT',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    if ($bplafon!=0) {
        $pdf->Cell(33,6,' Motif Kaligrafi','TR',1,'L',0);
    }else{
        $pdf->Cell(33,6,' Motif Awan','TR',1,'L',0);
    }
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'3.','LR',0,'C',0);
    $pdf->Cell(32,6,'Plafon Kubah','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Water Proofing Membrane Ethorching System setebal 3 mm','LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Rangka menggunakan pipa galvanis '.$rangka2,'LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'     '.$rangka2a,'LR',1,'L',0);
}else if($hasil['plafon'] == 2){
    $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    $pdf->Cell(32,6,'Plafon Kubah','LTR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Water Proofing Membrane Ethorching System setebal 3 mm','LTR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Rangka menggunakan pipa galvanis '.$rangka2,'LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'     '.$rangka2a,'LR',1,'L',0);
}

if ($hasil['d']>=5 && $hasil['d']<6){
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LTR',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    }
        
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah','LTR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold','LTR',1,'L',0);
        $pdf->Cell(8,6,'','LRB',0,'C',0);
        $pdf->Cell(32,6,'','LRB',0,'C',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(143,6,'   ~ BONUS : Penangkal Petir','LRB',1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
    
    $pdf->SetFont('helvetica', '', 11);
}else if ($hasil['d']>=6){
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LTR',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    }
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah','LTR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold','LTR',1,'L',0);
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(143,6,'   ~ BONUS : Penangkal Petir','LR',1,'L',0);
        $pdf->Cell(8,6,'','LRB',0,'C',0);
        $pdf->Cell(32,6,'','LRB',0,'C',0);
        $lampu='';
        if ($hasil['d']>=15) {
            $lampu='8';
        }else{
            $lampu='4';
        }
        $pdf->Cell(143,6,'   ~ BONUS : Lampu Sorot '.$lampu.' sisi','LRB',1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
    
    $pdf->SetFont('helvetica', '', 11);
}else{
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LBT',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'3.',1,0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah',1,0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold',1,1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
    
}
 $tbl = '<br>Masa pabrikasi Kubah Galvalume dengan ukuran diatas '.lamapabrikasi($d,'Galvalume').' hari kerja. <b><br>Harga Kubah dengan ukuran diatas adalah : Rp.'.number_format($harga1) .'</b>';
    $pdf->writeHTML($tbl);
    $nourut+=1;
    $pdf->addpage();
    $pdf->SetMargins(15, 10, 10, true);
}
//end bahan ga 


//if bahan en
if ( $bahan == '2' or $bahan == '4'or $bahan == '5' or $bahan == '0') {
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetAutoPageBreak(TRUE, 0);
    if ($ketkubah == 'Atap') {
        $pdf->SetMargins(74, 10, 10, true);
        $pdf->Ln(0);
        $pdf->Write(5,'Dengan Luas Atap '.$luas.' meter');
        $pdf->subWrite(5,'2','',6,4);
    }else{
        if ($dt != 0){
            $pdf->SetMargins(34, 10, 10, true);
            $pdf->Ln(10);
            $pdf->Write(5,'Diameter '.$d.' meter dan Tinggi '.$t.' meter Diameter Tengah '.$dt.' dengan Luas '.$luas.' meter');
            $pdf->subWrite(5,'2','',6,4);
        }else{
            $pdf->SetMargins(50, 10, 10, true);
            $pdf->Ln(-1);
            $pdf->Write(5,'Diameter '.$d.' meter dan Tinggi '.$t.' meter dengan Luas '.$luas.' meter');
            $pdf->subWrite(5,'2','',6,4);
        }
    }
    $pdf->Ln(5);
    $pdf->SetMargins(13, 10, 10, true);
    $tbl = '<br>
    <i><b>'.$nourut.'. SPESIFIKASI KUBAH PLAT BAJA FINISHING ENAMEL ( '.$ketkubah .') </b></i>
    ';
    $pdf->writeHTML($tbl);  
    $pdf->SetMargins(18, 10, 10, true);
    $pdf->Ln(9);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(8,6,'No',1,0,'C',0);
    $pdf->Cell(32,6,'Item',1,0,'C',0);
    $pdf->Cell(143,6,'Spesifikasi','1',1,'C',0); 
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'1.','LR',0,'C',0);
    $pdf->Cell(32,6,'Rangka Kubah','LR',0,'C',0);
    if ($hasil['model']=='custom') {
        $q3 = "SELECT * FROM aki_rangka WHERE 1=1 and aktif=1 and MD5(noSph)='".$noSph."'";
        $rs3 = mysql_query($q3, $dbLink);
        $jumrangka=0;
        while (  $hasil2 = mysql_fetch_array($rs3)) {
            if ($jumrangka!=0 && $hasil2['rangka']!='') {
                $pdf->Cell(8,6,'','LR',0,'C',0);
                $pdf->Cell(32,6,'','LR',0,'C',0);
            }
            if ($hasil2['rangka']!='') {
                $pdf->Cell(143,6,'   ~ '.$hasil2['rangka'],'LR',1,'L',0);
                $jumrangka++;
            }
        }
    }else{
        $pdf->Cell(143,6,'   ~ Rangka primer Pipa Galvanis dengan ukuran '.$rangka,'LR',1,'L',0);
        if ($hasil['d']>=6) {
            $pdf->Cell(8,6,'','LR',0,'C',0);
            $pdf->Cell(32,6,'','LR',0,'C',0);
            $pdf->Cell(35,6,'   ~ System Rangka ','L',0,'L',0);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->Cell(108,6,'Double Frame (Kremona)','R',1,'L',0);
            $pdf->SetFont('helvetica', '', 11);
        }
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Rangka Pendukung Hollow 1,5 x 3,5 cm, tebal 0,7  mm','LR',1,'L',0);
    }
    $pdf->Cell(8,6,'','LTR',0,'C',0);
    $pdf->Cell(32,6,'','LTR',0,'C',0);
    $pdf->Cell(38,6,'   ~ Bahan terbuat dari','LT',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(55,6,' plat besi SPCC SD 0,9 - 1 mm ','T',0,'L',0);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(50,6,' (Spek Enamel Grade)','TR',1,'L',0);
    $pdf->Cell(8,6,'2.','LR',0,'C',0);
    $pdf->Cell(32,6,'Atap Kubah','LR',0,'C',0);
    $pdf->Cell(65,6,'   ~ Finishing Coating Enamel dengan','',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(78,6,' 800-900 Celcius','R',1,'L',0);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(79,6,'   ~ Garansi ketahanan warna sampai dengan','',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(64,6,' 20 tahun','R',1,'L',0);
    $pdf->SetFont('helvetica', '', 11);
if ($hasil['plafon'] == 0){
    $pdf->Cell(8,6,'','LTR',0,'C',0);
    $pdf->Cell(32,6,'','LTR',0,'C',0);
    $pdf->Cell(110,6,'   ~ Bahan Kalsiboard 3 mm finishing cat dinding dengan lukisan','LT',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    if ($bplafon!=0) {
        $pdf->Cell(33,6,' Motif Kaligrafi','TR',1,'L',0);
    }else{
        $pdf->Cell(33,6,' Motif Awan','TR',1,'L',0);
    }
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'3.','LR',0,'C',0);
    $pdf->Cell(32,6,'Plafon Kubah','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Water Proofing Membrane Ethorching System setebal 3 mm','LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Rangka menggunakan pipa galvanis '.$rangka2,'LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'     '.$rangka2a,'LR',1,'L',0);
}else if($hasil['plafon'] == 2){
    $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    $pdf->Cell(32,6,'Plafon Kubah','LTR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Water Proofing Membrane Ethorching System setebal 3 mm','LTR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Rangka menggunakan pipa galvanis '.$rangka2,'LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'     '.$rangka2a,'LR',1,'L',0);
}

if ($hasil['d']>=5 && $hasil['d']<6){
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LTR',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    }
        
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah','LTR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold','LTR',1,'L',0);
        $pdf->Cell(8,6,'','LRB',0,'C',0);
        $pdf->Cell(32,6,'','LRB',0,'C',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(143,6,'   ~ BONUS : Penangkal Petir','LRB',1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
    $pdf->SetFont('helvetica', '', 11);
}else if ($hasil['d']>=6){
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LTR',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    }
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah','LTR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold','LTR',1,'L',0);
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(143,6,'   ~ BONUS : Penangkal Petir','LR',1,'L',0);
        $pdf->Cell(8,6,'','LRB',0,'C',0);
        $pdf->Cell(32,6,'','LRB',0,'C',0);
        $lampu='';
        if ($hasil['d']>=15) {
            $lampu='8';
        }else{
            $lampu='4';
        }
        $pdf->Cell(143,6,'   ~ BONUS : Lampu Sorot 4 sisi','LRB',1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }

    $pdf->SetFont('helvetica', '', 11);
}else{
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.',1,0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'3.',1,0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah',1,0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold',1,1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }

}
 $tbl = '<br>Masa pabrikasi Kubah Enamel dengan ukuran diatas '.lamapabrikasi($d,'Enamel').' hari kerja. <b><br>Harga Kubah dengan ukuran diatas adalah : Rp.'.number_format($harga2) .'</b><br>';
    $pdf->writeHTML($tbl);
    $nourut+=1;
}
//end bahan En 

//if bahan Titanium 
if ($bahan == '3' or $bahan == '5'or $bahan == '6' or $bahan == '0') {
    if ($ketkubah == 'Atap') {
        $pdf->SetMargins(74, 10, 10, true);
        $pdf->Ln(10);
        $pdf->Write(5,'Dengan Luas Atap '.$luas.' meter');
        $pdf->subWrite(5,'2','',6,4);
    }else{
        if ($dt != 0){
            $pdf->SetMargins(34, 10, 10, true);
            $pdf->Ln(10);
            $pdf->Write(5,'Diameter '.$d.' meter dan Tinggi '.$t.' meter Diameter Tengah '.$dt.' dengan Luas '.$luas.' meter');
            $pdf->subWrite(5,'2','',6,4);
        }else{
            $pdf->SetMargins(50, 10, 10, true);
            $pdf->Ln(7);
            $pdf->Write(5,'Diameter '.$d.' meter dan Tinggi '.$t.' meter dengan Luas '.$luas.' meter');
            $pdf->subWrite(5,'2','',6,4);
        }
    }
    $pdf->Ln(2);
    $pdf->SetMargins(13, 10, 10, true);
    $tbl = '<br>
    <i><b>'.$nourut.'. SPESIFIKASI KUBAH PLAT TITANIUM ( '.$ketkubah .') </b></i>
    ';
    $pdf->writeHTML($tbl);
    $pdf->SetMargins(18, 10, 10, true);
    
    $pdf->Ln(10);  
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(8,6,'No',1,0,'C',0);
    $pdf->Cell(32,6,'Item',1,0,'C',0);
    $pdf->Cell(143,6,'Spesifikasi','1',1,'C',0);  
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'1.','LR',0,'C',0);
    $pdf->Cell(32,6,'Rangka Kubah','LR',0,'C',0);
    if ($hasil['model']=='custom') {
        $q3 = "SELECT * FROM aki_rangka WHERE 1=1 and aktif=1 and MD5(noSph)='".$noSph."'";
        $rs3 = mysql_query($q3, $dbLink);
        $jumrangka=0;
        while (  $hasil2 = mysql_fetch_array($rs3)) {
            if ($jumrangka!=0 && $hasil2['rangka']!='') {
                $pdf->Cell(8,6,'','LR',0,'C',0);
                $pdf->Cell(32,6,'','LR',0,'C',0);
            }
            if ($hasil2['rangka']!='') {
                $pdf->Cell(143,6,'   ~ '.$hasil2['rangka'],'LR',1,'L',0);
                $jumrangka++;
            }
        }
    }else{
        $pdf->Cell(143,6,'   ~ Rangka primer Pipa Galvanis dengan ukuran '.$rangka,'LR',1,'L',0);
        if ($hasil['d']>=6) {
            $pdf->Cell(8,6,'','LR',0,'C',0);
            $pdf->Cell(32,6,'','LR',0,'C',0);
            $pdf->Cell(35,6,'   ~ System Rangka ','L',0,'L',0);
            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->Cell(108,6,'Double Frame (Kremona)','R',1,'L',0);
            $pdf->SetFont('helvetica', '', 11);
        }
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Rangka Pendukung Hollow 1,5 x 3,5 cm, tebal 0,7  mm','LR',1,'L',0);
    }
    $pdf->Cell(8,6,'','LTR',0,'C',0);
    $pdf->Cell(32,6,'','LTR',0,'C',0);
    $pdf->Cell(40,6,'   ~ Bahan terbuat dari','LT',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(26,6,'Plat Titanium','T','L',0);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(77,6,'0,5  mm','TR',1,'L',0);
    $pdf->Cell(8,6,'2.','LR',0,'C',0);
    $pdf->Cell(32,6,'Atap Kubah','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Garansi warna dan konstruksi 5 tahun.','LR',1,'L',0);
    
    if ($d >= '0.5' and $d<'3'){
        $rangka2 = '1 inch tebal 1,6  mm, hollow';
        $rangka2a = '1,5 x 3,5 cm';
    }
    elseif ($d >= '3' and $d<'8' ) {
        $rangka2 = '1,25 inch tebal 1,6 mm, Hollow';
        $rangka2a = '1,5 x 3,5 cm';
    }
    elseif ($d >= '8' and $d<'10') {
        $rangka2 = '1,25 inch tebal 1,6 mm, Hollow';
        $rangka2a = '1,5 x 3,5 cm dan 3,5 x 3,5 cm';
    }
    elseif ($d >= '10' and $d<'21') {
        $rangka2 = '1,25 inch tebal 1,6 mm, Hollow';
        $rangka2a = '3,5 x 3,5 cm';
    }
    elseif ($d >= '21' and $d<'31') {
        $rangka2 = '1,5 inch tebal 1,6 mm, Hollow';
        $rangka2a = '3,5 x 3,5 cm';
    }
if ($hasil['plafon'] == 0){
    $pdf->Cell(8,6,'','LTR',0,'C',0);
    $pdf->Cell(32,6,'','LTR',0,'C',0);
    $pdf->Cell(110,6,'   ~ Bahan Kalsiboard 3 mm finishing cat dinding dengan lukisan','LT',0,'L',0);
    $pdf->SetFont('helvetica', 'B', 11);
    if ($bplafon!=0) {
        $pdf->Cell(33,6,' Motif Kaligrafi','TR',1,'L',0);
    }else{
        $pdf->Cell(33,6,' Motif Awan','TR',1,'L',0);
    }
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(8,6,'3.','LR',0,'C',0);
    $pdf->Cell(32,6,'Plafon Kubah','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Water Proofing Membrane Ethorching System setebal 3 mm','LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Rangka menggunakan pipa galvanis '.$rangka2,'LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'     '.$rangka2a,'LR',1,'L',0);
}else if($hasil['plafon'] == 2){
    $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    $pdf->Cell(32,6,'Plafon Kubah','LTR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Water Proofing Membrane Ethorching System setebal 3 mm','LTR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'   ~ Rangka menggunakan pipa galvanis '.$rangka2,'LR',1,'L',0);
    $pdf->Cell(8,6,'','LR',0,'C',0);
    $pdf->Cell(32,6,'','LR',0,'C',0);
    $pdf->Cell(143,6,'     '.$rangka2a,'LR',1,'L',0);
}

if ($hasil['d']>=5 && $hasil['d']<6){
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LTR',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    }
        
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah','LTR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold','LTR',1,'L',0);
        $pdf->Cell(8,6,'','LRB',0,'C',0);
        $pdf->Cell(32,6,'','LRB',0,'C',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(143,6,'   ~ BONUS : Penangkal Petir','LRB',1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
    $pdf->SetFont('helvetica', '', 11);
}else if ($hasil['d']>=6){
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.','LTR',0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        $pdf->Cell(8,6,'3.','LTR',0,'C',0);
    }
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah','LTR',0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold','LTR',1,'L',0);
        $pdf->Cell(8,6,'','LR',0,'C',0);
        $pdf->Cell(32,6,'','LR',0,'C',0);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(143,6,'   ~ BONUS : Penangkal Petir','LR',1,'L',0);
        $pdf->Cell(8,6,'','LRB',0,'C',0);
        $pdf->Cell(32,6,'','LRB',0,'C',0);
        $lampu='';
        if ($hasil['d']>=15) {
            $lampu='8';
        }else{
            $lampu='4';
        }
        $pdf->Cell(143,6,'   ~ BONUS : Lampu Sorot 4 sisi','LRB',1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
    $pdf->SetFont('helvetica', '', 11);
}else{
    if ($hasil['plafon'] == 0 or $hasil['plafon'] == 2){
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'4.',1,0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }else {
        if ($ketkubah != 'Atap') {
            $pdf->Cell(8,6,'3.',1,0,'C',0);
        }else{
            $pdf->Cell(8,6,'','T',0,'C',0);
        }
    }
    if ($ketkubah != 'Atap') {
        $pdf->Cell(32,6,'Aksesoris Kubah',1,0,'C',0);
        $pdf->Cell(143,6,'   ~ Makara hiasan ujung kubah bagian luar bahan galvalume warna gold',1,1,'L',0);
    }else{
        $pdf->Cell(32,6,'','T',0,'C',0);
        $pdf->Cell(143,6,'','T',1,'L',0);
    }
}
 $tbl = '<br>Masa pabrikasi Kubah Titanium dengan ukuran diatas '.lamapabrikasi($d,'Galvalume').' hari kerja. <b><br>Harga Kubah dengan ukuran diatas adalah : Rp.'.number_format($harga3) .'</b><br>';
    $pdf->writeHTML($tbl);
}

if ($bahan == '2' or $bahan == '3' or $bahan == '0' ) {

    $pdf->addpage();
    $pdf->SetMargins(15, 10, 10, true);
}
    
    $tf = '';
    if ($ongkir != 0){
        $tf = 'sudah';
    }else{
        $tf = 'belum';
    }
    if ($bahan == '3' or $bahan == '0'  ) {
        $nourut++;
    }
     
}
// bahan galvalume = 1
// bahan enamel = 2
// bahan gal dan enam = 0
$tharga1 = number_format($tharga1);
$tharga2 = number_format($tharga2);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->Ln(6);

/*if ($nourut >3) {*/
    $rs2 = mysql_query($q2, $dbLink);
    $pdf->SetFont('helvetica', 'B', 11);

    if ($bahan == '1' or $bahan == '4' or $bahan == '6' or $bahan == '0') {
        $tharga1=0;
        $pdf->Cell(10,6,' ',0,0,'L',0);
        $pdf->Cell(80,7,'Rincian Galvalum',0,1,'L',0);
        while (  $hasil = mysql_fetch_array($rs2)) {
           $ketkubah = $hasil['ket'];
           $jumlah = $hasil['jumlah'];
           $pdf->Cell(80,6,' ',0,0,'L',0);
           $pdf->Cell(40,6,$ketkubah.' x '.$jumlah,0,0,'L',0);
           $pdf->Cell(10,6,':   Rp.',0,0,'C',0);
           $pdf->Cell(40,6,number_format($hasil['harga']),0,1,'R',0);
           if ($hasil['biaya_plafon'] !=0) {
               $pdf->Cell(80,6,' ',0,0,'L',0);
               $pdf->Cell(40,6,"Biaya Kaligrafi",0,0,'L',0);
               $pdf->Cell(10,6,':   Rp.',0,0,'C',0);
               $pdf->Cell(40,6,number_format($hasil['biaya_plafon']),0,1,'R',0);
           }
           $tharga1 +=  $hasil['harga']*$jumlah+$hasil['biaya_plafon'];
        }
        $pdf->Cell(80,6,' ',0,0,'L',0);
        $pdf->Cell(40,6,'Total ',0,0,'L',0);
        $pdf->Cell(10,6,':   Rp.','T',0,'C',0);
        $pdf->Cell(40,6,number_format($tharga1),'T',1,'R',0);
    }
    if($bahan == '2' or $bahan == '4' or $bahan == '5' or $bahan == '0'){
        $tharga2=0;
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(10,6,' ',0,0,'L',0);
        $pdf->Cell(80,7,'Rincian Enamel',0,1,'L',0);
        $rs2 = mysql_query($q2, $dbLink);
        while (  $hasil = mysql_fetch_array($rs2)) {
           $ketkubah = $hasil['ket'];
           $jumlah = $hasil['jumlah'];
           $pdf->Cell(80,6,' ',0,0,'L',0);
           $pdf->Cell(40,6,$ketkubah.' x '.$jumlah,0,0,'L',0);
           $pdf->Cell(10,6,':   Rp.',0,0,'C',0);
           $pdf->Cell(40,6,number_format($hasil['harga2']),0,1,'R',0);
           if ($hasil['biaya_plafon'] !=0) {
               $pdf->Cell(80,6,' ',0,0,'L',0);
               $pdf->Cell(40,6,"Biaya Kaligrafi",0,0,'L',0);
               $pdf->Cell(10,6,':   Rp.',0,0,'C',0);
               $pdf->Cell(40,6,number_format($hasil['biaya_plafon']),0,1,'R',0);
           }
            $tharga2 +=  $hasil['harga2']*$jumlah+$hasil['biaya_plafon'];
        }
        $pdf->Cell(80,6,' ',0,0,'L',0);
        $pdf->Cell(40,6,'Total ',0,0,'L',0);
        $pdf->Cell(10,6,':   Rp.','T',0,'C',0);
        $pdf->Cell(40,6,number_format($tharga2),'T',1,'R',0);
    }
    if($bahan == '3' or $bahan == '5' or $bahan == '6' or $bahan == '0'){
        $tharga3=0;
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(10,6,' ',0,0,'L',0);
        $pdf->Cell(80,7,'Rincian Titanium',0,1,'L',0);
        $rs2 = mysql_query($q2, $dbLink);
        while (  $hasil = mysql_fetch_array($rs2)) {
           $ketkubah = $hasil['ket'];
           $jumlah = $hasil['jumlah'];
           $pdf->Cell(80,6,' ',0,0,'L',0);
           $pdf->Cell(40,6,$ketkubah.' x '.$jumlah,0,0,'L',0);
           $pdf->Cell(10,6,':   Rp.',0,0,'C',0);
           $pdf->Cell(40,6,number_format($hasil['harga3']),0,1,'R',0);
           if ($hasil['biaya_plafon'] !=0) {
               $pdf->Cell(80,6,' ',0,0,'L',0);
               $pdf->Cell(40,6,"Biaya Kaligrafi",0,0,'L',0);
               $pdf->Cell(10,6,':   Rp.',0,0,'C',0);
               $pdf->Cell(40,6,number_format($hasil['biaya_plafon']),0,1,'R',0);
           }
            $tharga3 +=  $hasil['harga3']*$jumlah+$hasil['biaya_plafon'];
        }
        $pdf->Cell(80,6,' ',0,0,'L',0);
        $pdf->Cell(40,6,'Total ',0,0,'L',0);
        $pdf->Cell(10,6,':   Rp.','T',0,'C',0);
        $pdf->Cell(40,6,number_format($tharga3),'T',1,'R',0);
    }

if ($bahan != '0') {
    /*if ($nourut<3) {
        $pdf->addpage();
        $pdf->SetMargins(15, 10, 10, true);
    }*/
    $pdf->SetMargins(17, 10, 10, true);
    $pdf->Ln(6);
}

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(40,6,'NB :','',1,'',0);
$pdf->Ln(5);

$pdf->Cell(100,6,'Harga di atas belum termasuk PPn 10%.','',1,'L',0);$pdf->Ln(2);
$pdf->Cell(100,6,'Harga bisa berubah sewaktu-waktu mengikuti perubahan harga material.','',1,'L',0);$pdf->Ln(2);
$pdf->Cell(100,6,'Harga di atas '.$tf.' termasuk Biaya Transportasi (Barang dan Tukang Pemasang).','',1,'L',0);$pdf->Ln(2);
if ($bplafon!=0) {
    $pdf->Cell(100,6,'Harga di atas sudah termasuk Motif Plafon Kaligrafi.','',1,'L',0);$pdf->Ln(2);
}
$pdf->Cell(40,6,'Sistem pembayaran :','',1,'',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Ln(5);
$pdf->Cell(10,6,'','',0,'',0);$pdf->Cell(100,6,'- Pembayaran pertama sebesar 30% sebagai uang muka.','',1,'L',0);$pdf->Ln(2);
$pdf->Cell(10,6,'','',0,'',0);$pdf->Cell(100,6,'- Pembayaran kedua 25% diberikan saat kubah selesai pabrikasi dan akan dikirimkan.','',1,'L',0);$pdf->Ln(2);
$pdf->Cell(10,6,'','',0,'',0);$pdf->Cell(100,6,'- Pembayaran ketiga 35% diberikan saat barang dan tukang pemasang sampai di lokasi.','',1,'L',0);$pdf->Ln(2);
$pdf->Cell(10,6,'','',0,'',0);$pdf->Cell(100,6,'- Pembayaran keempat 10% diberikan saat makara siap terpasang.','',1,'L',0);$pdf->Ln(2);
$tbl = '
<div><b>NB :<br><br>';
if ($bahan != '0') {
    $pdf->Ln(5);
}

$pdf->addpage();
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(1);
$tbl = '
<u><b>Rangka Kubah</b></u>    
';
$pdf->writeHTML($tbl);
if ($plafon == 1){
    if ($model =='bawang') {
        $imgrangka = '../dist/img/bawangtanpaplafon.jpg';
    }elseif ($model =='pinang') {
        $imgrangka = '../dist/img/pinangtanpaplafon.jpg';
    }elseif ($model =='madinah') {
        $imgrangka = '../dist/img/madinahtanpaplafon.jpg';
    }elseif ($model =='setbola') {
        $imgrangka = '../dist/img/sbtanpaplafon.jpg';
    }else{
        $imgrangka = '../dist/img/sbtanpaplafon.jpg';
    }
}else{
    if ($model =='bawang') {
    $imgrangka = '../dist/img/RangkaBawang.jpg';
    }elseif ($model =='pinang') {
        $imgrangka = '../dist/img/RangkaPinang.jpg';
    }elseif ($model =='madinah') {
        $imgrangka = '../dist/img/RangkaMadinah.jpg';
    }elseif ($model =='setbola') {
        $imgrangka = '../dist/img/RangkaSetengahBola.jpg';
    }else{
        $imgrangka = '../dist/img/RangkaSetengahBola.jpg';
    }
}

$pdf->image('../dist/img/ttd.jpg',122,204);
$arr = explode('-', $tgl);
$newDate = $arr[2].' '.namaBulan_id($arr[1]).' '.$arr[0];
$pdf->Ln(140);
$pdf->image($imgrangka,17,60,184,120);
$pdf->SetFont('helvetica', '', 12);
$tbl = '
Demikian penawaran harga kubah dari kami, atas perhatian dan kerjasamanya kami sampaikan terima kasih.<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(120,6,'',0,0,'C',0);
$pdf->Cell(50,6,'Kediri, '.$newDate,0,1,'R',0);
$pdf->Ln(36);
$pdf->SetFont('helvetica', 'BU', 11);
$pdf->Cell(120,6,'',0,0,'C',0);
$pdf->Cell(50,6,'ANDIK NUR SETIAWAN',0,1,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(120,6,'',0,0,'C',0);
$pdf->Cell(50,6,'Direktur PT. Anugerah Kubah Indonesia',0,1,'C',0);
$pdf->addpage();
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetMargins(17, 10, 10, true);
$pdf->Cell(0,6,'CONTOH MOTIF KUBAH MASJID',0,1,'C',0);
$pdf->Cell(0,6,'A.  KUBAH MODEL SETENGAH BOLA',0,1,'L',0);
$pdf->image('../dist/img/setbola.png',17,60,184,202);
$pdf->addpage();
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(1);
$pdf->Cell(0,6,'B.   KUBAH MODEL MADINAH',0,1,'L',0);
$pdf->image('../dist/img/madinah.png',17,70,184,202);
$pdf->addpage();
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(1);
$pdf->Cell(0,6,'C.   KUBAH MODEL BAWANG',0,1,'L',0);
$pdf->image('../dist/img/bawang.png',17,70,184,202);
$pdf->addpage();
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(1);
$pdf->Cell(0,6,'D.   KUBAH MODEL PINANG',0,1,'L',0);
$pdf->image('../dist/img/pinang.png',17,70,184,45);
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(65);
$pdf->Cell(0,6,'E.  KUBAH CUSTOM',0,1,'L',0);
$pdf->Ln(45);
$pdf->image('../dist/img/custom.png',20,130,170,120);
$pdf->addpage();
$pdf->Cell(0,6,'CONTOH MOTIF PLAFON AWAN',0,1,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->SetMargins(17, 10, 10, true);
$pdf->Ln(1);
$pdf->Cell(0,6,'*) Motif Plafon Standart adalah Motif Awan, untuk Motif Plafon Kaligrafi dikenakan biaya tambahan.',0,1,'L',0);
$pdf->image('../dist/img/plafon.png',17,70,184,202);
$pdf->addpage();
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0,6,'CONTOH MOTIF PLAFON KALIGRAFI',0,1,'C',0);
$pdf->image('../dist/img/plafon2.png',17,70,184,202);
$pdf->Output(str_replace('/', '.', $no).'-'.$nama_cust.'-'.$alamat.'.pdf','I');
?>