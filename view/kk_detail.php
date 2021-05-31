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
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
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
        $pesan.="Gagal simpan data, mohon hubungi " . $mailSupport . " untuk keterangan lebih lanjut terkait masalah ini.";
    }
    header("Location:index.php?page=view/kk_list&pesan=" . $pesan);
    exit;
}
?>
<script>
    $(function () {
        $("[data-mask]").inputmask();
        //Initialize Select2 Elements
        $(".select2").select2();
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();
    });
</script>
<script type="text/javascript" charset="utf-8">
    function kalkulatorharga(){
        $.post("function/ajax_function.php",{ fungsi: "kalkulator",d:$('#txtD').val(),t:$('#txtT').val(),dt:$('#txtDt').val(),kel:$('#cbokelengkapan').val(),ongkir:0,margin:$('#idmargin').val(),bplafon:0},function(data)
        {
            if ($("#cbomodel").val() != 'custom') {
                $('#idluas').val(data.luas);
                $('#idmargin').attr("placeholder", data.margin);
                $('#idharga1').val(data.tharga);
            }
        },"json");
    }

$(document).ready(function () {
    $("#btnModal").click(function(){ 
        $("#myModal").modal({backdrop: false});
        document.getElementById("simpan").disabled = true;
        document.getElementById("idluas").disabled = true;
    });
    $("#btnModal2").click(function(){ 
        $("#myModal").modal('hide');
        $("#myModal2").modal({backdrop: false});
    });
    $("#btnclosemodal2").click(function(){ 
        $("#myModal2").modal('hide');
        $("#myModal").modal({backdrop: false});
    });
    $("#btnSaveModal2").click(function(){ 
        $("#myModal2").modal('hide');
        $("#myModal").modal({backdrop: false});
    });
    $("#btnCloseModal2").click(function(){ 
        $("#myModal2").modal('hide');
        $("#myModal").modal({backdrop: false});
    });
    $("#dt :input").prop("readonly", true);
            $("#txtDt").val(0);
    $("#cbomodel").change(function(){ 
        var cbomodel = $("#cbomodel").val(); 
        var dt = $("#txtDt").val(); 
        if(cbomodel == 'bawang'){
            $("#dt :input").prop("readonly", false);
        }else{
            $("#dt :input").prop("readonly", true);
            $("#txtDt").val(0); 
        }
        if ($("#cbomodel").val() == 'custom') {
            document.getElementById("idluas").disabled = false;
        }else{
            document.getElementById("idluas").disabled = true;
        }
    }); 
    var txtT = document.getElementById('txtT');
    txtT.addEventListener('keyup', function(e){
        kalkulatorharga();
    });
    var txtD = document.getElementById('txtD');
    txtD.addEventListener('keyup', function(e){
        kalkulatorharga();
    });
    var txtDt = document.getElementById('txtDt');
    txtDt.addEventListener('keyup', function(e){
        kalkulatorharga();
    });
    var cbokel = document.getElementById('cbokelengkapan');
    cbokel.addEventListener('keyup', function(e){
        kalkulatorharga();
    });
    var idmargin = document.getElementById('idmargin');
    idmargin.addEventListener('keyup', function(e){
        kalkulatorharga();
    });

    $("#chkppemerintah").click(function(){ 
        if ($('#chkppemerintah').is(":checked")) {
            $("#txtppemerintah").val(1);
        }else{
            $("#txtppemerintah").val(0);
        }
    });

    $("#deletedetail").click(function(){ 
        var txt;
        var r = confirm("Hapus Detail KK?");
        if (r == true) {
            txt = "Berhasil Hapus Detail KK!";
        } else {
            txt = "Batal Hapus Detail KK!";
        }
        document.getElementById("pesandel").innerHTML = '<div class="callout callout-info">'+txt+'</div>';
    });
});
function tnmasjid() {
    $("#txtnproyek").val('Masjid '+$("#txtnmasjid").val());
}
</script>
<!-- Include script untuk function auto complete -->
<SCRIPT language="JavaScript" TYPE="text/javascript">
    var tcounter = 0;
    function adddetail($param){
        $("#myModal").modal({backdrop: false});
        $('#validEdit').val($param);
        $("#chkeditval").val($("#chkEdit_"+$param).val());
        $.post("function/ajax_function.php",{ fungsi: "idListkk",id:$('#validEdit').val(),noKk:$('#txtnoKk').val()},function(data)
        {
            document.getElementById("txtjkubah").value = data.kubah;
            document.getElementById("cbobahan").value = data.bahan;
            document.getElementById("cbomodel").value = data.model;
            document.getElementById("cbokelengkapan").value = data.plafon;
            document.getElementById("txtqty").value = data.jumlah;
            document.getElementById("txtD").value = data.d;
            document.getElementById("txtDt").value = data.dt;
            document.getElementById("txtT").value = data.t;
            document.getElementById("idharga1").value = data.harga;

            if (data.model != 'bawang') {
                $("#dt :input").prop("readonly", true);
            }
            if (data.model != 'custom') {
                kalkulatorharga();
            }else{
                document.getElementById("idluas").value = data.luas;
            }
            document.getElementById("txtW1").value = data.txtw1;
            document.getElementById("txtW2").value = data.txtw2;
            document.getElementById("txtW3").value = data.txtw3;
            document.getElementById("txtW4").value = data.txtw4;
            document.getElementById("txtP1").value = data.txtp1;
            document.getElementById("txtP2").value = data.txtp2;
            document.getElementById("txtP3").value = data.txtp3;
            document.getElementById("txtP4").value = data.txtp4;

            var clr1 = (data.color1).split(' ');
            var clr2 = (data.color2).split(' ');
            var clr3 = (data.color3).split(' ');
            var clr4 = (data.color4).split(' ');
            var clr5 = (data.color5).split(' ');
            document.getElementById("color1").value = clr1[0];
            document.getElementById("color2").value = clr2[0];
            document.getElementById("color3").value = clr3[0];
            document.getElementById("color4").value = clr4[0];
            document.getElementById("color5").value = clr5[0];

            document.getElementById("kcolor1").value = clr1[1];
            document.getElementById("kcolor2").value = clr2[1];
            document.getElementById("kcolor3").value = clr3[1];
            document.getElementById("kcolor4").value = clr4[1];
            document.getElementById("kcolor5").value = clr5[1];
        },"json");
    }
    function addarray() {
        if($("#txtket").val()=='0' )
        {
            alert("Keterangan harus diisi!");
            $("#txtket").focus();
            return false;
        }
        if($("#cbomodel").val()=='0' )
        {
            alert("Pilih Model Kubah!");
            $("#cbomodel").focus();
            return false;
        }
        if($("#txtD").val()=='0' )
        {
            alert("Diameter harus diisi!");
            $("#txtD").focus();
            return false;
        }

        if($("#txtT").val()=='0' )
        {
            alert("Tinggi harus diisi!");
            $("#txtT").focus();
            return false;
        }

        if ($("#cbomodel").val()=='bawang'){
            if($("#txtDt").val()=='0' ){
                alert("Diameter Tengah harus diisi!");
                $("#txtDt").focus();
                return false;
            } 

        }

        var ket = $("#txtjkubah").val();
        var bahan = $("#cbobahan").val();
        var model = $("#cbomodel").val();
        var qty = $("#txtqty").val();
        var kelengkapan = $("#cbokelengkapan").val();
        var d = $("#txtD").val();
        var dt = $("#txtDt").val();
        var t = $("#txtT").val();
        var l = $("#idluas").val();
        var m = $("#idmargin").val();
        var h = $("#idharga1").val();
        var txtw1 = $("#txtW1").val();
        var txtw2 = $("#txtW2").val();
        var txtw3 = $("#txtW3").val();
        var txtw4 = $("#txtW4").val();
        var txtP1 = $("#txtP1").val();
        var txtP2 = $("#txtP2").val();
        var txtP3 = $("#txtP3").val();
        var txtP4 = $("#txtP4").val();
        var color1 = $("#color1").val();
        var color2 = $("#color2").val();
        var color3 = $("#color3").val();
        var color4 = $("#color4").val();
        var color5 = $("#color5").val();
        var kcolor1 = $("#kcolor1").val();
        var kcolor2 = $("#kcolor2").val();
        var kcolor3 = $("#kcolor3").val();
        var kcolor4 = $("#kcolor4").val();
        var kcolor5 = $("#kcolor5").val();
        var fkubah = $("#filekubah").val();
        var fkaligrafi = $("#filekaligrafi").val();
        var ppn = 1;
        var transport = 1;
        if ($('#chkTransport').is(":checked") || $('#chkPPN').is(":checked")){
            ppn = '1';
            transport = '1';
        }else{
            ppn = '0';
            transport = '0';
        }
        


        tcounter++;
        $("#myModal").modal('hide');
        document.getElementById("simpan").disabled = false;
        tcounter = $("#jumAddJurnal").val();
        if ($("#chkmode").val() == 'edit'){
            tcounter = tcounter-1;
            $("#jumAddJurnal").val(tcounter);
        }

        var link = window.location.href;
        var res = link.match(/mode=edit/g);
        $("#jumAddJurnal").val(parseInt($("#jumAddJurnal").val())+1);
        if (res == 'mode=edit') {
            $("#txtKet_"+$('#validEdit').val()).val($('#txtjkubah').val()+' - '+$('#cbomodel').val()+' - '+$('#cbobahan').val());
            $("#txtQty_"+$('#validEdit').val()).val($('#txtqty ').val());
            $("#txtD_"+$('#validEdit').val()).val( $("#txtD").val());
            $("#txtT_"+$('#validEdit').val()).val($('#txtT').val());
            $("#txtDt_"+$('#validEdit').val()).val( $("#txtDt").val());
            $("#txtModel_"+$('#validEdit').val()).val($('#cbomodel').val());
            $("#txtHarga1_"+$('#validEdit').val()).val( $("#idharga1").val());
            $("#txtBahan_"+$('#validEdit').val()).val($('#cbobahan').val());
            $("#txtKubah_"+$('#validEdit').val()).val($('#txtjkubah').val());
            $("#luas_"+$('#validEdit').val()).val($('#idluas').val());
            $("#txtKel_"+$('#validEdit').val()).val($('#cbokelengkapan').val());

            $("#transport_"+$('#validEdit').val()).val(transport);
            $("#ppn_"+$('#validEdit').val()).val( ppn);
            $("#txtw1_"+$('#validEdit').val()).val($('#txtW1 ').val());
            $("#txtw2_"+$('#validEdit').val()).val( $("#txtW2").val());
            $("#txtw3_"+$('#validEdit').val()).val($('#txtW3').val());
            $("#txtw4_"+$('#validEdit').val()).val( $("#txtW4").val());
            $("#txtP1_"+$('#validEdit').val()).val($('#txtP1').val());
            $("#txtP2_"+$('#validEdit').val()).val( $("#txtP2").val());
            $("#txtP3_"+$('#validEdit').val()).val($('#txtP3').val());
            $("#txtP4_"+$('#validEdit').val()).val( $("#txtP4").val());
            $("#color1_"+$('#validEdit').val()).val($('#color1').val()+' '+$('#kcolor1').val());
            $("#color2_"+$('#validEdit').val()).val( $("#color2").val()+' '+$('#kcolor2').val());
            $("#color3_"+$('#validEdit').val()).val($('#color3').val()+' '+$('#kcolor3').val());
            $("#color4_"+$('#validEdit').val()).val( $("#color4").val()+' '+$('#kcolor4').val());
            $("#color5_"+$('#validEdit').val()).val($('#color5').val()+' '+$('#kcolor5').val());
        }else{
//        
//Kolom 1 Checkbox
var td = document.createElement("TD");
td.setAttribute("align","center");
if ($("#chkmode").val()=='edit') {
    td.innerHTML+='<div class="form-group"><input type="checkbox" class="minimal" name="chkEdit_'+tcounter+'" id="chkEdit_'+tcounter+'" value="'+$("#chkeditval").val()+'" checked /></div>';
    var tr = document.getElementById("trid_"+tcounter);
    tr.remove();
}else{
    td.innerHTML+='<div class="form-group"><input type="checkbox" class="minimal" name="chkAddJurnal_'+tcounter+'" id="chkAddJurnal_'+tcounter+'" value="1" checked /></div>';
}
td.style.verticalAlign = 'top';

trow.appendChild(td);

//Kolom 2 Ket
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','adddetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtKet_'+tcounter+'" id="txtKet_'+tcounter+'" class="form-control" value="'+ket+' - '+bahan+' - '+model+'" readonly style="min-width: 120px;"><input name="txtModel_'+tcounter+'" id="txtModel_'+tcounter+'" class="form-control" type="hidden" value="'+model+'"><input name="txtBahan_'+tcounter+'" id="txtBahan_'+tcounter+'" class="form-control" type="hidden" value="'+bahan+'"><input name="txtKubah_'+tcounter+'" id="txtKubah_'+tcounter+'" class="form-control" type="hidden" value="'+ket+'"></div>';
trow.appendChild(td);

//Kolom 4 qty
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','adddetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtQty_'+tcounter+'" id="txtQty_'+tcounter+'" class="form-control"  value="'+qty+'" readonly style="min-width: 35px;"></div>';
trow.appendChild(td);

//Kolom 6 d
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','adddetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtD_'+tcounter+'" id="txtD_'+tcounter+'" class="form-control"  value="'+d+'" readonly style="min-width: 35px;"></div>';
trow.appendChild(td);

//Kolom 7 t
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','adddetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtT_'+tcounter+'" id="txtT_'+tcounter+'" class="form-control"  value="'+t+'" readonly style="min-width: 35px;"></div>';
trow.appendChild(td);

//Kolom 8 dt
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','adddetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtDt_'+tcounter+'" id="txtDt_'+tcounter+'" class="form-control"  value="'+dt+'" readonly style="min-width: 35px;"><input name="txtKel_'+tcounter+'" id="txtKel_'+tcounter+'" class="form-control" type="hidden" value="'+kelengkapan+'"></div>';
trow.appendChild(td);

//Kolom 9 h
var td = document.createElement("TD");
td.setAttribute("align","right");
td.setAttribute('onclick','adddetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group" ><input name="txtHarga_'+tcounter+'" id="txtHarga_'+tcounter+'" class="form-control" readonly value="'+h+'"style="min-width: 120px;" ><input name="luas_'+tcounter+'" id="luas_'+tcounter+'" class="form-control" type="hidden" value="'+l+'"><input name="filekubah_'+tcounter+'" id="filekubah_'+tcounter+'" class="form-control" type="hidden" value="'+fkubah+'"><input name="filekaligrafi_'+tcounter+'" id="filekaligrafi_'+tcounter+'" class="form-control" type="hidden" value="'+fkaligrafi+'"><input name="ppn_'+tcounter+'" id="ppn_'+tcounter+'" class="form-control" type="hidden" value="'+ppn+'"><input name="transport_'+tcounter+'" id="transport_'+tcounter+'" class="form-control" type="hidden" value="'+transport+'"><input name="txtw1_'+tcounter+'" id="txtw1_'+tcounter+'" class="form-control" type="hidden" value="'+txtw1+'"><input name="txtw2_'+tcounter+'" id="txtw2_'+tcounter+'" class="form-control" type="hidden" value="'+txtw2+'"><input name="txtw3_'+tcounter+'" id="txtw3_'+tcounter+'" class="form-control" type="hidden" value="'+txtw3+'"><input name="txtw4_'+tcounter+'" id="txtw4_'+tcounter+'" class="form-control" type="hidden" value="'+txtw4+'"><input name="txtP1_'+tcounter+'" id="txtP1_'+tcounter+'" class="form-control" type="hidden" value="'+txtP1+'"><input name="txtP2_'+tcounter+'" id="txtP2_'+tcounter+'" class="form-control" type="hidden" value="'+txtP2+'"><input name="txtP3_'+tcounter+'" id="txtP3_'+tcounter+'" class="form-control" type="hidden" value="'+txtP3+'"><input name="txtP4_'+tcounter+'" id="txtP4_'+tcounter+'" class="form-control" type="hidden" value="'+txtP4+'"><input name="color1_'+tcounter+'" id="color1_'+tcounter+'" class="form-control" type="hidden" value="'+color1+' '+kcolor1+'"><input name="color2_'+tcounter+'" id="color2_'+tcounter+'" class="form-control" type="hidden" value="'+color2+' '+kcolor2+'"><input name="color3_'+tcounter+'" id="color3_'+tcounter+'" class="form-control" type="hidden" value="'+color3+' '+kcolor3+'"><input name="color4_'+tcounter+'" id="color4_'+tcounter+'" class="form-control" type="hidden" value="'+color4+' '+kcolor4+'"><input name="color5_'+tcounter+'" id="color5_'+tcounter+'" class="form-control" type="hidden" value="'+color5+' '+kcolor5+'"></div>';
trow.appendChild(td);
ttable.appendChild(trow);
}
}

function validasiForm(form)
{
    if(form.txtnamacust.value=='' )
    {
        alert("Nama Klien harus diisi!");
        form.txtnamacust.focus();
        return false;
    }
    if(form.provinsi.value=='' )
    {
        alert("Pilih Provinsi !");
        form.provinsi.focus();
        return false;
    }
    if(form.kota.value=='')
    {
        alert("Pilih Kota !");
        form.kota.focus();
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

<form action="index2.php?page=view/kk_detail" method="post" name="frmKasKeluarDetail" enctype="multipart/form-data" onSubmit="return validasiForm(this);" autocomplete="off">
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <section class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
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

                            $q = "SELECT ROW_NUMBER() OVER(PARTITION BY dkk.model ORDER BY kk.idKk) AS id,kk.*, dkk.*,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK ";
                            $q.= "FROM aki_kk kk right join aki_dkk dkk on kk.noKk=dkk.noKk left join aki_user u on kk.kodeUser=u.kodeUser left join provinsi p on kk.provinsi=p.id LEFT join kota k on kk.kota=k.id ";
                            $q.= "WHERE 1=1 and MD5(kk.noKk)='" . $noKk."'";
                            $q.= " ORDER BY kk.noKk desc ";
                            $rsTemp = mysql_query($q, $dbLink);
                            if ($dataSph = mysql_fetch_array($rsTemp)) {

                                echo "<input type='hidden' name='noKk' value='" . $dataSph["noKk"] . "'>";
                            } else {
                                ?>
                                <script language="javascript">
                                    alert("Kode Tidak Valid ");
                                </script>
                                <?php
                            }
                            } else {
                                $q = "SELECT * FROM aki_kk where idKk=( SELECT max(idKk) FROM aki_kk )";
                                $rsTemp = mysql_query($q, $dbLink);
                                $tglTransaksi = date("Y-m-d");
                                if ($kode_ = mysql_fetch_array($rsTemp)) {
                                    $urut = "";
                                    $noKk = "";
                                    $tglTr = substr($tglTransaksi, 0,4);
                                    $bulan = bulanRomawi(substr($tglTransaksi,5,2));
                                    if ($kode_['noKk'] != ''){
                                        $urut = substr($kode_['noKk'],0, 4);
                                        $tahun = substr($kode_['noKk'],-4);
                                        $kode = $urut + 1;
                                        if (strlen($kode)==1) {
                                            $kode = '000'.$kode;
                                        }else if (strlen($kode)==2){
                                            $kode = '00'.$kode;
                                        }else if (strlen($kode)==3){
                                            $kode = '0'.$kode;
                                        }
                                        if ($tglTr != $tahun) {
                                            $kode = '0001';
                                        }
                                        $noKk = $kode.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;

                                    }else{
                                        $noKk = '0001'.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                    }
                                }
                                echo '<h3 class="box-title">Add KK</h3>';
                                echo "<input type='hidden' name='txtMode'  value='Add'>";
                            }?>
                    </div>
                    <div class="box-body">
                        <div class="form-group" >
                            <div class="input-group">
                                <span class="input-group-addon">No</span>
                                <input name="txtnoKk" id="txtnoKk" maxlength="30" class="form-control" 
                                readonly value="<?php if($_GET["mode"]=='edit'){ echo $dataSph["noKk"]; }else{echo $noKk;}?>" placeholder="Nomor otomatis dibuat">
                            </div>
                        </div>
                        <div class="form-group">
                            <input name="txtnamacust" id="txtnamacust" class="form-control" 
                            value="<?php  if($_GET['mode']=='edit'){echo $dataSph["nama_cust"]; }?>" placeholder="Client Name">
                        </div>
                        <div class="form-group" >
                            <div class="col-lg-2" style="padding-right: 0px;padding-left: 5px;">
                                <select name="cboJenisid" id="cboJenisid" class="form-control">
                                    <?php
                                    $selected = "";
                                    if ($dataSph['jenis_id'] == 'KTP') {
                                        $selected = " selected";
                                        echo '<option value="KTP"'.$selected.'>KTP</option>';
                                        echo '<option value="SIM">SIM</option>';
                                    }elseif ($dataSph['jenis_id']=="SIM") {
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
                                <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtNoid" id="txtNoid" class="form-control" value="<?php  if($_GET['mode']=='edit'){echo $dataSph["no_id"]; }?>"></div>
                        </div>
                        <label class="control-label" for="txtTglTransaksi">&nbsp;&nbsp;</label>
                        <div class="form-group" >
                            <div class="col-lg-6" style="padding-right: 0px;padding-left: 5px;">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtPhone" id="txtPhone" class="form-control" data-inputmask='"mask": "9999 9999 9999"' data-mask value="<?php  if($_GET['mode']=='edit'){echo $dataSph["no_phone"]; }?>"></div>
                            </div>
                            <div class="col-lg-6" style="padding-right: 0px;padding-left: 5px;">
                                <input type="text" name="txtPosition" id="txtPosition" class="form-control" value="<?php  if($_GET['mode']=='edit'){echo $dataSph["jabatan"]; }?>" placeholder='Jabatan' >
                            </div>
                        </div>
                        <label class="control-label" for="txtTglTransaksi">&nbsp;&nbsp;</label>

                        <div class="form-group" >
                            <textarea class="form-control" rows="3" placeholder="Address ..." name="txtalamat" id="txtalamat" ><?php  if($_GET['mode']=='edit'){echo $dataSph["alamat"]; }?></textarea>
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
                                        echo '<option value="'.$dataSph["idP"].'-'.$dataSph["idK"].'" selected>'.$dataSph["pn"].' - '.$dataSph["kn"].'</option>';
                                        while($rs_provinsi = mysql_fetch_assoc($sql_provinsi)){ 
                                            echo '<option value="'.$rs_provinsi['idP'].'-'.$rs_provinsi['idK'].'">'.$rs_provinsi['pname'].' - '.$rs_provinsi['kname'].'</option>';
                                        }  
                                    }else{
                                        echo '<option value="">Address</option>';
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
                             value="<?php  if($_GET['mode']=='edit'){echo $dataSph["nmasjid"]; }?>" onkeyup="tnmasjid()">
                            </div>
                        </div>
                        <div class="form-group" >
                            <div class="input-group">
                                <span class="input-group-addon">Nama Proyek</span>
                                <input name="txtnproyek" id="txtnproyek" maxlength="30" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataSph["nproyek"]; }?>">
                             <input type="hidden" name="txtppemerintah" id="txtppemerintah" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataSph["project_pemerintah"]; }else{echo '1';}?>">
                            </div>

                        </div>

                        <div class="form-group" >
                            <label class="control-label" for="txtKodeTransaksi">Alamat Proyek</label>
                            <textarea class="form-control" rows="3" placeholder="Enter ..." name="txtalamatp" id="txtalamatp"><?php  if($_GET['mode']=='edit'){echo $dataSph["alamat_proyek"]; }?></textarea>
                        </div>
                        <div class="form-group" >
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <label><input type="checkbox" id="chkppemerintah"<?php if($_GET['mode']=='edit'){if ($dataSph["project_pemerintah"]==1) {
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
                                value="<?php  if($_GET['mode']=='edit'){echo $dataSph["mproduksi"]; }?>" placeholder="0" ><span class="input-group-addon">Hari</span></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label" for="txtKodeTransaksi">Masa Pemasangan</label>
                            <div class="input-group">
                                <input type="number" name="txtPemasangan" id="txtPemasangan" class="form-control"
                                value="<?php  if($_GET['mode']=='edit'){echo $dataSph["mpemasangan"]; }?>" placeholder="0" ><span class="input-group-addon">Hari</span></div>
                        </div>
                    </div>
                    <label class="control-label" for="txtTglTransaksi">&nbsp;&nbsp;</label>
                    </div>
                </div>
                    
            </section>

            <section class="col-lg-6">
                <div id="pesandel"></div>
            </section> 
            <section class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">DETAIL SPESIFIKASI KUBAH</h3>
                        <span id="msgbox"> </span>
                    </div>
                    <div class="box-body"style="width: 100%;overflow-x: scroll;">
                    <table class="table table-bordered table-striped table-hover" >
                        <?php
                        echo '<input type="hidden" class="minimal"  name="chkmode" id="chkmode" value="'.$_GET["mode"].'" />';
                        ?>
                        <thead>
                            <tr>
                                <th style="width: 2%"><i class='fa fa-edit'></i></th>
                                <th style="width: 20%">Information</th>
                                <th style="width: 3%">Quantity</th>
                                <th style="width: 3%">D</th>
                                <th style="width: 3%">T</th>
                                <th style="width: 3%">Dt</th>
                                <th style="width: 13%">Price&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                            </tr>
                        </thead>
                        <tbody id="kendali">
                            <?php
                            if ($_GET['mode']=='edit'){
                                $q = "SELECT kk.*,dp.* FROM aki_dkk kk left join aki_dpembayaran dp on kk.noKK=dp.noKk ";
                                $q.= "WHERE 1=1 and MD5(kk.nokk)='" . $noKk;
                                $q.= "' ORDER BY  kk.nomer ";
                                $rsDetilJurnal = mysql_query($q, $dbLink);
                                $iJurnal = 0;
                                while ($DetilJurnal = mysql_fetch_array($rsDetilJurnal)) {
                                    $kel = '';
                                    echo '<div><tr id="trid_'.$iJurnal.'" >';
                                    echo '<td align="center" valign="top" ><div class="form-group">
                                    <input type="checkbox" class="minimal" checked name="chkEdit_' . $iJurnal . '" id="chkEdit_' . $iJurnal . '" value="' . $DetilJurnal["idKk"] . '" /></div></td>';
                                    echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                    <input readonly type="text" class="form-control"  name="txtKet_' . $iJurnal . '" id="txtKet_' . $iJurnal . '" value="' . $DetilJurnal["kubah"] .' - '. $DetilJurnal["model"].' - '. $DetilJurnal["bahan"].'"style="min-width: 120px;"></div></td>';
                                    echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                    <input type="text" class="form-control" name="txtQty_' . $iJurnal . '" id="txtQty_' . $iJurnal . '" value="' . $DetilJurnal["jumlah"] . '" readonly="" style="min-width: 35px;"></div></td>';
                                    echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                    <input type="text" class="form-control"name="txtD_' . $iJurnal . '" id="txtD_' . $iJurnal . '" value="' . ($DetilJurnal["d"]) . '" readonly="" style="min-width: 45px;"></div></td>';
                                    echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                    <input type="text" class="form-control"name="txtT_' . $iJurnal . '" id="txtT_' . $iJurnal . '" value="' . ($DetilJurnal["t"]) . '" readonly="" style="min-width: 45px;"></div></td>';
                                    echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                    <input type="text" class="form-control"name="txtDt_' . $iJurnal . '" id="txtDt_' . $iJurnal . '" value="' . ($DetilJurnal["dt"]) . '" readonly="" style="min-width: 45px;"><input type="hidden" class="form-control"  name="txtKel_' . $iJurnal . '" id="txtKel_' . $iJurnal . '" value="' . $DetilJurnal["plafon"] . '"/><input type="hidden" class="form-control"  name="chkEnGa_' . $iJurnal . '" id="chkEnGa_' . $iJurnal . '" value="' . $DetilJurnal["bahan"] . '"/><input type="hidden" class="form-control"  name="txtModel_' . $iJurnal . '" id="txtModel_' . $iJurnal . '" value="' . $DetilJurnal["model"] . '"/><input type="hidden" class="form-control"  name="txtBahan_' . $iJurnal . '" id="txtBahan_' . $iJurnal . '" value="' . $DetilJurnal["bahan"] . '"/><input type="hidden" class="form-control"  name="txtKubah_' . $iJurnal . '" id="txtKubah_' . $iJurnal . '" value="' . $DetilJurnal["kubah"] . '"/><input type="hidden" name="txtw1_' . $iJurnal . '" id="txtw1_' . $iJurnal . '" value="' . $DetilJurnal["wpembayaran1"] . '"/><input type="hidden" name="txtw2_' . $iJurnal . '" id="txtw2_' . $iJurnal . '" value="' . $DetilJurnal["wpembayaran2"] . '"/><input type="hidden" name="txtw3_' . $iJurnal . '" id="txtw3_' . $iJurnal . '" value="' . $DetilJurnal["wpembayaran3"] . '"/><input type="hidden" name="txtw4_' . $iJurnal . '" id="txtw4_' . $iJurnal . '" value="' . $DetilJurnal["wpembayaran4"] . '"/><input type="hidden" name="txtP1_' . $iJurnal . '" id="txtP1_' . $iJurnal . '" value="' . $DetilJurnal["persen1"] . '"/><input type="hidden" name="txtP2_' . $iJurnal . '" id="txtP2_' . $iJurnal . '" value="' . $DetilJurnal["persen2"] . '"/><input type="hidden" name="txtP3_' . $iJurnal . '" id="txtP3_' . $iJurnal . '" value="' . $DetilJurnal["persen3"] . '"/><input type="hidden" name="txtP4_' . $iJurnal . '" id="txtP4_' . $iJurnal . '" value="' . $DetilJurnal["persen4"] . '"/><input type="hidden" name="color1_' . $iJurnal . '" id="color1_' . $iJurnal . '" value="' . $DetilJurnal["color1"] . '"/><input type="hidden" name="color2_' . $iJurnal . '" id="color2_' . $iJurnal . '" value="' . $DetilJurnal["color2"] . '"/><input type="hidden" name="color3_' . $iJurnal . '" id="color3_' . $iJurnal . '" value="' . $DetilJurnal["color3"] . '"/><input type="hidden" name="color4_' . $iJurnal . '" id="color4_' . $iJurnal . '" value="' . $DetilJurnal["color4"] . '"/><input type="hidden" name="color5_' . $iJurnal . '" id="color5_' . $iJurnal . '" value="' . $DetilJurnal["color5"] . '"/><input type="hidden" class="form-control"  name="luas_' . $iJurnal . '" id="luas_' . $iJurnal . '" value="' . $DetilJurnal["luas"] . '"/><input type="hidden" class="form-control"  name="transport_' . $iJurnal . '" id="transport_' . $iJurnal . '" value="' . $DetilJurnal["transport"] . '"/><input type="hidden" class="form-control"  name="ppn_' . $iJurnal . '" id="ppn_' . $iJurnal . '" value="' . $DetilJurnal["ppn"] . '"/></div></td>';
                                    if ($DetilJurnal["model"] == 'custom') {
                                        echo '<input type="hidden" class="form-control"  name="luas_' . $iJurnal . '" id="luas_' . $iJurnal . '" value="' . $DetilJurnal["luas"] . '"/>';
                                    }   
                                    echo '<td align="center" valign="top" onclick="adddetail('.$iJurnal.')"><div class="form-group">
                                    <input type="text" class="form-control"  name="txtHarga1_' . $iJurnal . '" id="txtHarga1_' . $iJurnal . '" value="' . number_format($DetilJurnal["harga"]) . '" style="text-align:right;min-width: 120px;" readonly></div></td>';
                                    $iJurnal++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="hidden" value="<?php if($_GET['mode']=='edit'){echo $iJurnal;}else{echo '0';} ?>" id="jumAddJurnal" name="jumAddJurnal"/>
            <input type="hidden" value="" id="chkeditval" name="chkeditval"/>
            <input type="hidden" value="" id="validEdit" name="validEdit"/>
            <div class="container">
                <!-- Modal -->
                <div class="modal fade" id="myModal" role="dialog" style="height: 100%;overflow-x: scroll;">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <form action="index2.php?page=view/kk_detail" method="post" name="frmPerkiraanDetail" >
                                    <div class="box-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <i class="ion ion-clipboard"></i>
                                        <?php
                                        if ($_GET["mode"] == "edit") {
                                            echo '<h3 class="box-title">UBAH KK </h3>';
                                            echo "<input type='hidden' name='txtMode' value='Edit'>";
                                        } else {
                                            echo '<h3 class="box-title">Kontrak Kerja   </h3>';
                                            echo "<input type='hidden' name='txtMode'  value='Add'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <select name="txtjkubah" id="txtjkubah" class="form-control">
                                            <option value='Kubah Utama'>Kubah Utama</option>;
                                            <option value='Mahrab'>Mahrab</option>;
                                            <option value='Anakan'>Anakan</option>;
                                            <option value='Menara'>Menara</option>;
                                        </select>
                                    </div>
                                    <div class="form-group" >
                                        <select name="cbomodel" id="cbomodel" class="form-control">
                                            <option value=setbola>Setengah Bola</option>";
                                            <option value=pinang>Pinang</option>";
                                            <option value=madinah>Madinah</option>";
                                            <option value=bawang>Bawang</option>";
                                            <?php
                                            if ($_SESSION['my']->privilege == 'ADMIN') {
                                                echo '<option value=custom>Custom</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="cbobahan" id="cbobahan" class="form-control">
                                            <option value=Galvalume>Galvalume</option>";
                                            <option value=Enamel>Enamel</option>";
                                            <option value=Titanium>Titanium</option>";
                                        </select>
                                    </div>
                                    
                                    <div class="panel box box-primary" style="margin-bottom: 0px;">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseDetail">
                                                Detail
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseDetail" class="panel-collapse collapse">
                                            <div class="box-body">
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Kelengkapan</label>
                                                            <select name="cbokelengkapan" id="cbokelengkapan" class="form-control">
                                                                <option value=0>Full</option>";
                                                                <option value=1>Tanpa Plafon</option>";
                                                                <option value=2>Waterproof</option>";
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Motifs</label>
                                                            <select name="cbobahan" id="cbokaligrafi" class="form-control">
                                                                <option value=awan>Awan</option>";
                                                                <option value=kaligrafi>Kaligrafi</option>";
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Jumlah</label>
                                                            <input type="number" min='1' name="txtqty" id="txtqty" class="form-control" value="1"
                                                            value="">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Diameter</label><div class="input-group">
                                                                <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  name="txtD" id="txtD" class="form-control" placeholder="0"
                                                                value="" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Tinggi</label><div class="input-group">
                                                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  name="txtT" id="txtT" class="form-control" placeholder="0"
                                                            value="" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
                                                        </div>
                                                        <div class="col-lg-6" id="dt">
                                                            <label class="control-label" for="txtKeteranganKas">Diameter Tengah</label><div class="input-group">
                                                            <input type="text" placeholder="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtDt" id="txtDt" class="form-control" value="" ><span class="input-group-addon">meter</span></div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Luas</label><div class="input-group"><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  type="text" name="idluas" id="idluas" class="form-control" placeholder="0" 
                                                                value=""><span class="input-group-addon">m<sup>2</sup></span></div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Margin</label><div class="input-group"><input type="number" value=""placeholder="0" name="idmargin" id="idmargin" class="form-control" value="0" min="0" max="100"><span class="input-group-addon">%</span></div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label class="control-label" for="txtKeteranganKas">Harga</label><div class="input-group"><span class="input-group-addon">Rp</span><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" name="idharga1" id="idharga1" placeholder="0"class="form-control"value=""><span class="input-group-addon"><input type="checkbox" id="chkHargaGa"checked></span></div>
                                                        </div>                                        
                                                    <label class="control-label" for="txtTglTransaksi">&nbsp;</label>
                                                    <div class="form-group">
                                                        <div class="col-lg-6">
                                                            <div class="input-group"><input checked type="checkbox" id="chkPPN" <?php if($_GET['mode']=='edit'){if ($dataSph["ppn"]==1) {echo "checked";}} ?> ><label class="control-label" for="chkPPN">&nbsp;&nbsp;Harga termasuk PPN</label></div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-group"><input checked type="checkbox" id="chkTransport" <?php if($_GET['mode']=='edit'){if ($dataSph["transport"]==1) {echo "checked";}} ?>><label class="control-label" for="chkTransport">&nbsp;&nbsp;Harga termasuk Biaya Transport</label></div>
                                                        </div>
                                                    </div>
                                                    <div class="box-footer" style="padding-top: 1%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel box box-primary" style="margin-bottom: 0px;">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseColor">
                                                Color 
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseColor" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <label class="control-label" for="chkPPN">Warna</label>
                                                        <input type="text" class="form-control" id="color1"  placeholder="">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label class="control-label" for="chkPPN">Kode</label>
                                                        <input type="text" class="form-control" id="kcolor1"  placeholder="#00000">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="color2"  placeholder="">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="kcolor2"  placeholder="#00000">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="color3"  placeholder="">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="kcolor3"  placeholder="#00000">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="color4"  placeholder="">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="kcolor4"  placeholder="#00000">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="color5"  placeholder="">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <input type="text" class="form-control" id="kcolor5"  placeholder="#00000">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel box box-primary" style="margin-bottom: 0px;">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                              <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                Waktu & Persentase Pembayaran
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse">
                                            <div class="box-body">
                                                <div class="input-group">
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="txtW1" value="Saat penandatanganan Perjanjian">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group"><input type="text" id="txtP1"class="form-control" value="30"><span class="input-group-addon">%</span></div>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control"id="txtW2" value="Saat kubah selesai dipabrikasi">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group"><input type="text" id="txtP2"class="form-control" value="25"><span class="input-group-addon">%</span></div>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="txtW3"value="Saat tim pemasang dan kubah">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group"><input type="text" id="txtP3"class="form-control" value="35"><span class="input-group-addon">%</span></div>
                                                    </div>
                                                </div>
                                                <div class="input-group">
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="txtW4"value="Saat kubah sudah terpasang">
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="input-group"><input type="text" id="txtP4"class="form-control" value="10"><span class="input-group-addon">%</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <input type="button" class="btn btn-primary" value="Save" onclick="addarray();">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                                <div class="box-footer">
                                    
                                    <tr>
                                        <td>
                                            <?php
                                            if($_GET['mode']!='edit'){
                                                echo '<center><button type="button" class="btn btn-danger" id="btnModal">Tambah Kubah</button></center>';
                                            } 
                                            ?></td>
                                            <td><input type="submit" class="btn btn-primary" value="Save" id="simpan"></td>
                                            <td><a href="index.php?page=html/kk_list">
                                                <button type="button" class="btn btn-default pull-right">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>    
                                            </a></td>
                                        </tr>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>
                </form>
            </section>
        </div>
    </section>   
                      
</form>
