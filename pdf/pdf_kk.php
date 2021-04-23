<?php
require_once('../config.php');
require('../function/fpdf/html_table.php');
require_once ("../function/fungsi_formatdate.php");
require_once ("../function/fungsi_convertNumberToWord.php");
$pdf=new PDF('P','mm',array(210,330));
$html="";
$pdf->AddPage();
$pdf->SetMargins(20, 10, 10, true);
//HEADER        
$tgl = '';
$noKk = ($_GET["noKK"]);
$q = "SELECT s.*,dkk.*,u.nama,p.name as pname,k.name as kname ";
$q.= "FROM aki_kk s left join aki_dkk dkk on s.noKk=dkk.noKK left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
$q.= "WHERE 1=1 and MD5(s.noKk)='" . $noKk."'";
$q.= " ORDER BY s.noKk asc ";
$rs = mysql_query($q, $dbLink);
$hasil = mysql_fetch_array($rs);
$no = $hasil['noKk'];
$pdf->SetFont('helvetica', 'B', 14);
 $pdf->Cell(190,8,'PERJANJIAN JUAL BELI DAN PEMASANGAN KUBAH MASJID',0,1,'C',0);
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(190,6, 'Nomor : '.$no,0,0,'C',0);

$pdf->SetFont('helvetica', '', 11);
$pdf->SetAutoPageBreak(TRUE, 0);
$pdf->SetFont('helvetica', '', 11); 
$pdf->Ln(10);

function hariIndo ($hariInggris) {
  switch ($hariInggris) {
    case 'Sunday':
      return 'Minggu';
    case 'Monday':
      return 'Senin';
    case 'Tuesday':
      return 'Selasa';
    case 'Wednesday':
      return 'Rabu';
    case 'Thursday':
      return 'Kamis';
    case 'Friday':
      return 'Jumat';
    case 'Saturday':
      return 'Sabtu';
    default:
      return 'hari tidak valid';
  }
}

$tbl = '<br>
Pada hari ini '.hariIndo(strftime('%A', strtotime($hasil['tanggal']))).' tanggal '.date("d",strtotime($hasil['tanggal'])).' bulan '.date("F",strtotime($hasil['tanggal'])).' tahun '.date("Y",strtotime($hasil['tanggal'])).' kami yang bertanda tangan dibawah ini: <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,10,'1.',0,0,'R',0);
$pdf->Cell(1,10,'Nama',0,0,'L',0);
$pdf->Cell(40,10,':',0,0,'R',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(1,10,'ANDIK NUR SETIAWAN',0,1,'L',0); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,10,'',0,0,'R',0);
$pdf->Cell(12,2,'No. Identitas',0,0,'L',0);
$pdf->Cell(29,2,':',0,0,'R',0);
$pdf->Cell(23,2,'3571020710760001 (KTP)',0,1,'L',0);
$pdf->Ln(3);
$pdf->Cell(20,10,'',0,0,'R',0);
$pdf->Cell(12,2,'Alamat',0,0,'L',0);
$pdf->Cell(29,2,':',0,0,'R',0);
$pdf->Cell(23,2,'Ngadirejo Gg. I Buntu RT/RW 004/009 Kel/Desa Ngadirejo ',0,1,'L',0); 
$pdf->Ln(3);
$pdf->Cell(20,10,'',0,0,'R',0);
$pdf->Cell(12,2,'',0,0,'L',0);
$pdf->Cell(29,2,'',0,0,'R',0);
$pdf->Cell(23,2,'Kecamatan Kota  Kota Kediri, Jawa Timur.',0,1,'L',0); 
$pdf->Ln(1);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(2,5,'Jabatan',0,0,'L',0);
$pdf->Cell(39,5,':',0,0,'R',0);
$pdf->Cell(24,5,'Direktur PT. Anugerah Kubah Indonesia',0,1,'L',0); 
$pdf->Ln(3);
$tbl = '
Dalam hal ini bertindak untuk dan atas nama Direksi PT. Anugerah Kubah Indonesia selaku pihak yang akan  menjadi  Pemborong  Kerja dan Pemasangan Kubah Masjid, selanjutnya disebut  sebagai  <b>Pihak Pertama .</b><br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,10,'2.',0,0,'R',0);
$pdf->Cell(1,10,'Nama',0,0,'L',0);
$pdf->Cell(40,10,':',0,0,'R',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(1,10,strtoupper($hasil['nama_cust']),0,1,'L',0); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,10,'',0,0,'R',0);
$pdf->Cell(12,2,'No. Identitas',0,0,'L',0);
$pdf->Cell(29,2,':',0,0,'R',0);
$pdf->Cell(23,2,$hasil['no_id'].' ('.$hasil['jenis_id'].')',0,1,'L',0); 
$pdf->Cell(20,9,'',0,0,'R',0);
$pdf->Cell(2,9,'Alamat',0,0,'L',0);
$pdf->Cell(39,9,':',0,0,'R',0);
$pdf->MultiCell(120,8.5,$hasil['alamat'].', '.$hasil['kname'].', '.$hasil['pname'],0,'B',0);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(2,5,'Jabatan',0,0,'L',0);
$pdf->Cell(39,5,':',0,0,'R',0);
$pdf->Cell(24,5,$hasil['jabatan'],0,1,'L',0); 
$pdf->Ln(3);
$tbl = '
Dalam hal ini bertindak untuk dan atas nama Panitia Pembangunan Masjid '.$hasil['nmasjid'].' , selanjutnya disebut <b>Pihak Kedua .</b><br>
';
$pdf->writeHTML($tbl);
$pdf->Ln(1);
$tbl = '
Selanjutnya <b>Pihak Pertama </b>dan <b>Pihak Kedua</b> secara bersama-sama disebut "Para Pihak". Bahwa Para Pihak sepakat untuk membuat dan mengikatkan diri dalam Perjanjian Jual  Beli dan Pemasangan Kubah Masjid <b>("Perjanjian")</b> ini dan terlebih dahulu menjelaskan hal-hal sebagai berikut :
<br>
';
$pdf->writeHTML($tbl); 
$pdf->Ln(3);
$pdf->Cell(20,5,'1). ',0,0,'R',0);
$tbl = '
Bahwa <b>Pihak Pertama </b> adalah suatu Perseroan Terbatas yang berdiri berdasarkan hukum 
<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'Republik Indonesia, berkedudukan di Kediri, Jawa Timur yang memproduksi kubah masjid.',0,0,'L',0);
$pdf->Ln(5);
$pdf->Cell(20,5,'2). ',0,0,'R',0);
$tbl = '
Bahwa <b>Pihak Kedua</b> adalah perorangan yang memesan dan bertanggung jawab pada 
<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'pemesanan kubah Masjid '.$hasil['nmasjid'].'.',0,0,'L',0);
$pdf->Ln(15);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 1',0,1,'C',0);
$pdf->Cell(190,4, 'PEKERJAAN',0,0,'C',0);
$tbl = '
<b>Pihak Kedua</b> setuju untuk memesan Pekerjaan pada <b>Pihak Pertama</b> dengan nama pekerjaan :
<br>
';
$pdf->Ln(10);
$pdf->writeHTML($tbl);
$pdf->Cell(20,10,'',0,0,'R',0);
$pdf->Cell(1,10,'Nama Proyek',0,0,'L',0);
$pdf->Cell(40,10,':',0,0,'R',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(1,10,strtoupper($hasil['nproyek']),0,1,'L',0); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,10,'',0,0,'R',0);
$pdf->Cell(12,2,'Alamat Proyek',0,0,'L',0);
$pdf->Cell(29,2,':',0,0,'R',0);
$pdf->Cell(23,2,$hasil['alamat_proyek'],0,1,'L',0); 

