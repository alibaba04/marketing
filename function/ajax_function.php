<?php
global $passSalt;
require_once('../config.php' );
require_once('../function/secureParam.php');

switch ($_POST['fungsi']) {
case "checkKodeMenu":

    $result = mysql_query("select kodeMenu FROM aki_menu WHERE kodeMenu ='" . secureParamAjax($_POST['kodeMenu'], $dbLink) . "'", $dbLink);

    if (mysql_num_rows($result)) {
       echo "yes";
    } else {
       echo "no";
    }
    break;
    case "checkKodeGroup":
    $result = mysql_query("select KodeGroup FROM aki_groups WHERE KodeGroup ='" . secureParamAjax($_POST['kodeGroup'], $dbLink) . "'", $dbLink);
    if (mysql_num_rows($result)) {
        echo "yes";
    } else {
        echo "no";
    }
break;

case "checkKodeUser":
    $result = mysql_query("select kodeUser FROM aki_user WHERE kodeUser ='" . secureParamAjax($_POST['kodeUser'], $dbLink) . "'", $dbLink);
    if (mysql_num_rows($result)) {
        echo "yes";
    } else {
        echo "no";
    }
break;

case "cekpass":
    $kodeUser = secureParamAjax($_POST['kodeUser'], $dbLink);
    $pass = HASH('SHA512',$passSalt.secureParamAjax($_POST['pass'], $dbLink));
    $result = mysql_query("SELECT kodeUser, nama FROM aki_user WHERE kodeUser='".$kodeUser."' AND  password='".$pass."' AND aktif='Y'", $dbLink);
    if (mysql_num_rows($result)) {
        echo "yes";
    } else {
        echo "no";
    }
break;

case "checkNamaSetting":
    $result = mysql_query("select namaSetting FROM aki_setting WHERE namaSetting ='" . secureParamAjax($_POST['namaSetting'], $dbLink) . "'", $dbLink);

    if (mysql_num_rows($result)) {
       echo "yes";
    } else {
       echo "no";
    }
    break;

case "ambilKota":
    $result = mysql_query("SELECT * FROM `kota` WHERE 1", $dbLink);
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {

            $output[$idx] = array($data['name']);
            $idx++;
        } 
        echo json_encode($output);
        break;
    }
break;
case "ambilProv":
    $result = mysql_query("SELECT * FROM `provinsi` WHERE 1", $dbLink);
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {

            $output[$idx] = array($data['name']);
            $idx++;
        } 
        echo json_encode($output);
        break;
    } 
break;
case "cek":
    $d = $_POST['d'];
    $t = $_POST['t'];
    $dt = $_POST['dt'];
    $kel = $_POST['kel'];
    $transport = $_POST['ongkir'];
    $luas = 0;
    if ($dt == 0) {
        $luas = ($d * $t * 3.14);
    }else{
        $luas = ($dt * $t * 3.14);
    }
    echo json_encode($luas);
break;
case "chart":
    $result = mysql_query("SELECT count(idSph) as id,YEAR(tanggal) as tahun, MONTH(tanggal) as bulan FROM `aki_sph`GROUP BY YEAR(tanggal), MONTH(tanggal)", $dbLink);
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {

            $output[$idx] = array($data['id']);
            $idx++;
        } 
        echo json_encode($output);
        break;

    } 
break;
case "idList":
    $id = $_POST['id'];
    $no = $_POST['nosph'];
    $q = "SELECT nomer,s.*,ds.luas,ds.bahan,ds.biaya_plafon,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id WHERE 1=1 and nomer ='".$id."' and (s.noSph)='".$no."' ORDER BY s.noSph desc";
    $result = mysql_query($q, $dbLink);
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode(array("biaya_plafon"=>number_format($data['biaya_plafon']).'',"model"=>$data['model'].'',"d"=>$data['d'].'',"t"=>$data['t'].'',"harga3"=>number_format($data['harga3']).'',"dt"=>$data['dt'].'',"plafon"=>$data['plafon'].'',"harga"=>number_format($data['harga']).'',"harga2"=>number_format($data['harga2']).'',"jumlah"=>$data['jumlah'].'',"ket"=>$data['ket'].'',"transport"=>number_format($data['transport']).'',"bahan"=>$data['bahan'].'',"luas"=>$data['luas']));
            $idx++;
        } 
        //echo json_encode($output);
        break;
    } 
