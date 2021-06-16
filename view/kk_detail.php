<?php
/* ==================================================
//=======  : Alibaba
==================================================== */
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/kk_detail";
//Periksa hak user pada modul/menu ini
$judulMenu = 'KK';
$hakUser = getUserPrivilege($curPage);
if ($hakUser != 90) {
    session_unregister("my");
    echo "<p class='error'>";
    die('User cannot access this page!');
    echo "</p>";
}
//Periksa apakah merupakan proses headerless (tambah, edit atau hapus) dan apakah hak user cukup
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" && $hakUser == 90) {
    require_once("./class/c_kk.php");
    $tmpkk = new c_kk;

//Jika Mode Tambah/Add
    if ($_POST["txtMode"] == "Add") {
        $folderUpload = "../uploads/";
        $nameimg= array();
        $files = $_FILES;
        $jumlahFile = count($files['listGambar']['name']);

        for ($i = 0; $i < $jumlahFile; $i++) {
            $namaFile = $files['listGambar']['name'][$i];
            $lokasiTmp = $files['listGambar']['tmp_name'][$i];
        }
        for ($i = 0; $i < $jumlahFile; $i++) {
            $namaFile = $files['listGambar']['name'][$i];
            $lokasiTmp = $files['listGambar']['tmp_name'][$i];

    # kita tambahkan uniqid() agar nama gambar bersifat unik
            $namaBaru = uniqid() . '-' . $namaFile;
            $lokasiBaru = "{$folderUpload}/{$namaBaru}";
            $prosesUpload = move_uploaded_file($lokasiTmp, $lokasiBaru);
            if ($namaFile != '') {
                array_push($nameimg,$namaBaru);
            }
        }
        $pesan = $tmpkk->addkk($_POST, $nameimg);
    }
//Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "Edit") {
        $folderUpload = "../uploads/";
        $nameimg= array();
        $files = $_FILES;
        $jumlahFile = count($files['listGambar']['name']);

        for ($i = 0; $i < $jumlahFile; $i++) {
            $namaFile = $files['listGambar']['name'][$i];
            $lokasiTmp = $files['listGambar']['tmp_name'][$i];
        }
        for ($i = 0; $i < $jumlahFile; $i++) {
            $namaFile = $files['listGambar']['name'][$i];
            $lokasiTmp = $files['listGambar']['tmp_name'][$i];

    # kita tambahkan uniqid() agar nama gambar bersifat unik
            $namaBaru = uniqid() . '-' . $namaFile;
            $lokasiBaru = "{$folderUpload}/{$namaBaru}";
            $prosesUpload = move_uploaded_file($lokasiTmp, $lokasiBaru);
            if ($namaFile != '') {
                array_push($nameimg,$namaBaru);
            }
        }
        $pesan = $tmpkk->edit($_POST, $nameimg);
    }
