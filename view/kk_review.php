<?php
//=======  : Alibaba
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/kk_review";

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
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" && $hakUser == 90) {

    require_once("./class/c_kk.php");
    $tmpkk = new c_kk;

//Jika Mode Tambah/Add
    if ($_POST["txtMode"] == "Add") {
        $pesan = $tmpkk->add($_POST);
    }

//Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "Edit") {
        $pesan = $tmpkk->edit($_POST);
    }

//Jika Mode Hapus/Delete
    if ($_GET["txtMode"] == "Delete") {
        $pesan = $tmpkk->delete($_GET["kode"]);
    }

//Seharusnya semua transaksi Add dan Edit Sukses karena data sudah tervalidasi dengan javascript di form detail.
//Jika masih ada masalah, berarti ada exception/masalah yang belum teridentifikasi dan harus segera diperbaiki!
    if (strtoupper(substr($pesan, 0, 5)) == "GAGAL") {
        global $mailSupport;
        $pesan.="Gagal simpan data, mohon hubungi " . $mailSupport . " untuk keterangan lebih lanjut terkait masalah ini.";
    }
    header("Location:index.php?page=$curPage&pesan=" . $pesan);
    exit;
}
?>

</script>

<!-- Main content -->
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Review KK
    <small>#007612</small>
  </h1>
  
</section>

<div class="pad margin no-print">
  <div class="callout callout-info" style="margin-bottom: 0!important;">
    <h4><i class="fa fa-info"></i> Note:</h4>
    This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
  </div>
</div>
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
$rsTemp = mysql_query($q, $dbLink);
if ($dataSph = mysql_fetch_array($rsTemp)) {

echo "<input type='hidden' name='noKk' value='" . $dataSph["noKk"] . "'>";
}
?>
<!-- Main content -->
<section class="invoice">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <i class="fa fa-globe"></i> <?php  echo $dataSph["noKk"]; ?>
        <small class="pull-right">Date: <?php  echo $dataSph["tanggal"]; ?></small>
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
        <strong><?php  echo $dataSph["nama"]; ?></strong><br>
        <?php  echo $dataSph["no_id"].' ('.$dataSph["jenis_id"].')'; ?><br>
        <?php  echo $dataSph["alamat"].' '.$dataSph["kn"].', '.$dataSph["pn"]; ?><br>
        <?php  echo $dataSph["no_phone"]; ?><br>
        <?php  echo $dataSph["jabatan"]; ?>
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      <b>Proyek #007612</b><br>
      <br>
      <b>Nama Masjid:</b> <?php  echo $dataSph["nproyek"]; ?><br>
      <b>Alamat Proyek:</b> <?php  echo $dataSph["alamat_proyek"]; ?><br>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- Table row -->
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>Jumlah</th>
          <th>Jenis Pekerjaan</th>
          <th>Bahan</th>
          <th>Ukuran</th>
          <th>Masa Produksi</th>
          <th>Masa Pemasangan</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><?php  echo $dataSph["jumlah"]; ?></td>
          <td><?php  echo $dataSph["kubah"]; ?></td>
          <td><?php  echo $dataSph["bahan"]; ?></td>
          <td><?php  echo 'Diameter '.$dataSph["d"].' m, Tinggi '. $dataSph["t"].' m, Luas '.$dataSph["luas"].' m<sup>2</sup>'; ?></td>
          <td><?php  echo $dataSph["mproduksi"].' Hari'; ?></td>
          <td><?php  echo $dataSph["mpemasangan"].' Hari'; ?></td>
        </tr>
        </tbody>
      </table>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  <div class="row">
    <!-- accepted payments column -->
    <div class="col-xs-6">
      <p class="lead">Spesifikasi:</p>       
      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">     

        <?php  
            $rangka='';
            if ($hasil['dt'] != 0){
              $rangka = cekrangka($hasil['dt']);
            }else{
                $rangka = cekrangka($hasil['d']);
            }
        echo $dataSph["nama"]; ?><br>
        <?php  echo $dataSph["no_id"].' ('.$dataSph["jenis_id"].')'; ?><br>
        <?php  echo $dataSph["alamat"].' '.$dataSph["kn"].', '.$dataSph["pn"]; ?><br>
        <?php  echo $dataSph["no_phone"]; ?><br>
        <?php  echo $dataSph["jabatan"]; ?></p>

      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
      </p>
    </div>
    <!-- /.col -->
    <div class="col-xs-6">
      <p class="lead">Amount Due 2/22/2014</p>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Termin</th>
              <th>Waktu Pembayaran</th>
              <th>Persentase</th>
              <th>Nilai (Rp)</th>
            </tr>
          </thead>
          <tr>
            <td >1</td>
            <td style="width:50%">$250.30</td>
            <td >10%</td>
            <td style="text-align: right;">2342</td>
          </tr>
        </table>
      </div>
    </div>
    <!-- /.col -->
  </div>

  <div class="row">
    <!-- accepted payments column -->
    <div class="col-xs-6">
      <p class="lead">Payment Methods:</p>
      <img src="../../dist/img/credit/visa.png" alt="Visa">
      <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
      <img src="../../dist/img/credit/american-express.png" alt="American Express">
      <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
        dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
      </p>
    </div>
    <!-- /.col -->
    <div class="col-xs-6">
      <p class="lead">Amount Due 2/22/2014</p>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Termin</th>
              <th>Waktu Pembayaran</th>
              <th>Persentase</th>
              <th>Nilai (Rp)</th>
            </tr>
          </thead>
          <tr>
            <td >1</td>
            <td style="width:50%">$250.30</td>
            <td >10%</td>
            <td style="text-align: right;">2342</td>
          </tr>
        </table>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- this row will not appear when printing -->
  <div class="row no-print">
    <div class="col-xs-12">
      <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
      <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
      </button>
      <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
        <i class="fa fa-download"></i> Generate PDF
      </button>
    </div>
  </div>
</section>
<!-- /.content -->
<div class="clearfix"></div>