$pdf->Ln(43);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(135,2,'',0,0,'L',0);
$pdf->Cell(20,5,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,5,'PIHAK II',1,1,'C',0); 
$pdf->Cell(135,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->Ln(-10);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 2',0,1,'C',0);
$pdf->Cell(190,4, 'DASAR PELAKSANAAN PEKERJAAN',0,0,'C',0);
$pdf->SetFont('helvetica', '', 11);
$tbl = '
Pekerjaan tersebut dalam Pasal 1 Perjanjian ini harus dilaksanakan oleh <b>Pihak Pertama</b> dengan spesifikasi rincian pekerjaan sebagai berikut : 
<br>
';
$pdf->Ln(10);
$pdf->writeHTML($tbl);
$pdf->Ln(3);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(1,5,'Jenis Pekerjaan',0,0,'L',0);
$pdf->Cell(40,5,':',0,0,'R',0);
$pdf->Cell(1,5,strtoupper($hasil['kubah']),0,1,'L',0); 
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(12,5,'Bahan',0,0,'L',0);
$pdf->Cell(29,5,':',0,0,'R',0);
$pdf->Cell(23,5,$hasil['bahan'],0,1,'L',0); 
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(2,5,'Ukuran',0,0,'L',0);
$pdf->Cell(39,5,':',0,0,'R',0);
$pdf->MultiCell(120,5,'Diameter '.$hasil['d'].' m Tinggi '.$hasil['t'].' m Luas '.$hasil['luas'].' m'.chr(178),0,'B',0);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(2,5,'Jumlah Kubah',0,0,'L',0);
$pdf->Cell(39,5,':',0,0,'R',0);
$pdf->Cell(24,5,$hasil['jumlah'],0,1,'L',0); 
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(2,5,'Design Kubah',0,0,'L',0);
$pdf->Cell(39,5,':',0,0,'R',0);
$pdf->MultiCell(120,5,'Terlampir.',0,'B',0);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(2,5,'Spesifikasi',0,0,'L',0);
$pdf->Cell(39,5,':',0,0,'R',0);
$pdf->Ln(3);
$pdf->SetMargins(30, 10, 10, true);
$pdf->MultiCell(120,5,'',0,'B',0);

$tbl = 
chr(149).'  Rangka utama pipa galvanis 1,25 inch tebal 1,6  mm<br>
'.chr(149).'  Bahan terbuat dari plat besi SPCC SD 0,9 - 1 mm (Spek Enamel Grade)<br>
'.chr(149).'  Hollow 1,5 x 3,5 cm tebal 0,7 mm<br>
'.chr(149).'  Finishing coating Enamel dengan suhu 800-900'.chr(176).' Celcius<br>
'.chr(149).'  Plafon kalsiboard 3 mm motif AWAN.<br>
'.chr(149).'  Kedap air menggunakan membran bakar 3 mm<br>
'.chr(149).'  Makara bahan galvalume warna gold bentuk Lafadz Allah<br>
';
$pdf->writeHTML($tbl);
$pdf->SetMargins(10, 10, 10, true);
$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 3',0,1,'C',0);
$pdf->Cell(190,4, 'HARGA BORONGAN DAN CARA PEMBAYARAN',0,1,'C',0);

$pdf->Ln(3);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Harga Borongan untuk pelaksanaan pekerjaan Kubah Masjid adalah Rp '.number_format($hasil['harga']).' <b>belum </b><br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(40,5,'termasuk PPN.',0,1,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Harga Borongan <b>sudah termasuk</b> Biaya Transportasi dan Biaya Pemasangan. 
<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'3. ',0,0,'R',0);
$tbl = '
Harga Borongan sebagaimana tersebut diatas wajib dibayarkan oleh <b>Pihak Kedua</b> dengan  <br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'ketentuan sebagai berikut: ',0,1,'L',0);

