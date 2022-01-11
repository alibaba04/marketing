<?php
    require_once('../function/fpdf/html_table.php');
    require_once ("../function/fungsi_formatdate.php");
    require_once ("../function/fungsi_convertNumberToWord.php");
    $pdf=new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(12, 20, 10, true);
    $tgl1 = $_GET['tgl1'];
    $tgl2 = $_GET['tgl2'];
    
    $filter = "";
    $pdf->AddFont('Calibri','','Calibri_Regular.php');
    $pdf->AddFont('Calibri','B','Calibri_Bold.php');
    $pdf->AddFont('Calibri','I','Calibri_Italic.php');
    $pdf->SetFont('Calibri', 'B', 18);
    $pdf->Cell(190,8,'DATA SPH',0,1,'C',0);
    $pdf->SetFont('Calibri', '', 11);
    $pdf->Cell(0, 5, "Tanggal SPH : ".$tgl1." s/d ".$tgl2, 0, 1, 'C');
    //ISI
    $pdf->Ln(5);
    $pdf->Cell(27,6,'SPH',1,0,'C',0);
    $pdf->Cell(30,6,'Cust',1,0,'C',0);
    $pdf->Cell(10,6,'Nope',1,0,'C',0);
    $pdf->Cell(10,6,'D',1,0,'C',0);
    $pdf->Cell(20,6,'T',1,0,'C',0);
    $pdf->Cell(10,6,'DT',1,0,'C',0);
    $pdf->Cell(15,6,'Kelengkapan',1,0,'C',0);
    $pdf->Cell(15,6,'Provinsi',1,0,'C',0);
    $pdf->Cell(15,6,'Operator',1,0,'C',0);
    $pdf->Cell(15,6,'Affiliate',1,1,'C',0);
    /*if (isset($_GET["tgl"])){
        $tgl = secureParam($_GET["tgl"], $dbLink);
        $tgl = explode(" - ", $tgl);
        $tgl1 = $tgl[0];
        $tgl2 = $tgl[1];
        $namarek=$_GET["txtKodeRekeningbb"];
    }else{
        $tgl1 = "";
        $tgl2 = "";
    }
    $filter = "";
    if ($tgl1 && $tgl2)
        $filter = $filter . " AND t.tanggal_transaksi BETWEEN '" . tgl_mysql($tgl1) . "' 
    AND '" . tgl_mysql($tgl2) . "'  ";

    $q = "SELECT t.tanggal_transaksi, t.kode_transaksi, t.kode_rekening, m.nama_rekening, t.keterangan_transaksi, t.debet, t.kredit ";
    $q.= "FROM aki_tabel_transaksi t INNER JOIN aki_tabel_master m ON t.kode_rekening=m.kode_rekening  AND t.kode_rekening= '". $no."'  ";
    $q.= "WHERE 1=1 and keterangan_posting='Post'" . $filter;
    $q.= " ORDER BY t.tanggal_transaksi, id_transaksi ";
    $result=mysqli_query($dbLink,$q);
    $totDebet = 0;
    $totKredit = 0;
    while ($lap = mysqli_fetch_array($result)) {
       $pdf->Cell(27,6,$lap['tanggal_transaksi'],1,0,'C',0);
        $pdf->Cell(40,6,$lap["kode_transaksi"],1,0,'C',0);
        $pdf->Cell(28,6,$lap["kode_rekening"],1,0,'C',0);
        $pdf->Cell(45,6,$lap["nama_rekening"],1,0,'L',0);
        $pdf->Cell(55,6,$lap["keterangan_transaksi"],1,0,'L',0);
        $pdf->Cell(40,6,number_format($lap["debet"], 2),1,0,'R',0);
        $pdf->Cell(40,6,number_format($lap["kredit"], 2),1,1,'R',0);
        // $no++; 
         $totDebet += $lap["debet"];
         $totKredit += $lap["kredit"];
    }
    $pdf->Cell(195,7,'Total Transaksi',1,0,'R',0);
    $pdf->Cell(40,7,number_format($totDebet, 2),'LTB',0,'R',0);
    $pdf->Cell(40,7,number_format($totKredit, 2),1,1,'R',0);
    $pdf->Cell(195,7,'Total Saldo',1,0,'R',0);
    $pdf->Cell(80,7,number_format($totDebet-$totKredit, 2),1,1,'C',0);*/

    //output file PDF
    $pdf->Output('Data_SPH.pdf', 'I'); //download file pdf
?>