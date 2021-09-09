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
case "sendnotif":
    $url ="https://fcm.googleapis.com/fcm/send";
    $fields=array(
        "to"=>'c9DZ3uTKaPaar-EOaVJMWo:APA91bH_2DnASkJ3ExQskgW7PKjDmGL0ah0iVbW4t5-FpobJq-UdvckZSwQz2AvIuTrTYDy7cOZhxBC4pkQoOF6xwmG7-AY-qomTRlLkMVDj9ZaOYDLg_-M_eqx5rcVFD3_8Fm0gfLTC',
        "notification"=>array(
            "body"=>$_POST['message'],
            "title"=>'Sikubah',
            "click_action"=>'https://sikubah.com/marketing'
        )
    );
    $headers=array(
        'Authorization: key=AAAA-drRgeY:APA91bGaAAaXRV5K9soSk_cFyKSkWkFSu1Nr3MO3OofWYjM_S0HEEX1IZtMLGZpcbx-N0RTFDMqk4hoOEkXA0PbqnSThk5qemRdkK7gPiuUQFHPWNzfeWbj-WRnFtpCVb17Fop4JRu6o',
        'Content-Type:application/json'
    );
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
    $result=curl_exec($ch);
    print_r($result);
    curl_close($ch);
    echo "s";
break;
case "gettoken":
    $result = mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where g.kodeGroup='".$_POST['user']."'", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode(array("token"=>$data['token'],"nama"=>$data['nama']));
        } 
        break;
    } 
case "checkKodeUser":
    $result = mysql_query("select kodeUser FROM aki_user WHERE kodeUser ='" . secureParamAjax($_POST['kodeUser'], $dbLink) . "'", $dbLink);
    if (mysql_num_rows($result)) {
        echo "yes";
    } else {
        echo "no";
    }
break;
case "hitungtotal":
    $kaligrafi = $_POST['kaligrafi'];
    $hkubah = $_POST['hkubah'];
    $total = $kaligrafi+$hkubah;
    echo json_encode(array("total"=>number_format($total)));
break;
case "getOngkir":
    $result = mysql_query("select * FROM provinsi WHERE id ='" . secureParamAjax($_POST['idP'], $dbLink) . "'", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode(array("transport"=>number_format($data['transport'])));
        } 
        break;
    } 
break;

case "getcountSPH":
    $result = mysql_query("SELECT COUNT(IF( kodeUser = 'reza', kodeUser, NULL)) AS reza,COUNT(IF( kodeUser = 'antok', kodeUser, NULL)) AS antok,COUNT(IF( kodeUser = 'agus', kodeUser, NULL)) AS agus,COUNT(IF( kodeUser = 'tina', kodeUser, NULL)) AS tina FROM `aki_sph` WHERE 1", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode( array("reza"=>$data['reza'],"antok"=>$data['antok'],"agus"=>$data['agus'],"tina"=>$data['tina']));
        } 
        break;
    }
break;
case "getcountAffiliate":
    $result = mysql_query("SELECT COUNT(IF( affiliate = 'Web Qoobah Official', affiliate, NULL)) AS office,COUNT(IF( affiliate = 'Web Contractor', affiliate, NULL)) AS contr,COUNT(IF( affiliate = 'Representative', affiliate, NULL)) AS repre,COUNT(IF( affiliate = 'Offline', affiliate, NULL)) AS offline,COUNT(IF( affiliate = 'Edy', affiliate, NULL)) AS edy,COUNT(IF( affiliate = 'Ibnu', affiliate, NULL)) AS ibnu,COUNT(IF( affiliate = 'Sigit', affiliate, NULL)) AS sigit,COUNT(IF( affiliate = 'Isaq', affiliate, NULL)) AS isaq,COUNT(IF( affiliate = 'Fendy', affiliate, NULL)) AS fendy,COUNT(IF( affiliate = 'Habibi', affiliate, NULL)) AS habibi,COUNT(IF( affiliate = 'Rizal', affiliate, NULL)) AS rizal,COUNT(IF( affiliate = 'Bekasi', affiliate, NULL)) AS bekasi FROM `aki_sph` WHERE 1", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode( array("office"=>$data['office'],"contr"=>$data['contr'],"repre"=>$data['repre'],"offline"=>$data['offline'],"edy"=>$data['edy'],"ibnu"=>$data['ibnu'],"sigit"=>$data['sigit'],"isaq"=>$data['isaq'],"fendy"=>$data['fendy'],"habibi"=>$data['habibi'],"rizal"=>$data['rizal'],"bekasi"=>$data['bekasi']));
        } 
        break;
    }
break;