$pdf->SetMargins(30, 10, 10, true);
$pdf->Ln(1); 
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(15,5,'Termin',1,0,'C',0);
$pdf->Cell(65,5,'Waktu Pembayaran',1,0,'C',0);
$pdf->Cell(30,5,'Presentase',1,0,'C',0);
$pdf->Cell(50,5,'Nilai (Rp)',1,1,'C',0); 
$pdf->Cell(15,5,'I*','LR',0,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,5,'Saat penandatanganan Perjanjian','LR',0,'L',0);
$pdf->Cell(30,5,'30% x Harga ','LR',0,'C',0);
$pdf->Cell(50,5,'Rp. '.number_format($hasil['harga']*0.3),'LR',1,'L',0); 
$pdf->Cell(15,5,'','LRB',0,'C',0);
$pdf->Cell(65,5,'ini','LRB',0,'C',0);
$pdf->Cell(30,5,'Borongan','LRB',0,'C',0);
$pdf->Cell(50,5,'','LRB',1,'C',0); 

$pdf->Cell(15,5,'II','LR',0,'C',0);
$pdf->Cell(65,5,'Saat kubah selesai dipabrifikasi ','LR',0,'C',0);
$pdf->Cell(30,5,'25% x Harga ','LR',0,'C',0);
$pdf->Cell(50,5,'Rp. '.number_format($hasil['harga']*0.25),'LR',1,'L',0); 
$pdf->Cell(15,5,'','LRB',0,'C',0);
$pdf->Cell(65,5,'dan akan dikirimkan','LRB',0,'C',0);
$pdf->Cell(30,5,'Borongan','LRB',0,'C',0);
$pdf->Cell(50,5,'','LRB',1,'C',0); 

$pdf->Cell(15,5,'III','LR',0,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,5,'Saat tim pemasang dan kubah ','LR',0,'C',0);
$pdf->Cell(30,5,'35% x Harga ','LR',0,'C',0);
$pdf->Cell(50,5,'Rp. '.number_format($hasil['harga']*0.35),'LR',1,'L',0); 
$pdf->Cell(15,5,'','LRB',0,'C',0);
$pdf->Cell(65,5,'sudah sampai di lokasi Proyek','LRB',0,'C',0);
$pdf->Cell(30,5,'Borongan','LRB',0,'C',0);
$pdf->Cell(50,5,'','LRB',1,'C',0); 

$pdf->Cell(15,5,'IV','LR',0,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,5,'Saat kubah sudah terpasang ','LR',0,'C',0);
$pdf->Cell(30,5,'10% x Harga ','LR',0,'C',0);
$pdf->Cell(50,5,'Rp. '.number_format($hasil['harga']*0.1),'LR',1,'L',0); 
$pdf->Cell(15,5,'','LRB',0,'C',0);
$pdf->Cell(65,5,'','LRB',0,'L',0);
$pdf->Cell(30,5,'Borongan','LRB',0,'C',0);
$pdf->Cell(50,5,'','LRB',1,'C',0); 
$tbl = '
*Berlaku sebagai uang panjar<br>
';
$pdf->writeHTML($tbl);
  
$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(28);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,5,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,5,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);
$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);

