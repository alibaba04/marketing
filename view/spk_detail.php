<?php
//=======  : Alibaba
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/spk_detail";

//Periksa hak user pada modul/menu ini
$judulMenu = 'SPK';
$hakUser = getUserPrivilege($curPage);

if ($hakUser < 10) {
    session_unregister("my");
    echo "<p class='error'>";
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
    echo "</p>";
}

//Periksa apakah merupakan proses headerless (tambah, edit atau hapus) dan apakah hak user cukup
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" ) {

    require_once("./class/c_spk.php");
    $tmpspk = new c_spk;
    if ($_POST["txtMode"] == "Add") {
        $pesan = $tmpspk->addspk($_POST);
    }

    if (strtoupper(substr($pesan, 0, 5)) == "GAGAL") {
        global $mailSupport;
        $pesan.="Warning!!, please text to " . $mailSupport . " for support this error!.";
    }
    header("Location:index.php?page=view/spk_list&pesan=" . $pesan);
    exit;
}
?>
</script>
<SCRIPT language="JavaScript" TYPE="text/javascript">
$(document).ready(function () {
    var link = window.location.href;

    var res = link.match(/mode=edit/g);
    if (res != 'mode=edit') {
        if (link.match(/noKK=/g)) {
            $("#mySpkn").modal({backdrop: 'static'});
            $nokk= link.split('noKK=');
            $("#txtnokk").val($nokk[1]);
        }else{
            $("#mySpk").modal({backdrop: 'static'});
            $("#createspk").click(function(){ 
                if ($("#nokk").val()!='') {
                    $("#nokk").focus();
                    location.href="?page=view/kkreview_detail&mode=addNote&noKK=" + ($("#nokk").val());
                }
            });
        }
    }
});
</SCRIPT>
<form action="index2.php?page=view/spk_detail" method="post" name="frmSiswaDetail" onSubmit="return validasiForm(this);" autocomplete="off" enctype="multipart/form-data"> 
    <input type='hidden' name='txtMode' value='Add'>
    <div class="modal fade" id="mySpk" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">No KK</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <?php  
                        $q = 'SELECT noKK FROM `aki_kk` WHERE aktif=1 and approve =1 and noKK not in(SELECT noKK FROM `aki_tabel_proyek`) ORDER BY idkk desc';
                        $sql_sph = mysql_query($q,$dbLink);
                        ?>
                        <select class="form-control select2" name="nokk" id="nokk" style="width: 100%">
                            <?php
                            $selected = "";
                            echo '<option value="">No KK</option>';
                            while($rs_sph = mysql_fetch_assoc($sql_sph)){ 
                                echo '<option value="'.md5($rs_sph['noKK']).'">'.$rs_sph['noKK'].'</option>';
                            }  
                            ?>
                        </select>   
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary pull-right" id="createspk"><i class="fa fa-plus"></i> Create</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mySpkn" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">SPK </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="txtnokk" id="txtnokk" value="">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <label class="control-label" for="txtKodeTransaksi">Proyek</label>
                        </div>
                        <select class="form-control select2" name="statuskk" id="statuskk">
                            <option value="Pembuatan Kubah Baru">Pembuatan Kubah Baru</option>
                            <option value="Pelapisan Kubah">Pelapisan Kubah</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary pull-right" id="createspkn"><i class="fa fa-plus"></i> Create</button>
            </div>
        </div>
    </div>
    </div>
</form>