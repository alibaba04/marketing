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
function compress($source, $destination, $quality) {
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);
    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);
    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);
    imagejpeg($image, $destination, $quality);
    return $destination;
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

    # kita tambahkan uniqid() agar nama gambar bersifat unik
            $namaBaru = uniqid() . '-' . $namaFile;
            $lokasiBaru = "{$folderUpload}/{$namaBaru}";
            compress($lokasiTmp, $lokasiBaru, 65);
            if ($namaFile != '') {
                array_push($nameimg,$namaBaru);
            }
        }
        $pesan = $tmpkk->addkk($_POST, $nameimg);
    }
//Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "edit") {
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
            compress($lokasiTmp, $lokasiBaru, 65);
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
/* Fungsi formatRupiah */
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split           = number_string.split(','),
        sisa            = split[0].length % 3,
        rupiah          = split[0].substr(0, sisa),
        ribuan          = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
    }
    
$(document).ready(function () {
    $def = 0;
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    var $nokk = link.split('noKK=');
    var jmlid = 0;
    var jmldetail = $("#jumAddJurnal").val();
    if (res == 'mode=edit') {
        var idr = 0;
        var idc = 0;
        for(var i=0; i<jmldetail; ++i) {
            $.post("function/ajax_function.php",{ fungsi: "getdrangka",nokk:$nokk[1],nomer:i},function(data){
                if (data != 0) {
                    $("#jumDetailr_"+idr).val(data.length);
                }
                idr++;
            },"json");
            $.post("function/ajax_function.php",{ fungsi: "getdwarna",nokk:$nokk[1],nomer:i},function(data){
                if (data != 0) {
                    $("#jumWarna_"+idc).val(data.length);
                }
                idc++;
            },"json");
        }
    }
    
    $('#btnrangka').click(function(){
        var idrangka = $("#idrangka").val();
        var norangka = $("#jumDetailr_"+idrangka).val();
        var ttable = document.getElementById("drangka_"+idrangka);
        ttable.innerHTML='';

        $("#jumDetailr_"+idrangka).val(0);
        var sumd =0;
        for(var j=1; j<=norangka; ++j) {
            var drangka = $("#txtmrangka"+idrangka+"_"+j).val();
            if (drangka!='') {
                sumd++;
                var trow = document.createElement("TR");
                trow.setAttribute("id", "trid_"+idrangka);
                var td = document.createElement("TD");
                td.setAttribute("align","left");
                td.style.verticalAlign = 'top';
                trow.innerHTML+='<input type="hidden" class="form-control" id="txtrangka'+idrangka+'_'+sumd+'"name="txtrangka'+idrangka+'_'+sumd+'" value="'+drangka+'">';
                trow.appendChild(td);
                ttable.appendChild(trow);
            }
        }
        $("#jumDetailr_"+idrangka).val(sumd);
        $("#mySModal").modal('hide');
    });
    $('#btnwarna').click(function(){
        var idwarna = $("#idwarna").val();
        var nowarna = $("#jumWarna_"+idwarna).val();
        var sumd =0;var sumdk =0;
        var ttable = document.getElementById("dwarna_"+idwarna);
        ttable.innerHTML='';
        for($i = 1; $i <= nowarna; $i++){
            var dwarna = $("#txtmwarna"+idwarna+"_"+$i).val();
            var dkwarna = $("#txtmkwarna"+idwarna+"_"+$i).val();
            if (dwarna!='') {
                sumd++;
                var trow = document.createElement("TR");
                trow.setAttribute("id", "trid_"+idwarna);
                var td = document.createElement("TD");
                td.setAttribute("align","left");
                td.style.verticalAlign = 'top';
                trow.innerHTML+='<input type="hidden" class="form-control" id="txtwarna'+idwarna+'_'+sumd+'"name="txtwarna'+idwarna+'_'+sumd+'" value="'+dwarna+'">';
                trow.appendChild(td);
                ttable.appendChild(trow);
            }
            if (dkwarna!='') {
                sumdk++;
                var tkrow = document.createElement("TR");
                tkrow.setAttribute("id", "trid_"+idwarna);
                var tdk = document.createElement("TD");
                tdk.setAttribute("align","left");
                tdk.style.verticalAlign = 'top';
                tkrow.innerHTML+='<input type="hidden" class="form-control" id="txtkwarna'+idwarna+'_'+sumdk+'"name="txtkwarna'+idwarna+'_'+sumdk+'" value="'+dkwarna+'">';
                tkrow.appendChild(tdk);
                ttable.appendChild(tkrow);
            }
        }
        $("#myModal").modal('hide');
    });
    var harga = document.getElementById('idharga1');
    harga.addEventListener('keyup', function(e){
        harga.value = formatRupiah(this.value,'');
    });
    var kaligrafi = document.getElementById('txtkaligrafi');
    kaligrafi.addEventListener('keyup', function(e){
        kaligrafi.value = formatRupiah(this.value,'');
    });
    var ntrans = document.getElementById('txtntrans');
    ntrans.addEventListener('keyup', function(e){
        ntrans.value = formatRupiah(this.value,'');
    });
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    if (res != 'mode=edit') {
        if (link.match(/noSph=/g)) {
            $("#mySph").modal('hide');
        }else{
            $("#mySph").modal({backdrop: 'static'});
            $("#createKk").click(function(){ 
                //if ($("#snosph").val()!='') {
                    //$("#snosph").focus();
                    location.href=link+"&noSph="+ $("#snosph").val();
                //}
            });
        }
        getpabrikasi();getpemasangan();
    }
    $("#chkppemerintah").click(function(){ 
        if ($('#chkppemerintah').is(":checked")) {
            $("#txtppemerintah").val(1);
        }else{
            $("#txtppemerintah").val(0);
        }
        hitungtotal(0);
    });
    $("#chktransport").click(function(){ 
        if ($('#chktransport').is(":checked")) {
            $("#txttransport").val(1);
        }else{
            $("#txttransport").val(0);
        }
    });
    $("#closemyCModal").click(function(){ 
        $("#mySModal").modal('hide');
    });
    $("#closemyModal").click(function(){ 
        $("#myModal").modal('hide');
    });
});

