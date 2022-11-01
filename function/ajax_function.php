<?php
global $passSalt;
require_once('../config.php' );
require_once('../function/secureParam.php');http://localhost/marketing/index.php

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
        "to"=>$_POST['token'],
        "notification"=>array(
            "body"=>$_POST['message'],
            "title"=>'Sikubah',
            "click_action"=>$_POST['url']
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
break;
case "gettoken":
    $result = mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where s.aktif='Y' and g.kodeGroup='".$_POST['user']."' limit 1", $dbLink);
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
case "cutoff":
    $year = $_POST['year'];
    $q = "UPDATE `aki_sph` SET `aktif`='99' WHERE year(tanggal)='".$year."'";
    if (mysql_query($q, $dbLink)) {
        $q2 = "UPDATE `aki_kk` SET `aktif`='99' WHERE year(tanggal)='".$year."'";
        if (mysql_query($q2, $dbLink)) {
            $q3 = "DELETE FROM `aki_report`";
            if (mysql_query($q3, $dbLink)) {
                date_default_timezone_set("Asia/Jakarta");
                $tgl = date("Y-m-d H:i:s");
                $q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
                $q4.= "('Admin','".$tgl."','Cut Off ".$year."');";
                if (mysql_query($q4, $dbLink)) {
                    echo "yes";
                }
            }
        }
    } else {
        echo "no";
    }
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
    $result = mysql_query("SELECT COUNT(IF( kodeUser = 'reza', kodeUser, NULL)) AS reza,COUNT(IF( kodeUser = 'antok', kodeUser, NULL)) AS antok,COUNT(IF( kodeUser = 'agus', kodeUser, NULL)) AS agus,COUNT(IF( kodeUser = 'trio', kodeUser, NULL)) AS trio FROM `aki_sph` WHERE aktif=1 and year(tanggal)=YEAR(CURDATE())", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode( array("reza"=>$data['reza'],"antok"=>$data['antok'],"agus"=>$data['agus'],"trio"=>$data['trio']));
        } 
        break;
    }
break;
case "getcountAffiliate":
    $result = mysql_query("SELECT COUNT(IF( affiliate = 'Web Qoobah Official', affiliate, NULL)) AS office,COUNT(IF( affiliate = 'Web Contractor', affiliate, NULL)) AS contr,COUNT(IF( affiliate = 'Representative', affiliate, NULL)) AS repre,COUNT(IF( affiliate = 'Offline', affiliate, NULL)) AS offline,COUNT(IF( affiliate = 'Edy', affiliate, NULL)) AS edy,COUNT(IF( affiliate = 'Ibnu', affiliate, NULL)) AS ibnu,COUNT(IF( affiliate = 'Sigit', affiliate, NULL)) AS sigit,COUNT(IF( affiliate = 'Isaq', affiliate, NULL)) AS isaq,COUNT(IF( affiliate = 'Fendy', affiliate, NULL)) AS fendy,COUNT(IF( affiliate = 'Habibi', affiliate, NULL)) AS habibi,COUNT(IF( affiliate = 'Rizal', affiliate, NULL)) AS rizal,COUNT(IF( affiliate = 'Bekasi', affiliate, NULL)) AS bekasi,COUNT(IF( affiliate = 'Arief', affiliate, NULL)) AS arief,COUNT(IF( affiliate = 'Pupun', affiliate, NULL)) AS pupun FROM `aki_sph` WHERE aktif=1 and year(tanggal)=YEAR(CURDATE())", $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            echo json_encode( array("office"=>$data['office'],"contr"=>$data['contr'],"repre"=>$data['repre'],"offline"=>$data['offline'],"edy"=>$data['edy'],"ibnu"=>$data['ibnu'],"sigit"=>$data['sigit'],"isaq"=>$data['isaq'],"fendy"=>$data['fendy'],"habibi"=>$data['habibi'],"rizal"=>$data['rizal'],"bekasi"=>$data['bekasi'],"arief"=>$data['arief'],"pupun"=>$data['pupun']));
        } 
        break;
    }
break;

