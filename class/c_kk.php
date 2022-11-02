<?php
/*==================================================
//=======  : Alibaba
====================================================*/
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined( 'validSession' ) or die( 'Restricted access' ); 

class c_kk
{
	var $strResults="";
	
	function addkk(&$params,$nameimg){
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
        $tglTransaksi = date("Y-m-d");
        $nokk = secureParam($params["txtnoKk"],$dbLink);
        $noSph = secureParam($params["txtnomersph"],$dbLink);
        $namacust = secureParam($params["txtnamacust"],$dbLink);
        $jenis_id = secureParam($params["cboJenisid"],$dbLink);
        $no_id = secureParam($params["txtNoid"],$dbLink);
        $no_phone = secureParam($params["txtPhone"],$dbLink);
        $jabatan = secureParam($params["txtPosition"],$dbLink);
        $nmasjid = secureParam($params["txtnmasjid"],$dbLink);
        $nproyek = secureParam($params["txtnproyek"],$dbLink);
        $kproyek = secureParam($params["cbokproject"],$dbLink);
        $project_pemerintah = secureParam($params["txtppemerintah"],$dbLink);
        $alamat_proyek = secureParam($params["txtalamatp"],$dbLink);
        $mproduksi = secureParam($params["txtproduksi"],$dbLink);
        $mpemasangan = secureParam($params["txtPemasangan"],$dbLink);
        $alamat = secureParam($params["txtalamat"],$dbLink);
        $alamat2 = secureParam($params["provinsi"],$dbLink);
        $treport = secureParam($params["treport"],$dbLink);
        $provinsi = substr($alamat2,0, 2);
        $kota = substr($alamat2,3, 6);
        $pembuat = $_SESSION["my"]->id;
        
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			$qs = "UPDATE `aki_sph` SET `keterangan_kk`='y'";
			$qs.= "WHERE (noSph)='".$noSph."';";

			if (!mysql_query( $qs, $dbLink))
				throw new Exception('Gagal ket data KK.');

			$q = "INSERT INTO aki_kk(`noKk`, `noSph`, `nama_cust`, `jenis_id`, `no_id`, `no_phone`, `jabatan`,`nmasjid`, `nproyek`,`kproyek`, `project_pemerintah`, `alamat_proyek`, `mproduksi`, `mpemasangan`, `alamat`, `provinsi`, `kota`, `tanggal`, `kodeUser`, `aktif`) ";
			$q.= "VALUES ('".$nokk."','".$noSph."','".$namacust."','".$jenis_id."','".$no_id."','".$no_phone."','".$jabatan."','".$nmasjid."','".$nproyek."','".$kproyek."','".$project_pemerintah."','".$alamat_proyek."','".$mproduksi."','".$mpemasangan."','".$alamat."','".$provinsi."','".$kota."','".$tglTransaksi."','".$pembuat."','1');";
			if (!mysql_query($q, $dbLink))
				throw new Exception('Gagal masukkan data dalam database.');
			$w1 = secureParam($params["txtW1"],$dbLink);
			$w2 = secureParam($params["txtW2"], $dbLink);
			$w3 = secureParam($params["txtW3"], $dbLink);
			$w4 = secureParam($params["txtW4"], $dbLink);
			$p1 = secureParam($params["txtP1"], $dbLink);
			$p2 = secureParam($params["txtP2"],$dbLink);
			$p3 = secureParam($params["txtP3"], $dbLink);
			$p4 = secureParam($params["txtP4"], $dbLink);

			$q4 = "INSERT INTO `aki_dpembayaran`(	`noKk`, `wpembayaran1`, `wpembayaran2`, `wpembayaran3`, `wpembayaran4`, `persen1`, `persen2`, `persen3`, `persen4`) VALUES ";
			$q4.= " ('".$nokk."','".$w1."','".$w2."','".$w3."','".$w4."','".$p1."','".$p2."','".$p3."','".$p4."');";
			if (!mysql_query( $q4, $dbLink))
				throw new Exception('Gagal tambah data KK-4.');

			$jumData = $params["jumAddJurnal"];
			$jumRangka = $params["norangka"];
			$nomer=0;
			$files = $_FILES;
			for ($j = 0; $j < $jumData ; $j++){
				if (($params['chkAddJurnal_'.$j])!=0){
					$jumRangka = $params["jumDetailr_".$j];
					for ($k = 1; $k <= $jumRangka ; $k++){
						$idrangka = secureParam($params["idrangka"],$dbLink);
						$rangka = secureParam($params["txtrangka".$j.'_'.$k],$dbLink);
						$q7 = "INSERT INTO `aki_kkrangka`( `noKK`, `nomer`, `rangka`) ";
						$q7.= "VALUES ('".$nokk."','".$j."','".$rangka."');";
						if ($rangka != '') {
							if (!mysql_query( $q7, $dbLink))
								throw new Exception('KK.'.mysql_error());
						}
					}
					$jumWarna = $params["jumWarna_".$j];
					for ($k = 1; $k <= $jumWarna ; $k++){
						$warna = secureParam($params["txtwarna".$j.'_'.$k],$dbLink);
						$kwarna = secureParam($params["txtkwarna".$j.'_'.$k],$dbLink);
						$q7 = "INSERT INTO `aki_kkcolor`(`noKk`, `nomer`, `color`, `kcolor`) ";
						$q7.= "VALUES ('".$nokk."','".$j."','".$warna."','".$kwarna."');";
						if ($warna != '' || $kwarna != '') {
							if (!mysql_query( $q7, $dbLink))
								throw new Exception('KK.'.mysql_error());
						}
					}

					$idKk = secureParam($params["chkAddJurnal_" . $j], $dbLink);
                    $model = secureParam($params["txtModel_". $j],$dbLink);
					$jkubah = secureParam($params["txtKubah_". $j],$dbLink);
					$diameter = secureParam($params["txtD_". $j],$dbLink);
                    $tinggi = secureParam($params["txtT_". $j],$dbLink);
                    $dtengah = secureParam($params["txtDt_". $j],$dbLink);
                    $luas = 0;
                    $plafon = secureParam($params["txtPlafon_". $j],$dbLink);
                    $harga1 = secureParam($params["txtHarga_" . $j], $dbLink);
                    $h = preg_replace("/\D/", "", $harga1);
                    $qty = secureParam($params["txtQty_" . $j], $dbLink);
                    $bahan = secureParam($params["txtBahan_" . $j], $dbLink);
                    $filekubah = secureParam($params["filekubah_" . $j], $dbLink);
					$kaligrafi = secureParam($params["txtKaligrafi_" . $j], $dbLink);
					$ntrans = secureParam($params["txtntrans_" . $j], $dbLink);
					$ktrans = secureParam($params["txtKtransport_" . $j], $dbLink);
					$ntransport = preg_replace("/\D/", "", $ntrans);
					$transport = secureParam($params["txttransport"], $dbLink);
					$makara = secureParam($params["txtMakara_" . $j], $dbLink);
					$bmakara = secureParam($params["txtbMakara_" . $j], $dbLink);
					$hppn = secureParam($params["txtHargappn_" . $j], $dbLink);
					if ($dtengah == 0) {
                    	$luas = ($diameter * $tinggi * 3.14);
                    }else{
                    	$luas = ($dtengah * $tinggi * 3.14);
                    }
                    
                    if ($nameimg[0]!=''){
                    	$qimg=",`filekubah`='".$nameimg[0]."',`filekaligrafi`='".$nameimg[1]."'";
                    }

                    $filekaligrafi = secureParam($params["filekaligrafi_" . $j], $dbLink);
                    $q2 = "INSERT INTO aki_dkk(`nomer`, `noKK`, `model`, `kubah`, `d`, `t`, `dt`, `luas`, `plafon`, `makara`, `bmakara`, `kaligrafi`, `harga`, `jumlah`, `bahan`,`ppn`,`hppn`, `transport`, `ntransport`, `ktransport`,`filekubah`, `filekaligrafi`) ";
					$q2.= "VALUES ('".$nomer."','".$nokk."','".$model."', '".$jkubah."', '".$diameter."', '".$tinggi."', '".$dtengah."','".$luas."', '".$plafon."', '".$makara."', '".$bmakara."', '".$kaligrafi."', '".$h."', '".$qty."', '".$bahan."', '".$project_pemerintah."', '".$hppn."', '".$transport."', '".$ntransport."', '".$ktrans."', '".$nameimg[0]."', '".$nameimg[1]."');";
					if (!mysql_query( $q2, $dbLink))
						throw new Exception($q2.'Gagal ubah data KK5.');
					$nomer++;
				}
			}

			
			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d H:i:s");
			$ket = "KK Note, nokk=".$nokk.", note=".$treport.", read by kpenjualan=1";
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception($q4.'Gagal ubah data KK. ');
			$privilegeU='';
			if ($_SESSION["my"]->privilege == 'ADMIN') {
				$privilegeU = 'kpenjualan';
			}else if($_SESSION["my"]->privilege == 'kpenjualan'){
				$privilegeU = 'ADMIN';
			}else{
				$privilegeU = 'GODMODE';
			}
			$rsTemp=mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where g.kodeGroup='".$privilegeU."' limit 1", $dbLink);
			$temp = mysql_fetch_array($rsTemp);
			$token=$temp['token'];
			$Message = "SIKUBAH - Pesan dari ".$_SESSION["my"]->privilege." Please Check 'New Kontrak Kerja'. Nomor KK : '".$nokk."', Note : '".$treport."' ";
			$url ="https://fcm.googleapis.com/fcm/send";
			$fields=array(
				"to"=>$token,
				"notification"=>array(
					"body"=>$Message,
					"title"=>'Sikubah',
					"click_action"=>"https://sikubah.com/marketing/index.php?page=view/kk_list"
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
			@mysql_query("COMMIT", $dbLink);
			$this->strResults="Sukses";
		}
		catch(Exception $e) 
		{
			  $this->strResults="Gagal Tambah Data - ".$e->getMessage().'<br/>';
			  $result = @mysql_query('ROLLBACK', $dbLink);
			  $result = @mysql_query('SET AUTOCOMMIT=1', $dbLink);
			  return $this->strResults;
		}
		return $this->strResults;
	}

	function validate(&$params) 
	{
		$temp=TRUE;

		if($params["txtnoKk"]=='' )
		{
			$this->strResults.="Harga belum terakumulasi!<br/>";
			$temp=FALSE;
		}       
		return $temp;
	}

	function edit(&$params,$nameimg) 
	{
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
		$q='';
		//Jika input tidak valid, langsung kembalikan pesan error ke user ($this->strResults)
		if(!$this->validate($params))
		{	//Pesan error harus diawali kata "Gagal"
			$this->strResults="Gagal Ubah Data KK - ".$this->strResults;
			return $this->strResults;
		}
		$tglTransaksi = date("Y-m-d");
		$nokk = secureParam($params["txtnoKk"],$dbLink);
        $namacust = secureParam($params["txtnamacust"],$dbLink);
        $jenis_id = secureParam($params["cboJenisid"],$dbLink);
        $no_id = secureParam($params["txtNoid"],$dbLink);
        $no_phone = secureParam($params["txtPhone"],$dbLink);
        $jabatan = secureParam($params["txtPosition"],$dbLink);
        $nmasjid = secureParam($params["txtnmasjid"],$dbLink);
        $nproyek = secureParam($params["txtnproyek"],$dbLink);
        $kproyek = secureParam($params["cbokproject"],$dbLink);
        $project_pemerintah = secureParam($params["txtppemerintah"],$dbLink);
        $alamat_proyek = secureParam($params["txtalamatp"],$dbLink);
        $mproduksi = secureParam($params["txtproduksi"],$dbLink);
        $mpemasangan = secureParam($params["txtPemasangan"],$dbLink);
        $alamat = secureParam($params["txtalamat"],$dbLink);
        $alamat2 = secureParam($params["provinsi"],$dbLink);
        $provinsi = substr($alamat2,0, 2);
        $kota = substr($alamat2,3, 6);
        $pembuat = $_SESSION["my"]->id;
		$treport = secureParam($params["treport"],$dbLink);

		$q3='';
		$qimg='';
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			
			//report
			$rsTemp=mysql_query("SELECT s.`nama_cust`,s.`provinsi`,s.`kota`,ds.`model`,ds.`d`,ds.`dt`,ds.`t`,ds.`luas`,ds.`plafon`,ds.`harga`,ds.`hppn`,ds.`jumlah`,ds.`transport`,ds.`bahan`,ds.`filekubah` FROM aki_kk s LEFT JOIN aki_dkk ds ON s.`noKk`=ds.`noKk` WHERE s.`noKk` = '".$params["txtnoKk"]."'", $dbLink);
			$temp = mysql_fetch_array($rsTemp);
			$tempNamecust  = $temp['nama_cust'];
			$tempP  = $temp['provinsi'];
			$tempK  = $temp['kota'];
			$tempModel  = $temp['model'];
			$tempD  = $temp['d'];
			$tempDt  = $temp['dt'];
			$tempT  = $temp['t'];
			$tempLuas  = $temp['luas'];
			$tempPlafon  = $temp['plafon'];
			$tempHarga  = $temp['harga'];
			$tempHarga2  = $temp['hppn'];
			$tempJumlah  = $temp['jumlah'];
			$tempTrans  = $temp['transport'];
			$tempBahan  = $temp['bahan'];
			$tempfile  = $temp['filekubah'];

			$qkk = "UPDATE aki_kk SET `nama_cust`='".$namacust."',`jenis_id`='".$jenis_id."',`no_id`='".$no_id."',`no_phone`='".$no_phone."',`jabatan`='".$jabatan."',`nmasjid`='".$nmasjid."',`nproyek`='".$nproyek."',`kproyek`='".$kproyek."',`project_pemerintah`='".$project_pemerintah."',`alamat_proyek`='".$alamat_proyek."',`mproduksi`='".$mproduksi."',`mpemasangan`='".$mpemasangan."',`alamat`='".$alamat."',`provinsi`='".$provinsi."',`kota`='".$kota."',`approve`='0',`approve_kpenjualan`='-',`approve_tgl`='0000-00-00',`approve_koperational`='-',`approve_tgl2`='0000-00-00' WHERE noKk='".$nokk."'";
			if (!mysql_query( $qkk, $dbLink))
						throw new Exception('Gagal ubah data KK2. ');

			$w1 = secureParam($params["txtW1"],$dbLink);
			$w2 = secureParam($params["txtW2"], $dbLink);
			$w3 = secureParam($params["txtW3"], $dbLink);
			$w4 = secureParam($params["txtW4"], $dbLink);
			$p1 = secureParam($params["txtP1"], $dbLink);
			$p2 = secureParam($params["txtP2"],$dbLink);
			$p3 = secureParam($params["txtP3"], $dbLink);
			$p4 = secureParam($params["txtP4"], $dbLink);

			$q3 = "UPDATE `aki_dpembayaran` SET `noKk`='".$nokk."',`wpembayaran1`='".$w1."',`wpembayaran2`='".$w2."',`wpembayaran3`='".$w3."',`wpembayaran4`='".$w4."',`persen1`='".$p1."',`persen2`='".$p2."',`persen3`='".$p3."',`persen4`='".$p4."'";
			$q3.= " WHERE noKk='".$nokk."'";
			if (!mysql_query( $q3, $dbLink))
				throw new Exception('Gagal update data KK3.');

			$jumData = $params["jumAddJurnal"];
			$nomer =0;
			$qq2 = "DELETE FROM `aki_dkk` WHERE noKK='".$nokk."'";
			if (!mysql_query( $qq2, $dbLink))
				throw new Exception('Gagal del data KK.');
			$qq3 = "DELETE FROM `aki_kkrangka` WHERE noKK='".$nokk."'";
			if (!mysql_query( $qq3, $dbLink))
				throw new Exception('Gagal del data KK.');
			$qq4 = "DELETE FROM `aki_kkcolor` WHERE noKk='".$nokk."'";
			if (!mysql_query( $qq4, $dbLink))
				throw new Exception('Gagal del data KK.');
			for ($j = 0; $j < $jumData ; $j++){
				if (!empty($params['chkAddJurnal_'.$j])){
					$jumRangka = $params["jumDetailr_".$j];
					for ($k = 1; $k <= $jumRangka ; $k++){
						$idrangka = secureParam($params["idrangka"],$dbLink);
						$rangka = secureParam($params["txtrangka".$j.'_'.$k],$dbLink);
						$q7 = "INSERT INTO `aki_kkrangka`( `noKK`, `nomer`, `rangka`) ";
						$q7.= "VALUES ('".$nokk."','".$j."','".$rangka."');";
						if ($rangka != '') {
							if (!mysql_query( $q7, $dbLink))
								throw new Exception('KK.'.mysql_error());
						}
					}
					$jumWarna = $params["jumWarna_".$j];
					for ($k = 1; $k <= $jumWarna ; $k++){
						$warna = secureParam($params["txtwarna".$j.'_'.$k],$dbLink);
						$kwarna = secureParam($params["txtkwarna".$j.'_'.$k],$dbLink);
						$q7 = "INSERT INTO `aki_kkcolor`(`noKk`, `nomer`, `color`, `kcolor`) ";
						$q7.= "VALUES ('".$nokk."','".$j."','".$warna."','".$kwarna."');";
						if ($warna != '' || $kwarna != '') {
							if (!mysql_query( $q7, $dbLink))
								throw new Exception('KK.'.mysql_error());
						}
					}

					$idKk = secureParam($params["chkAddJurnal_" . $j], $dbLink);
                    $model = secureParam($params["txtModel_". $j],$dbLink);
					$jkubah = secureParam($params["txtKubah_". $j],$dbLink);
					$diameter = secureParam($params["txtD_". $j],$dbLink);
                    $tinggi = secureParam($params["txtT_". $j],$dbLink);
                    $dtengah = secureParam($params["txtDt_". $j],$dbLink);
                    $luas = 0;
                    $plafon = secureParam($params["txtPlafon_". $j],$dbLink);
                    $harga1 = secureParam($params["txtHarga_" . $j], $dbLink);
                    $h = preg_replace("/\D/", "", $harga1);
                    $qty = secureParam($params["txtQty_" . $j], $dbLink);
                    $bahan = secureParam($params["txtBahan_" . $j], $dbLink);
                    $filekubah = secureParam($params["filekubah_" . $j], $dbLink);
					$kaligrafi = secureParam($params["txtKaligrafi_" . $j], $dbLink);
					$ntrans = secureParam($params["txtntrans_" . $j], $dbLink);
					$ktrans = secureParam($params["txtKtransport_" . $j], $dbLink);
					$ntransport = preg_replace("/\D/", "", $ntrans);
					$transport = secureParam($params["txttransport"], $dbLink);
					$makara = secureParam($params["txtMakara_" . $j], $dbLink);
					$bmakara = secureParam($params["txtbMakara_" . $j], $dbLink);
					$hppn = secureParam($params["txtHargappn_" . $j], $dbLink);
					if ($dtengah == 0) {
                    	$luas = ($diameter * $tinggi * 3.14);
                    }else{
                    	$luas = ($dtengah * $tinggi * 3.14);
                    }
                    
                    if ($nameimg[0]!=''){
                    	$qimg=",`filekubah`='".$nameimg[0]."',`filekaligrafi`='".$nameimg[1]."'";
                    }

                    $filekaligrafi = secureParam($params["filekaligrafi_" . $j], $dbLink);
                    $filedesain = '';
                    if ($tempfile != '') {
                    	$filedesain = $tempfile;
                    }elseif ($tempfile != '' && $nameimg[0] !='') {
                    	$filedesain = $nameimg[0];
                    }{
                    	$filedesain = $nameimg[0];
                    }
                    $q2 = "INSERT INTO aki_dkk(`nomer`, `noKK`, `model`, `kubah`, `d`, `t`, `dt`, `luas`, `plafon`, `makara`, `bmakara`, `kaligrafi`, `harga`, `jumlah`, `bahan`,`ppn`,`hppn`, `transport`, `ntransport`, `ktransport`,`filekubah`, `filekaligrafi`) ";
					$q2.= "VALUES ('".$nomer."','".$nokk."','".$model."', '".$jkubah."', '".$diameter."', '".$tinggi."', '".$dtengah."','".$luas."', '".$plafon."', '".$makara."', '".$bmakara."', '".$kaligrafi."', '".$h."', '".$qty."', '".$bahan."', '".$project_pemerintah."', '".$hppn."', '".$transport."', '".$ntransport."', '".$ktrans."', '".$filedesain."', '".$nameimg[1]."');";

					if (!mysql_query( $q2, $dbLink))
						throw new Exception($q2.'Gagal ubah data KK5.');
                    $nomer++;
				}
			}

			$rsTempSPK=mysql_query("SELECT * FROM `aki_spk` WHERE `nokk` = '".$params["txtnoKk"]."'", $dbLink);
			$tempSPK = mysql_fetch_array($rsTempSPK);
			$noproyek  = $temp['noproyek'];
			$ket = 'first';
			if ($noproyek !='-') {
				$ket = 'revisi';
				$q3 = "UPDATE `aki_spk` SET `ket`='".$ket."' WHERE noKk='".$nokk."'";
				if (!mysql_query( $q3, $dbLink))
					throw new Exception('Gagal ubah data KK. ');
			}
			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d H:i:s");
			$ket = "`nomer`=".$params["txtnoKk"]." -has change, ket : ".$tempNamecust.", ".$tempP.", ".$tempK.", ".$tempModel.", ".$tempD.", ".$tempT.", ".$tempDt.", ".$tempTrans.", ".$tempLuas.", ".$tempJumlah.", ".$tempHarga.", ".$tempHarga2.", ".$tempPlafon.", ".$tempBahan.", datetime: ".$tgl;
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception($q4.'Gagal ubah data KK6. ');

			$ket = "KK Note, nokk=".$nokk.", note=".$treport.", read by kpenjualan=1";
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
				throw new Exception('Gagal ubah data KK7. ');
			@mysql_query("COMMIT", $dbLink);
			//API send firebase
			$privilegeU='';
			if ($_SESSION["my"]->privilege == 'ADMIN') {
				$privilegeU = 'kpenjualan';
			}else if($_SESSION["my"]->privilege == 'kpenjualan'){
				$privilegeU = 'ADMIN';
			}else{
				$privilegeU = 'GODMODE';
			}
			$rsTemp=mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where g.kodeGroup='".$privilegeU."' limit 1", $dbLink);
			$temp = mysql_fetch_array($rsTemp);
			$token=$temp['token'];
			$Message = "SIKUBAH - Message from ".$_SESSION["my"]->privilege." Please Check 'Kontrak Kerja'. Nomor KK : '".$nokk."', Note : '".$treport."' ";
			$url ="https://fcm.googleapis.com/fcm/send";
			$fields=array(
				"to"=>$token,
				"notification"=>array(
					"body"=>$Message,
					"title"=>'Sikubah',
					"click_action"=>"https://sikubah.com/marketing/index.php?page=view/kk_list"
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
			@mysql_query("COMMIT", $dbLink);
			$this->strResults="Sukses Edit";
		}
		catch(Exception $e) 
		{
			  $this->strResults="Gagal Ubah Data KK - ".$e->getMessage().'<br/>';
			  $result = @mysql_query('ROLLBACK', $dbLink);
			  $result = @mysql_query('SET AUTOCOMMIT=1', $dbLink);
			  return $this->strResults;
		}
		return $this->strResults;
	}
	
	function validateDelete($kode) 
	{
		global $dbLink;
		$temp=FALSE;
		if(empty($kode))
		{
			$this->strResults.="No KK tidak ditemukan!<br/>";
			$temp=FALSE;
		}

		//cari ID inisiasi di tabel penyusunan
		$rsTemp=mysql_query("SELECT * FROM aki_kk WHERE (noKk) = '".$kode."'", $dbLink);
                $rows = mysql_num_rows($rsTemp);
                if($rows!=0)
		{
			$temp=TRUE;
		} 
		
		return $temp;
	}

	function delete($kode)
	{
		global $dbLink;

		//Jika input tidak valid, langsung kembalikan pesan error ke user ($this->strResults)
		if(!$this->validateDelete($kode))
		{	//Pesan error harus diawali kata "Gagal"
			$this->strResults="Gagal Hapus Data KK - ".$this->strResults;
			return $this->strResults;
		}

		$noKk  = secureParam($kode,$dbLink);
        $pembatal = $_SESSION["my"]->id;

		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			$rsTemp=mysql_query("SELECT s.`nama_cust`,s.`provinsi`,s.`kota`,ds.`model`,ds.`d`,ds.`dt`,ds.`t`,ds.`luas`,ds.`plafon`,ds.`harga`,ds.`jumlah`,s.`noSph`,ds.`bahan` FROM aki_kk s LEFT JOIN aki_dkk ds ON s.`noKk`=ds.`noKk` WHERE s.`noKk` = '".$noKk."'", $dbLink);
			$temp = mysql_fetch_array($rsTemp);
			$tempNamecust  = $temp['nama_cust'];
			$tempP  = $temp['provinsi'];
			$tempK  = $temp['kota'];
			$tempModel  = $temp['model'];
			$tempD  = $temp['d'];
			$tempDt  = $temp['dt'];
			$tempT  = $temp['t'];
			$tempLuas  = $temp['luas'];
			$tempPlafon  = $temp['plafon'];
			$tempHarga  = $temp['harga'];
			$tempJumlah  = $temp['jumlah'];
			$tempBahan  = $temp['bahan'];
			$nosph  = $temp['noSph'];

			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d h:i:sa");
			$ket = "`nomer`=".$noKk." -has delete, ket : ".$tempNamecust.", ".$tempP.", ".$tempK.", ".$tempModel.", ".$tempD.", ".$tempT.", ".$tempDt.", ".$tempLuas.", ".$tempJumlah.", ".$tempHarga.", ".$tempPlafon.", ".$tempBahan.", datetime: ".$tgl;
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembatal."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception($q4.'Gagal ubah data KK. ');

			$qs = "UPDATE `aki_sph` SET `keterangan_kk`=''";
			$qs.= "WHERE (noSph)='".$nosph."';";

			if (!mysql_query( $qs, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			$qd = "UPDATE `aki_kk` SET `aktif`='0'";
			$qd.= "WHERE (noKk)='".$noKk."';";
			if (!mysql_query( $qd, $dbLink))
				throw new Exception('Gagal hapus data KK.');


			$q = "DELETE FROM  `aki_kkrangka`";
			$q.= "WHERE (noKk)='".$noKk."';";

			if (!mysql_query( $q, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			$q2 = "DELETE FROM aki_dpembayaran ";
			$q2.= "WHERE (noKk)='".$noKk."';";
			if (!mysql_query( $q2, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			$q3 = "DELETE FROM aki_dkk ";
			$q3.= "WHERE (noKk)='".$noKk."';";

			if (!mysql_query( $q3, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			$q4 = "DELETE FROM aki_kkcolor ";
			$q4.= "WHERE (noKk)='".$noKk."';";

			if (!mysql_query( $q4, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			$q5 = "DELETE FROM aki_report ";
			$q5.= "WHERE ket like'%".$noKk."%';";

			if (!mysql_query( $q5, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			@mysql_query("COMMIT", $dbLink);
			$this->strResults="Sukses Hapus Data KK ";
		}
		catch(Exception $e) 
		{
			  $this->strResults="Gagal Hapus Data KK - ".$e->getMessage().'<br/>';
			  $result = @mysql_query('ROLLBACK', $dbLink);
			  $result = @mysql_query('SET AUTOCOMMIT=1', $dbLink);
			  return $this->strResults;
		}
		return $this->strResults;
		
	}
	function uploadimg(){
		echo "<pre>";
		print_r($_FILES);
		echo "</pre>";
	}

}
?>