break;
case "noSph":
    $tglTransaksi = date("Y-m-d");
    $result = mysql_query("SELECT * FROM aki_sph where idSph=( SELECT max(idSph) FROM aki_sph )", $dbLink);
    if (mysql_num_rows($result)>0) {
        $urut = "";
        $noSph = "";
        $tglTr = substr($tglTransaksi, 0,4);
        $bulan = bulanRomawi(substr($tglTransaksi,5,2));
        $result = mysql_fetch_array($q_kode);
        if ($result['noSph'] != ''){
            $urut = substr($result['noSph'],0, 3);
            $tahun = substr($result['noSph'],-4);
            $kode = $urut + 1;
            if (strlen($kode)==1) {
                $kode = '00'.$kode;
            }else if (strlen($kode)==2){
                $kode = '0'.$kode;
            }
            if ($tglTr != $tahun) {
                $kode = '001';
            }
            $noSph = $kode.'/SPH-MS/PTAKI/'.$bulan.'/'.$tglTr;

        }else{
            $noSph = '001'.'/SPH-MS/PTAKI/'.$bulan.'/'.$tglTr;
        }
        echo json_encode($tglTransaksi);
        break;
    } 
break;
case "kalkulator":
    $d = $_POST['d'];
    $t = $_POST['t'];
    $dt = $_POST['dt'];
    $kel = $_POST['kel'];
    $m = $_POST['margin'];
    $transport = $_POST['ongkir'];
    $bplafon = $_POST['bplafon'];
    if ($transport == '') {
        $transport = 0;
    }
    $luas = 0;
    if ($dt == 0) {
        $luas = ($d * $t * 3.14);
    }else{
        $luas = ($dt * $t * 3.14);
    }
    $pmargin = 0; 
    if ($luas <= 15) {$pmargin = 100;}
    else if($luas <= 25){$pmargin = 80;}
    else if($luas <= 40){$pmargin = 60;}
    else if($luas <= 60){$pmargin = 50;}
    else if($luas <= 100){$pmargin = 40;}
    else{$pmargin = 33;}
    if ($m !=0 ) {
        $pmargin = $m; 
    }
    $xtp = 0;
    if($d >= 4){ $xtp = 800000;}else{$xtp = 850000;} 
    $xwa = 0;
    if($d >= 4){ $xwa = 850000;}else{$xwa = 900000;} 
    $xfull = 0;
    if($d >= 4){ $xfull = 900000;}else{$xfull = 950000;} 
    $x = 0;
    if( $kel == 0){$x = $xfull;}else if($kel == 2){$x = $xwa;}else{$x = $xtp;}
    $modal = $luas * $x;
    $margin = $modal * ($pmargin*0.01);
    $hpp = $modal + $margin;
    $affiliate = $hpp * 0.05;
    $marketing = $hpp * 0.01;
    $harga = $hpp + $affiliate + $marketing + $transport;
        //EN
    $xtp2 = 0;
    if($d >= 4){ $xtp2 = 1700000;}else{$xtp2 = 1900000;} 
    $xwa2 = 0;
    if($d >= 4){ $xwa2 = 1800000;}else{$xwa2 = 1950000;} 
    $xfull2 = 0;
    if($d >= 4){ $xfull2 = 1900000;}else{$xfull2 = 2000000;} 
    $x2 = 0;
    if( $kel == 0){$x2 = $xfull2;}else if($kel == 2){$x2 = $xwa2;}else{$x2 = $xtp2;}
    $modal2 = $luas * $x2;
    $margin2 = $modal2 * ($pmargin*0.01);
    $hpp2 = $modal2 + $margin2;
    $affiliate2 = $hpp2 * 0.05;
    $marketing2 = $hpp2 * 0.01;
    $harga2 = $hpp2 + $affiliate2 + $marketing2 + $transport;
    //echo json_encode(array("luas"=>$luas,"margin"=>$pmargin,"harga"=>number_format(round($harga,-6)), "harga2"=>number_format(round($harga2,-6)));
    $tharga = $harga+$bplafon;
    $tharga2 = $harga2+$bplafon;
    $harga2 = number_format(round($harga2,-6));
    $harga = number_format(round($harga,-6));
    $tharga = number_format(round($tharga,-6));
    $tharga2 = number_format(round($tharga2,-6));
    //echo json_encode(array("bplafon"=>$tharga2));
    echo json_encode(array("tharga"=>$tharga.'',"tharga2"=>$tharga2.'',"luas"=>$luas.'',"margin"=>$pmargin.'',"harga"=>''.$harga,"harga2"=>''.$harga2));
break;
}
?>
