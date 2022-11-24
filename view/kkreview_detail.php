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
$datacolor1 ='';
$datakcolor1 = '';
?>
<SCRIPT language="JavaScript" TYPE="text/javascript">
  $(document).ready(function () {
    //$("#myNoteAcc").modal({backdrop: 'static'});
    var link = window.location.href;
    $('#btnSend').click(function(){
      $('#txtNote').val($('#txtmNote').val());
      sendnotif('Send');
    });
    $('#btnApprove').click(function(){
      sendnotif('Approve');
    });
    $('#btnSubmit').click(function(){
      var password = $('#txtPass').val();
      $.post("function/ajax_function.php",{ fungsi: "cekpass", kodeUser:"alibaba",pass:password } ,function(data)
      {
        if(data=='yes') {
          location.href = 'http://localhost/marketing/index.php?page=view/kk_detail&mode=edit&noKK='+$('#txtnoKkEn').val();
        }else{
          toastr.error('Gagal !!!<br> Password Admin Salah . . . .')
          $("#txtPass").focus();
        }
      });
    });
  });
  function omodal() {
    $("#myNoteAcc").modal({backdrop: 'static'});
  }
  function modalpass() {
    $("#modal-pass").modal({backdrop: 'static'});
  }

  function accmodal() {
    if ($('#ktransport').val()!='' ) {
      if ($('#datadesain').val()!='' && $('#color1').val()!='' && $('#kcolor1').val()!='' ) {
        $("#myAcc").modal({backdrop: 'static'});
        $('#txtMode').val('approve');
      }else{
        alert('Data Desain Belum Lengkap!!');
      }
    }else{
      alert('Data Keterangan Transport Belum Lengkap!!');
    }
  }