$pdf->SetFont('helvetica', '', 11);
$tbl = '
Pembayaran dilakukan melalui rekening resmi sebagai Berikut: <br>
';
$pdf->SetMargins(15, 10, 10, true);
$pdf->writeHTML($tbl);
$pdf->Ln(4);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,8,'Nama Bank',1,0,'C',0);
$pdf->Cell(65,8,'Nama Akun',1,0,'C',0);
$pdf->Cell(55,8,'No. Rekening',1,1,'C',0);
$pdf->Cell(65,6,'Bank Rakyat Indonesia (BRI)',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'PT. Anugerah Kubah Indonesia',1,0,'L',0);
$pdf->Cell(55,6,'0033 - 01 - 003664 - 30 - 5',1,1,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,6,'Bank Central Asia (BCA)',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'PT. Anugerah Kubah Indonesia',1,0,'L',0);
$pdf->Cell(55,6,'033 - 330 - 2558',1,1,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,6,'Bank Mandiri',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'PT. Anugerah Kubah Indonesia',1,0,'L',0);
$pdf->Cell(55,6,'171 - 00 - 2558002 - 2',1,1,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,6,'BSI (BNI Syariah)',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'Andik Nur Setiawan ',1,0,'L',0);
$pdf->Cell(55,6,'11722 - 91744',1,1,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,6,'Bank Mandiri',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'Andik Nur Setiawan ',1,0,'L',0);
$pdf->Cell(55,6,'171 - 00 - 0743525 - 1',1,1,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,6,'Bank Rakyat Indonesia (BRI)',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'Andik Nur Setiawan ',1,0,'L',0);
$pdf->Cell(55,6,'2289 - 01 - 000402 - 56 - 7',1,1,'L',0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(65,6,'Bank Central Asia (BCA)',1,0,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(65,6,'Andik Nur Setiawan ',1,0,'L',0);
$pdf->Cell(55,6,'033 - 245 - 9846 ',1,1,'L',0);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 4',0,1,'C',0);
$pdf->Cell(190,4, 'MASA BERLAKU PERJANJIAN',0,1,'C',0);
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Perjanjian ini berlaku efektif sejak ditandatangani oleh Para Pihak<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Perjanjian ini berakhir dengan sendirinya saat seluruh kewajiban Para Pihak berdasarkan<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'Perjanjian ini telah dipenuhi.',0,0,'L',0);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 5',0,1,'C',0);
$pdf->Cell(190,4, 'JANGKA WAKTU PEKERJAAN',0,1,'C',0);
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Dalam menyelesaikan pekerjaan yang disepakati oleh Para Pihak, <b>Pihak Pertama</b> harus <br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'menyelesaikan Pekerjaan sesuai dengan ketentuan sebagai berikut :',0,1,'L',0);

$pdf->SetMargins(30, 10, 10, true);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Ln(2);
$pdf->Cell(45,6,'Tahapan Pekerjaan','LT',0,'C',0);
$pdf->Cell(30,6,'Waktu ','LRT',0,'C',0);
$pdf->Cell(85,6,'Keterangan','LRT',1,'C',0);
$pdf->Cell(45,6,' ','L',0,'C',0);
$pdf->Cell(30,6,'(Hari Kerja)','LR',0,'C',0);
$pdf->Cell(85,6,' ','LRB',1,'C',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(45,30,'Masa Produksi','LT',0,'C',0);
$pdf->Cell(30,30,$hasil['mproduksi'].' hari','LT',0,'C',0);
$pdf->MultiCell(85,5,'Waktu pengerjaan terhitung sejak terpenuhinya hal berikut:
a).  Uang Muka Lunas 
b).  Konfirmasi Pihak Kedua atas desain, motif, warna, ukuran dan spesifikasi serta sudah selesai atau belumnya dudukan kubah.
',1,'B',0);
$pdf->Cell(45,25,'Masa Pemasangan','LTB',0,'C',0);
$pdf->Cell(30,25,$hasil['mpemasangan'].' hari','LTB',0,'C',0);
$pdf->MultiCell(85,5,'Terhitung sejak tim pemasangan sampai di lokasi dengan ketentuan dudukan kubah sudah diselesaikan dan peralatan yang dibutuhkan (scaffolding dsb.) telah disiapkan oleh Pihak Kedua.
','LRB','B',0);
$pdf->SetMargins(15, 10, 10, true);
$pdf->Ln(2);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Hari Kerja yang dimaksud adalah hari Senin - Sabtu dan tidak termasuk Hari Libur Nasional <br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'dan Hari Libur yang ditentukan oleh Perusahaan.',0,1,'L',0);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(18);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,5,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,5,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);
$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 6',0,1,'C',0);
$pdf->Cell(190,4, 'HAK DAN KEWAJIBAN PIHAK PERTAMA',0,1,'C',0);
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Kewajiban <b>Pihak Pertama</b> <br>
';
$pdf->SetMargins(18, 10, 10, true);
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'a. ',0,0,'R',0);
$tbl = '
Memberikan Berita Acara Serah Terima Pekerjaan serta Surat Garansi kepada <b>Pihak Kedua</b><br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'setelah pekerjaan selesai 100%.',0,1,'L',0);
$pdf->Cell(20,5,'b. ',0,0,'R',0);
$tbl = '
Memberikan informasi kepada <b>Pihak Kedua</b> terkait perkembangan Pekerjaan.<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'c. ',0,0,'R',0);
$tbl = '
Menyelesaikan Pekerjaan sesuai dengan spesifikasi yang tercantum dalam Pasal 2 <br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'Perjanjian ini.',0,1,'L',0);