case "getcountAffm":
    $filter = '';
    if (isset($_POST['aff'])) {
        $filter = "and affiliate='".$_POST['aff']."'";
    }
    $result = mysql_query("SELECT affiliate,COUNT(IF( month(tanggal) = 01, affiliate, NULL)) AS jan,COUNT(IF( month(tanggal) = 02, affiliate, NULL)) AS feb,COUNT(IF( month(tanggal) = 03, affiliate, NULL)) AS maret,COUNT(IF( month(tanggal) = 04, affiliate, NULL)) AS april,COUNT(IF( month(tanggal) = 05, affiliate, NULL)) AS mei,COUNT(IF( month(tanggal) = 06, affiliate, NULL)) AS jun,COUNT(IF( month(tanggal) = 07, affiliate, NULL)) AS jul,COUNT(IF( month(tanggal) = 08, affiliate, NULL)) AS agus,COUNT(IF( month(tanggal) = 09, affiliate, NULL)) AS sep,COUNT(IF( month(tanggal) = 10, affiliate, NULL)) AS okt,COUNT(IF( month(tanggal) = 11, affiliate, NULL)) AS nov,COUNT(IF( month(tanggal) = 12, affiliate, NULL)) AS des FROM `aki_sph` where aktif=1 and YEAR(tanggal) = YEAR(CURDATE()) ".$filter." group by affiliate ", $dbLink);
    if (mysql_num_rows($result)>0) {
        $idx=0;
        while ( $data = mysql_fetch_assoc($result)) {
            $output[$idx]=array($data['affiliate'],$data['jan'],$data['feb'],$data['maret'],$data['april'],$data['mei'],$data['jun'],$data['jul'],$data['agus'],$data['sep'],$data['okt'],$data['nov'],$data['des']);
            $idx++;
        } 
        echo json_encode($output);
        break;
    }
break;

case "getcountSPHm":
    $filter = '';
    if ($_POST['user']!='-') {
        $filter = "and kodeUser='".$_POST['user']."'";
    }
    $result = mysql_query("SELECT COUNT(IF( month(tanggal) = 01, kodeUser, NULL)) AS jan,COUNT(IF( month(tanggal) = 02, kodeUser, NULL)) AS feb,COUNT(IF( month(tanggal) = 03, kodeUser, NULL)) AS maret,COUNT(IF( month(tanggal) = 04, kodeUser, NULL)) AS april,COUNT(IF( month(tanggal) = 05, kodeUser, NULL)) AS mei,COUNT(IF( month(tanggal) = 06, kodeUser, NULL)) AS jun,COUNT(IF( month(tanggal) = 07, kodeUser, NULL)) AS jul,COUNT(IF( month(tanggal) = 08, kodeUser, NULL)) AS agus,COUNT(IF( month(tanggal) = 09, kodeUser, NULL)) AS sep,COUNT(IF( month(tanggal) = 10, kodeUser, NULL)) AS okt,COUNT(IF( month(tanggal) = 11, kodeUser, NULL)) AS nov,COUNT(IF( month(tanggal) = 12, kodeUser, NULL)) AS des FROM `aki_sph` where aktif=1 and YEAR(tanggal) = YEAR(CURDATE()) ".$filter, $dbLink);
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
        echo $pass;
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
case "getjumdrangka":
    $nokk = $_POST['nokk'];
    $result = mysql_query("SELECT * FROM aki_kkrangka WHERE `aktif`=1 and MD5(noKK)='".$nokk."' order by idRangka asc", $dbLink); 
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {
            $output[$idx] = array("rangka"=>$data['rangka'],"nomer"=>$data['nomer']);
            $idx++;
        } 
        echo json_encode($output);
        break;
    }else{
        echo "yes";
    }
break;
case "getdrangka":
    $nokk = $_POST['nokk'];
    $no = $_POST['nomer'];
    $result = mysql_query("SELECT * FROM aki_kkrangka WHERE `aktif`=1 and MD5(noKK)='".$nokk."' and nomer='".$no."' order by idRangka asc", $dbLink); 
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {
            $output[$idx] = array($data['rangka']);
            $idx++;
        } 
        echo json_encode($output);
        break;
    }else{
        echo "0";
    }