</SCRIPT>
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
  $approvekk = '';
  $rsTemp = mysql_query($q, $dbLink);
  if ($dataSph = mysql_fetch_array($rsTemp)) {
    echo "<input type='hidden' name='txtnoKk' id='txtnoKk' value='" . $dataSph["noKk"] . "'>";
    echo "<input type='hidden' name='txtnoSph' id='txtnoSph' value='" . $dataSph["noSph"] . "'>";
    echo "<input type='hidden' name='txtnoKkEn' id='txtnoKkEn' value='" . $_GET["noKK"] . "'>";
    $txtnokk=$dataSph["noKk"];
    $filekubah=$dataSph["filekubah"];
    $filekaligrafi=$dataSph["filekaligrafi"];
    $approvekk = $dataSph["approve"];
    $hargaKubah = $dataSph["harga"];
  }
  if ($_GET["mode"] == "addNote") {
    echo "<input type='hidden' name='txtMode' id='txtMode' value='addNote'>";
  }
  echo "<input type='hidden' name='txtuser' id='txtuser' value='" . $_SESSION["my"]->privilege . "'>";
  ?>
  <section class="invoice">
    <div class="row">
      <div class="col-sm-6 invoice-col">
        <center>
          <?php 
          if ($filekubah!='') {
            echo '<img src="../uploads/'.$filekubah.'" alt="First slide" width="300" height="200">'; 
          }
          if ($filekaligrafi!='') {
            echo '<img src="../uploads/'.$filekaligrafi.'" alt="First slide" width="300" height="200">'; 
          }
          if ($filekubah=='' && $filekaligrafi=='') {
            echo '<img src="../uploads/blank.png" alt="First slide" width="300" height="200">'; 
          }
          echo '<input type="hidden" name="datadesain" id="datadesain" class="form-control" value="'.$filekubah.'">';
          ?>
        </center>
        <p class="lead"> </p>
      </div>
      <div class="col-sm-6 invoice-col">
        <h2 class="page-header" style="margin: 0;">
          <?php
            $q= "SELECT * FROM `aki_spk` WHERE 1 and MD5(nokk)='" . $noKk."'";
            $rsTemp = mysql_query($q, $dbLink);
            if ($dataspk = mysql_fetch_array($rsTemp)) {
              if ($dataspk["noproyek"]=='-') {
                echo '<button type="button" class="btn btn-default pull-right" id="btnEdit" style="margin-right: 5px;"';
                echo "onclick=location.href='" . $_SERVER['PHP_SELF'] . "?page=view/kk_detail&mode=edit&noKK=" . $noKk."'"; 
                echo '><i class="fa fa-pencil" ></i></button>';
              }else{
                echo '<button type="button" class="btn btn-default pull-right" id="btnEdit" style="margin-right: 5px;"';
                echo 'onclick=modalpass()><i class="fa fa-pencil" ></i></button>';
              }
            }else{
              echo '<button type="button" class="btn btn-default pull-right" id="btnEdit" style="margin-right: 5px;"';
                echo "onclick=location.href='" . $_SERVER['PHP_SELF'] . "?page=view/kk_detail&mode=edit&noKK=" . ($noKk)."'"; 
                echo '><i class="fa fa-pencil" ></i></button>';
            }
          ?>
          
          <b><i class="fa fa-globe"></i> <?php  echo $dataSph["noKk"]; ?></b>
          <small>No SPH: <?php  echo $dataSph["noSph"]; ?></small>
          <input type="hidden" name="txtNote" id="txtNote" class="form-control" value="" placeholder="Empty" >
        </h2>
        <h4>Proyek <?php if ( $dataSph["project_pemerintah"]=='1') {
         echo 'Pemerintah '.$dataSph["nproyek"];
         }else{
          echo $dataSph["nproyek"];
        }  
        $mproduksi=0;
        $mpemasangan=0;
        $q2="SELECT kk.mproduksi,kk.mpemasangan, dkk.*FROM aki_kk kk right join aki_dkk dkk on kk.noKk=dkk.noKk WHERE 1=1 and MD5(kk.noKk)='".$noKk."'";
        $rsTemp = mysql_query($q2, $dbLink);
        $i=1;
        while ($datadSph = mysql_fetch_array($rsTemp)) {
          $mproduksi=$datadSph['mproduksi'];
          $mpemasangan=$datadSph['mpemasangan'];
          $i++;
        }
        ?></h4>
        <h5><address><th>Alamat Masjid: </th><br><?php  echo $dataSph["alamat_proyek"]; ?></td><address></h5>
          <?php
            if ($dataSph["kproyek"]=='patas') {
              echo '<div class="col-lg-3"><button type="" class="btn btn-block btn-default btn-lg bg-yellow" disabled="">PATAS</button></div>';
            }
          ?>
        <div class="col-lg-4">
          <button type="button" class="btn btn-block btn-default btn-lg" disabled="">
            <?php
            if ($dataSph["project_pemerintah"]=='1') {
              echo "PPN";
            }else{
              echo "Non PPN";
            }
            ?>
          </button>
        </div>
        <p class="lead"> </p>
      </div>

    </div>
  </section>
  <section class="invoice">
    <div class="row">
      <div class="nav-tabs-custo">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#cust" data-toggle="tab">Customer</a></li>
          <li><a href="#spec" data-toggle="tab">Specification</a></li>
          <li><a href="#termin" data-toggle="tab">Termin</a></li>
          <div class="col-lg-6 pull-right">
            <?php 
            if ($hakUser > 60 ) {
              if ($dataSph["approve_koperational"] != '-' && $dataSph["approve_kpenjualan"] != '-') {
                echo '<button type="button" class="btn btn-success pull-right" id="btnaccSPK" style="margin-left: 5px;" '.$dsbl2.' onclick=location.href="'. $_SERVER['PHP_SELF'].'?page=html/spk_detail&mode=add&noKK='. md5($dataSph["noKk"]).'"><i class="fa fa-send" ></i> SPK </button>';
              }else if($dataSph["approve_koperational"] == '-' && $dataSph["approve_kpenjualan"] != '-'){
                echo '<button type="button" class="btn btn-success pull-right" id="btnaccKK" onclick="accmodal()"><i class="fa fa-thumbs-up"></i> Approved</button>';
              }else{
                echo '<button type="button" class="btn btn-success pull-right" id="btnaccKK" onclick="accmodal()"><i class="fa fa-thumbs-up"></i> Approve KK</button>';
              }
            }?>
            <button type="button" class="btn btn-primary pull-right" id="btnNote" onclick="omodal()" style="margin-right: 5px;"><i class="fa fa-pencil-square-o" ></i> Note
            </button>
            <button type="button" class="btn btn-default pull-left" id="btnNote" onclick=location.href=location.href="<?php echo 'pdf/pdf_kk.php?&noKK='.$_GET["noKK"];?>"><i class="fa fa-download"></i> Download
            </button>
          </div>
        </ul>
        <div class="tab-content">
          <div class="tab-pane" id="termin">
            <div class="table-responsive" style="margin: 1%;">
              <table class="table">
                <tr>
                  <th>Termin</th>
                  <th>Waktu Pembayaran</th>
                  <th>Persentase</th>
                  <th style="text-align: right;">Nilai (Rp)</th>
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
          <div class="active tab-pane" id="cust">
            <ul class="timeline timeline-inverse" style="margin: 1%;">
              <li>
                <i class="fa fa-user bg-aqua"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> </span>
                  <h5 class="timeline-header">Nama </h5>
                  <div class="timeline-body"><?php  echo $dataSph["nama_cust"].' ('.$dataSph["jabatan"].')'; ?>
                  </div>
                </div>
              </li>
              <li>
                <i class="fa fa-id-card-o bg-aqua"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i></span>
                  <h5 class="timeline-header">No Identitas </h5>
                  <div class="timeline-body"><?php  echo $dataSph["no_id"].' ('.$dataSph["jenis_id"].')'; ?>
                  </div>
                </div>
              </li>
              <li>
                <i class="fa fa-phone bg-aqua"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i></span>
                  <h5 class="timeline-header">No HP </h5>
                  <div class="timeline-body">
                    <?php  echo $dataSph["no_phone"]; ?>
                  </div>
                </div>
              </li>
              <li>
                <i class="fa fa-map-marker bg-aqua"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i></span>
                  <h5 class="timeline-header">Alamat Customer </h5>
                  <div class="timeline-body">
                    <?php  echo $dataSph["alamat"].' '.$dataSph["kn"].', '.$dataSph["pn"]; ?>
                  </div>
                </div>
              </li>
              <li>
                <i class="fa fa-clock-o bg-gray"></i>
              </li>
            </ul>
          </div>
          <div class="tab-pane" id="spec">
            <?php
                $q4="SELECT dkk.* FROM aki_dkk dkk WHERE 1=1 and MD5(dkk.noKk)='".$noKk."' group by dkk.nomer order by dkk.nomer asc";
                $rsTemp3 = mysql_query($q4, $dbLink);
                while ($detail = mysql_fetch_array($rsTemp3)) {
                  ?>
                  <ul class="timeline timeline-inverse" style="margin: 1%;">
                    <li style="margin-bottom: 0;">
                      <i class="fa fa-dot-circle-o bg-aqua"></i>
                      <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> </span>
                        <h5 class="timeline-header">Proyek <?php  echo $detail["nomer"]+1; ?> </h5>
                        <div class="timeline-body"><h4><b>Kubah <?php echo $detail["bahan"].', '.strtoupper($detail["model"]); ?>
                        </b></h4></div>
                        <div class="timeline-body"><h4>Diameter <b><?php  echo $detail["d"]; ?> meter,</b> Tinggi <b><?php  echo $detail["t"]; ?> meter,</b> Luas <b><?php  echo $detail["luas"]; ?> meter<sup>2</sup></b></h4>
                        </div>
                        <div class="timeline-body" style="margin-left: 1.5%;">
                          <?php  
                          $qq2 = "SELECT * FROM aki_kkrangka WHERE `aktif`=1 and MD5(noKK)='".$noKk."' and nomer='".$detail["nomer"]."' order by idRangka asc";
                          $rsq2 = mysql_query($qq2, $dbLink);
                          while (  $hasildr1 = mysql_fetch_array($rsq2)) {
                            echo chr(12).'  '.$hasildr1['rangka'].'<br>';
                          }
                          ?></div>
                        <div class="timeline-body"><h4><b><?php  echo 'Rp '.number_format($detail['ntransport']).' ('.$detail['ktransport'].')'; ?></h4></b>
                        </div>
                        <div class="timeline-body"><table class="table">
                          <tr>
                            <th><center>Warna</center></th>
                            <th><center>Kode</center></th>
                          </tr>
                          <?php 
                            $qq3 = "SELECT * FROM `aki_kkcolor` WHERE MD5(noKK)='".$noKk."'";
                            $rsq3 = mysql_query($qq3, $dbLink);
                            while (  $hasilcol = mysql_fetch_array($rsq3)) {
                              echo '<tr><td style="text-align: center;">'.$hasilcol['color'];
                              echo '<td style="text-align: center;">'.$hasilcol['kcolor'].'</tr>';
                              $datacolor1 =$hasilcol['color'];
                              $datakcolor1 = $hasilcol['kcolor'];
                            }
                          
                          echo '<input type="hidden" name="color1" id="color1" value="'.$datacolor1.'">';
                          echo '<input type="hidden" name="kcolor1" id="kcolor1" value="'.$datakcolor1.'">';
                          ?></table>
                        </div>
                        <div class="timeline-body"><h4>Masa Produksi <b><?php  echo $mproduksi; ?> hari,</b> Masa Pemasangan <b><?php  echo $mpemasangan; ?> hari</b></h4></div>
                      </div>
                    </li>
                    <li>
                      <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                  </ul>
                  <?php
                }
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>
    <div class="clearfix"></div>
    
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
        </div>
      </div>
  <div class="modal fade" id="myNoteAcc" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="box box-success direct-chat direct-chat-success">
          <div class="box-header with-border">
            <h3 class="box-title">Direct Chat</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool " data-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="direct-chat-messages">
              <?php
              $q3= "SELECT * FROM `aki_report` WHERE ket LIKE 'KK Note, nokk=%".$txtnokk."%'";
              $rsTemp3 = mysql_query($q3, $dbLink);
              $txtket = '';
              while ($dataSph3 = mysql_fetch_array($rsTemp3)) {
                $ket = explode("=",$dataSph3["ket"]);
                $ket = explode(",",$ket[2]);
                $txtket = $dataSph3["ket"];
                $date=date_create($dataSph3["datetime"]);
                if ($dataSph3["kodeUser"] == $_SESSION["my"]->id) {
                  echo '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'.$dataSph3["kodeUser"];
                  echo '</span><span class="direct-chat-timestamp pull-left">'.date_format($date,"H:i d-m-Y").'</span></div>';
                  echo '<img src="dist/img/'.$_SESSION["my"]->avatar.'" class="direct-chat-img" alt="User Image"><div class="direct-chat-text">'.$ket[0].'</div></div>';
                }else{
                  echo '<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'.$dataSph3["kodeUser"];
                  echo '</span><span class="direct-chat-timestamp pull-right">'.date_format($date,"H:i d-m-Y").'</span></div>';
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
          </div>
          <div class="box-footer">
            <div class="input-group">
              <input type="text" name="message" placeholder="Type Message ..." class="form-control" id="txtmNote">
              <span class="input-group-btn">
                <button type="Submit" class="btn btn-success btn-flat" id="btnSend">Send</button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-pass">
    <div class="modal-dialog">
      <div class="modal-content bg-secondary">
        <div class="modal-header">
          <h4 class="modal-title">KK sudah menjadi SPK, Input password (Admin) untuk edit</h4>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <div class="input-group-addon">
              <label class="control-label" for="txtTglTransaksi">Password</label>
            </div>
            <input type="password" name="txtPass" id="txtPass" class="form-control">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btnSubmit">Enter</button>
        </div>
      </div>
    </div>
  </div>
</form>
<div class="clearfix"></div>
