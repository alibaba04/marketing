<?php
//=======  : Alibaba
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/sph_list";

//Periksa hak user pada modul/menu ini
$judulMenu = 'Surat Penawaran Harga';
$hakUser = getUserPrivilege($curPage);

if ($hakUser < 10) {
    session_unregister("my");
    echo "<p class='error'>";
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
    echo "</p>";
}

//Periksa apakah merupakan proses headerless (tambah, edit atau hapus) dan apakah hak user cukup
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" && $hakUser == 90) {

    require_once("./class/c_sph.php");
    $tmpsph = new c_sph;

//Jika Mode Tambah/Add
    if ($_POST["txtMode"] == "Add") {
        $pesan = $tmpsph->add($_POST);
    }

//Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "Edit") {
        $pesan = $tmpsph->edit($_POST);
    }

//Jika Mode Hapus/Delete
    if ($_GET["txtMode"] == "Delete") {
        $pesan = $tmpsph->delete($_GET["kode"]);
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
<!-- Include script date di bawah jika ada field tanggal -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/js/jquery-ui.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" charset="utf-8">

    $(function () {
        $('#tglTransaksi').daterangepicker({ 
            locale: { format: 'DD-MM-YYYY' } });
    //$('#tglTransaksi').val('00-00-0000');
    /*for (var i = 1; i >= 12; i++) {
        $("#btn_"+i).click(function(){ 
            var link =window.location.href.split("&noSph");
            location.href=link[0]+'&noSph=='+i;
        });
    }*/
    $("#btn_sm").click(function(){ 
        var link =window.location.href;
        var res = link.match(/&month=/g);
        if (res == '&month=') {
            link = link.split('&month=');
            location.href=link[0]+'&month='+$("#cbom").val();
        }else{
            location.href=window.location.href+'&month='+$("#cbom").val();
        }
        
    });
    
});

</script>

<!-- End of Script Tanggal -->
<section class="content-header">
    <h1>
        SURAT PENAWARAN HARGA
        <small>List SPH</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Input</li>
        <li class="active">SPH</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <section class="col-lg-6">
            <!-- TO DO List -->
            <div class="box box-primary">
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Search SPH </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form name="frmCariJurnalMasuk" method="GET" action="<?php echo $_SERVER['REQUEST_URI']; ?>"autocomplete="off">
                        <input type="hidden" name="page" value="<?php echo $curPage; ?>">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="noSph" id="noSph" placeholder="Cari . . . ."
                            <?php
                            if (isset($_GET["noSph"])) {
                                echo("value='" . $_GET["noSph"] . "'");
                            }
                            ?>
                            onKeyPress="return handleEnter(this, event)" data-toggle="tooltip" data-placement="bottom" title="Gunakan '=' untuk data per user">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    
                    <?php
                    if ($hakUser==90 or $hakUser==80){
                        if ($_SESSION["my"]->privilege!='SALES' && $_SESSION["my"]->privilege!='kpenjualan') {
                            ?>
                            <div class="btn-group pull-right">
                                <div class="btn-group dropright">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-plus"></i>&nbsp SPH
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                        <li><a href="<?php echo $_SERVER['PHP_SELF']."?page=html/sph_detail&mode=add";?>"><i class="fa fa-bars"></i>KUBAH</a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo $_SERVER['PHP_SELF']."?page=html/sphkaligrafi_detail&mode=add";?>"><i class="fa fa-bars"></i>KALIGRAFI</a></li>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }else{
                        ?>
                            <a href="<?php echo $_SERVER['PHP_SELF']."?page=html/sph_detail&mode=add";?>"><button type="button" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add SPH</button></a>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- /.box -->
        </section>
        <section class="col-lg-6">
            <?php
            //informasi hasil input/update Sukses atau Gagal
            if (isset($_GET["pesan"]) != "") {
                ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-warning"></i>
                        <h3 class="box-title">Pesan</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        if (substr($_GET["pesan"],0,5) == "Gagal") { 
                            echo '<div class="callout callout-danger">';
                        }else{
                            echo '<div class="callout callout-success">';
                        }
                        if ($_GET["pesan"] != "") {

                            echo $_GET["pesan"];
                        }
                        echo '</div>';
                        ?>
                    </div>
                </div>
            <?php } ?>
        </section>
        <!-- /.right col -->
        <section class="col-lg-12 connectedSortable">
            <div class="box box-primary">
                <?php
                $filter="";$filter3="";$noSph="";
                if(isset($_GET["month"])){

                    $_SESSION["month"] = $_GET["month"];
                }
                if ($_SERVER['REQUEST_URI']=='/marketing/index.php?page=view/sph_list') {
                    unset($_SESSION["month"]);
                }
                if (isset($_SESSION["month"])) {
                    $filter = $filter . " and month(s.tanggal)='" . secureParam($_SESSION["month"], $dbLink) . "' ";
                    $filter3 = $filter3 . " and month(s1.tanggal)='" . secureParam($_SESSION["month"], $dbLink) . "' ";
                }
                
                if(isset($_GET["noSph"]) ){
                    $noSph = secureParam($_GET["noSph"], $dbLink);
                    if ($noSph)
                        if (strpos($noSph, '=') !== FALSE) {
                            $filter = $filter . " AND s.kodeUser LIKE '%" . substr($noSph,1) . "%'";
                            $filter3 = $filter3 . " AND s1.kodeUser LIKE '%" . substr($noSph,1) . "%'";
                        }else{
                            $filter = $filter . " AND (k.name LIKE '%" . $noSph . "%' or p.name LIKE '%" . $noSph . "%'  or s.nama_cust LIKE '%" . $noSph . "%'  or s.noSph LIKE '%" . $noSph . "%'  or s.affiliate LIKE '%" . $noSph . "%')";
                            $filter3 = $filter3 . " AND (k1.name LIKE '%" . $noSph . "%'or p1.name LIKE '%" . $noSph . "%'  or s1.nama_cust LIKE '%" . $noSph . "%'  or s1.noSph LIKE '%" . $noSph . "%'  or s1.affiliate LIKE '%" . $noSph . "%')";
                        }
                }

                $filter2 = '';
                $filter4 = '';
                if ($_SESSION['my']->privilege == 'SALES') {
                    $filter2 =  " AND s.kodeUser='".$_SESSION['my']->id."' ";
                    $filter4 =  " AND s1.kodeUser='".$_SESSION['my']->id."' ";
                }
            //database
                $q = "SELECT s.*,ds.bahan,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.jumlah,ds.ket,ds.transport,u.kodeUser,u.nama,p.name as pn,k.name as kn ";
                $q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
                $q.= "WHERE 1=1 " .$filter2. $filter."group by s.noSph Union All" ;
                $q.= " SELECT s1.*,'Kaligrafi' as bahan,'Kaligrafi' as model,ds1.d,ds1.t,'-' as dt,'-' as plafon,ds1.harga,'-' as harga2,'-' as jumlah,'-' as ket,'-' as transport, u1.kodeUser, u1.nama, p1.name as pn, k1.name as kn ";
                $q.= "FROM aki_sph s1 right join aki_dkaligrafi ds1 on s1.noSph=ds1.noSph left join aki_user u1 on s1.kodeUser=u1.kodeUser left join provinsi p1 on s1.provinsi=p1.id LEFT join kota k1 on s1.kota=k1.id ";
                $q.= "WHERE 1=1 " .$filter4. $filter3."group by s1.noSph" ;
                $q.= " ORDER BY idSph desc ";
            //Paging
                $rs = new MySQLPagedResultSet($q, 50, $dbLink);
                ?>
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <ul class="pagination pagination-sm inline"><?php 
                    $monthName='';
                    if (isset($_SESSION["month"])) {
                        $dateObj   = DateTime::createFromFormat('!m', secureParam($_SESSION["month"], $dbLink));
                        $monthName = $dateObj->format('F'); 
                    }
                    
                    echo $monthName.' - '.$rs->getPageNav($_SERVER['QUERY_STRING']) ?></ul>
                    <?php
                    if ($_SESSION['my']->privilege == 'DM') {
                        /*echo '<a href="class/c_exportexcel.php?"><button class="btn btn-info pull-right"><i class="ion ion-ios-download"></i> Export Excel</button></a>';*/
                        echo '<div class="input-group input-group-sm col-lg-2 pull-right"><select name="cbom" id="cbom" class="form-control select2">';
                        for ($i = 0; $i < 12; ) {
                            $month_val = date('m', strtotime($i." months"));
                            $month_str = date('F', strtotime($i++." months"));
                            echo '<option value="'.$month_val.'">'.$month_str.'</option>';
                        } 
                        echo '</select><span class="input-group-btn"><button class="btn btn-primary dropdown-toggle" type="button" id="btn_sm"><i class="fa fa-fw fa-search" ></i>Month</button></span></div>';
                    }
                    ?>
                </div>

                <div class="box-body" style="width: 100%;overflow-x: scroll;">
                    <table class="table table-bordered table-striped table-hover" >
                        <thead>
                            <tr>
                                <th width="3%">Action</th>
                                <th style="width: 17%">No SPH</th>
                                <th style="width: 5%">Date</th>
                                <th style="width: 15%">Client</th>
                                <th style="width: 20%">Address</th>
                                <th style="width: 28%">Information</th>
                                <th style="width: 15%">Operator</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rowCounter=1;
                            while ($query_data = $rs->fetchArray()) {
                                echo "<tr>";
                                if (empty($query_data["keterangan_kk"])) {
                                    if($hakUser == 90){
                                        if ($_SESSION["my"]->id == $query_data["kodeUser"] || $_SESSION["my"]->privilege == "GODMODE"|| $_SESSION["my"]->privilege == "ADMIN") {
                                            echo '<td><div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fa fa-fw fa-angle-double-down"></i></button>
                                            <ul class="dropdown-menu" style="border-color:#000;">';
                                            if ($query_data["model"]=='Kaligrafi') {
                                            echo "<li><a style='cursor:pointer;' onclick=location.href='" . $_SERVER['PHP_SELF'] . "?page=view/sphkaligrafi_detail&mode=edit&noSph=" . md5($query_data["noSph"]) . "'><i class='fa fa-edit'></i>&nbsp;Edit</a></li>";
                                                $pdf = 'pdf_kaligrafi.php';
                                            }else{
                                            echo "<li><a style='cursor:pointer;' onclick=location.href='" . $_SERVER['PHP_SELF'] . "?page=view/sph_detail&mode=edit&noSph=" . md5($query_data["noSph"]) . "'><i class='fa fa-edit'></i>&nbsp;Edit</a></li>";
                                                $pdf = 'pdf_sph.php';
                                            }
                                            if ($_SESSION["my"]->privilege == "GODMODE"|| $_SESSION["my"]->privilege == "ADMIN") {
                                                echo "<li><a onclick=\"if(confirm('Apakah anda yakin akan menghapus data SPH ?')){location.href='index2.php?page=" . $curPage . "&txtMode=Delete&kode=" . ($query_data["noSph"]) . "'}\" style='cursor:pointer;'><i class='fa fa-trash'></i>&nbsp;Delete</a></li>";
                                            }
                                            echo "</ul></div></td>";
                                        }else{
                                            echo '<td><div class="dropdown">
                                            <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fa fa-fw fa-exclamation"></i></button>
                                            <ul class="dropdown-menu" style="border-color:#000;">';
                                            echo "<li><a ><i class='fa fa-fw fa-remove'></i></i>Akun Tidak Punya Akses Edit</a></li>";
                                            echo "</ul></div></td>";
                                        }
                                    } else{
                                        echo '<td><div class="dropdown">
                                        <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-fw fa-exclamation"></i></button>
                                        <ul class="dropdown-menu" style="border-color:#000;">';
                                        echo "<li><a><i class='fa fa-fw fa-remove'></i>Akun Tidak Punya Akses</a></li>";
                                        echo "</ul></div></td>";
                                    }

                                } else {
                                    echo '<td><div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-fw fa-check"></i></button>
                                        <ul class="dropdown-menu" style="border-color:#000;">';
                                        echo "<li>&nbsp&nbsp<i class='fa fa-fw fa-money'></i>SPH Sudah Jadi KK&nbsp</li>";
                                        echo "</ul></div></td>";
                                }
                                $pdf = '';
                                if ($query_data["model"]=='Kaligrafi') {
                                    $pdf = 'pdf_kaligrafi.php';
                                }else{
                                    $pdf = 'pdf_sph.php';
                                }
                                $colorr='btn-info';
                                if (strtoupper($query_data["model"])=='KALIGRAFI') {
                                    $colorr='btn-primary';
                                }
                                echo "<td><a onclick=\"if(confirm('Download data SPH ?')){location.href='pdf/".$pdf."?&noSph=" . md5($query_data["noSph"]) . "'}\" style='cursor:pointer;'>
                                <button type='button' class='btn btn-block ".$colorr."'>".($query_data["noSph"])."</button></a></td>";
                                echo "<td><button style='pointer-events: none;' class='btn btn-block btn-default'>" . tgl_ind($query_data["tanggal"]) . "</button></td>";
                                echo "<td><b>" . ($query_data["nama_cust"]) . "</b> <div class='pull-right'>";
                                if ($query_data["bahan"]==0 || $query_data["bahan"]==4 || $query_data["bahan"]==5) {
                                    if ($query_data["harga"]>=100000000 || $query_data["harga2"]>=100000000) {
                                        echo "<i class='bi bi-coin'></i><i class='fa fa-fw fa-money' style='color:Tomato;'></i></div>";
                                    }
                                }else if($query_data["bahan"]==1){
                                    if ($query_data["harga"]>=100000000) {
                                        echo "<i class='fa fa-fw fa-money' style='color:Tomato;'></i></div>";
                                    }
                                }else if($query_data["bahan"]==2 || $query_data["bahan"]==3 || $query_data["bahan"]==6){
                                    if ($query_data["harga2"]>=100000000) {
                                        echo "<i class='fa fa-fw fa-money' style='color:Tomato;'></i></div>";
                                    }
                                }
                                
                                echo "</td><td>" . $query_data["kn"] . ", ". $query_data["pn"] ."</td>";
                                $kel = '';
                                if ($query_data["plafon"] == 0){
                                    $kel = 'Full';
                                }else if ($query_data["plafon"] == 1){
                                    $kel = 'Tanpa Plafon';
                                }else{
                                    $kel = 'Waterproof';
                                }
                                $dt = '';
                                if ($query_data["dt"] != 0){
                                    $dt = ', DT : '.$query_data["dt"];
                                }
                                $spek = '<b>'.$query_data["masjid"].'</b>, MODEL : '.strtoupper($query_data["model"]).', D: '.$query_data["d"].', T : '.$query_data["t"].$dt.', '.strtoupper($kel);
                                echo "<td>" . $spek ."</td>";
                                echo "<td><center>" . strtoupper($query_data["nama"]) ." <br>(".$query_data["affiliate"].")</center></td>";
                                echo("</tr>");
                                $rowCounter++;
                            }
                            if (!$rs->getNumPages()) {
                                echo("<tr class='even'>");
                                echo ("<td colspan='10' align='center'>Maaf, data tidak ditemukan</td>");
                                echo("</tr>");
                            }
                            ?>
                        </tbody>
                    </table>
                </div> 
            </div>
        </section>
    </div>
</section>