case "getcountSPHm":
    $result = mysql_query("SELECT COUNT(IF( month(tanggal) = 01, kodeUser, NULL)) AS jan,COUNT(IF( month(tanggal) = 02, kodeUser, NULL)) AS feb,COUNT(IF( month(tanggal) = 03, kodeUser, NULL)) AS maret,COUNT(IF( month(tanggal) = 04, kodeUser, NULL)) AS april,COUNT(IF( month(tanggal) = 05, kodeUser, NULL)) AS mei,COUNT(IF( month(tanggal) = 06, kodeUser, NULL)) AS jun,COUNT(IF( month(tanggal) = 07, kodeUser, NULL)) AS jul,COUNT(IF( month(tanggal) = 08, kodeUser, NULL)) AS agus,COUNT(IF( month(tanggal) = 09, kodeUser, NULL)) AS sep,COUNT(IF( month(tanggal) = 10, kodeUser, NULL)) AS okt,COUNT(IF( month(tanggal) = 11, kodeUser, NULL)) AS nov,COUNT(IF( month(tanggal) = 12, kodeUser, NULL)) AS des FROM `aki_sph` where YEAR(tanggal) = YEAR(CURDATE())", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode( array("jan"=>$data['jan'],"feb"=>$data['feb'],"maret"=>$data['maret'],"april"=>$data['april'],"mei"=>$data['mei'],"jun"=>$data['jun'],"jul"=>$data['jul'],"agus"=>$data['agus'],"sep"=>$data['sep'],"okt"=>$data['okt'],"nov"=>$data['nov'],"des"=>$data['des']));
        } 
        break;
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
case "idListkk":
    $id = $_POST['id'];
    $no = $_POST['noKk'];
    $q = "SELECT kk.*,dp.* FROM aki_dkk kk left join aki_dpembayaran dp on kk.noKK=dp.noKk WHERE 1=1 and nomer ='".$id."' and (kk.noKK)='".$no."' ORDER BY kk.noKK desc";
    $result = mysql_query($q, $dbLink);
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode(array("model"=>$data['model'].'',"d"=>$data['d'].'',"t"=>$data['t'].'',"dt"=>$data['dt'].'',"plafon"=>$data['plafon'].'',"harga"=>number_format($data['harga']).'',"jumlah"=>$data['jumlah'].'',"ket"=>$data['ket'].'',"bahan"=>$data['bahan'].'',"luas"=>$data['luas'].'',"kubah"=>$data['kubah'].'',"txtw1"=>$data['wpembayaran1'].'',"txtw2"=>$data['wpembayaran2'].'',"txtw3"=>$data['wpembayaran3'].'',"txtw4"=>$data['wpembayaran4'].'',"txtp1"=>$data['persen1'].'',"txtp2"=>$data['persen2'].'',"txtp3"=>$data['persen3'].'',"txtp4"=>$data['persen4'].'',"color1"=>$data['color1'].'',"color2"=>$data['color2'].'',"color3"=>$data['color3'].'',"color4"=>$data['color4'].'',"color5"=>$data['color5']));
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
case "getpemasangan":
    $d = $_POST['d'];
    $return = '';
    if ($d > '0.5' and $d<='4'){
        $return = '8';
    }elseif ($d > '4' and $d<='6') {
        $return = '10';
    }
    elseif ($d > '6' and $d<='8') {
        $return = '15';
    }
    elseif ($d > '8' and $d<='9') {
        $return = '18';
    }
    elseif ($d > '9' and $d<='11') {
        $return = '20';
    }
    elseif ($d > '11' and $d<='13') {
        $return = '25';
    }
    elseif ($d > '13' and $d<='14') {
        $return = '30';
    }
    else{
        $return = '30';
    }
    echo json_encode(array("pemasangan"=>$return));
break;
case "getpabrikasi":
    $d = $_POST['d'];
    $bahan = $_POST['bahan'];
    $return = '';
    if ($bahan == 1){
        if ($d > '0.5' and $d<='3'){
            $return = '28';
        }elseif ($d > '3' and $d<='5') {
            $return = '38';
        }
        elseif ($d > '5' and $d<='7') {
            $return = '58';
        }
        elseif ($d > '7' and $d<='9') {
            $return = '78';
        }
        elseif ($d > '9' and $d<='12') {
            $return = '83';
        }
        elseif ($d > '12' and $d<='14') {
            $return = '103';
        }
        elseif ($d > '14' and $d<='18') {
            $return = '123';
        }
        else{
            $return = '123';
        }
    }else{
        if ($d > '0.5' and $d<'3'){
            $return = '80';
        }elseif ($d > '3' and $d<'6') {
            $return = '110';
        }
        elseif ($d > '6' and $d<'12') {
            $return = '125';
        }
        else{
            $return = '125';
        }
    }
    echo json_encode(array("pabrikasi"=>$return));
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
