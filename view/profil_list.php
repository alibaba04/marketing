<?php
//=======  : Alibaba
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/profil_list";

//Periksa hak user pada modul/menu ini
$judulMenu = 'Data Profil ';
$hakUser = getUserPrivilege($curPage);
if ($hakUser < 10) {
    unset($_SESSION['my']);
    echo "<p class='error'>";
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
    echo "</p>";
}

//Periksa apakah merupakan proses headerless (tambah, edit atau hapus) dan apakah hak user cukup
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" && $hakUser == 90) {

    require_once("./class/c_profil.php");
    $tmpProfil = new c_profil();

    //Jika Mode Tambah/Add
    if ($_POST["txtMode"] == "Add") {
        $pesan = $tmpProfil->add($_POST);
    }

    //Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "Edit") {
        $pesan = $tmpProfil->edit($_POST);
    }

    //Jika Mode Hapus/Delete
    if ($_GET["txtMode"] == "Delete") {
        $pesan = $tmpProfil->delete($_GET["kodeProfil"]);
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
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<section class="content-header">
    <h1>
        DATA PROFIL
        <small>List Profil</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pengaturan</li>
        <li class="active">Data Profil</li>
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
                    <h3 class="box-title">Pencarian Profil </h3>
                </div>


                <!-- /.box-header -->
                <div class="box-body">
                    <form name="frmCariPerkiraan" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="page" value="<?php echo $curPage; ?>">

                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="namaPerusahaan" id="namaPerusahaan" placeholder="Nama..."
                            <?php
                            if (isset($_GET["namaPerusahaan"])) {
                                echo("value='" . $_GET["namaPerusahaan"] . "'");
                            }
                            ?>
                                   onKeyPress="return handleEnter(this, event)">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-info btn-flat">Go!</button>
                            </span>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php
                        if ($hakUser==90){
                            $q_profil = mysql_query("SELECT id FROM aki_tabel_profil", $dbLink);
                            if (mysql_num_rows($q_profil)>0){


                    ?>
                    
                    <?php
                            }else{

                    ?>
                    <a href="<?php echo $_SERVER['PHP_SELF']."?page=html/profil_detail&mode=add";?>"><button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Tambah Data</button></a>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.Left col -->
        <!-- right col -->
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
                if (isset($_GET["namaPerusahaan"])){
                    $namaPerusahaan = secureParam($_GET["namaPerusahaan"], $dbLink);
                }else{
                    $namaPerusahaan = "";
                }

                //Set Filter berdasarkan query string
                $filter="";
                if ($namaPerusahaan)
                    $filter = $filter . " AND nama_perusahaan LIKE '%" . $namaPerusahaan . "%'";

                //database
                $q = "SELECT id, nama_perusahaan, gedung, jalan, kelurahan, kecamatan, kota,  propinsi, negara, telepon, fax, email, website ";
                $q.= "FROM aki_tabel_profil ";
                $q.= "WHERE 1=1 " . $filter;
                
                //Paging
                //$rs = new MySQLPagedResultSet($q, $recordPerPage, $dbLink);
                $rs = new MySQLPagedResultSet($q, 100, $dbLink);
                ?>
                <div class="box-header">
                    <i class="ion ion-clipboard"></i>
                    <ul class="pagination pagination-sm inline"><?php echo $rs->getPageNav($_SERVER['QUERY_STRING']) ?></ul>
                </div>

                <div class="box-body">
                    <table class="table table-bordered table-striped table-hover" >
                        <thead>
                            <tr>
                                <th style="width: 25%">Nama</th>
                                <th style="width: 30%">Alamat</th>
                                <th style="width: 10%">Telepon</th>
                                <th style="width: 10%">Fax</th>
                                <th style="width: 10%">Email</th>
                                <th style="width: 15%">Website</th>
                                <th colspan="2" width="3%">Aksi</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $rowCounter=1;
                            while ($query_data = $rs->fetchArray()) {
                                echo "<tr>";
                                echo "<td>" . $query_data["nama_perusahaan"] . "</td>";
                                echo "<td>" . $query_data["gedung"] . " ". $query_data["jalan"]." ". $query_data["kelurahan"]." ". $query_data["kecamatan"]." ". $query_data["kota"]." ". $query_data["propinsi"]." ". $query_data["negara"]."</td>";
                                echo "<td>" . $query_data["telepon"] . "</td>";
                                echo "<td>" . $query_data["fax"] . "</td>";
                                echo "<td>" . $query_data["email"] . "</td>";
                                echo "<td>" . $query_data["website"] . "</td>";
                                
                                if ($hakUser == 90) {
                                    echo "<td><span class='label label-success' style='cursor:pointer;' onclick=location.href='" . $_SERVER['PHP_SELF'] . "?page=view/profil_detail&mode=edit&kode=" . md5($query_data["id"]) . "'><i class='fa fa-edit'></i>&nbsp;Ubah</span></td>";
                                    //echo("<td><span class='label label-danger' onclick=\"if(confirm('Apakah anda yakin akan menghapus data Profil Kopkar" . $query_data["nama_perusahaan"] . " ?')){location.href='index2.php?page=" . $curPage . "&txtMode=Delete&kodePerusahaan=" . md5($query_data["id"]) . "'}\" style='cursor:pointer;'><i class='fa fa-trash'></i>&nbsp;Hapus</span></td>");
                                    
                                } else {
                                    echo("<td>&nbsp;</td>");
                                    echo("<td>&nbsp;</td>");
                                }
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
    <!-- /.row -->
</section>