$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(1);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Hak <b>Pihak Pertama</b> <br>
';
$pdf->SetMargins(18, 10, 10, true);
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'a. ',0,0,'R',0);
$tbl = '
Mendapatkan pembayaran sebagaimana yang tercantum dalam Pasal 3 Perjanjian ini.<br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'b. ',0,0,'R',0);
$tbl = '
Menerima informasi terkait spesifikasi kubah dan pengerjaan dudukan kubah dari <b>Pihak </b> <br>
';
$pdf->writeHTML($tbl);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'Kedua.',0,1,'L',0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'c. ',0,0,'R',0);
$tbl = '
Melakukan pemberhentian pengerjaan proyek jika belum ada pembayaran sesuai dengan <br>
';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'Pasal 3 pada Perjanjian ini.',0,1,'L',0);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 7',0,1,'C',0);
$pdf->Cell(190,4, 'HAK DAN KEWAJIBAN PIHAK KEDUA',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Kewajiban <b>Pihak Kedua</b> <br>
';
$pdf->SetMargins(18, 10, 10, true);
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'a. ',0,0,'R',0);
$tbl = '
Menyelesaikan administrasi kelengkapan pekerjaan seperti desain, motif, warna, dan lain <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$pdf->Cell(40,5,'sebagainya setelah dilakukan pembayaran uang panjar atau uang termin I.',0,1,'L',0);
$pdf->Cell(20,5,'b. ',0,0,'R',0);
$tbl = '
Membuat dudukan kubah dan menginformasikan penyelesaian dudukan kubah kepada <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
<b>Pihak Pertama</b>. Apabila dudukan kubah belum selesai, maka <b>Pihak Kedua</b> wajib mengikuti <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
arahan dari <b>Pihak Pertama</b> untuk pembuatan dudukan kubah.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'c. ',0,0,'R',0);
$tbl = '
Memberikan ukuran lubang dalam dudukan disertai dengan foto/gambar dan ukurannya <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
agar dapat disesuaikan dengan Kubah buatan <b>Pihak Pertama.</b><br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'d. ',0,0,'R',0);
$tbl = '
Melakukan pembayaran sesuai dengan harga pekerjaan dan cara pembayaran yang  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
terdapat pada Pasal 3 surat Perjanjian ini.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'e. ',0,0,'R',0);
$tbl = '
Mengirimkan foto dudukan yang sudah jadi dan foto masjid yang akan dipasang kubah <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
kepada <b>Pihak Pertama</b> sebelum pembayaran termin ketiga.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'f. ',0,0,'R',0);
$tbl = '
Menjaga keamanan material bahan yang telah terkirim di lokasi <b>Pihak Kedua.</b><br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'g. ',0,0,'R',0);
$tbl = '
Melakukan  pengecekan barang yang telah terkirim di lokasi <b>Pihak Kedua</b> dengan <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
mengisi Surat Jalan yang telah disediakan  <b>Pihak Pertama.</b><br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'h. ',0,0,'R',0);
$pdf->SetMargins(40, 10, 10, true);
$tbl = '
Selama proses pemasangan, Pihak Kedua menyediakan : <br>';
$pdf->writeHTML($tbl);
$pdf->MultiCell(150,5,'i.      Dudukan Kubah selesai 100%
ii.      Listrik dengan tegangan stabil minimal 1300 Watt
iii.     LPG 3 Kg/12 Kg (Luar Jawa)
iv.     Bambu/Kayu/Scaffolding sesuai tinggi masjid dan diameter kubah
v.      Penginapan yang layak untuk teknisi pemasangan
vi.     Konsumsi team pemasang',0,'B',0);
$pdf->SetMargins(18, 10, 10, true);
$pdf->Ln(0);
$pdf->Cell(20,5,'i. ',0,0,'R',0);
$tbl = '
Melakukan pengecekan terakhir pada hasil pekerjaan <b>Pihak Pertama</b> sebelum tim <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
pemasangan pulang dengan menandatangani Berita Acara yang disediakan <b>Pihak Pertama.</b><br>';
$pdf->writeHTML($tbl);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(7);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,4,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,4,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(2);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Hak <b>Pihak Kedua</b> <br>
';
$pdf->SetMargins(18, 10, 10, true);
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'a. ',0,0,'R',0);
$tbl = '
Mendapatkan arahan pembuatan dudukan Kubah yang benar dari <b>Pihak Pertama</b> jika <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
dudukan belum selesai dibuat.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'b. ',0,0,'R',0);
$tbl = '
Mendapatkan laporan perkembangan penyelesaian pekerjaan dari <b>Pihak Pertama</b>. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'c. ',0,0,'R',0);
$tbl = '
Mendapatkan pelayanan prima dari sales yang berada di bawah <b>Pihak Pertama</b>. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'d. ',0,0,'R',0);
$tbl = '
Mengajukan komplain, jika pekerjaan <b>Pihak Pertama</b> tidak memenuhi standar kualitas  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
sesuai dengan spesifikasi yang terdapat pada pasal 2 perjanjian ini.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'e. ',0,0,'R',0);
$tbl = '
Menolak bahan-bahan yang disediakan oleh <b>Pihak Pertama</b>  jika kualitasnya tidak  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
memenuhi persyaratan.<br>';
$pdf->writeHTML($tbl);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 8',0,1,'C',0);
$pdf->Cell(190,4, 'MASA GARANSI',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Masa Garansi yang  ditetapkan adalah selama 5 (lima) tahun kalender masehi terhitung sejak<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
telah selesainya penyelesaian Pekerjaan oleh <b>Pihak Pertama</b>.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Garansi hanya dapat diklaim oleh <b>Pihak Kedua</b> apabila memenuhi ketentuan sebagai berikut:<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(6,5,'',0,0,'R',0);
$pdf->Cell(20,5,'a. ',0,0,'R',0);
$tbl = '
Melampirkan Berita Acara Serah Terima Pekerjaan dan Surat Garansi <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(6,5,'',0,0,'R',0);
$pdf->Cell(20,5,'b. ',0,0,'R',0);
$tbl = '
Kerusakan Kubah disebabkan karena kesalahan <b>Pihak Pertama</b> atau kualitas kubah yang<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(6,5,'',0,0,'R',0);
$pdf->Cell(20,5,'c. ',0,0,'R',0);
$tbl = '
Garansi yang diberikan meliputi hal-hal sebagai berikut:<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(30,5,'',0,0,'R',0);
$pdf->MultiCell(120,5,'- Ketahanan Konstruksi
- Ketahanan Warna 
- Kobocoran Kubah 
',0,'B',0);
$pdf->Cell(27,5,'',0,0,'R',0);
$tbl = 'Pemberian garansi selain daripada hal sebagaimana tersebut pada point c ayat (i) s/d (iii) <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(27,5,'',0,0,'R',0);
$tbl = 'akan diberikan berdasarkan hak prerogative <b>Pihak Pertama</b>. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(19,5,'3.',0,0,'R',0);
$tbl = 'Garansi tidak berlaku apabila penyebab kerusakan adalah karena keadaan memaksa (force<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
sebagaimana dimaksud pada Pasal 9 Perjanjian ini.<br>';
$pdf->writeHTML($tbl);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 9',0,1,'C',0);
$pdf->Cell(190,4, 'KEADAAN MEMAKSA (FORCE MAJEURE)',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1. ',0,0,'R',0);
$tbl = '
Para Pihak dapat diberikan keringanan atau dibebaskan dari tanggung jawab dalam Perjanjian <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
ini apabila terjadi keadaan memaksa (force majeure).<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2. ',0,0,'R',0);
$tbl = '
Keadaan memaksa (force majeure) yang dimaksud dalam ayat  (1) pasal ini adalah :<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'a.',0,0,'R',0);
$tbl = '
Hujan saat pemasangan dan bencana alam (gempa bumi, tsunami, angin topan, tanah<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
longsor, banjir, gunung meletus), Wabah penyakit baik yangmenular maupun tidak menular  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
yang dinyatakan oleh pemerintah sebagai pandemi,  kebakaran, Kerusuhan, Teror, Perang <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
yang dapat mengakibatkan kerusakan dan terlambatnya pelaksanaan pekerjaan.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'b.',0,0,'R',0);
$tbl = '
Adanya pemogokan pekerja yang bukan disebabkan oleh kesalahan <b>Pihak Kedua</b>.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'c.',0,0,'R',0);
$tbl = '
Keterlambatan pengiriman barang yang disebabkan oleh Pihak Ekspedisi, Embargo<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
(didefinisikan sebagai waktu melebihi 30 hari sejak hari kedatangan dari kapal-kapal yang<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
siap pembongkaran muatan);<br>';
$pdf->writeHTML($tbl);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(7);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,4,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,4,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->Cell(25,5,'d.',0,0,'R',0);
$tbl = '
Pemberontakan, kerusuhan massal, huru hara, perebutan kekuasaan, gangguan sosial, <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
pemogokan atau lock out, pemblokiran oleh orang-orang selain personil Kontraktor atau <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'',0,0,'R',0);
$tbl = '
subkontraktor;<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(25,5,'e.',0,0,'R',0);
$tbl = '
Perubahan peraturan perundang-undangan nasional maupun daerah secara material;<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'3.',0,0,'R',0);
$tbl = '
Pihak yang menjadi terhambat pemenuhan kewajibannya karena force majeure harus <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
memberitahukan secara tertulis kepada pihak lainnya dalam Perjanjian ini paling lambat 7 x 24 <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
jam setelah kejadian tersebut.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'4.',0,0,'R',0);
$tbl = '
Pihak yang menerima pemberitahuan force majeure, wajib memberikan jawaban  paling lambat <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
waktu 7 x 24 jam. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'5.',0,0,'R',0);
$tbl = '
Apabila Pihak yang menerima pemberitahuan force majeure tidak memberikan jawaban <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
sebagaimana tersebut pada ayat (4) maka pihak tersebut menerima kondisi <i>force majeure</i>.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'6.',0,0,'R',0);
$tbl = '
<b>Pihak Pertama</b> berhak untuk tetap menerima pembayaran dari <b>Pihak Kedua</b> atas pekerjaan <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
waktu 7 x 24 jam. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'6',0,0,'R',0);
$tbl = '
yang sebagian atau seluruhnya sudah diselesaikan oleh <b>Pihak Kedua</b> meski terjadi <i>Force  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
Majeure</i>. <br>';
$pdf->writeHTML($tbl);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 10',0,1,'C',0);
$pdf->Cell(190,4, 'DENDA PEMUTUSAN PERJANJIAN KERJA SAMA',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1.',0,0,'R',0);
$tbl = '
Pemutusan Perjanjian ini dapat dilakukan oleh Para Pihak tanpa perlu meminta putusan  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
pengadilan.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2.',0,0,'R',0);
$tbl = '
Apabila <b>Pihak Kedua</b> memutuskan kontrak kerja ini secara sepihak maka <b>Pihak Kedua</b>  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
dikenakan denda 5% (Lima perseratus) dari Harga Borongan dan uang yang telah dibayarkan <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
kepada <b>Pihak Pertama</b> tidak dapat diminta kembali. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'3.',0,0,'R',0);
$tbl = '
Dalam hal <b>Pihak Pertama</b> yang memutuskan Perjanjian ini secara sepihak, maka <b>Pihak  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
Pertama</b> dikenakan denda 5% (Lima perseratus) dari Harga Borongan  dan semua pembayaran<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
yang telah dibayarkan akan dikembalikan kepada <b>Pihak Kedua</b>. <br>';
$pdf->writeHTML($tbl);

