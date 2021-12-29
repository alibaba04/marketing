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
    var link = window.location.protocol;
    var res = link.match(/mode=edit/g);
    if (res != 'mode=edit') {
        if (link.match(/noSph=/g)) {
            $("#mySph").modal('hide');
        }else{
            $("#mySph").modal({backdrop: 'static'});
            $("#createspk").click(function(){ 
                if ($("#nokk").val()!='') {
                    $("#nokk").focus();
                    location.href=link + "?page=view/kkreview_detail&mode=addNote&noKK=" + ($("#nokk").val());
                }
            });
        }
    }
});

</SCRIPT>

<div class="modal fade" id="mySph" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">No KK</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?php  
                    $q = 'SELECT noKK FROM `aki_kk` WHERE aktif=1 and approve =1 ORDER BY idkk desc';
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
