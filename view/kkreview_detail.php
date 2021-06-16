<?php
//=======  : Alibaba
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/kkreview_detail";

//Periksa hak user pada modul/menu ini
$judulMenu = 'Review Kontrak Kerja';
$hakUser = getUserPrivilege($curPage);

if ($hakUser < 10) {
    session_unregister("my");
    echo "<p class='error'>";
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
    echo "</p>";
}

//Periksa apakah merupakan proses headerless (tambah, edit atau hapus) dan apakah hak user cukup
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" ) {

    require_once("./class/c_kkreview.php");
    $tmpkk = new c_kkreview;

//Jika Mode Tambah/Add Note
    if ($_POST["txtMode"] == "addNote") {
        $pesan = $tmpkk->addnote($_POST);
    }

//Jika Mode Approve
    if ($hakUser == 90) {
      if ($_POST["txtMode"] == "approve") {
        $pesan = $tmpkk->approve($_POST);
      }
    }
    
//Seharusnya semua transaksi Add dan Edit Sukses karena data sudah tervalidasi dengan javascript di form detail.
//Jika masih ada masalah, berarti ada exception/masalah yang belum teridentifikasi dan harus segera diperbaiki!
    if (strtoupper(substr($pesan, 0, 5)) == "GAGAL") {
        global $mailSupport;
        $pesan.="Warning!!, please text to " . $mailSupport . " for support this error!.";
    }
    header("Location:index.php?page=view/kk_list&pesan=" . $pesan);
    exit;
}
?>
</script>
<SCRIPT language="JavaScript" TYPE="text/javascript">
$(document).ready(function () {
    $("#myNoteAcc").modal({backdrop: 'static'});
});
function omodal() {
  $("#myNoteAcc").modal({backdrop: 'static'});
    $('#btnSend').click(function(){
        var link = window.location.href;
        $('#txtNote').val($('#txtmNote').val());
        location.href=link+"&note="+ $("#txtNote").val();
    });
}
function accmodal() {
  $("#myAcc").modal({backdrop: 'static'});
    $('#btnApprove').click(function(){
        $('#txtMode').val('approve');
    });
}
</SCRIPT>
<!-- Main content -->
<!-- Content Header (Page header) -->

<form action="index2.php?page=view/kkreview_detail" method="post" name="frmSiswaDetail" onSubmit="return validasiForm(this);" autocomplete="off">
<?php
if (isset($_GET["noKK"])){
    $noKk = secureParam($_GET["noKK"], $dbLink);
    
}else{
    $noKk = "";
}
$q = "SELECT ROW_NUMBER() OVER(PARTITION BY dkk.model ORDER BY kk.idKk) AS id,kk.*, dkk.*,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK ";
$q.= "FROM aki_kk kk right join aki_dkk dkk on kk.noKk=dkk.noKk left join aki_user u on kk.kodeUser=u.kodeUser left join provinsi p on kk.provinsi=p.id LEFT join kota k on kk.kota=k.id ";
$q.= "WHERE 1=1 and MD5(kk.noKk)='" . $noKk."'";
$q.= " ORDER BY kk.noKk desc ";
$txtnokk='';

$rsTemp = mysql_query($q, $dbLink);
if ($dataSph = mysql_fetch_array($rsTemp)) {
echo "<input type='hidden' name='txtnoKk' value='" . $dataSph["noKk"] . "'>";
$txtnokk=$dataSph["noKk"];
$filekubah=$dataSph["filekubah"];
$filekaligrafi=$dataSph["filekaligrafi"];
}

if ($_GET["mode"] == "addNote") {
  echo "<input type='hidden' name='txtMode' id='txtMode' value='addNote'>";
}
?>
<section class="content-header">
  <h1>
    Preview KK
    <small>Date: <?php  echo $dataSph["tanggal"]; ?></small>
  </h1>
  
