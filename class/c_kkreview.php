<?php
/*==================================================
//=======  : Alibaba
====================================================*/
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined( 'validSession' ) or die( 'Restricted access' ); 

class c_kkreview
{
	var $strResults="";
	
	function addnote(&$params){
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
		$nokk = secureParam($params["txtnoKk"],$dbLink);
        $treport = secureParam($params["txtNote"],$dbLink);
        $pembuat = $_SESSION["my"]->id;
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
				date_default_timezone_set("Asia/Jakarta");
				$tgl = date("Y-m-d h:i:sa");
				$readby = '';
				if ($_SESSION["my"]->privilege == 'ADMIN') {
					$readby = 'kpenjualan';
				}else{
					$readby = 'ADMIN';
				}
				$ket = "KK Note, nokk=".$nokk.", note=".$treport.", read by ".$readby."=1";
				$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
				$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
				if (!mysql_query( $q4, $dbLink))
							throw new Exception($q4.'Gagal ubah data KK. ');

				@mysql_query("COMMIT", $dbLink);
				$this->strResults="Sukses Note";
			
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

	function approve(&$params) {
		global $dbLink;
		require_once './function/fungsi_formatdate.php';
		$nokk = secureParam($params["txtnoKk"],$dbLink);
        $pembuat = $_SESSION["my"]->id;
		try
		{
			$result = @mysql_query('SET AUTOCOMMIT=0', $dbLink);
			$result = @mysql_query('BEGIN', $dbLink);
			if (!$result) {
				throw new Exception('Could not begin transaction');
			}
				date_default_timezone_set("Asia/Jakarta");
				$tgl = date("Y-m-d h:i:sa");
				$ket = "KK Approve, nokk=".$nokk;

				$q3 = "UPDATE aki_kk SET `approve`='1',`approve_by`='".$pembuat."',`approve_tgl`='".$tgl."'  WHERE noKk='".$nokk."'";
				if (!mysql_query( $q3, $dbLink))
					throw new Exception('Gagal ubah data KK. ');

				$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
				$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
				if (!mysql_query( $q4, $dbLink))
							throw new Exception($q4.'Gagal ubah data KK. ');

				@mysql_query("COMMIT", $dbLink);
				$this->strResults="Sukses Approve";
			
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
}
?>