function hitungtotal($param) {
    var kaligrafi =  $('#txtKaligrafi_'+$param).val().match(/\d/g);
    var hkubah = $('#txtHargaKubah_'+$param).val().match(/\d/g);
    var transport = $('#txtntrans_'+$param).val().match(/\d/g);
    var total = parseInt(hkubah.join(""))+parseInt(kaligrafi.join(""))+parseInt(transport.join(""));
    if ($('#chkppemerintah').is(":checked")) {
        var ppn = (parseInt(total)*0.1)+parseInt(total);
        var pra=Math.round(ppn / 1000) * 1000;
        var hasil = pra.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $('#txtHarga_'+$param).val(hasil);
        $('#txtHargappn_'+$param).val((parseInt(total)*0.1));
    }else{
        var pra = parseFloat(total);
        var harga=Math.round(pra / 1000) * 1000;
        $('#txtHarga_'+$param).val(harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#txtHargappn_'+$param).val(0);
    }
}
function getpabrikasi(){
    $.post("function/ajax_function.php",{ fungsi: "getpabrikasi",bahan:$("#txtBahan_0").val(),d:$("#txtD_0").val()},function(data)
        {
            $('#txtproduksi').val(data.pabrikasi);
        },"json"); 
}
function getpemasangan(){
    $.post("function/ajax_function.php",{ fungsi: "getpemasangan",d:$("#txtD_0").val()},function(data)
        {
            $('#txtPemasangan').val(data.pemasangan);
        },"json"); 
}

function cmodal($no,$nokk) {
    $("#myModal").modal({backdrop: 'static'});
    $('#txtnomerc').val($no);
    $('#labelclr').html(parseInt($no)+1);
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    $("#idwarna").val($no);
    var x = document.getElementById("detailwarna");
    var kx = document.getElementById("detailkwarna");
    x.innerHTML = '';kx.innerHTML = '';
    if ($("#jumWarna_"+$no).val() != 0) {
        var nowarna = $("#jumWarna_"+$no).val();
        for(var j=1; j<=nowarna; ++j) {
            var dwarna = $("#txtwarna"+$no+"_"+j).val();
            var dkwarna = $("#txtkwarna"+$no+"_"+j).val();
            if (dwarna!='') {
                var y = document.createElement("input");
                y.setAttribute("type", "text");
                y.classList.add("form-control")
                y.setAttribute("id", "txtmwarna"+$no+'_'+j);
                y.setAttribute("name", "txtmwarna"+$no+'_'+j);
                y.setAttribute("value", dwarna);
                x.appendChild(y);
            }
            if (dkwarna!='') {
                var ky = document.createElement("input");
                ky.setAttribute("type", "text");
                ky.classList.add("form-control")
                ky.setAttribute("id", "txtmkwarna"+$no+'_'+j);
                ky.setAttribute("name", "txtmkwarna"+$no+'_'+j);
                ky.setAttribute("value", dkwarna);
                kx.appendChild(ky);
            }
        }
    }
}
function rmodal($no,$nokk) {
    $("#idrangka").val($no);
    var x = document.getElementById("detailrangka");
    if ($("#jumDetailr_"+$no).val() == 0) {
        x.innerHTML = '';
        $.post("function/ajax_function.php",{ fungsi: "getdrangka",nokk:'c21f969b5f03d33d43e04f8f136e7682',nomer:0},function(data){
            $("#norangka_"+$no).val(data.length);
            for(var i=1; i<=data.length; ++i) {
                var y = document.createElement("input");
                y.setAttribute("type", "text");
                y.classList.add("form-control")
                y.setAttribute("id", "txtmrangka"+$no+'_'+i);
                y.setAttribute("name", "txtmrangka"+$no+'_'+i);
                y.setAttribute("value", data[i-1]);
                x.appendChild(y);
            }
            var jmlid = 0;
            jmlid +=data.length;
            jmlid = parseInt($("#jumDetailr_"+$no).val())+parseInt(jmlid);
            $("#jumDetailr_"+$no).val(jmlid);
        },"json");
    }else{
        var norangka = $("#jumDetailr_"+$no).val();
        x.innerHTML = '';
        for(var j=1; j<=norangka; ++j) {
            var drangka = $("#txtrangka"+$no+"_"+j).val();
            if (drangka!='') {
                var ttable = document.getElementById("drangka_"+$no);
                var y = document.createElement("input");
                y.setAttribute("type", "text");
                y.classList.add("form-control")
                y.setAttribute("id", "txtmrangka"+$no+'_'+j);
                y.setAttribute("name", "txtmrangka"+$no+'_'+j);
                y.setAttribute("value", drangka);
                x.appendChild(y);
            }
        }
    }
    $def++;
    $("#mySModal").modal({backdrop: 'static'});
}
function prangka() {
    var idrangka = $("#idrangka").val();
    var norangka = $("#jumDetailr_"+idrangka).val();
    var trow = document.createElement("DIV");
    var inp = document.getElementById("detailrangka");
    trow.innerHTML+='<input type="text" class="form-control" id="txtmrangka'+idrangka+'_'+(parseInt(norangka)+1)+'"name="txtmrangka'+idrangka+'_'+(parseInt(norangka)+1)+'" value="">';
    inp.appendChild(trow);
    $("#norangka_"+idrangka).val(parseInt(norangka)+1);
    $("#jumDetailr_"+idrangka).val(parseInt($("#jumDetailr_"+idrangka).val())+1);
}
function pwarna() {
    var idwarna = $("#idwarna").val();
    var nowarna = $("#jumWarna_"+idwarna).val();
    var trow = document.createElement("DIV");
    var inp = document.getElementById("detailwarna");
    trow.innerHTML+='<input type="text" class="form-control" id="txtmwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'"name="txtmwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'" value="">';
    inp.appendChild(trow);
    var trowk = document.createElement("DIV");
    var inpk = document.getElementById("detailkwarna");
    trowk.innerHTML+='<input type="text" class="form-control" id="txtmkwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'"name="txtmkwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'" value="">';
    inpk.appendChild(trowk);

    /*var ttable = document.getElementById("dwarna");
    var trow = document.createElement("TR");
    trow.setAttribute("id", "trid_"+idwarna);
    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    trow.innerHTML+='<input type="" class="form-control" id="txtwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'"name="txtwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'" value="">';
    trow.appendChild(td);
    ttable.appendChild(trow);

    var trowk = document.createElement("TR");
    trowk.setAttribute("id", "trid_"+idwarna);
    var tdk = document.createElement("TD");
    tdk.setAttribute("align","left");
    tdk.style.verticalAlign = 'top';
    trowk.innerHTML+='<input type="" class="form-control" id="txtkwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'"name="txtkwarna'+idwarna+'_'+(parseInt(nowarna)+1)+'" value="">';
    trowk.appendChild(tdk);
    ttable.appendChild(trowk);*/

    $("#nowarna_"+idwarna).val(parseInt(nowarna)+1);
    $("#jumWarna_"+idwarna).val(parseInt($("#jumWarna_"+idwarna).val())+1);
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
    $("#txtnproyek").val($("#txtnmasjid").val());
}
function opendmodal(tcounter) {
    $("#myCModal").modal({backdrop: 'static'});
    $('#txtket').val($("#txtKubah_"+tcounter).val());
    $('#cbomodel').val($("#txtModel_"+tcounter).val());
    $('#cbokelengkapan').val($("#txtPlafon_"+tcounter).val());
    $('#cbobahan').val($("#txtBahan_"+tcounter).val());
    $('#txtqty').val($("#txtQty_"+tcounter).val());
    $('#txtD').val($("#txtD_"+tcounter).val());
    $('#txtT').val($("#txtT_"+tcounter).val());
    $('#txtDt').val($("#txtDt_"+tcounter).val());
    $('#txtkaligrafi').val($("#txtKaligrafi_"+tcounter).val());
    $('#txtntrans').val($("#txtntrans_"+tcounter).val());
    $('#cbotrans').val($("#txtKtransport_"+tcounter).val());
    $('#txtMakara').val($("#txtMakara_"+tcounter).val());
    $('#txtbMakara').val($("#txtbMakara_"+tcounter).val());
    $('#idharga1').val($("#txtHargaKubah_"+tcounter).val());
    $("#btnAdd").click(function(){ 
        $("#txtKubah_"+tcounter).val($('#txtket').val());
        $("#txtModel_"+tcounter).val($('#cbomodel').val());
        $("#txtPlafon_"+tcounter).val($('#cbokelengkapan').val());
        $("#txtBahan_"+tcounter).val($('#cbobahan').val());
        $("#txtQty_"+tcounter).val($('#txtqty').val());
        $("#txtD_"+tcounter).val($('#txtD').val());
        $("#txtT_"+tcounter).val($('#txtT').val());
        $("#txtDt_"+tcounter).val($('#txtDt').val());
        $("#txtKaligrafi_"+tcounter).val($('#txtkaligrafi').val());
        $('#txtntrans_'+tcounter).val($("#txtntrans").val());
        $('#txtMakara_'+tcounter).val($("#txtMakara").val());
        $('#txtbMakara_'+tcounter).val($("#txtbMakara").val());
        $('#txtKtransport_'+tcounter).val($("#cbotrans").val());
        $("#txtHargaKubah_"+tcounter).val($('#idharga1').val());
        hitungtotal(tcounter);
        getpabrikasi();
        $("#myCModal").modal('hide');
    });
    $('#cbobahan').change(function(){
        var link = window.location.href;
        var res = link.match(/mode=edit/g);
        if (res != 'mode=edit') {
            if ($('#cbobahan').val() == 'Galvalume') {
                $('#idharga1').val($("#txtharga1_"+tcounter).val());
            }else if($('#cbobahan').val() == 'Titanium'){
                $('#idharga1').val($("#txtharga2_"+tcounter).val());
            }else{
                $('#idharga1').val($("#txtharga3_"+tcounter).val());
            }
        }
        
    });
}
function addJurnal(){    
    var link = window.location.href;
    var res = link.match(/mode=edit/g);
    var $nokk = link.split('noKK=');
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
    td.innerHTML+='<div class="form-group"><input style="text-align:center" name="txtKubah_'+tcounter+'" id="txtKubah_'+tcounter+'" class="form-control" value="Kubah Utama" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input style="text-align:center" name="txtModel_'+tcounter+'" id="txtModel_'+tcounter+'" class="form-control" value="setbola" readonly><input name="txtPlafon_'+tcounter+'" id="txtPlafon_'+tcounter+'" type="hidden" value="Full"><input name="txtBahan_'+tcounter+'" id="txtBahan_'+tcounter+'" type="hidden" value="Enamel"><input name="txtQty_'+tcounter+'" id="txtQty_'+tcounter+'" type="hidden" value="1"><input name="txtKtransport_'+tcounter+'" id="txtKtransport_'+tcounter+'" type="hidden" value="Transport Biasa"><input name="txtbMakara_'+tcounter+'" id="txtbMakara_'+tcounter+'" type="hidden"><input name="txtMakara_'+tcounter+'" id="txtMakara_'+tcounter+'" type="hidden"><input name="txtHargaKubah_'+tcounter+'" id="txtHargaKubah_'+tcounter+'" type="hidden"></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    //Kolom 6 d
    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtD_'+tcounter+'" id="txtD_'+tcounter+'" class="form-control" " value="0" style="text-align:right" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    //Kolom 7 t
    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtT_'+tcounter+'" id="txtT_'+tcounter+'" class="form-control" " value="0" style="text-align:right" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtDt_'+tcounter+'" id="txtDt_'+tcounter+'" class="form-control" " value="0" style="text-align:right" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtKaligrafi_'+tcounter+'" id="txtKaligrafi_'+tcounter+'" class="form-control" value="0" style="text-align:right" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtntrans_'+tcounter+'" id="txtntrans_'+tcounter+'" class="form-control" value="0" style="text-align:right" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtHargaKubah_'+tcounter+'" id="txtHargaKubah_'+tcounter+'" class="form-control" " value="0" style="text-align:right" readonly></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","left");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input name="txtHarga_'+tcounter+'" id="txtHarga_'+tcounter+'" class="form-control" readonly value="0" style="text-align:right"></div>';
    trow.appendChild(td);
    td.setAttribute('onclick','opendmodal('+tcounter+');');

    var td = document.createElement("TD");
    td.setAttribute("align","center");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input type="button" class="btn btn-primary" value="select" onclick=rmodal("' + tcounter + '","'+$nokk[1]+'" )></div>';
    trow.appendChild(td);

    var td = document.createElement("TD");
    td.setAttribute("align","center");
    td.style.verticalAlign = 'top';
    td.innerHTML+='<div class="form-group"><input type="button" class="btn btn-primary" value="select" onclick=cmodal("' + tcounter + '","'+$nokk[1]+'")></div><input type="hidden" id="jumDetailr_' + tcounter + '" name="jumDetailr_' + tcounter + '" value="0" /><input type="hidden" id="jumWarna_' + tcounter + '" name="jumWarna_' + tcounter + '" value="0" /><div id="drangka_' + tcounter + '"></div><div id="dwarna_' + tcounter + '"></div>';
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
                            echo "<input type='hidden' name='txtMode' value='edit'>";
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
                            $q = "SELECT * FROM aki_kk where idKk=( SELECT max(idKk) FROM aki_kk where 1)";
                            $rsTemp = mysql_query($q, $dbLink);
                            $tglTransaksi = date("Y-m-d");
                            $tglTr = substr($tglTransaksi, 0,4);
                            $bulan = bulanRomawi(substr($tglTransaksi,5,2));
                            if ($kode_ = mysql_fetch_array($rsTemp)) {
                                $urut = "";
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

                                if ($kode_['aktif']==99) {
                                    $noKk = '001'.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                }else{
                                    $noKk = $kode.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                }
                            }else{
                                $noKk = '001'.'/KK-MS/PTAKI/'.$bulan.'/'.$tglTr;
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
                            value="<?php  if($_GET['mode']=='edit'){echo $dataKk["nama_cust"]; }else{ if (isset($_GET["noSph"])){if (strpos($dataSph["nama_cust"], 'Bapak') !== FALSE){echo substr($dataSph["nama_cust"],6);}}}?>" placeholder="Client Name">
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
                                    <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtPhone" id="txtPhone" class="form-control" data-inputmask='"mask": "9999 9999 9999"' data-mask value="<?php  if($_GET['mode']=='edit'){echo $dataKk["no_phone"]; }else{ if (isset($_GET["noSph"])){echo $dataSph["no_phone"];}}?>"></div>
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
                                <input name="txtnmasjid" id="txtnmasjid" maxlength="50" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["nmasjid"]; }else{echo $dataSph["masjid"];}?>" onkeyup="tnmasjid()">
                            </div>
                        </div>
                        <div class="form-group" >
                            <div class="input-group">
                                <span class="input-group-addon">Nama Proyek</span>
                                <input name="txtnproyek" id="txtnproyek" maxlength="50" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["nproyek"]; }else{echo $dataSph["masjid"];}?>">
                             <input type="hidden" name="txtppemerintah" id="txtppemerintah" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["project_pemerintah"]; }else{echo '0';}?>">
                             <input type="hidden" name="txttransport" id="txttransport" class="form-control" 
                             value="<?php  if($_GET['mode']=='edit'){echo $dataKk["transport"]; }else{echo '0';}?>">
                            </div>
                        </div>
                        <div class="form-group" >
                            <label class="control-label" for="txtKodeTransaksi">Alamat Proyek</label>
                            <textarea class="form-control" rows="3" placeholder="Enter ..." name="txtalamatp" id="txtalamatp"><?php  if($_GET['mode']=='edit'){echo $dataKk["alamat_proyek"]; }?></textarea>
                        </div>
                        <div class="form-group" >
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label><input type="checkbox" id="chktransport"<?php if($_GET['mode']=='edit'){if ($dataKk["transport"]>0) {
                                            echo "checked";
                                        }} ?> >&nbsp;&nbsp;Biaya Transportasi</label>
                                    </div>
                                </div><div class="col-lg-3">
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
                        <div class="col-lg-4">
                            <label class="control-label" for="txtKodeTransaksi">Project</label>
                            <div class="input-group">
                                <select class="form-control" name="cbokproject" id="cbokproject">
                                <?php
                                    $selected = "";
                                    if ($dataKk['kproyek'] == 'nonpatas') {
                                        $selected = " selected";
                                        echo '<option value="nonpatas"'.$selected.'>Non Patas</option>';
                                        echo '<option value="patas">Patas</option>';
                                    }elseif ($dataKk['kproyek']=="patas") {
                                        $selected = " selected";
                                        echo '<option value="nonpatas">Non Patas</option>';
                                        echo '<option value="patas"'.$selected.'>Patas</option>';
                                    }else{
                                        echo '<option value="nonpatas">Non Patas</option>';
                                        echo '<option value="patas">Patas</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label" for="txtKodeTransaksi">Produksi</label>
                            <div class="input-group">
                                <input type="number" name="txtproduksi" id="txtproduksi" class="form-control"
                                value="<?php  if($_GET['mode']=='edit'){echo $dataKk["mproduksi"]; }?>" placeholder="0" ><span class="input-group-addon">Hari</span></div>
                        </div>
                        <div class="col-lg-4">
                            <label class="control-label" for="txtKodeTransaksi">Pemasangan</label>
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
                    <div class="box-body" style="width: 100%;overflow-x: scroll;">
                        <table class="table table-bordered table-striped table-hover"  >
                            <thead>
                                <tr>
                                   <th style="width: 1%"><i class='fa fa-edit'></i></th>
                                   <th style="width: 20%;min-width: 100px;" colspan="2">Spec</th>
                                   <th style="width: 3%">D</th>
                                   <th style="width: 3%">T</th>
                                   <th style="width: 3%">Dt</th>
                                   <th style="width: 10%">Transport</th>
                                   <th style="width: 10%">Kaligrafi</th>
                                   <th style="width: 10%">Price&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                   <th style="width: 10%">Total</th>
                                   <th style="width: 2%">Spec</th>
                                   <th style="width: 2%">Color</th>
                                </tr>
                            </thead>
                            <tbody id="kendali">
                                <?php
                                    $q='';
                                    if ($_GET['mode']=='edit'){
                                        $q = "SELECT kk.*,dp.* FROM aki_dkk kk left join aki_dpembayaran dp on kk.noKK=dp.noKk ";
                                        $q.= "WHERE 1=1 and MD5(kk.nokk)='" . $noKk;
                                        $q.= "' ORDER BY  kk.nomer ";
                                    }else{
                                        $q = "SELECT idDsph AS 'idKk',ket as 'kubah',jumlah,d,t,dt,plafon,transport as ntransport,luas,harga,harga2,harga3,bahan,biaya_plafon,model,biaya_plafon as kaligrafi FROM aki_dsph ";
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
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group" ><input type="text" class="form-control"name="txtKubah_' . $iJurnal . '" id="txtKubah_' . $iJurnal . '" value="' . ($DetilJurnal["kubah"]) . '" ></div></td>';
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group" ><input type="text" class="form-control"name="txtModel_' . $iJurnal . '" id="txtModel_' . $iJurnal . '" value="' . ($DetilJurnal["model"]) . '"></div></td>';
                                        $bahan='';
                                        if ($DetilJurnal["bahan"]=='3') {
                                            $bahan='Titanium';
                                        }else if($DetilJurnal["bahan"]=='2'){
                                            $bahan='Enamel';
                                        }else if($DetilJurnal["bahan"]=='1'){
                                            $bahan='Galvalume';
                                        }else{
                                            $bahan='Titanium';
                                        }
                                        echo '<input type="hidden" class="form-control"name="txtBahan_' . $iJurnal . '" id="txtBahan_' . $iJurnal . '" value="' . $bahan . '" >';
                                        $plafon = '';
                                        if ($DetilJurnal["plafon"]=='0') {
                                            $plafon = 'Full';
                                        }else if($DetilJurnal["plafon"]=='2'){
                                            $plafon = 'Tanpa Plafon';
                                        }else if($DetilJurnal["plafon"]=='1'){
                                            $plafon = 'Waterproof';
                                        }
                                        echo '<input type="hidden" class="form-control"name="txtPlafon_' . $iJurnal . '" id="txtPlafon_' . $iJurnal . '" value="' . $plafon . '" ><input type="hidden" class="form-control" name="txtKtransport_' . $iJurnal . '" id="txtKtransport_' . $iJurnal . '" value="';if ($_GET['mode']!='edit'){echo "Transport Biasa";}else{ echo $DetilJurnal["ktransport"];}
                                        echo '" ><input type="hidden" class="form-control"name="txtMakara_' . $iJurnal . '" id="txtMakara_' . $iJurnal . '" value="' . $DetilJurnal["makara"] . '" ><input type="hidden" class="form-control"name="txtbMakara_' . $iJurnal . '" id="txtbMakara_' . $iJurnal . '" value="' . $DetilJurnal["bmakara"] . '" >';
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group" style="min-width: 50px;"><input type="text" class="form-control"name="txtD_' . $iJurnal . '" id="txtD_' . $iJurnal . '" value="' . ($DetilJurnal["d"]) . '"></div></td>';
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group"style="min-width: 50px;">
                                        <input type="text" class="form-control"name="txtT_' . $iJurnal . '" id="txtT_' . $iJurnal . '" value="' . ($DetilJurnal["t"]) . '" ></div></td>';
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group"style="min-width: 50px;">
                                        <input type="number" class="form-control"name="txtDt_' . $iJurnal . '" id="txtDt_' . $iJurnal . '" value="' . ($DetilJurnal["dt"]) . '" ></div></td>';
                                        $harga = 0;
                                        if ($_GET['mode']!='edit'){
                                            if($DetilJurnal["bahan"]=='1'){
                                                $harga= $DetilJurnal["harga"]-$DetilJurnal["ntransport"];
                                            }else if($DetilJurnal["bahan"]=='2'){
                                                $harga= $DetilJurnal["harga2"]-$DetilJurnal["ntransport"];
                                            }else{
                                                $harga= $DetilJurnal["harga3"]-$DetilJurnal["ntransport"];
                                            }
                                            echo '<input type="hidden" name="txtharga1_' . $iJurnal . '" id="txtharga1_' . $iJurnal . '" value="'.number_format($DetilJurnal["harga"]-$DetilJurnal["ntransport"]).'"/>';
                                            echo '<input type="hidden" name="txtharga2_' . $iJurnal . '" id="txtharga2_' . $iJurnal . '" value="'.number_format($DetilJurnal["harga2"]-$DetilJurnal["ntransport"]).'"/>';
                                            echo '<input type="hidden" name="txtharga3_' . $iJurnal . '" id="txtharga3_' . $iJurnal . '" value="'.number_format($DetilJurnal["harga3"]-$DetilJurnal["ntransport"]).'"/>';
                                            echo '<input type="hidden" name="txtQty_' . $iJurnal . '" id="txtQty_' . $iJurnal . '" value="'.$DetilJurnal["jumlah"].'"/>';
                                        }else{
                                            $harga= ($DetilJurnal["harga"]-$DetilJurnal["ntransport"]);
                                            echo '<input type="hidden" name="txtQty_' . $iJurnal . '" id="txtQty_' . $iJurnal . '" value="'.$DetilJurnal["jumlah"].'"/>';
                                        }
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group">
                                        <input type="text" class="form-control"  name="txtntrans_' . $iJurnal . '" id="txtntrans_' . $iJurnal . '" value="'.number_format($DetilJurnal["ntransport"]).'" style="text-align:right;min-width: 120px;" onkeyup="hitungtotal(' . $iJurnal . ')"></div></td>';
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group">
                                        <input type="text" class="form-control"  name="txtKaligrafi_' . $iJurnal . '" id="txtKaligrafi_' . $iJurnal . '" value="'.number_format($DetilJurnal["kaligrafi"]).'" style="text-align:right;min-width: 120px;" onkeyup="hitungtotal(' . $iJurnal . ')"></div></td>';
                                        $totharga = 0;
                                        if ($_GET['mode']!='edit') {
                                            echo '<input type="hidden" name="txtHargappn_' . $iJurnal . '" id="txtHargappn_' . $iJurnal . '" value=""/>';
                                            $totharga = number_format(round($harga+$DetilJurnal["kaligrafi"],-6)+$DetilJurnal["ntransport"]);
                                        }else{
                                            echo '<input type="hidden" name="txtHargappn_' . $iJurnal . '" id="txtHargappn_' . $iJurnal . '" value="'.$DetilJurnal["hppn"].'"/>';
                                            $harga = $harga-$DetilJurnal["hppn"];
                                            $totharga = number_format(round($harga+$DetilJurnal["kaligrafi"],-6)+$DetilJurnal["hppn"]+$DetilJurnal["ntransport"]);
                                        }
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group">
                                        <input type="text" class="form-control"  name="txtHargaKubah_' . $iJurnal . '" id="txtHargaKubah_' . $iJurnal . '" value="'.number_format(round($harga,-6)).'" style="text-align:right;min-width: 120px;" onkeyup="hitungtotal(' . $iJurnal . ')"></div></td>';
                                        echo '<td align="center" valign="top" onclick="opendmodal('.$iJurnal.')"><div class="form-group">
                                        <input readonly type="text" class="form-control"  name="txtHarga_' . $iJurnal . '" id="txtHarga_' . $iJurnal . '" value="'.$totharga.'" style="text-align:right;min-width: 120px;" ></div></td>';
                                        echo '<td valign="top" ><div class="form-group"><center>
                                        <input type="button" class="btn btn-primary" value="select" onclick=rmodal("' . $iJurnal . '","' . $_GET['noKK'] . '")></center></div></td>';
                                        echo '<td valign="top" ><div class="form-group"><center>
                                        <input type="button" class="btn btn-primary" value="select" onclick=cmodal("' . $iJurnal . '","' . $_GET['noKK'] . '")></center></div><input type="hidden" name="txtPatas_' . $iJurnal . '" id="txtPatas_' . $iJurnal . '" value="-"/><input type="hidden" id="jumDetailr_' . $iJurnal . '" name="jumDetailr_' . $iJurnal . '" value="0" /><input type="hidden" id="jumWarna_' . $iJurnal . '" name="jumWarna_' . $iJurnal . '" value="0" />';
                                        $qrangka = 'SELECT * FROM aki_kkrangka WHERE `aktif`=1 and MD5(noKK)="'.$_GET["noKK"].'" and nomer="'.$iJurnal.'" order by idRangka asc';
                                        $rsrangka = mysql_query($qrangka, $dbLink);
                                        $irgk = 1;
                                        echo '<div id="drangka_' . $iJurnal . '">';
                                        while ($Detilrgk = mysql_fetch_array($rsrangka)) {
                                            echo '<input type="hidden" id="txtrangka'.$iJurnal.'_'.$irgk.'"name="txtrangka'.$iJurnal.'_'.$irgk.'" value="'.$Detilrgk["rangka"].'">';
                                            $irgk++;
                                        }

                                        $qwarna = 'SELECT * FROM aki_kkcolor WHERE MD5(noKK)="'.$_GET["noKK"].'" and nomer="'.$iJurnal.'" order by id asc';
                                        $rswarna = mysql_query($qwarna, $dbLink);
                                        $iwrn = 1;
                                        echo '</div><div id="dwarna_' . $iJurnal . '">';
                                        while ($Detilwrn = mysql_fetch_array($rswarna)) {
                                            echo '<input type="hidden" id="txtwarna'.$iJurnal.'_'.$iwrn.'"name="txtwarna'.$iJurnal.'_'.$iwrn.'" value="'.$Detilwrn["color"].'">';
                                            echo '<input type="hidden" id="txtkwarna'.$iJurnal.'_'.$iwrn.'"name="txtkwarna'.$iJurnal.'_'.$iwrn.'" value="'.$Detilwrn["kcolor"].'">';
                                            $iwrn++;
                                        }
                                        echo "</div></td>";
                                        $iJurnal++;
                                    }
                                ?>
                            </tbody>
                            <div id="dwarna"></div>
                        </table>
                        <input type="hidden" value="<?php echo $iJurnal; ?>" id="jumAddJurnal" name="jumAddJurnal"/>
                        
                        <center><button type="button" class="btn btn-success" onclick="javascript:addJurnal()">Add Detail</button></center>
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
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closemyModal">&times;</button>
                    <h4 class="modal-title">Warna Kubah <label id="labelclr"></label></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div id="detailwarna" class="col-lg-6"></div>
                        <div id="detailkwarna" class="col-lg-6"></div>
                        <input type="hidden"  id="idwarna" name="idwarna" value="" >
                    </div>
                    <center>
                        <button type="button" class="btn btn-primary" id="addwarna" onclick="pwarna();"><i class="fa fa-plus"></i></button>
                    </center>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-primary" value="Add" id="btnwarna">
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
                            <input type="text" class="form-control" id="mtxtW1" value="Saat penandatanganan Perjanjian ini">
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group"><input type="number" id="mtxtP1" class="form-control" value="30"><span class="input-group-addon">%</span></div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-lg-9">
                            <input type="text" class="form-control"id="mtxtW2" value="Saat kubah selesai dipabrikasi dan akan dikirimkan">
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group"><input type="number" id="mtxtP2"class="form-control" value="25"><span class="input-group-addon">%</span></div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="col-lg-9">
                            <input type="text" class="form-control" id="mtxtW3"value="Saat tim pemasang dan kubah sudah sampai di lokasi">
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
                        $q = 'SELECT noSph FROM `aki_sph` WHERE aktif=1 ORDER BY idSph desc';
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
    <div class="modal fade" id="myCModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detail Kubah <label id="labelclr"></label></h4>
                    <input type="hidden" class="form-control" id="txtnomer" value="">
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        
                        <div class="col-lg-6">
                            <select name="txtket" id="txtket" class="form-control">
                                <option value='Kubah Utama'>Kubah Utama</option>;
                                <option value='Mahrab'>Mahrab</option>;
                                <option value='Anakan'>Anakan</option>;
                                <option value='Menara'>Menara</option>;
                                <?php
                                if ($_SESSION['my']->privilege == 'ADMIN') {
                                    echo '<option value=Atap>Atap</option>';
                                }
                                ?>
                            </select>
                            <label class="control-label" for="txtKeteranganKas"></label>
                            <select name="cbomodel" id="cbomodel" class="form-control">
                                <option value='setbola'>Setengah Bola</option>";
                                <option value='pinang'>Pinang</option>";
                                <option value='madinah'>Madinah</option>";
                                <option value='bawang'>Bawang</option>";
                                <option value='custom'>Custom</option>";
                            </select>
                            <label class="control-label" for="txtKeteranganKas">Transport</label>
                            <div class="input-group"><span class="input-group-addon">Rp</span>
                            <input type="text" name="txtntrans" id="txtntrans" class="form-control" value="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" onfocus="this.value=''" placeholder="0" ></div>
                           
                            <label class="control-label" for="txtKeteranganKas">Jumlah</label>
                            <input type="number" min='1' name="txtqty" id="txtqty" class="form-control">
                            <label class="control-label" for="txtKeteranganKas">Diameter</label><div class="input-group">
                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  name="txtD" id="txtD" class="form-control" placeholder="0"
                            value="0" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
                            <label class="control-label" for="txtKeteranganKas">Makara</label>
                            <input type="text" name="txtMakara" id="txtMakara" class="form-control" value="" placeholder="Lafadz Allah">
                            <label class="control-label" for="txtKeteranganKas">Kaligrafi</label><div class="input-group"><span class="input-group-addon">Rp</span>
                            <input type="text" name="txtkaligrafi" id="txtkaligrafi" class="form-control" value="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" onfocus="this.value=''" placeholder="0" ></div>
                        </div>
                        <div class="col-lg-6">
                            <select name="cbobahan" id="cbobahan" class="form-control">
                                <option value=Galvalume>Galvalume</option>';
                                <option value=Enamel>Enamel</option>';
                                <option value=Titanium>Titanium</option>';
                            </select>
                            <label class="control-label" for="txtKeteranganKas"></label>
                            <select name="cbokelengkapan" id="cbokelengkapan" class="form-control">
                                <option value="Full">Full</option>";
                                <option value="Tanpa Plafon">Tanpa Plafon</option>";
                                <option value="Waterproof">Waterproof</option>";
                            </select>
                            <label class="control-label" for="txtKeteranganKas">Transport</label><div class="input-group">
                            <select class="form-control " name="cbotrans" id="cbotrans">
                                <option value="Transport Biasa" selected>Transport Biasa</option>
                                <option value="Prioritas">Prioritas</option>
                            </select>
                            <label class="control-label" for="txtKeteranganKas">Diameter Tengah</label><div class="input-group">
                                <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtDt" id="txtDt" class="form-control" value="0" ><span class="input-group-addon">meter</span></div>
                                <label class="control-label" for="txtKeteranganKas">Tinggi</label><div class="input-group">
                            <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  name="txtT" id="txtT" class="form-control" placeholder="0" value="0" onfocus="this.value=''"><span class="input-group-addon">meter</span></div></div>
                            <label class="control-label" for="txtbMakara">Bahan Makara</label>
                            <input type="text" name="txtbMakara" id="txtbMakara" class="form-control" value="" placeholder="Galvalume">
                            <label class="control-label" for="txtKeteranganKas">Harga</label><div class="input-group"><span class="input-group-addon">Rp</span><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" name="idharga1" id="idharga1" placeholder="0"class="form-control" value="0" onfocus="this.value=''"></div>
                        </div>
                    </div>
                    <div class="box-footer" style="padding-top: 10%;"></div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-primary" value="Add" id="btnAdd">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mySModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="closemyCModal">&times;</button>
                    <h4 class="modal-title">Rangka Kubah <label id="labelclr"></label></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div id="detailrangka"></div>
                        <input type="hidden"  id="idrangka" name="idrangka" value="" >
                    </div>
                    <center>
                        <button type="button" class="btn btn-primary" id="addrangka" onclick="prangka();"><i class="fa fa-plus"></i></button>
                    </center>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-primary" value="Add" id="btnrangka">
                </div>
            </div>
        </div>
    </div>
</form>