//Jika Mode Hapus/Delete
    if ($_GET["txtMode"] == "Delete") {
        $pesan = $tmpkk->delete($_GET["kodeTransaksi"]);
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
?><script>
    $(function () {
        $("[data-mask]").inputmask();
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>
<!-- Include script untuk function auto complete -->
<script type="text/javascript" src="js/autoCompletebox.js"></script>
<SCRIPT language="JavaScript" TYPE="text/javascript">
$(document).ready(function () {
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    if (res != 'mode=edit') {
        if (link.match(/noSph=/g)) {
            $("#mySph").modal('hide');
        }else{
            $("#mySph").modal({backdrop: 'static'});
            $("#createKk").click(function(){ 
                if ($("#snosph").val()!='') {
                    $("#snosph").focus();
                    location.href=link+"&noSph="+ $("#snosph").val();
                }
            });
        }
    }
    $("#chkppemerintah").click(function(){ 
        if ($('#chkppemerintah').is(":checked")) {
            $("#txtppemerintah").val(1);
        }else{
            $("#txtppemerintah").val(0);
        }
    });
    $("#color1").click(function(){ 
        $("#color1").val('');
    });
    $("#color2").click(function(){ 
        $("#color2").val('');
    });
    $("#color3").click(function(){ 
        $("#color3").val('');
    });
    $("#color4").click(function(){ 
        $("#color4").val('');
    });
    $("#color5").click(function(){ 
        $("#color5").val('');
    });
    $("#kcolor1").click(function(){ 
        $("#kcolor1").val('');
    });
    $("#kcolor2").click(function(){ 
        $("#kcolor2").val('');
    });
    $("#kcolor3").click(function(){ 
        $("#kcolor3").val('');
    });
    $("#kcolor4").click(function(){ 
        $("#kcolor4").val('');
    });
    $("#kcolor5").click(function(){ 
        $("#kcolor5").val('');
    });

});
function cmodal($param) {
    $("#myModal").modal({backdrop: 'static'});
    $('#txtnomer').val($param);
    $('#labelclr').html($param+1);
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    if (res == 'mode=edit') {
        $('#color1').val($("#color1_"+$param).val());
        $('#color2').val($("#color2_"+$param).val());
        $('#color3').val($("#color3_"+$param).val());
        $('#color4').val($("#color4_"+$param).val());
        $('#color5').val($("#color5_"+$param).val());

        $('#kcolor1').val($("#kcolor1_"+$param).val());
        $('#kcolor2').val($("#kcolor2_"+$param).val());
        $('#kcolor3').val($("#kcolor3_"+$param).val());
        $('#kcolor4').val($("#kcolor4_"+$param).val());
        $('#kcolor5').val($("#kcolor5_"+$param).val());
    }

    $('#btncolor').click(function(){
        $("#color1_"+$('#txtnomer').val()).val($('#color1').val());
        $("#color2_"+$('#txtnomer').val()).val($("#color2").val());
        $("#color3_"+$('#txtnomer').val()).val($('#color3').val());
        $("#color4_"+$('#txtnomer').val()).val($("#color4").val());
        $("#color5_"+$('#txtnomer').val()).val($('#color5').val());

        $("#kcolor1_"+$('#txtnomer').val()).val($('#kcolor1').val());
        $("#kcolor2_"+$('#txtnomer').val()).val($('#kcolor2').val());
        $("#kcolor3_"+$('#txtnomer').val()).val($('#kcolor3').val());
        $("#kcolor4_"+$('#txtnomer').val()).val($('#kcolor4').val());
        $("#kcolor5_"+$('#txtnomer').val()).val($('#kcolor5').val());
        $("#myModal").modal('hide');
    });
}
function chkadddetail(tcounter) {
    if ($("#chkAddJurnal_"+tcounter).val()==1) {
        $("#chkAddJurnal_"+tcounter).val(0);
    }else{
        $("#chkAddJurnal_"+tcounter).val(1);
    }
}

function omodal() {
    $("#myNote").modal({backdrop: 'static'});
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    if (res == 'mode=edit') {
        $('#mtxtW1').val($("#txtW1").val());
        $('#mtxtW2').val($("#txtW2").val());
        $('#mtxtW3').val($("#txtW3").val());
        $('#mtxtW4').val($("#txtW4").val());

        $('#mtxtP1').val($("#txtP1").val());
        $('#mtxtP2').val($("#txtP2").val());
        $('#mtxtP3').val($("#txtP3").val());
        $('#mtxtP4').val($("#txtP4").val());
    }
    $('#btnsimpan').click(function(){
        if($("#txtReport").val()== ''){
            alert('Description Cannot Empty!');
            $("#txtReport").focus();
            return false;
        }
        $("#treport").val($("#txtReport").val());
            $('#txtW1').val($("#mtxtW1").val());
            $('#txtW2').val($("#mtxtW2").val());
            $('#txtW3').val($("#mtxtW3").val());
            $('#txtW4').val($("#mtxtW4").val());

            $('#txtP1').val($("#mtxtP1").val());
            $('#txtP2').val($("#mtxtP2").val());
            $('#txtP3').val($("#mtxtP3").val());
            $('#txtP4').val($("#mtxtP4").val());
    });
}
function tnmasjid() {
    $("#txtnproyek").val('Masjid '+$("#txtnmasjid").val());
}
function addJurnal(){    
    tcounter = $("#jumAddJurnal").val();

    var ttable = document.getElementById("kendali");
    var trow = document.createElement("TR");
    trow.setAttribute("id", "trid_"+tcounter);

    //Kolom 1 Checkbox
    var td = document.createElement("TD");
    td.setAttribute("align","center");
    td.setAttribute('onclick','chkadddetail('+tcounter+');');
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input type="checkbox" class="minimal" name="chkAddJurnal_'+tcounter+'" id="chkAddJurnal_'+tcounter+'" value="1" checked /></div>';
    trow.appendChild(td);

    //Kolom 2 Kode Rekening

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><select class="form-control" name="txtKubah_'+tcounter+'" id="txtKubah_'+tcounter+'"><option value="Kubah Utama">Kubah Utama</option><option value="Anakan">Anakan</option><option value="Mahrab">Mahrab</option><option value="Menara">Menara</option></select></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><select class="form-control" name="txtModel_'+tcounter+'" id="txtModel_'+tcounter+'"><option value="setbola">Setengah Bola</option><option value="pinang">Pinang</option><option value="madinah">Madinah</option><option value="bawang">Bawang</option></select></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><select class="form-control" name="txtBahan_'+tcounter+'" id="txtBahan_'+tcounter+'"><option value="Enamel">Enamel</option><option value="Galvalume">Galvalume</option><option value="Titanium">Titanium</option></select></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><select class="form-control" name="txtPlafon_'+tcounter+'" id="txtPlafon_'+tcounter+'"><option value="0">Full</option><option value="1">Tanpa Plafon</option><option value="2">Waterproof</option></select></div>';
    trow.appendChild(td);

    //Kolom 5 qty
    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.setAttribute('onclick','adddetail('+tcounter+');');
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input onkeydown="return numbersonly(this, event);"  value="0" style="text-align:right" name="txtQty_'+tcounter+'" id="txtQty_'+tcounter+'" class="form-control" ></div>';
    trow.appendChild(td);

    //Kolom 6 d
    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtD_'+tcounter+'" id="txtD_'+tcounter+'" class="form-control" " onkeydown="return numbersonly(this, event);"  value="0" style="text-align:right"></div>';
    trow.appendChild(td);

    //Kolom 7 t
    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtT_'+tcounter+'" id="txtT_'+tcounter+'" class="form-control" " onkeydown="return numbersonly(this, event);" value="0" style="text-align:right"></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtDt_'+tcounter+'" id="txtDt_'+tcounter+'" class="form-control" " onkeydown="return numbersonly(this, event);" value="0" style="text-align:right"></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtHarga_'+tcounter+'" id="txtHarga_'+tcounter+'" class="form-control" " onkeydown="return numbersonly(this, event);" value="0" style="text-align:right"></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","center");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input type="button" class="btn btn-primary" value="select" onclick="cmodal(' + tcounter + ')"></div>';
    trow.appendChild(td);


    ttable.appendChild(trow);
    tcounter = $("#jumAddJurnal").val();
    $("#jumAddJurnal").val(parseInt($("#jumAddJurnal").val())+1);

    
}

function validasiForm(form)
{

    if(form.txtNoid.value=='' )
    {
        $("#myNote").modal('hide');
        alert("No ID cannot Empty!");
        form.txtNoid.focus();
        return false;
    }
    if(form.txtPhone.value=='' )
    {
        $("#myNote").modal('hide');
        alert("No Telephone cannot Empty!");
        form.txtPhone.focus();
        return false;
    }
    if(form.txtPosition.value=='' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        form.txtPosition.focus();
        return false;
    }
    if(form.txtalamat.value=='' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        form.txtalamat.focus();
        return false;
    }
    if(form.txtnmasjid.value=='' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        form.txtnmasjid.focus();
        return false;
    }
    if(form.txtalamatp.value=='' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        form.txtalamatp.focus();
        return false;
    }
    if(form.txtproduksi.value=='' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        form.txtproduksi.focus();
        return false;
    }
    if(form.txtPemasangan.value=='' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        form.txtPemasangan.focus();
        return false;
    }
    if(form.color1_0.value=='-' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        $("#myModal").modal({backdrop: 'static'});
        form.color1_0.focus();
        return false;
    }
    if(form.kcolor1_0.value=='-' )
    {
        $("#myNote").modal('hide');
        alert("Data cannot Empty!");
        $("#myModal").modal({backdrop: 'static'});
        form.kcolor1_0.focus();
        return false;
    }


return true;
}

</SCRIPT>

<section class="content-header">
    <h1>
        Kontrak Kerja
        <small>Detail KK</small>
    </h1>
</section>

<form action="index2.php?page=view/kk_detail" method="post" name="frmSiswaDetail" onSubmit="return validasiForm(this);" autocomplete="off" enctype="multipart/form-data"> 
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <section class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <?php

                        if ($_GET["mode"] == "edit") {
                            $noKk='';
                            echo '<h3 class="box-title">UBAH KK</h3>';
                            echo "<input type='hidden' name='txtMode' value='Edit'>";
                            if (isset($_GET["noKK"])){
                                $noKk = secureParam($_GET["noKK"], $dbLink);
                            }else{
                                $noKk = "";
                            }

                            $q = "SELECT ROW_NUMBER() OVER(PARTITION BY dkk.model ORDER BY kk.idKk) AS id,kk.*, dkk.*,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK,dp.wpembayaran1,dp.wpembayaran2,dp.wpembayaran3,dp.wpembayaran4,dp.persen1,dp.persen2,dp.persen3,dp.persen4 ";
                            $q.= "FROM aki_kk kk right join aki_dkk dkk on kk.noKk=dkk.noKk left join aki_user u on kk.kodeUser=u.kodeUser left join provinsi p on kk.provinsi=p.id LEFT join kota k on kk.kota=k.id left join aki_dpembayaran as dp on kk.noKk=dp.noKk ";
                            $q.= "WHERE 1=1 and MD5(kk.noKk)='" . $noKk."'";
                            $q.= " ORDER BY kk.noKk desc ";
                            $rsTemp = mysql_query($q, $dbLink);
                            if ($dataKk = mysql_fetch_array($rsTemp)) {

                                echo "<input type='hidden' name='noKk' value='" . $dataKk["noKk"] . "'>";
                            } else {
                                ?>
                                <script language="javascript">
                                    alert("Kode Tidak Valid ");
                                </script>
                                <?php
                            }
                        } else {
                            $noSph = "";
                            if (isset($_GET["noSph"])){
                                $noSph = secureParam($_GET["noSph"], $dbLink);
                            }
                            $q = "SELECT  ROW_NUMBER() OVER(PARTITION BY ds.model ORDER BY ds.idDsph) AS id,s.*,ds.biaya_plafon,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK ";
                            $q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
                            $q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph."'";
                            $q.= " ORDER BY s.noSph desc ";
                            $rsTemp = mysql_query($q, $dbLink);
                            if ($dataSph = mysql_fetch_array($rsTemp)) {
                                echo "<input type='hidden' name='noSph' value='" . $dataSph["noSph"] . "'>";
                            } 
                            $noKk = "";
                            $q = "SELECT * FROM aki_kk where idKk=( SELECT max(idKk) FROM aki_kk where aktif='1')";
                            $rsTemp = mysql_query($q, $dbLink);
                            $tglTransaksi = date("Y-m-d");
                            if ($kode_ = mysql_fetch_array($rsTemp)) {
                                $urut = "";
                                $tglTr = substr($tglTransaksi, 0,4);
                                $bulan = bulanRomawi(substr($tglTransaksi,5,2));
                                if ($kode_['noKk'] != ''){
                                    $urut = substr($kode_['noKk'],0, 3);
                                    $tahun = substr($kode_['noKk'],-4);
                                    $kode = $urut + 1;
                                    if (strlen($kode)==1) {
                                        $kode = '00'.$kode;
                                    }else if (strlen($kode)==2){
                                        $kode = '0'.$kode;
                                    }
                                    if ($tglTr != $tahun) {
                                        $kode = '001';
                                    }
                                    $noKk = $kode.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;

                                }else{
                                    $noKk = '001'.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                }
                            }
                            echo "<input type='hidden' name='txtMode'  value='Add'>";
                        }
                        ?>
                    </div>
                    <div class="box-body">
                        <div class="form-group" >
                            <div class="input-group">
                                <span class="input-group-addon">No</span>
                                <input name="txtnoKk" id="txtnoKk" maxlength="30" class="form-control" 
                                readonly value="<?php if($_GET["mode"]=='edit'){ echo $dataKk["noKk"]; }else{echo($noKk);}?>" placeholder="Nomor otomatis dibuat">
                            </div>
                        </div>
                        <div class="form-group">
                            <input name="txtnamacust" id="txtnamacust" class="form-control" 
                            value="<?php  if($_GET['mode']=='edit'){echo $dataKk["nama_cust"]; }else{ echo $dataSph["nama_cust"];}?>" placeholder="Client Name">
                            <input type='hidden' name="txtnomersph" id="txtnomersph" class="form-control" 
                            value="<?php if($_GET['mode']=='edit'){echo $dataKk["noSph"]; }else{ echo $dataSph["noSph"];}?>" placeholder="Client Name">
                            <input type="hidden" name="treport" id="treport" class="form-control" 
                            value="" placeholder="Empty" >
                        </div>
                        <div class="form-group" >
                            <div class="col-lg-2" style="padding-right: 0px;padding-left: 5px;">
                                <select name="cboJenisid" id="cboJenisid" class="form-control">
                                    <?php
                                    $selected = "";
                                    if ($dataKk['jenis_id'] == 'KTP') {
                                        $selected = " selected";
                                        echo '<option value="KTP"'.$selected.'>KTP</option>';
                                        echo '<option value="SIM">SIM</option>';
                                    }elseif ($dataKk['jenis_id']=="SIM") {
                                        $selected = " selected";
                                        echo '<option value="KTP">KTP</option>';
                                        echo '<option value="SIM"'.$selected.'>SIM</option>';
                                    }else{
                                        echo '<option value="KTP">KTP</option>';
                                        echo '<option value="SIM">SIM</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-10" style="padding-right: 0px;padding-left: 5px;">
                                <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" name="txtNoid" id="txtNoid" class="form-control" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["no_id"]; }?>"></div>
                        </div>
                        <label class="control-label" for="txtTglTransaksi">&nbsp;&nbsp;</label>
                        <div class="form-group" >
                            <div class="col-lg-6" style="padding-right: 0px;padding-left: 5px;">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtPhone" id="txtPhone" class="form-control" data-inputmask='"mask": "9999 9999 9999"' data-mask value="<?php  if($_GET['mode']=='edit'){echo $dataKk["no_phone"]; }?>"></div>
                            </div>
                            <div class="col-lg-6" style="padding-right: 0px;padding-left: 5px;">
                                <input type="text" name="txtPosition" id="txtPosition" class="form-control" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["jabatan"]; }?>" placeholder='Jabatan' >
                            </div>
                        </div>
                        <label class="control-label" for="txtTglTransaksi">&nbsp;&nbsp;</label>
                        <div class="form-group" >
                            <textarea class="form-control" rows="3" placeholder="Address ..." name="txtalamat" id="txtalamat" ><?php  if($_GET['mode']=='edit'){echo $dataKk["alamat"]; }?></textarea>
                        </div>
                        <div class="form-group">
                            <div class="" style="padding-bottom: 10px;padding-right: 0px;padding-left: 5px;">
                                <?php  
                                $q = 'SELECT provinsi.id as idP,provinsi.name as pname,kota.id as idK, kota.name as kname FROM provinsi left join kota on provinsi.id=kota.provinsi_id ORDER BY kota.name ASC';
                                $sql_provinsi = mysql_query($q,$dbLink);
                                ?>
                                <select class="form-control select2" name="provinsi" id="provinsi">
                                    <?php
                                    $selected = "";
                                    if ($_GET['mode'] == 'edit') {
                                        echo '<option value="'.$dataKk["idP"].'-'.$dataKk["idK"].'" selected>'.$dataKk["pn"].' - '.$dataKk["kn"].'</option>';
                                        while($rs_provinsi = mysql_fetch_assoc($sql_provinsi)){ 
                                            echo '<option value="'.$rs_provinsi['idP'].'-'.$rs_provinsi['idK'].'">'.$rs_provinsi['pname'].' - '.$rs_provinsi['kname'].'</option>';
                                        }  
                                    }else{
                                        echo '<option value="'.$dataSph["provinsi"].'-'.$dataSph["kota"].'" selected>'.$dataSph["pn"].' - '.$dataSph["kn"].'</option>';
                                        while($rs_provinsi = mysql_fetch_assoc($sql_provinsi)){ 
                                            echo '<option value="'.$rs_provinsi['idP'].'-'.$rs_provinsi['idK'].'">'.$rs_provinsi['pname'].' - '.$rs_provinsi['kname'].'</option>';
                                        }  
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $("#kota").change(function(){
                                                $("#provinsi").html('');
                                                var id_kota = $("#kota").val(); 
                                                var url = 'http://localhost/marketing/get_provinsi.php?id_kota=' + id_kota; 
                                                $.ajax({ url : url, 
                                                    type: 'GET', 
                                                    dataType : 'json', 
                                                    success : function(result){
                                                        for(var i = 0; i < result.length; i++) 
                                                            $("#provinsi").append('<option value="'+ result[i].id +'">' + result[i].name + '</option>'); 
                                                    } 
                                                });  
                                            });
                                        });
                                    </script>
                                </select>   
                            </div>
                        </div>
                    </div>
                </div>    
            </section>
            <section class="col-lg-6">
                <div class="box box-primary">
                        <label class="control-label" for="txtTglTransaksi">&nbsp;</label>
                        <label class="control-label" for="txtTglTransaksi">&nbsp;</label>
                        <div class="box-body">
                        <div class="form-group" >
                            <div class="input-group">
                                <span class="input-group-addon">Nama Masjid</span>
                                <input name="txtnmasjid" id="txtnmasjid" maxlength="30" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["nmasjid"]; }?>" onkeyup="tnmasjid()">
                            </div>
                        </div>
                        <div class="form-group" >
                            <div class="input-group">
                                <span class="input-group-addon">Nama Proyek</span>
                                <input name="txtnproyek" id="txtnproyek" maxlength="30" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["nproyek"]; }?>">
                             <input type="hidden" name="txtppemerintah" id="txtppemerintah" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["project_pemerintah"]; }else{echo '0';}?>">
                            </div>

                        </div>

                        <div class="form-group" >
                            <label class="control-label" for="txtKodeTransaksi">Alamat Proyek</label>
                            <textarea class="form-control" rows="3" placeholder="Enter ..." name="txtalamatp" id="txtalamatp"><?php  if($_GET['mode']=='edit'){echo $dataKk["alamat_proyek"]; }?></textarea>
                        </div>
                        <div class="form-group" >
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <label><input type="checkbox" id="chkppemerintah"<?php if($_GET['mode']=='edit'){if ($dataKk["project_pemerintah"]==1) {
                                            echo "checked";
                                        }} ?> >&nbsp;&nbsp;Project Pemerintah</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="exampleInputFile">File Desain </label>
                                    <input type="file" name="listGambar[]" accept="image/*" multiple>
                                </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-body">
                    <div class="form-group" >
                        <div class="col-lg-6">
                            <label class="control-label" for="txtKodeTransaksi">Masa Produksi</label>
                            <div class="input-group">
                                <input type="number" name="txtproduksi" id="txtproduksi" class="form-control"
                                value="<?php  if($_GET['mode']=='edit'){echo $dataKk["mproduksi"]; }?>" placeholder="0" ><span class="input-group-addon">Hari</span></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label" for="txtKodeTransaksi">Masa Pemasangan</label>
                            <div class="input-group">
                                <input type="number" name="txtPemasangan" id="txtPemasangan" class="form-control"
                                value="<?php  if($_GET['mode']=='edit'){echo $dataKk["mpemasangan"]; }?>" placeholder="0" ><span class="input-group-addon">Hari</span></div>
                                <!-- waktu pembayaran -->
                                <input type="hidden" name="txtW1" id="txtW1" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["wpembayaran1"]; }?>"/><input type="hidden" name="txtW2" id="txtW2" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["wpembayaran2"]; }?>"/><input type="hidden" name="txtW3" id="txtW3" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["wpembayaran3"]; }?>"/><input type="hidden" name="txtW4" id="txtW4" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["wpembayaran4"]; }?>"/><input type="hidden" name="txtP1" id="txtP1" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["persen1"]; }?>"/><input type="hidden" name="txtP2" id="txtP2" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["persen2"]; }?>"/><input type="hidden" name="txtP3" id="txtP3" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["persen3"]; }?>"/><input type="hidden" name="txtP4" id="txtP4" value="<?php  if($_GET['mode']=='edit'){echo $dataKk["persen4"]; }?>"/>
                        </div>
                    </div>
                    <label class="control-label" for="txtTglTransaksi">&nbsp;&nbsp;</label>
                    </div>
                </div>
            </section>

            <section class="col-lg-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">DETAILS</h3>
                        <span id="msgbox"> </span>
                    </div>
                    <div class="box-body">

                        <table class="table table-bordered table-striped table-hover"  >
                            <thead>
                                <tr>
                                   <th style="width: 2%"><i class='fa fa-edit'></i></th>
                                   <th style="width: 20%" colspan="4">Information</th>
                                   <th style="width: 3%">Quantity</th>
                                   <th style="width: 3%">D</th>
                                   <th style="width: 3%">T</th>
                                   <th style="width: 3%">Dt</th>
                                   <th style="width: 13%">Price&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                   <th style="width: 3%">Color</th>
                                </tr>
                            </thead>
                            <tbody id="kendali">

                                <?php
                                    $q='';
                                    if ($_GET['mode']=='edit'){
                                        $q = "SELECT kk.*,dp.*,kkc.* FROM aki_dkk kk left join aki_dpembayaran dp on kk.noKK=dp.noKk  left join aki_kkcolor as kkc on kk.noKK=kkc.noKk and kk.nomer=kkc.nomer ";
                                        $q.= "WHERE 1=1 and MD5(kk.nokk)='" . $noKk;
                                        $q.= "' ORDER BY  kk.nomer ";
                                    }else{
                                        $q = "SELECT idDsph AS 'idKk',ket as 'kubah',jumlah,d,t,dt,plafon,transport,luas,harga,harga2,harga3,bahan,biaya_plafon,model FROM aki_dsph ";
                                        $q.= "WHERE 1=1 and MD5(noSph)='" . $noSph;
                                        $q.= "' ORDER BY idDsph ";
                                    }
                                    $rsDetilJurnal = mysql_query($q, $dbLink);
                                    $iJurnal = 0;
                                    while ($DetilJurnal = mysql_fetch_array($rsDetilJurnal)) {
                                        $kel = '';
                                        echo '<div><tr id="trid_'.$iJurnal.'" >';
                                        echo '<td align="center" valign="top" ><div class="form-group">
                                        <input onclick="chkadddetail('.$iJurnal.')" type="checkbox" class="minimal" checked name="chkAddJurnal_' . $iJurnal . '" id="chkAddJurnal_' . $iJurnal . '" value="' . $DetilJurnal["idKk"] . '" /></div></td>';
                                        echo '<td align="center" valign="top" ><div class="form-group">
                                        <select class="form-control"  name="txtKubah_' . $iJurnal . '" id="txtKubah_' . $iJurnal . '">';
                                        if ($DetilJurnal["kubah"]=='Mahrab') {
                                            echo '<option value="Kubah Utama">Kubah Utama</option>';
                                            echo '<option value="Anakan">Anakan</option>';
                                            echo '<option value="Mahrab" selected>Mahrab</option>';
                                        }else if($DetilJurnal["kubah"]=='Anakan'){
                                            echo '<option value="Kubah Utama">Kubah Utama</option>';
                                            echo '<option value="Anakan" selected>Anakan</option>';
                                            echo '<option value="Mahrab">Mahrab</option>';
                                        }else {
                                            echo '<option value="Kubah Utama" selected>Kubah Utama</option>';
                                            echo '<option value="Anakan">Anakan</option>';
                                            echo '<option value="Mahrab">Mahrab</option>';
                                        }
                                        echo '</select></div></td><td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                        <select class="form-control"  name="txtModel_' . $iJurnal . '" id="txtModel_' . $iJurnal . '"> ';
                                        if ($DetilJurnal["model"]=='setbola') {
                                            echo '<option value="setbola" selected>Setengah Bola</option>';
                                            echo '<option value="pinang">Pinang</option>';
                                            echo '<option value="madinah">Madinah</option>';
                                            echo '<option value="bawang">Bawang</option>';
                                        }else if($DetilJurnal["model"]=='pinang'){
                                            echo '<option value="setbola" >Setengah Bola</option>';
                                            echo '<option value="pinang" selected>Pinang</option>';
                                            echo '<option value="madinah">Madinah</option>';
                                            echo '<option value="bawang">Bawang</option>';
                                        }else if($DetilJurnal["model"]=='madinah'){
                                            echo '<option value="setbola" >Setengah Bola</option>';
                                            echo '<option value="pinang">Pinang</option>';
                                            echo '<option value="madinah" selected>Madinah</option>';
                                            echo '<option value="bawang">Bawang</option>';
                                        }else {
                                            echo '<option value="setbola" >Setengah Bola</option>';
                                            echo '<option value="pinang">Pinang</option>';
                                            echo '<option value="madinah">Madinah</option>';
                                            echo '<option value="bawang" selected>Bawang</option>';
                                        }
                                        echo '</select></div></td><td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                        <select class="form-control"  name="txtBahan_' . $iJurnal . '" id="txtBahan_' . $iJurnal . '"> ';
                                        if ($DetilJurnal["bahan"]=='3') {
                                            echo '<option value="1">Galvalume</option>';
                                            echo '<option value="2">Enamel</option>';
                                            echo '<option value="3" selected>Titanium</option>';
                                        }else if($DetilJurnal["bahan"]=='2'){
                                            echo '<option value="1">Galvalume</option>';
                                            echo '<option value="2" selected>Enamel</option>';
                                            echo '<option value="3">Titanium</option>';
                                        }else if($DetilJurnal["bahan"]=='1'){
                                            echo '<option value="1" selected>Galvalume</option>';
                                            echo '<option value="2" >Enamel</option>';
                                            echo '<option value="3">Titanium</option>';
                                        }else {
                                            echo '<option value="1" >Galvalume</option>';
                                            echo '<option value="2" >Enamel</option>';
                                            echo '<option value="3">Titanium</option>';
                                        }
                                        echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                        <select class="form-control"  name="txtPlafon_' . $iJurnal . '" id="txtPlafon_' . $iJurnal . '"> ';
                                        if ($DetilJurnal["plafon"]=='0') {
                                            echo '<option value="0" selected>Full</option>';
                                            echo '<option value="1">Tanpa Plafon</option>';
                                            echo '<option value="2" >Waterproof</option>';
                                        }else if($DetilJurnal["plafon"]=='2'){
                                            echo '<option value="0" >Full</option>';
                                            echo '<option value="1">Tanpa Plafon</option>';
                                            echo '<option value="2" selected>Waterproof</option>';
                                        }else if($DetilJurnal["plafon"]=='1'){
                                            echo '<option value="0" >Full</option>';
                                            echo '<option value="1" selected>Tanpa Plafon</option>';
                                            echo '<option value="2" >Waterproof</option>';
                                        }
                                        echo '</select></div></td>';
                                        echo '<td align="center" valign="top" ><div class="form-group">
                                        <input type="number" class="form-control" name="txtQty_' . $iJurnal . '" id="txtQty_' . $iJurnal . '" value="' . $DetilJurnal["jumlah"] . '" ="" style="min-width: 35px;"></div></td>';
                                        echo '<td align="center" valign="top" ><div class="form-group">
                                        <input type="number" class="form-control"name="txtD_' . $iJurnal . '" id="txtD_' . $iJurnal . '" value="' . ($DetilJurnal["d"]) . '" ="" style="min-width: 45px;"></div></td>';
                                        echo '<td align="center" valign="top" ><div class="form-group">
                                        <input type="number" class="form-control"name="txtT_' . $iJurnal . '" id="txtT_' . $iJurnal . '" value="' . ($DetilJurnal["t"]) . '" ="" style="min-width: 45px;"></div></td>';
                                        echo '<td align="center" valign="top"><div class="form-group">
                                        <input type="number" class="form-control"name="txtDt_' . $iJurnal . '" id="txtDt_' . $iJurnal . '" value="' . ($DetilJurnal["dt"]) . '" ="" style="min-width: 45px;"></div></td>';
                                        $harga = 0;
                                        if ($_GET['mode']!='edit'){
                                            if($DetilJurnal["bahan"]=='1'){
                                                $harga= number_format($DetilJurnal["harga"]+$DetilJurnal["transport"]);
                                            }else if($DetilJurnal["bahan"]=='2'){
                                                $harga= number_format($DetilJurnal["harga2"]+$DetilJurnal["transport"]);
                                            }else{
                                                $harga= number_format($DetilJurnal["harga3"]+$DetilJurnal["transport"]);
                                            }
                                            echo '<input type="hidden" name="color1_' . $iJurnal . '" id="color1_' . $iJurnal . '" value="-"/><input type="hidden" name="color2_' . $iJurnal . '" id="color2_' . $iJurnal . '" value=""/><input type="hidden" name="color3_' . $iJurnal . '" id="color3_' . $iJurnal . '" value=""/><input type="hidden" name="color4_' . $iJurnal . '" id="color4_' . $iJurnal . '" value=""/><input type="hidden" name="color5_' . $iJurnal . '" id="color5_' . $iJurnal . '" value=""/><input type="hidden" name="kcolor1_' . $iJurnal . '" id="kcolor1_' . $iJurnal . '" value="-"/><input type="hidden" name="kcolor2_' . $iJurnal . '" id="kcolor2_' . $iJurnal . '" value=""/><input type="hidden" name="kcolor3_' . $iJurnal . '" id="kcolor3_' . $iJurnal . '" value=""/><input type="hidden" name="kcolor4_' . $iJurnal . '" id="kcolor4_' . $iJurnal . '" value=""/><input type="hidden" name="kcolor5_' . $iJurnal . '" id="kcolor5_' . $iJurnal . '" value=""/>';
                                        }else{
                                            $harga= number_format($DetilJurnal["harga"]+$DetilJurnal["transport"]);
                                            echo '<input type="hidden" name="color1_' . $iJurnal . '" id="color1_' . $iJurnal . '" value="' . $DetilJurnal["color1"] . '"/><input type="hidden" name="color2_' . $iJurnal . '" id="color2_' . $iJurnal . '" value="' . $DetilJurnal["color2"] . '"/><input type="hidden" name="color3_' . $iJurnal . '" id="color3_' . $iJurnal . '" value="' . $DetilJurnal["color3"] . '"/><input type="hidden" name="color4_' . $iJurnal . '" id="color4_' . $iJurnal . '" value="' . $DetilJurnal["color4"] . '"/><input type="hidden" name="color5_' . $iJurnal . '" id="color5_' . $iJurnal . '" value="' . $DetilJurnal["color5"] . '"/><input type="hidden" name="kcolor1_' . $iJurnal . '" id="kcolor1_' . $iJurnal . '" value="' . $DetilJurnal["kcolor1"] . '"/><input type="hidden" name="kcolor2_' . $iJurnal . '" id="kcolor2_' . $iJurnal . '" value="' . $DetilJurnal["kcolor2"] . '"/><input type="hidden" name="color3_' . $iJurnal . '" id="kcolor3_' . $iJurnal . '" value="' . $DetilJurnal["kcolor3"] . '"/><input type="hidden" name="kcolor4_' . $iJurnal . '" id="kcolor4_' . $iJurnal . '" value="' . $DetilJurnal["kcolor4"] . '"/><input type="hidden" name="kcolor5_' . $iJurnal . '" id="kcolor5_' . $iJurnal . '" value="' . $DetilJurnal["kcolor5"] . '"/>';
                                        }
                                        echo '<td align="center" valign="top" ><div class="form-group">
                                        <input type="text" class="form-control"  name="txtHarga_' . $iJurnal . '" id="txtHarga_' . $iJurnal . '" value="'.$harga.'" style="text-align:right;min-width: 120px;" ></div></td>';
                                        echo '<td valign="top" ><div class="form-group"><center>
                                        <input type="button" class="btn btn-primary" value="select" onclick="cmodal(' . $iJurnal . ')"></center></div></td>';
                                        $iJurnal++;
                                    }
                                ?>
                            </tbody>
                        </table>

                        <input type="hidden" value="<?php echo $iJurnal; ?>" id="jumAddJurnal" name="jumAddJurnal"/>
                        <input type="hidden" value="0" id="idebit" name="idebit"/>
                        <input type="hidden" value="0" id="ikredit" name="ikredit"/>
                        <center><button type="button" class="btn btn-success" onclick="javascript:addJurnal()">Add Detail</button></center>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Warna <label id="labelclr"></label></h4>
                                    <input type="hidden" class="form-control" id="txtnomer" value="">
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label class="control-label" for="chkPPN">Warna</label>
                                            <input type="text" class="form-control" id="color1" value="-" placeholder="">
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="control-label" for="chkPPN">Kode</label>
                                            <input type="text" class="form-control" id="kcolor1" value="-" placeholder="#00000">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="color2" value="-" placeholder="">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="kcolor2" value="-" placeholder="#00000">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="color3" value="-" placeholder="">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="kcolor3" value="-" placeholder="#00000">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="color4" value="-" placeholder="">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="kcolor4" value="-" placeholder="#00000">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="color5" value="-" placeholder="">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control" id="kcolor5" value="-" placeholder="#00000">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-primary" value="Add"  id="btncolor">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myNote" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Waktu & Persentase Pembayaran</h4>
                                </div>
                                <div class="modal-body">
                                    
                                    <div class="input-group">
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" id="mtxtW1" value="Saat penandatanganan Perjanjian">
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="input-group"><input type="number" id="mtxtP1" class="form-control" value="30"><span class="input-group-addon">%</span></div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control"id="mtxtW2" value="Saat kubah selesai dipabrikasi">
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="input-group"><input type="number" id="mtxtP2"class="form-control" value="25"><span class="input-group-addon">%</span></div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" id="mtxtW3"value="Saat tim pemasang dan kubah">
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="input-group"><input type="number" id="mtxtP3"class="form-control" value="35"><span class="input-group-addon">%</span></div>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" id="mtxtW4"value="Saat kubah sudah terpasang">
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="input-group"><input type="number" id="mtxtP4"class="form-control" value="10"><span class="input-group-addon">%</span></div>
                                        </div>
                                    </div>
                                    <div class="modal-header">
                                        <h4 class="modal-title">Note</h4>
                                    </div>
                                    <textarea class="form-control" id="txtReport"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <input type="submit" class="btn btn-primary" value="Save" id="btnsimpan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="mySph" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">No SPH</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <?php  
                                        $q = 'SELECT noSph FROM `aki_sph` WHERE 1 ORDER BY idSph ASC';
                                        $sql_sph = mysql_query($q,$dbLink);
                                        ?>
                                        <select class="form-control select2" name="snosph" id="snosph" style="width: 100%">
                                            <?php

                                            $selected = "";
                                            echo '<option value="">No SPH</option>';
                                                while($rs_sph = mysql_fetch_assoc($sql_sph)){ 
                                                    echo '<option value="'.md5($rs_sph['noSph']).'">'.$rs_sph['noSph'].'</option>';
                                                }  
                                            ?>
                                        </select>   
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary pull-right" id="createKk"><i class="fa fa-plus"></i> Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="button" class="btn btn-primary" value="Save" onclick="omodal()">
                        <a href="index.php?page=html/kk_list">
                            <button type="button" class="btn btn-default pull-right">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>    
                        </a>

                    </div>
                </div>

            </section>

        </div>
    </section>
    
</form>