$pdf->Ln(8);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 11',0,1,'C',0);
$pdf->Cell(190,4, 'RESIKO - RESIKO',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1.',0,0,'R',0);
$tbl = '
Jika terjadi kesalahan pekerjaan yang disebabkan karena kesalahan <b>Pihak Kedua</b> seperti <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
misalnya kesalahan pembuatan dudukan tidak sesuai arahan <b>Pihak Pertama</b>, maka <b>Pihak  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'',0,0,'R',0);
$tbl = '
Kedua</b> bertanggung jawab sepenuhnya atas segala biaya yang timbul. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2.',0,0,'R',0);
$tbl = '
Jika hasil pekerjaan musnah, rusak, tidak memenuhi spesifikasi teknik atau tidak rapi dengan <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
cara apapun sebelum diserahkan kepada <b>Pihak Kedua</b>, kecuali keadaan <i>force majeure</i>, maka <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
<b>Pihak Pertama</b> bertanggung jawab sepenuhnya atas segala kerugian yang timbul.<br>';
$pdf->writeHTML($tbl);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(30);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,4,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,4,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 12',0,1,'C',0);
$pdf->Cell(190,4, 'PENAMBAHAN ATAU PENGURANGAN PEKERJAAN',0,1,'C',0);
$pdf->Cell(190,4, 'DAN',0,1,'C',0);
$pdf->Cell(190,4, 'BERITA ACARA SERAH TERIMA PEKERJAAN',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1.',0,0,'R',0);
$tbl = '
Apabila terdapat rencana penambahan atau pengurangan Pekerjaan, maka Pihak yang  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
mengusulkan hal tersebut wajib memberitahukannya kepada Pihak lain. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2.',0,0,'R',0);
$tbl = '
Biaya atas penambahan atau pengurangan Pekerjaan tidak termasuk dalam Harga Borongan <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
pada Perjanjian ini dan akan disepakati bersama oleh Para Pihak baik melalui Adendum <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
Perjanjian atau pembuatan perjanjian baru.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'3.',0,0,'R',0);
$tbl = '
Jika <b>Pihak Kedua</b> berkehendak untuk mengganti salah satu atau beberapa material dari setiap .<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
pekerjaan, maka dikenakan biaya sesuai dengan harga yang diajukan oleh <b>Pihak Pertama</b><br>';
$pdf->writeHTML($tbl);