break;
case "getdwarna":
    $nokk = $_POST['nokk'];
    $no = $_POST['nomer'];
    $result = mysql_query("SELECT * FROM aki_kkcolor WHERE MD5(noKK)='".$nokk."' and nomer='".$no."' order by id asc", $dbLink); 
    if (mysql_num_rows($result)>0) {
        $idx = 0;
        while ( $data = mysql_fetch_assoc($result)) {
            $output[$idx] = array("color"=>$data['color'],"kcolor"=>$data['kcolor']);
            $idx++;
        } 
        echo json_encode($output);
        break;
    }else{
        echo "0";
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
    if ($bahan == 'Galvalume' || $bahan == 'Titanium'){
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
case "updatehpp":
    $kodeUser = secureParamAjax($_POST['kodeUser'], $dbLink);
    $pinang1 = secureParamAjax(str_replace(',', '',$_POST['txtpinang1']), $dbLink);
    $pinang2 = secureParamAjax(str_replace(',', '',$_POST['txtpinang2']), $dbLink);
    $pinang3 = secureParamAjax(str_replace(',', '',$_POST['txtpinang3']), $dbLink);
    $madina1 = secureParamAjax(str_replace(',', '',$_POST['txtmadina1']), $dbLink);
    $madina2 = secureParamAjax(str_replace(',', '',$_POST['txtmadina2']), $dbLink);
    $madina3 = secureParamAjax(str_replace(',', '',$_POST['txtmadina3']), $dbLink);
    $bawang1 = secureParamAjax(str_replace(',', '',$_POST['txtbawang1']), $dbLink);
    $bawang2 = secureParamAjax(str_replace(',', '',$_POST['txtbawang2']), $dbLink);
    $bawang3 = secureParamAjax(str_replace(',', '',$_POST['txtbawang3']), $dbLink);
    $setbola1 = secureParamAjax(str_replace(',', '',$_POST['txtsetbola1']), $dbLink);
    $setbola2 = secureParamAjax(str_replace(',', '',$_POST['txtsetbola2']), $dbLink);
    $setbola3 = secureParamAjax(str_replace(',', '',$_POST['txtsetbola3']), $dbLink);
    date_default_timezone_set("Asia/Jakarta");
    $dtime = date("Y-m-d H:i:s");
    $q1 = "UPDATE `aki_hpp` SET `aktif`='1' WHERE `id` = '1000' or `id` = '3000' or `id` = '5000' or `id` = '7000'";

    if (mysql_query( $q1, $dbLink)){
        $q2 = "INSERT INTO `aki_hpp`(`id`, `bahan`, `model`, `full`, `waterproof`, `tplafon`,`dtime`, `kodeUser`,  `aktif`) VALUES ('1000','hpermeter','pinang','".$pinang1."','".$pinang2."','".$pinang3."','".$dtime."','".$kodeUser."','0'), ('3000','hpermeter','madinah','".$madina1."','".$madina2."','".$madina3."','".$dtime."','".$kodeUser."','0'), ('5000','hpermeter','bawang','".$bawang1."','".$bawang2."','".$bawang3."','".$dtime."','".$kodeUser."','0'), ('7000','hpermeter','setbola','".$setbola1."','".$setbola2."','".$setbola3."','".$dtime."','".$kodeUser."','0')";
        if (mysql_query( $q2, $dbLink)){
            echo "yes";
        }else{
            echo $q2;
        }
    } else {
        echo "no";
    }
break;
case "kalkulator":
    $model = $_POST['model'];
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
    $ltabung = 0;
    if ($dt == 0) {
        $ltabung = ($d * $t * 3.14);
    }else{
        $ltabung = ($dt * $t * 3.14);
    }

    $luas=0;
    if ($model=='pinang') {
        $luas=$ltabung-(0.183016557250314*$ltabung);
    }elseif ($model=='madinah') {
        $luas=$ltabung-(0.125805048091737*$ltabung);
    }elseif ($model=='bawang') {
        $luas=$ltabung-(0.0971*$ltabung);
    }elseif ($model=='setbola') {
        $luas=$ltabung-(0*$ltabung);
    }

    $pmargin = 0; 
    if ($d >= 5) {$pmargin = 33;}
    else {$pmargin = 40;}
    /*if ($luas <= 15) {$pmargin = 100;}
    else if($luas <= 25){$pmargin = 80;}
    else if($luas <= 40){$pmargin = 60;}
    else if($luas <= 60){$pmargin = 50;}
    else if($luas <= 100){$pmargin = 40;}
    else{$pmargin = 33;}*/
    if ($m !=0 ) {
        $pmargin = $m; 
    }
    
    
    //GA
    $sql = "SELECT * FROM aki_hpp where model='".$model."' and bahan='galvalum' and aktif=0";
    $result = mysql_query($sql, $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            $xfull = (double)$data['full'];
            $xtp = (double)$data['tplafon'];
            $xwa = (double)$data['waterproof'];
        }
    }
    $h_en=0;
    $sql = "SELECT * FROM aki_hpp where model='".$model."' and bahan='hpermeter' and aktif=0";
    $result = mysql_query($sql, $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            $h_ga = (double)$data['full'];
            $h_ss = (double)$data['waterproof'];
            $h_en = (double)$data['tplafon'];
        }
    }
    $hmodal = $luas*$h_ga;
    if( $kel == 0){
        $modal = $hmodal * $xfull;
    }else if($kel == 2){
        $modalt = $hmodal -(($xtp/100)*$hmodal) ;
        $modal = $modalt + (($xwa/100)*$hmodal);
    }else{
        $modal = $hmodal -(($xtp/100)*$hmodal) ;
    }
    $margin = $modal * ($pmargin*0.01);
    $hpp = $modal + $margin;
    $affiliate = $hpp * 0.05;
    $marketing = $hpp * 0.01;
    $lain2 = $modal * 0.1;
    $harga = $hpp + $affiliate + $marketing + $transport +$lain2;
    $marketing = number_format(round($marketing,0));
    $lain2 = number_format(round($lain2,0));
    $hpp = number_format(round($hpp,0));
    $affiliate = number_format(round($affiliate,0));
    $hargaGA = number_format(round($harga,-6));
    //end GA

    //SS
    $sql = "SELECT * FROM aki_hpp where model='".$model."' and bahan='gold' and aktif=0";
    $result = mysql_query($sql, $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            $xfull = (double)$data['full'];
            $xtp = (double)$data['tplafon'];
            $xwa = (double)$data['waterproof'];
        }
    }
    $hmodal2 = $luas*$h_ss;
    if( $kel == 0){
        $modal = $hmodal2 * $xfull;
    }else if($kel == 2){
        $modalt2 = $hmodal2 -(($xtp/100)*$hmodal2) ;
        $modal = $modalt2 + (($xwa/100)*$hmodal2);
    }else{
        $modal = $hmodal2 -(($xtp/100)*$hmodal2) ;
    }
    $margin = $modal * ($pmargin*0.01);
    $hpp = $modal + $margin;
    $affiliate = $hpp * 0.05;
    $marketing = $hpp * 0.01;
    $lain2 = $modal * 0.1;
    $hargaSS = $hpp + $affiliate + $marketing + $transport +$lain2;
    $marketing = number_format(round($marketing,0));
    $lain2 = number_format(round($lain2,0));
    $hpp = number_format(round($hpp,0));
    $affiliate = number_format(round($affiliate,0));
    $hargaSS = number_format(round($hargaSS,-6));
    //endSS

    //EN
    $sql = "SELECT * FROM aki_hpp where model='".$model."' and bahan='enamel' and aktif=0";
    $result = mysql_query($sql, $dbLink);
    if (mysql_num_rows($result)>0) {
        while ( $data = mysql_fetch_assoc($result)) {
            $xfull = (double)$data['full'];
            $xtp = (double)$data['tplafon'];
            $xwa = (double)$data['waterproof'];
        }
    }
   
    $hmodal3 = $luas*$h_en;
    if( $kel == 0){
        $modal3 = $hmodal3 * $xfull;
    }else if($kel == 2){
        $modalt3 = $hmodal3 -(($xtp/100)*$hmodal3) ;
        $modal3 = $modalt3 + (($xwa/100)*$hmodal3);
    }else{
        $modal3 = $hmodal3 -(($xtp/100)*$hmodal3) ;
    }
    $margin3 = $modal3 * ($pmargin*0.01);
    $hpp3 = $modal3 + $margin3;
    $affiliate = $hpp3 * 0.05;
    $marketing = $hpp3 * 0.01;
    $lain2 = $modal3 * 0.1;
    $hargaEN = $hpp3 + $affiliate + $marketing + $transport +$lain2;
    $marketing = number_format(round($marketing,0));
    $lain2 = number_format(round($lain2,0));
    $hpp3 = number_format(round($hpp3,0));
    $affiliate = number_format(round($affiliate,0));
    $hargaEN = number_format(round($hargaEN,-6));
    //endEN

    echo json_encode(array("hpp"=>$hpp.'',"hmargin"=>$margin3.'',"hmodal"=>$hmodal3.'',"h"=>$h_en.'',"luas"=>round($luas,2).'',"margin"=>$pmargin.'',"hargaga"=>$hargaGA.'',"hargass"=>$hargaSS.'',"hargaen"=>$hargaEN.'')); 
break;
}
?>