</section>
<!-- Main content -->
<section class="invoice">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <i class="fa fa-globe"></i> <?php  echo $dataSph["noKk"]; ?>
        <small>No SPH: <?php  echo $dataSph["noSph"]; ?></small>
          <input type="hidden" name="txtNote" id="txtNote" class="form-control" value="" placeholder="Empty" >
      </h2>
    </div>
    <!-- /.col -->
  </div>
  <!-- info row -->
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      <u>Pihak Pertama</u>
      <address>
        <strong>ANDIK NUR SETIAWAN</strong><br>
        3571020710760001 (KTP)<br>
        Ngadirejo Gg. I Buntu RT/RW 004/009 Kel/Desa Ngadirejo Kecamatan Kota  Kota Kediri, Jawa Timur.<br>
        Direktur PT. Anugerah Kubah Indonesia<br>
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      <u>Pihak Kedua</u>
      <address>
        <strong><?php  echo $dataSph["nama_cust"]; ?></strong><br>
        <?php  echo $dataSph["no_id"].' ('.$dataSph["jenis_id"].')'; ?><br>
        <?php  echo $dataSph["alamat"].' '.$dataSph["kn"].', '.$dataSph["pn"]; ?><br>
        <?php  echo $dataSph["no_phone"]; ?><br>
        <?php  echo $dataSph["jabatan"]; ?>
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      <b>Proyek <?php if ( $dataSph["project_pemerintah"]=='1') {
       echo 'Pemerintah '.$dataSph["nproyek"];
      }else{
        echo $dataSph["nproyek"];
      }  ?></b><br><br>
      <th>Alamat : </th><br><?php  echo $dataSph["alamat_proyek"]; ?></td>
    </div>
  </div>
  <!-- /.row -->

  <!-- Table row -->
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-striped">
        <thead>
        <tr>
          <th style='width:2%'>No</th>
          <th style='width:12%'>Jenis Pekerjaan</th>
          <th style='width:8%'>Bahan</th>
          <th style='width:8%'>Model</th>
          <th style='width:25%'>Ukuran</th>
          <th style='width:8%'>Plafon</th>
          <th style='width:8%'>Jumlah</th>
          <th colspan="3"><center>Harga Kubah</center></th>
        </tr>
        </thead>
        <tbody>
          <?php  
          $hargaKubah = 0;
          $q2="SELECT kk.mproduksi,kk.mpemasangan, dkk.*FROM aki_kk kk right join aki_dkk dkk on kk.noKk=dkk.noKk WHERE 1=1 and MD5(kk.noKk)='".$noKk."'";
          $rsTemp = mysql_query($q2, $dbLink);
            while ($dataSph = mysql_fetch_array($rsTemp)) {
              $plafon='';
              $bahan='';
              if ($dataSph["plafon"]=='0') {
                $plafon='Full';
              }else if($dataSph["plafon"]=='2'){
                $plafon='Tanpa Plafon';
              }else if($dataSph["plafon"]=='1'){
                $plafon='Waterproof';
              }
              if ($dataSph["bahan"]=='3') {
                $bahan='Titanium';
              }else if($dataSph["bahan"]=='2'){
                $bahan='Enamel';
              }else if($dataSph["bahan"]=='1'){
                $bahan='Galvalume';
              }
              echo "<tr><td>".($dataSph["nomer"]+1)."</td><td>".$dataSph["kubah"]."</td><td>".$bahan."</td><td>".ucfirst($dataSph["model"])."</td>";
              echo "<td>Diameter <b>".$dataSph["d"]." m</b>, Tinggi <b>". $dataSph["t"]." m</b>, Luas <b>".$dataSph["luas"]." m<sup>2</sup></b>"."</td>";
              echo "<td>".$plafon."</td><td><b>".$dataSph["jumlah"]."</b> Kubah</td><td style='width:10%;text-align:right;'>Rp </td><td style='text-align:right;width:10%'>".number_format($dataSph["harga"])."</td><td style='width:10%'></td><tr>";
              $hargaKubah += $dataSph["harga"];
            }
          ?>
        </tbody>
      </table>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  <div class="row">
    <!-- accepted payments column -->
    <div class="col-sm-6 invoice-col">
      <p class="lead">Spesifikasi:</p>       
      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;height: 300px; overflow: auto;">     

        <?php  
        $mproduksi=0;
        $mpemasangan=0;
        $q2="SELECT kk.mproduksi,kk.mpemasangan, dkk.*FROM aki_kk kk right join aki_dkk dkk on kk.noKk=dkk.noKk WHERE 1=1 and MD5(kk.noKk)='".$noKk."'";
        $rsTemp = mysql_query($q2, $dbLink);
        $i=1;
        while ($dataSph = mysql_fetch_array($rsTemp)) {
          $mproduksi=$dataSph['mproduksi'];
          $mpemasangan=$dataSph['mpemasangan'];
            $rangka='';
            if ($dataSph['dt'] != 0){
              $rangka = cekrangka($dataSph['dt']);
            }else{
                $rangka = cekrangka($dataSph['d']);
            }
            $rangkad='';
            if ($dataSph['d']>=6) {
              $rangkad=chr(149).'  Model Rangka <b>Double Frame (Kremona)</b><br>';
            }
            $bahan='';
            $Finishing='';
            if ($dataSph['bahan']>='Galvalume') {
              $Finishing=chr(149).'  Finishing coating Enamel dengan suhu 800-900<sup>o</sup> Celcius<br>';
              $bahan=chr(149).'  Bahan terbuat dari plat besi SPCC SD 0,9 - 1 mm (Spek Enamel Grade)<br>';
            }else{
              if ($dataSph['d']>=1 ) {
                $bahan=chr(149).'  Bahan terbuat dari plat Galvalume 0,4 - 0,5 mm<br>';
              }else{
                $bahan=chr(149).'  Bahan terbuat dari plat Galvalume 0,4 mm<br>';
              }
              $Finishing=chr(149).'  Finishing <b>Cat PU</b> dengan 2 komponen pengecatan :<br>&nbsp&nbsp&nbsp'.chr(45).'  Epoxy<br>&nbsp&nbsp&nbsp'.chr(45).'  Cat PU 2 Komponen <br>';
            }
            $plafon='';
            if ($dataSph['plafon']==0) {
              $plafon=chr(149).'  Plafon kalsiboard 3 mm motif <b>AWAN</b>.<br>'.chr(149).'  Kedap air menggunakan membran bakar 3 mm<br>';
            }else if($dataSph['plafon']==2){
              $plafon=chr(149).'  Kedap air menggunakan membran bakar 3 mm<br>';
            }
            $aksesoris='';
            if ($dataSph['d']>=5 ){
              $aksesoris=chr(149).'  Makara bahan galvalume bola full warna gold bentuk <b>Lafadz Allah</b><br>'.chr(149).'  Penangkal Petir (Panjang Kabel 25 m)<br>';
              if ($dataSph['d']>=6){
                $lampu='';
                if ($dataSph['d']>=15) {
                  $lampu='8';
                }else{
                  $lampu='4';
                }
                $aksesoris=$aksesoris.chr(149).'  Lampu Sorot '.$lampu.' Sisi (Panjang Kabel 5 m)<br>';
              }
            }else{
              $aksesoris=chr(149).'  Makara bahan galvalume warna gold bentuk <b>Lafadz Allah</b><br>';
            }
            echo "<br><u>Pekerjaan ".$i." : ".$dataSph['kubah']."</u><br><br> ";
            echo chr(149).'  Rangka utama pipa galvanis '.$rangka.'<br>'.$rangkad.chr(149).'  Hollow 1,5 x 3,5 cm tebal 0,7 mm<br>
            '.$bahan.$Finishing.$plafon.$aksesoris; 
            $i++;
            echo $dataSph["filekubah"];
        }
        ?><br>
    </div>
    <!-- /.col -->
    <div class="col-sm-6 invoice-col">
      <p class="lead">Desain </p>
      <center>
        <?php 
        if ($filekubah!='') {
          echo '<img src="../uploads/'.$filekubah.'" alt="First slide" width="300" height="200">'; 
        }
        if ($filekaligrafi!='') {
          echo '<img src="../uploads/'.$filekaligrafi.'" alt="First slide" width="300" height="200">'; 
        }
        ?>
      </center>
    </div>
    <div class="col-sm-6 invoice-col">
      <p class="lead"> </p>
        <!-- <table class="table">
            <tr>
              <th =><center>Warna</center></th>
              <th ><center>Kode</center></th>
            </tr>
            
              <?php 
                  $q2="SELECT * FROM `aki_kkcolor` WHERE 1=1 and MD5(noKk)='".$noKk."'";
                  $rsTemp2 = mysql_query($q2, $dbLink);
                  $i=1;
                  while ($dataSph2 = mysql_fetch_array($rsTemp2)) {
                    if ($dataSph2['color1']!='-') {
                      echo '<tr><td style="text-align: center;">'.$dataSph2['color1'];
                      echo '<td style="text-align: center;">'.$dataSph2['kcolor1'].'</tr>';
                    }
                    if ($dataSph2['color2']!='-') {
                      echo '<tr><td style="text-align: center;">'.$dataSph2['color2'];
                      echo '<td style="text-align: center;">'.$dataSph2['kcolor2'].'</tr>';
                    }
                    if ($dataSph2['color3']!='-') {
                      echo '<tr><td style="text-align: center;">'.$dataSph2['color3'];
                      echo '<td style="text-align: center;">'.$dataSph2['kcolor3'].'</tr>';
                    }
                    if ($dataSph2['color4']!='-') {
                      echo '<tr><td style="text-align: center;">'.$dataSph2['color4'];
                      echo '<td style="text-align: center;">'.$dataSph2['kcolor4'].'</tr>';
                    }
                    if ($dataSph2['color5']!='-') {
                      echo '<tr><td style="text-align: center;">'.$dataSph2['color5'];
                      echo '<td style="text-align: center;">'.$dataSph2['kcolor5'].'</tr>';
                    }
                  }
              ?>
            
        </table> -->
        <table class="table">
            <tr>
              <th colspan="2"><center>Masa Produksi</center></th>
              <th colspan="2"><center>Masa Pemasangan</center></th>
            </tr>
            <tr>
              <td style="text-align: right;"><?php  echo $mproduksi; ?></td>
              <td >Hari</td>
              <td style="text-align: right;"><?php  echo $mpemasangan; ?></td>
              <td >Hari</td>
            </tr>
        </table>
        
    </div>
    <!-- /.col -->
  </div>

  <div class="row">
    <!-- accepted payments column -->
    <div class="col-sm-6 invoice-col">
      <p class="lead">Payment Method </p>
      <img src="dist/img/credit/bca.png" alt="BCA">&nbsp&nbsp&nbsp
      <img src="dist/img/credit/bnisyariah.png" alt="BNI Syariah">&nbsp&nbsp&nbsp
      <img src="dist/img/credit/mandiri.png" alt="Mandiri">&nbsp&nbsp&nbsp
      <img src="dist/img/credit/bri.png" alt="BRI">&nbsp&nbsp&nbsp

    </div>
    <!-- /.col -->
    <div class="col-sm-6 invoice-col">
      <p class="lead"></p>

      <div class="table-responsive">
        <table class="table">
            <tr>
              <th>Termin</th>
              <th>Waktu Pembayaran</th>
              <th>Persentase</th>
              <th>Nilai (Rp)</th>
            </tr>
          <?php 
          $q1 = "SELECT * FROM `aki_dpembayaran` WHERE MD5(noKk)='" . $noKk."'";
          $rsTemp1 = mysql_query($q1, $dbLink);
          $dataSph1 = mysql_fetch_array($rsTemp1);
          $hp1 = ($hargaKubah*($dataSph1['persen1']/100));
          $hp2 = ($hargaKubah*($dataSph1['persen2']/100));
          $hp3 = ($hargaKubah*($dataSph1['persen3']/100));
          $hp4 = ($hargaKubah*($dataSph1['persen4']/100));
          ?>
          <tr>
            <td >1</td>
            <td style="width:50%"><?php  echo $dataSph1["wpembayaran1"]; ?></td>
            <td ><?php  echo $dataSph1["persen1"].' %'; ?></td>
            <td style="text-align: right;"><?php  echo number_format($hp1); ?></td>
          </tr>
          <tr>
            <td >2</td>
            <td style="width:50%"><?php  echo $dataSph1["wpembayaran2"]; ?></td>
            <td ><?php  echo $dataSph1["persen2"].' %'; ?></td>
            <td style="text-align: right;"><?php  echo number_format($hp2); ?></td>
          </tr>
          <tr>
            <td >3</td>
            <td style="width:50%"><?php  echo $dataSph1["wpembayaran3"]; ?></td>
            <td ><?php  echo $dataSph1["persen3"].' %'; ?></td>
            <td style="text-align: right;"><?php  echo number_format($hp3); ?></td>
          </tr>
          <tr>
            <td >4</td>
            <td style="width:50%"><?php  echo $dataSph1["wpembayaran4"]; ?></td>
            <td ><?php  echo $dataSph1["persen4"].' %'; ?></td>
            <td style="text-align: right;"><?php  echo number_format($hp4); ?></td>
          </tr>
          <tr style="background-color: #ddd">
            <td ></td>
            <td style="width:50%">Total</td>
            <td ><?php  echo $dataSph1["persen1"]+$dataSph1["persen2"]+$dataSph1["persen3"]+$dataSph1["persen4"].' %'; ?></td>
            <td style="text-align: right;"><b><?php  echo number_format($hp1+$hp2+$hp3+$hp4); ?></b></td>
          </tr>
        </table>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  <div class="modal fade" id="myNoteAcc" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="box box-success direct-chat direct-chat-success">
          <div class="box-header with-border">
            <h3 class="box-title">Direct Chat</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool " data-dismiss="modal"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">
                <!-- Message. Default to the left -->
                <?php
                $q3= "SELECT * FROM `aki_report` WHERE ket LIKE 'KK Note, nokk=%".$txtnokk."%'";
                $rsTemp3 = mysql_query($q3, $dbLink);
                $txtket = '';
                while ($dataSph3 = mysql_fetch_array($rsTemp3)) {
                  $ket = explode("=",$dataSph3["ket"]);
                  $ket = explode(",",$ket[2]);
                  $txtket = $dataSph3["ket"];
                  if ($dataSph3["kodeUser"] == $_SESSION["my"]->id) {
                    echo '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'.$dataSph3["kodeUser"];
                    echo '</span><span class="direct-chat-timestamp pull-left">'.$dataSph3["datetime"].'</span></div>';
                    echo '<img src="dist/img/'.$_SESSION["my"]->avt.'" class="direct-chat-img" alt="User Image"><div class="direct-chat-text">'.$ket[0].'</div></div>';
                  }else{
                    echo '<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'.$dataSph3["kodeUser"];
                    echo '</span><span class="direct-chat-timestamp pull-right">'.$dataSph3["datetime"].'</span></div>';
                    echo '<img src="dist/img/avt5.png" class="direct-chat-img" alt="User Image"><div class="direct-chat-text">'.$ket[0].'</div></div>';
                  }
                }
                date_default_timezone_set("Asia/Jakarta");
                $tgl = date("Y-m-d H:i:s");
                $txtket = explode($_SESSION["my"]->privilege."=",$txtket);
                $q11 = "UPDATE `aki_report` SET `datetime`='".$tgl."',`ket`='".$txtket[0].$_SESSION["my"]->privilege."=0' WHERE ket like '%".$txtnokk."%read by ".$_SESSION["my"]->privilege."=1%'";
                $result=mysql_query($q11 , $dbLink);
                ?>
              </div>
              <!--/.direct-chat-messages-->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." class="form-control" id="txtmNote">
                  <span class="input-group-btn">
                    <button type="Submit" class="btn btn-success btn-flat" id="btnSend">Send</button>
                  </span>
                </div>
            </div>
            <!-- /.box-footer-->
          </div>
      </div>
    </div>
  </div>
  <!-- /.modal -->
  <div class="modal" id="myAcc" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Approve KK </h4>
          </div>
          <div class="modal-body">
            <p>No KK <?php echo $txtnokk; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="Submit" class="btn btn-primary" id="btnApprove">Approve</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    
  <!-- this row will not appear when printing -->
  <div class="row no-print">
    <div class="col-xs-12">
      <?php 
        if ($hakUser > 60) {
          echo '<button type="button" class="btn btn-success pull-right" id="btnaccKK" onclick="accmodal()" ><i class="fa fa-credit-card"></i>Approve KK</button>';
        }
      ?>
      <button type="button" class="btn btn-primary pull-right" id="btnNote" onclick="omodal()" style="margin-right: 5px;"><i class="fa fa-credit-card" ></i> Note
      </button>
    </div>
  </div>
</section>
</form>
<!-- /.content -->
<div class="clearfix"></div>