$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 13',0,1,'C',0);
$pdf->Cell(190,4, 'PENYELESAIAN PERSELISIHAN',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1.',0,0,'R',0);
$tbl = '
Apabila dalam pelaksanaan pekerjaan ini terjadi perselisihan atau perbedaan pendapat antara<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
Para Pihak, maka terlebih dahulu akan diselesaikan secara musyawarah dan mufakat. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2.',0,0,'R',0);
$tbl = '
Perselisihan terkait teknik konstruksi kubah akan diselesaikan melalui suatu Panitia Arbitrase <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
yang akan terdiri dari seorang anggota yang ditunjuk oleh <b>Pihak Pertama</b>, seorang yang akan  <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
ditunjuk oleh Pihak Kedua dan seorang yang netral sebagai ketua merangkap anggota yang <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
disetujui oleh kedua belah pihak.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'3.',0,0,'R',0);
$tbl = '
Semua biaya penyelesaian perselisihan akan menjadi tanggung jawab Para Pihak.<br>';
$pdf->writeHTML($tbl);

$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(190,6,'PASAL 14',0,1,'C',0);
$pdf->Cell(190,4, 'PENUTUP',0,1,'C',0);
$pdf->SetMargins(12, 10, 10, true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(20,5,'1.',0,0,'R',0);
$tbl = '
Hal-hal yang belum ditetapkan dalam Perjanjian ini akan ditentukan kemudian atas persetujuan <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
Para Pihak. <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'2.',0,0,'R',0);
$tbl = '
Demikian Surat Perjanjian ini dibuat rangkap 2 (dua) masing-masing bermaterai cukup yang <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
mempunyai kekuatan hukum yang sama yang dipegang oleh masing-masing pihak dan berlaku <br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
sejak ditandatangani Surat Perjanjian ini.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,' ',0,0,'R',0);
$tbl = '
disetujui oleh kedua belah pihak.<br>';
$pdf->writeHTML($tbl);
$pdf->Cell(20,5,'3.',0,0,'R',0);
$tbl = '
Para Pihak beritikad baik untuk melaksanakan Surat Perjanjian Kerja ini sesuai dengan isinya.<br>';
$pdf->writeHTML($tbl);
$pdf->Ln(5);
$tbl = '
Dibuat di : Kediri <BR>
Tanggal   : ________________      
<br>';
$pdf->writeHTML($tbl);

