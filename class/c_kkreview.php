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
				$token='';
				if ($_SESSION["my"]->privilege == 'ADMIN') {
					$readby = 'kpenjualan';
					$rsTemp=mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where g.kodeGroup='kpenjualan'", $dbLink);
					$token  = $temp['token'];
				$temp = mysql_fetch_array($rsTemp);
				}elseif($_SESSION["my"]->privilege == 'kpenjualan'){
					$readby = 'ADMIN';
					$rsTemp=mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where g.kodeGroup='ADMIN'", $dbLink);
					$token  = $temp['token'];
				}else{
					$readby = 'ADMIN';
					$rsTemp=mysql_query("SELECT s.*,g.kodeGroup FROM `aki_user` s left join aki_usergroup g on s.kodeUser=g.kodeUser where g.kodeGroup='GODMODE'", $dbLink);
					$token  = $temp['token'];
				}
				$ket = "KK Note, nokk=".$nokk.", note=".$treport.", read by ".$readby."=1";
				$q4 = "INSERT INTO `aki_report`( `kodeUser`, `datetime`, `ket`) VALUES";
				$q4.= "('".$pembuat."','".$tgl."','".$ket."');";
				if (!mysql_query( $q4, $dbLink))
							throw new Exception('Gagal note KK. ');

				@mysql_query("COMMIT", $dbLink);
				
				$destination = 0; 
				$q = "SELECT phone FROM `aki_user` as auser left join aki_usergroup agroup on auser.kodeUser=agroup.kodeuser where agroup.kodeGroup='".$readby."'";
				$result=mysql_query($q, $dbLink);

				if($dataMenu=mysql_fetch_row($result))
				{
					$destination = $dataMenu[0]; 
				}

				//API send web push
				$url ="https://fcm.googleapis.com/fcm/send";
				$fields=array(
					"to"=>$token,
					"notification"=>array(
						"body"=>'$message',
						"title"=>'test',
						"click_action"=>"https://sikubah.com/marketing"
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
				if ($result->success != 0) {
					$this->strResults="Sukses Note";
				}else{
					$this->strResults=$result->description;
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
							throw new Exception('Gagal approve KK. ');

				@mysql_query("COMMIT", $dbLink);
				$destination = 0; 
				$q = "SELECT phone FROM `aki_user` as auser left join aki_usergroup agroup on auser.kodeUser=agroup.kodeuser where agroup.kodeGroup='ADMIN'";
				$result=mysql_query($q, $dbLink);

				if($dataMenu=mysql_fetch_row($result))
				{
					$destination = $dataMenu[0]; 
				}
				//API send WA
				$my_apikey = "ZDMMOCURFXUCNH8EEK36"; 
				
				$message = "SIKUBAH - Message from ".$_SESSION["my"]->privilege." Please Check 'Kontrak Kerja'. Number KK : '".$nokk."', Note : 'Has Been Approved' https://bit.ly/2SpMdIo"; 
				$api_url = "http://panel.rapiwha.com/send_message.php"; 
				$api_url .= "?apikey=". urlencode ($my_apikey); 
				$api_url .= "&number=". urlencode ($destination); 
				$api_url .= "&text=". urlencode ($message); 
				$my_result_object = json_decode(file_get_contents($api_url, false)); 
				if ($my_result_object->success != 0) {
					$this->strResults="Sukses Approve";
				}else{
					$this->strResults=$my_result_object->description;
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
}
?>
