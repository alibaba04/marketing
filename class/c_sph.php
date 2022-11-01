<?php
/*==================================================
//=======  : Alibaba
====================================================*/
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined( 'validSession' ) or die( 'Restricted access' ); 

class c_sph
{
	var $strResults="";
	function note(&$params){
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
		$pembuat = $_SESSION["my"]->id;
		$noSph = secureParam($params["notenoSph"],$dbLink);
		$note = secureParam($params["txtnote"],$dbLink);
		try
		{
			$q4 = "UPDATE aki_sph SET `note`='".$note."' WHERE noSph='".$noSph."'";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception('Gagal tambah data NOTE');
			@mysql_query("COMMIT", $dbLink);
					$this->strResults="Sukses Tambah Data NOTE";
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
	
	function addsph(&$params){
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
		date_default_timezone_set("Asia/Jakarta");
        $tglTransaksi = date("Y-m-d H:i:s");
        $namacust = secureParam($params["txtnamacust"],$dbLink);
        $sdr = secureParam($params["cbosdr"],$dbLink);
        $nmasjid = secureParam($params["txtnmasjid"],$dbLink);
        $tmasjid = secureParam($params["cbomasjid"],$dbLink);
        $noSph = secureParam($params["txtnoSph"],$dbLink);
        $alamat = secureParam($params["provinsi"],$dbLink);
        $affiliate = secureParam($params["cboAffiliate"],$dbLink);
        $phone = secureParam($params["txtPhone"],$dbLink);
        
        $provinsi = substr($alamat,0, 2);
        $kota = substr($alamat,3, 6);
        $pembuat = $_SESSION["my"]->id;
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			
			$q = "INSERT INTO aki_sph(noSph, no_phone, nama_cust, masjid, provinsi, kota, affiliate, tanggal, keterangan_kk, kodeUser) ";
			$q.= "VALUES ('".$noSph."','".$phone."','".$sdr.$namacust."','".$tmasjid.$nmasjid."','".$provinsi."','".$kota."','".$affiliate."','".$tglTransaksi."','','".$pembuat."');";
			if (!mysql_query($q, $dbLink))
				throw new Exception('SPH.'.mysql_error());
			$jumData = $params["jumAddJurnal"];
			$jumRangka = $params["norangka"];
			$nomer=0;
			for ($j = 1; $j <= $jumData ; $j++){
				if (!empty($params['chkAddJurnal_'.$j])){
                    $ketkubah = secureParam($params["txtKet_" . $j], $dbLink);
                    $qty = secureParam($params["txtQty_" . $j], $dbLink);
                    $transport = secureParam($params["txtTransport_" . $j], $dbLink);
                    $harga1 = secureParam($params["txtHarga1_" . $j], $dbLink);
                    $harga2 = secureParam($params["txtHarga2_" . $j], $dbLink);
                    $harga3 = secureParam($params["txtHarga3_" . $j], $dbLink);
                    $h1 = preg_replace("/\D/", "", $harga1);
                    $h2 = preg_replace("/\D/", "", $harga2);
                    $h3 = preg_replace("/\D/", "", $harga3);
                    $transport = preg_replace("/\D/", "", $transport);
                    $diameter = secureParam($params["txtD_". $j],$dbLink);
                    $tinggi = secureParam($params["txtT_". $j],$dbLink);
                    $dtengah = secureParam($params["txtDt_". $j],$dbLink);
                    $model = secureParam($params["txtModel_". $j],$dbLink);
                    $plafon = secureParam($params["txtKel_". $j],$dbLink);
                    $chkEnGa = secureParam($params["chkEnGa_". $j],$dbLink);
                    $bplafon = secureParam($params["txtBplafon_". $j],$dbLink);
                    $luas = secureParam($params["txtLuas_". $j],$dbLink);
                    $gold = secureParam($params["chkGold_". $j],$dbLink);
                    
                    if ($model=='custom') {
                    	for ($k = 1; $k <= $jumRangka ; $k++){
                    		$rangka = secureParam($params["rangka". $k],$dbLink);
                    		$q7 = "INSERT INTO `aki_rangka`( `noSph`,`rangka`) ";
                    		$q7.= "VALUES ('".$noSph."','".$rangka."');";
                    		if ($rangka != '') {
                    			if (!mysql_query( $q7, $dbLink))
                    			throw new Exception('SPH.'.mysql_error());
                    		}
                    	}
                    	
                    }
                    $q2 = "INSERT INTO aki_dsph(nomer,noSph, model, d, t, dt, plafon, gold, harga, harga2, harga3, jumlah, ket, transport,bahan,biaya_plafon,luas) ";
					$q2.= "VALUES ('".$nomer."','".$noSph."','".$model."', '".$diameter."', '".$tinggi."', '".$dtengah."', '".$plafon."', '".$gold."', '".$h1."', '".$h2."', '".$h3."', '".$qty."', '".$ketkubah."', '".$transport."','".$chkEnGa."','".$bplafon."','".$luas."');";
					if (!mysql_query( $q2, $dbLink))
						throw new Exception('SPH.'.mysql_error());
					@mysql_query("COMMIT", $dbLink);
					$this->strResults="Sukses Tambah Data SPH";
					$nomer++;
				}
			}
			
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

		if($params["txtnoSph"]=='' )
		{
			$this->strResults.="Harga belum terakumulasi!<br/>";
			$temp=FALSE;
		}       
		return $temp;
	}

	function edit(&$params) 
	{
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
		$q='';
		//Jika input tidak valid, langsung kembalikan pesan error ke user ($this->strResults)
		if(!$this->validate($params))
		{	//Pesan error harus diawali kata "Gagal"
			$this->strResults="Gagal Ubah Data SPH - ".$this->strResults;
			return $this->strResults;
		}
		$noSph = secureParam($params["txtnoSph"],$dbLink);
		$tglTransaksi = date("Y-m-d");
        $namacust = secureParam($params["txtnamacust"],$dbLink);
        $sdr = secureParam($params["cbosdr"],$dbLink);
        $nmasjid = secureParam($params["txtnmasjid"],$dbLink);
        $tmasjid = secureParam($params["cbomasjid"],$dbLink);
        $alamat = secureParam($params["provinsi"],$dbLink);
        $provinsi = substr($alamat,0, 2);
        $kota = substr($alamat,3, 6);
        $affiliate = secureParam($params["cboAffiliate"],$dbLink);
        $pembuat = $_SESSION["my"]->id;
        $jumRangka = $params["norangka"];
        $phone = secureParam($params["txtPhone"],$dbLink);
		$q3='';
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			
			//report
			$rsTemp=mysql_query("SELECT s.`nama_cust`,s.`provinsi`,s.`kota`,ds.`model`,ds.`d`,ds.`dt`,ds.`t`,ds.`luas`,ds.`plafon`,ds.`harga`,ds.`harga2`,ds.`harga3`,ds.`jumlah`,ds.`ket`,ds.`transport`,ds.`biaya_plafon`,ds.`bahan` FROM aki_sph s LEFT JOIN aki_dsph ds ON s.`noSph`=ds.`noSph` WHERE s.`noSph` = '".$params["txtnoSph"]."'", $dbLink);
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
			$tempHarga2  = $temp['harga2'];
			$tempHarga3  = $temp['harga3'];
			$tempJumlah  = $temp['jumlah'];
			$tempKet  = $temp['ket'];
			$tempTrans  = $temp['transport'];
			$tempBiaya  = $temp['biaya_plafon'];
			$tempBahan  = $temp['bahan'];

			$q3 = "UPDATE aki_sph SET `no_phone`='".$phone."',`masjid`='".$tmasjid.$nmasjid."',`nama_cust`='".$sdr.$namacust."',`provinsi`='".$provinsi."',`kota`='".$kota."',`affiliate`='".$affiliate."' WHERE noSph='".$params["txtnoSph"]."'";
			if (!mysql_query( $q3, $dbLink))
				throw new Exception('SPH.'.mysql_error());
			$jumData = $params["jumAddJurnal"];
			$jumRangka = $params["norangka"];
			$nomer=0;
			$q2 = "DELETE FROM aki_dsph ";
			$q2.= "WHERE (noSph)='".$noSph."';";
			if (!mysql_query( $q2, $dbLink))
				throw new Exception('Gagal hapus data SPH.');
			if ($model=='custom') {
				$q = "DELETE FROM aki_rangka ";
				$q.= "WHERE (noSph)='".$noSph."';";
				if (!mysql_query( $q, $dbLink))
					throw new Exception('Gagal hapus data SPH.');
			}
			for ($j = 0; $j <= $jumData ; $j++){
				if (!empty($params['chkAddJurnal_'.$j])){
                    $ketkubah = secureParam($params["txtKet_" . $j], $dbLink);
                    $qty = secureParam($params["txtQty_" . $j], $dbLink);
                    $transport = secureParam($params["txtTransport_" . $j], $dbLink);
                    $harga1 = secureParam($params["txtHarga1_" . $j], $dbLink);
                    $harga2 = secureParam($params["txtHarga2_" . $j], $dbLink);
                    $harga3 = secureParam($params["txtHarga3_" . $j], $dbLink);
                    $h1 = preg_replace("/\D/", "", $harga1);
                    $h2 = preg_replace("/\D/", "", $harga2);
                    $h3 = preg_replace("/\D/", "", $harga3);
                    $transport = preg_replace("/\D/", "", $transport);
                    $diameter = secureParam($params["txtD_". $j],$dbLink);
                    $tinggi = secureParam($params["txtT_". $j],$dbLink);
                    $dtengah = secureParam($params["txtDt_". $j],$dbLink);
                    $model = secureParam($params["txtModel_". $j],$dbLink);
                    $plafon = secureParam($params["txtKel_". $j],$dbLink);
                    $chkEnGa = secureParam($params["chkEnGa_". $j],$dbLink);
                    $bplafon = secureParam($params["txtBplafon_". $j],$dbLink);
                    $luas = secureParam($params["txtLuas_". $j],$dbLink);
                    $gold = secureParam($params["chkGold_". $j],$dbLink);
                    
                    if ($model=='custom') {
                    	for ($k = 1; $k <= $jumRangka ; $k++){
                    		$rangka = secureParam($params["rangka". $k],$dbLink);
                    		$q7 = "INSERT INTO `aki_rangka`( `noSph`,`rangka`) ";
                    		$q7.= "VALUES ('".$noSph."','".$rangka."');";
                    		if ($rangka != '') {
                    			if (!mysql_query( $q7, $dbLink))
                    			throw new Exception('SPH.'.mysql_error());
                    		}
                    	}
                    	
                    }
                    $q2 = "INSERT INTO aki_dsph(nomer,noSph, model, d, t, dt, plafon, gold, harga, harga2, harga3, jumlah, ket, transport,bahan,biaya_plafon,luas) ";
					$q2.= "VALUES ('".$nomer."','".$noSph."','".$model."', '".$diameter."', '".$tinggi."', '".$dtengah."', '".$plafon."', '".$gold."', '".$h1."', '".$h2."', '".$h3."', '".$qty."', '".$ketkubah."', '".$transport."','".$chkEnGa."','".$bplafon."','".$luas."');";
					if (!mysql_query( $q2, $dbLink))
						throw new Exception('SPH.'.mysql_error());
					@mysql_query("COMMIT", $dbLink);
					$this->strResults="Sukses Tambah Data SPH";
					$nomer++;
				}
			}
			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d H:i:s");
			$ket = "`nomer`=".$params["txtnoSph"]."  -has change, ket : ".$tempNamecust.", ".$tempP.", ".$tempK.", ".$tempModel.", ".$tempD.", ".$tempT.", ".$tempDt.", ".$tempTrans.", ".$tempKet.", ".$tempLuas.", ".$tempJumlah.", ".$tempBiaya.", ".$tempHarga.", ".$tempHarga2.", ".$tempPlafon.", ".$tempBahan.", datetime: ".$tgl;
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
				throw new Exception('SPH.'.mysql_error());
			@mysql_query("COMMIT", $dbLink);
			$this->strResults="Sukses Ubah Data SPH";
		}
		catch(Exception $e) 
		{
			  $this->strResults="Gagal Ubah Data SPH - ".$e->getMessage().'<br/>';
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
			$this->strResults.="No SPH tidak ditemukan!<br/>";
			$temp=FALSE;
		}

		//cari ID inisiasi di tabel penyusunan
		$rsTemp=mysql_query("SELECT idSph, noSph FROM aki_sph WHERE (noSph) = '".$kode."'", $dbLink);
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
			$this->strResults="Gagal Hapus Data SPH - ".$this->strResults;
			return $this->strResults;
		}

		$noSph = secureParam($kode,$dbLink);
        $pembatal = $_SESSION["my"]->id;

		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			$rsTemp=mysql_query("SELECT s.`nama_cust`,s.`provinsi`,s.`kota`,ds.`model`,ds.`d`,ds.`dt`,ds.`t`,ds.`luas`,ds.`plafon`,ds.`harga`,ds.`harga2`,ds.`harga2`,ds.`jumlah`,ds.`ket`,ds.`transport`,ds.`biaya_plafon`,ds.`bahan` FROM aki_sph s LEFT JOIN aki_dsph ds ON s.`noSph`=ds.`noSph` WHERE s.`noSph` = '".$noSph."'", $dbLink);
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
			$tempHarga2  = $temp['harga2'];
			$tempHarga3  = $temp['harga3'];
			$tempJumlah  = $temp['jumlah'];
			$tempKet  = $temp['ket'];
			$tempTrans  = $temp['transport'];
			$tempBiaya  = $temp['biaya_plafon'];
			$tempBahan  = $temp['bahan'];

			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d H:i:s");
			$ket = "`nomer`=".$noSph." -has delete, ket : ".$tempNamecust.", ".$tempP.", ".$tempK.", ".$tempModel.", ".$tempD.", ".$tempT.", ".$tempDt.", ".$tempTrans.", ".$tempKet.", ".$tempLuas.", ".$tempJumlah.", ".$tempBiaya.", ".$tempHarga.", ".$tempHarga2.", ".$tempPlafon.", ".$tempBahan.", datetime: ".$tgl;
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembatal."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception($q4.'Gagal ubah data SPH. ');

			$q = "DELETE FROM aki_sph ";
			$q.= "WHERE (noSph)='".$noSph."';";
			if (!mysql_query( $q, $dbLink))
				throw new Exception('Gagal hapus data SPH.');

			$q = "DELETE FROM aki_rangka ";
			$q.= "WHERE (noSph)='".$noSph."';";
			if (!mysql_query( $q, $dbLink))
				throw new Exception('Gagal hapus data SPH.');

			$q2 = "DELETE FROM aki_dsph ";
			$q2.= "WHERE (noSph)='".$noSph."';";
			if (!mysql_query( $q2, $dbLink))
				throw new Exception('Gagal hapus data SPH.');

			$q2 = "DELETE FROM aki_dkaligrafi ";
			$q2.= "WHERE (noSph)='".$noSph."';";
			if (!mysql_query( $q2, $dbLink))
				throw new Exception('Gagal hapus data Kaligrafi.');

			@mysql_query("COMMIT", $dbLink);
			$this->strResults="Sukses Hapus Data SPH ";
		}
		catch(Exception $e) 
		{
			  $this->strResults="Gagal Hapus Data SPH - ".$e->getMessage().'<br/>';
			  $result = @mysql_query('ROLLBACK', $dbLink);
			  $result = @mysql_query('SET AUTOCOMMIT=1', $dbLink);
			  return $this->strResults;
		}
		return $this->strResults;
		
	}

}
?>
