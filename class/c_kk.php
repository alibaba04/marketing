<?php
/*==================================================
//=======  : Alibaba
====================================================*/
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined( 'validSession' ) or die( 'Restricted access' ); 

class c_kk
{
	var $strResults="";
	
	function addkk(&$params){
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
        $tglTransaksi = date("Y-m-d");
        $nokk = secureParam($params["txtnoKk"],$dbLink);
        $namacust = secureParam($params["txtnamacust"],$dbLink);
        $jenis_id = secureParam($params["cboJenisid"],$dbLink);
        $no_id = secureParam($params["txtNoid"],$dbLink);
        $no_phone = secureParam($params["txtPhone"],$dbLink);
        $jabatan = secureParam($params["txtPosition"],$dbLink);
        $nmasjid = secureParam($params["txtnmasjid"],$dbLink);
        $nproyek = secureParam($params["txtnproyek"],$dbLink);
        $alamat_proyek = secureParam($params["txtalamatp"],$dbLink);
        $mproduksi = secureParam($params["txtproduksi"],$dbLink);
        $mpemasangan = secureParam($params["txtPemasangan"],$dbLink);
        $alamat = secureParam($params["txtalamat"],$dbLink);
        $alamat2 = secureParam($params["provinsi"],$dbLink);
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
			
			$q = "INSERT INTO aki_kk(`noKk`, `nama_cust`, `jenis_id`, `no_id`, `no_phone`, `jabatan`,`nmasjid`, `nproyek`, `alamat_proyek`, `mproduksi`, `mpemasangan`, `alamat`, `provinsi`, `kota`, `tanggal`, `kodeUser`, `aktif`) ";
			$q.= "VALUES ('".$nokk."','".$namacust."','".$jenis_id."','".$no_id."','".$no_phone."','".$jabatan."','".$nmasjid."','".$nproyek."','".$alamat_proyek."','".$mproduksi."','".$mpemasangan."','".$alamat."','".$provinsi."','".$kota."','".$tglTransaksi."','".$pembuat."','1');";
			if (!mysql_query($q, $dbLink))
				throw new Exception('Gagal masukkan data dalam database.');
			$jumData = $params["jumAddJurnal"];
			$nomer=0;
			for ($j = 0; $j < $jumData ; $j++){
				if (!empty($params['chkAddJurnal_'.$j])){
					$model = secureParam($params["txtModel_". $j],$dbLink);
					$jkubah = secureParam($params["txtKubah_". $j],$dbLink);
					$diameter = secureParam($params["txtD_". $j],$dbLink);
                    $tinggi = secureParam($params["txtT_". $j],$dbLink);
                    $dtengah = secureParam($params["txtDt_". $j],$dbLink);
                    $luas = secureParam($params["luas_". $j],$dbLink);
                    $plafon = secureParam($params["txtKel_". $j],$dbLink);
                    $harga1 = secureParam($params["txtHarga_" . $j], $dbLink);
                    $h = preg_replace("/\D/", "", $harga1);
                    $qty = secureParam($params["txtQty_" . $j], $dbLink);
                    $ketkubah = secureParam($params["txtKet_" . $j], $dbLink);
                    $bahan = secureParam($params["txtBahan_" . $j], $dbLink);
                    $filekubah = secureParam($params["filekubah_" . $j], $dbLink);
                    $filekaligrafi = secureParam($params["filekaligrafi_" . $j], $dbLink);
                    
                    $q2 = "INSERT INTO aki_dkk(`nomer`, `noKK`, `model`, `kubah`, `d`, `t`, `dt`, `luas`, `plafon`, `harga`, `jumlah`, `ket`, `bahan`,`filekubah`, `filekaligrafi`) ";
					$q2.= "VALUES ('".$nomer."','".$nokk."','".$model."', '".$jkubah."', '".$diameter."', '".$tinggi."', '".$dtengah."','".$luas."', '".$plafon."', '".$h."', '".$qty."', '".$ketkubah."', '".$bahan."', '".$filekubah."', '".$filekaligrafi."');";

					if (!mysql_query( $q2, $dbLink))
						throw new Exception('Gagal tambah data KK.');
					@mysql_query("COMMIT", $dbLink);
					$this->strResults="Sukses Tambah Data KK";
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

		if($params["txtnoKk"]=='' )
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
        $alamat_proyek = secureParam($params["txtalamatp"],$dbLink);
        $mproduksi = secureParam($params["txtproduksi"],$dbLink);
        $mpemasangan = secureParam($params["txtPemasangan"],$dbLink);
        $alamat = secureParam($params["txtalamat"],$dbLink);
        $alamat2 = secureParam($params["provinsi"],$dbLink);
        $provinsi = substr($alamat2,0, 2);
        $kota = substr($alamat2,3, 6);
        $pembuat = $_SESSION["my"]->id;
		$q3='';
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
			
			//report
			$rsTemp=mysql_query("SELECT s.`nama_cust`,s.`provinsi`,s.`kota`,ds.`model`,ds.`d`,ds.`dt`,ds.`t`,ds.`luas`,ds.`plafon`,ds.`harga`,ds.`harga2`,ds.`harga3`,ds.`jumlah`,ds.`ket`,ds.`transport`,ds.`biaya_plafon`,ds.`bahan` FROM aki_KK s LEFT JOIN aki_dkk ds ON s.`noKk`=ds.`noKk` WHERE s.`noKk` = '".$params["txtnoKk"]."'", $dbLink);
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

			$q3 = "UPDATE aki_kk SET `nama_cust`='".$namacust."',`jenis_id`='".$jenis_id."',`no_id`='".$no_id."',`no_phone`='".$no_phone."',`jabatan`='".$jabatan."',`nmasjid`='".$nmasjid."',`nproyek`='".$nproyek."',`alamat_proyek`='".$alamat_proyek."',`mproduksi`='".$mproduksi."',`mpemasangan`='".$mpemasangan."',`alamat`='".$alamat."',`provinsi`='".$provinsi."',`kota`='".$kota."' WHERE nokk='".$nokk."'";
			if (!mysql_query( $q3, $dbLink))
						throw new Exception('Gagal ubah data KK. ');
			$jumData = $params["jumAddJurnal"];
			$nomer =0;
			for ($j = 0; $j < $jumData ; $j++){
				if (!empty($params['chkEdit_'.$j])){
					$idKk = secureParam($params["chkEdit_" . $j], $dbLink);
                    $model = secureParam($params["txtModel_". $j],$dbLink);
					$jkubah = secureParam($params["txtKubah_". $j],$dbLink);
					$diameter = secureParam($params["txtD_". $j],$dbLink);
                    $tinggi = secureParam($params["txtT_". $j],$dbLink);
                    $dtengah = secureParam($params["txtDt_". $j],$dbLink);
                    $luas = secureParam($params["luas_". $j],$dbLink);
                    $plafon = secureParam($params["txtKel_". $j],$dbLink);
                    $harga1 = secureParam($params["txtHarga_" . $j], $dbLink);
                    $h = preg_replace("/\D/", "", $harga1);
                    $qty = secureParam($params["txtQty_" . $j], $dbLink);
                    $ketkubah = secureParam($params["txtKet_" . $j], $dbLink);
                    $bahan = secureParam($params["txtBahan_" . $j], $dbLink);
                    $filekubah = secureParam($params["filekubah_" . $j], $dbLink);
                    $filekaligrafi = secureParam($params["filekaligrafi_" . $j], $dbLink);
                    $q = "UPDATE aki_dkk SET `luas`='".$luas."',`nomer`='".$nomer."',`bahan`='".$bahan."',`kubah`='".$jkubah."',`model`='".$model."',`d`='".$diameter."',`t`='".$tinggi."',`dt`='".$dtengah."',`plafon`='".$plafon."',`jumlah`='".$qty."',`harga`='".$h1."',`ket`='".$ketkubah."'";
					$q.= " WHERE idKk='".$idKk."' ;";

					if (!mysql_query( $q, $dbLink))
						throw new Exception($q.'Gagal ubah data KK.');
                    $nomer++;
					
				}
			}
			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d h:i:sa");
			$ket = "`nomer`=".$params["txtnoKk"]."  -has change, ket : ".$tempNamecust.", ".$tempP.", ".$tempK.", ".$tempModel.", ".$tempD.", ".$tempT.", ".$tempDt.", ".$tempTrans.", ".$tempKet.", ".$tempLuas.", ".$tempJumlah.", ".$tempBiaya.", ".$tempHarga.", ".$tempHarga2.", ".$tempPlafon.", ".$tempBahan.", datetime: ".$tgl;
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception($q4.'Gagal ubah data KK. ');
			@mysql_query("COMMIT", $dbLink);
			$this->strResults="Sukses Ubah Data KK";
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
			$rsTemp=mysql_query("SELECT s.`nama_cust`,s.`provinsi`,s.`kota`,ds.`model`,ds.`d`,ds.`dt`,ds.`t`,ds.`luas`,ds.`plafon`,ds.`harga`,ds.`jumlah`,ds.`ket`,ds.`bahan` FROM aki_KK s LEFT JOIN aki_dkk ds ON s.`noKk`=ds.`noKk` WHERE s.`noKk` = '".$noKk."'", $dbLink);
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
			$tempKet  = $temp['ket'];
			$tempBahan  = $temp['bahan'];

			date_default_timezone_set("Asia/Jakarta");
			$tgl = date("Y-m-d h:i:sa");
			$ket = "`nomer`=".$noKk." -has delete, ket : ".$tempNamecust.", ".$tempP.", ".$tempK.", ".$tempModel.", ".$tempD.", ".$tempT.", ".$tempDt.", ".$tempKet.", ".$tempLuas.", ".$tempJumlah.", ".$tempHarga.", ".$tempPlafon.", ".$tempBahan.", datetime: ".$tgl;
			$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
			$q4.= "('".$pembatal."','".$tgl."','".$ket."');";
			if (!mysql_query( $q4, $dbLink))
						throw new Exception($q4.'Gagal ubah data KK. ');

			$q = "DELETE FROM aki_kk ";
			$q.= "WHERE (noKk)='".$noKk."';";

			if (!mysql_query( $q, $dbLink))
				throw new Exception('Gagal hapus data KK.');

			$q2 = "DELETE FROM aki_dkk ";
			$q2.= "WHERE (noKk)='".$noKk."';";

			if (!mysql_query( $q2, $dbLink))
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

}
?>