$pdf->SetFont('helvetica', 'b', 11);
$pdf->Ln(5);
$pdf->Cell(20,5,'Pihak Pertama',0,0,'L',0);
$pdf->Cell(135,5,' ',0,0,'R',0);
$pdf->Cell(20,5,'Pihak Kedua',0,0,'L',0);
$pdf->Ln(30);
$pdf->SetFont('helvetica','BU');
$pdf->Cell(20,5,'ANDIK NUR SETIAWAN',0,0,'L',0);
$pdf->Cell(135,5,'',0,0,'R',0);
$pdf->Cell(20,5,'             ',0,1,'L',0);
$pdf->SetFont('helvetica','');
$pdf->Cell(20,5,'Direktur PT  Anugerah Kubah Indonesia',0,0,'L',0);
$pdf->Cell(135,5,'',0,0,'R',0);
$pdf->Cell(20,5,'',0,0,'L',0);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(10);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,4,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,4,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->SetFont('helvetica', 'bu', 11);
$pdf->Cell(190,6,'DESAIN KUBAH',0,1,'C',0);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(180);
$pdf->SetFont('helvetica', 'b', 14);
$pdf->Cell(45,2,'',0,0,'L',0);
$pdf->SetFillColor(100,100,100);
$pdf->Cell(50,8,'Warna',1,0,'C',0);
$pdf->Cell(50,8,'Kode',1,1,'C',0); 
$pdf->Cell(45,2,'',0,0,'L',0);
$pdf->Cell(50,10,'',1,0,'C',0);
$pdf->Cell(50,10,'',1,1,'C',0);
$pdf->Cell(45,2,'',0,0,'L',0);
$pdf->Cell(50,10,'',1,0,'C',0);
$pdf->Cell(50,10,'',1,1,'C',0);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,4,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,4,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->addpage();
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0);
$pdf->SetFont('helvetica', 'bu', 11);
$pdf->Cell(190,6,'DESAIN KALIGRAFI',0,1,'C',0);

$pdf->SetMargins(13, 10, 10, true);
$pdf->Ln(215);
$pdf->SetTextColor(130);
$pdf->SetDrawColor(130);
$pdf->SetFont('helvetica', 'b', 11);
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,4,'PIHAK I',1,0,'C',0);
$pdf->Cell(20,4,'PIHAK II',1,1,'C',0); 
$pdf->Cell(130,2,'',0,0,'L',0);
$pdf->Cell(20,10,'',1,0,'C',0);
$pdf->Cell(20,10,'',1,1,'C',0);

$pdf->Output('s.pdf','I');
?>


