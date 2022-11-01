    <?php
/* ==================================================
//=======  : Alibaba
==================================================== */
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/sph_detail";
//Periksa hak user pada modul/menu ini
$judulMenu = 'Penawaran';
$hakUser = getUserPrivilege($curPage);
if ($hakUser != 90) {
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
        $pesan = $tmpsph->addsph($_POST);
    }
//Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "Edit") {
        $pesan = $tmpsph->edit($_POST);
    }
//Jika Mode Hapus/Delete
    if ($_GET["txtMode"] == "Delete") {
        $pesan = $tmpsph->delete($_GET["kodeTransaksi"]);
    }
//Seharusnya semua transaksi Add dan Edit Sukses karena data sudah tervalidasi dengan javascript di form detail.
//Jika masih ada masalah, berarti ada exception/masalah yang belum teridentifikasi dan harus segera diperbaiki!
    if (strtoupper(substr($pesan, 0, 5)) == "GAGAL") {
        global $mailSupport;
        $pesan.="Gagal simpan data, mohon hubungi " . $mailSupport . " untuk keterangan lebih lanjut terkait masalah ini.";
    }
    header("Location:index.php?page=view/sph_list&pesan=" . $pesan);
    exit;
}
?>
<script>
    $(function () {
        $("[data-mask]").inputmask();
        $(".select2").select2();
    });
</script>
<script type="text/javascript" charset="utf-8">

    function kalkulatorharga(){
        var a = $('#txtmongkir').val();
        var v = a.replace(/[^0-9\.]+/g, '');
        var d = v.replace(/\./g,'');
        $.post("function/ajax_function.php",{ fungsi: "kalkulator",model:$('#cbomodel').val(),d:$('#txtmD').val(),t:$('#txtmT').val(),dt:$('#txtmDt').val(),kel:$('#cbokelengkapan').val(),ongkir:d,margin:$('#idmargin').val(),bplafon:0},function(data)
        {
            if ($("#cbomodel").val() != 'custom') {
                $('#idluas').val(data.luas);
                $('#idmargin').attr("placeholder", data.margin);
                $('#idharga1').val(data.hargaga);
                $('#idharga2').val(data.hargaen);
                $('#idharga3').val(data.hargass);
            }
        },"json");

    }
    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split           = number_string.split(','),
        sisa            = split[0].length % 3,
        rupiah          = split[0].substr(0, sisa),
        ribuan          = split[0].substr(sisa).match(/\d{3}/gi);

        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
    }
$(document).ready(function () {
    $("#btnModal").click(function(){ 
        var idP = $('#provinsi').val();
        idP = idP.split("-");
        var idK = idP[1];
        var link = window.location.href;
        var res = link.match(/mode=edit/g);
        if (res != 'mode=edit') {
            $.post("function/ajax_function.php",{ fungsi: "getOngkir",idP:idP[0]},function(data)
            {
                if (idK == '3506' || idK == '3571') {
                    document.getElementById("txtmongkir").value = 0;
                }else{
                    document.getElementById("txtmongkir").value = data.transport;
                }
                
            },"json");
        }
        $('#chkmodel').val(1);
        $("#modalDetailkubah").modal({backdrop: false});
        document.getElementById("simpan").disabled = true;
        document.getElementById("idluas").disabled = true;
    });
    $("#txtmDt").val(0);
    $("#txtmket").change(function(){
        var cboket = $("#txtmket").val();  
        if(cboket == 'Atap'){
            document.getElementById("idluas").disabled = false;
            document.getElementById("txtmD").disabled = true;
            document.getElementById("txtmDt").disabled = true;
            document.getElementById("txtmT").disabled = true;
            document.getElementById("txtmbplafon").disabled = true;
        }else{
            document.getElementById("idluas").disabled = true;
            document.getElementById("txtmD").disabled = false;
            document.getElementById("txtmDt").disabled = false;
            document.getElementById("txtmT").disabled = false;
            document.getElementById("txtmbplafon").disabled = false;
        }
    });
    $("#cbomodel").change(function(){ 
        var cbomodel = $("#cbomodel").val(); 
        var dt = $("#txtmDt").val(); 
        if(cbomodel == 'bawang'){
            $("#dt :input").prop("readonly", false);
        }else{
            $("#dt :input").prop("readonly", true);
            $("#txtmDt").val(0); 
        }
        if ($("#cbomodel").val() == 'custom') {
            document.getElementById("idluas").disabled = false;
        }else{
            document.getElementById("idluas").disabled = true;
            $("#idluas").val(0);
        }
    }); 
    var txtT = document.getElementById('txtmT');
    txtT.addEventListener('keyup', function(e){
        var cbomodel = $("#cbomodel").val(); 
        if(cbomodel == 'pinang'){
            if ($("#txtmT").val()<$("#txtmD").val()) {
                alert("Tinggi minimal sama!");
                $("#txtmT").focus();
                return false;
            }
        }
        kalkulatorharga();
    });
    var txtD = document.getElementById('txtmD');
    txtD.addEventListener('keyup', function(e){
        var cbomodel = $("#cbomodel").val(); 
        var d = $("#txtmD").val();
        if(cbomodel == 'bawang'){
            $("#txtmT").val(d);
            var dt = (0.23*(d))+parseFloat(d);
            var resdt = dt.toString().length;
            if (resdt > 5) {
                $("#txtmDt").val(dt.toFixed(3));
            }else{
                $("#txtmDt").val(dt);
            }
        }else if(cbomodel == 'pinang'){
            $("#txtmT").val(d);
        }else if(cbomodel == 'madinah'){
            $("#txtmT").val((d*0.75));
        }else if(cbomodel == 'setbola'){
            var t = (d*0.5);
            $("#txtmT").val(t);
        }
        kalkulatorharga();
    });
    var cbokel = document.getElementById('cbokelengkapan');
    cbokel.addEventListener('change', function(e){
        kalkulatorharga();
    });
    var idmargin = document.getElementById('idmargin');
    idmargin.addEventListener('keyup', function(e){
        if($("#txtmket").val() != 'Atap'){
            kalkulatorharga();
        }
    });
    var idmargin = document.getElementById('idmargin');
    idmargin.addEventListener('focusout', function(e){
        if ($("#idmargin").val()<21) {
            alert("Minimal Margin 21%!");
            $("#idmargin").focus();
            $("#idmargin").val('21');
        }
        if($("#txtmket").val() != 'Atap'){
            kalkulatorharga();
        }
    });
    var rupiah = document.getElementById('txtmongkir');
    rupiah.addEventListener('keyup', function(e){
        rupiah.value = formatRupiah(this.value,'');
        kalkulatorharga();
    });
    var bplafon = document.getElementById('txtmbplafon');
    bplafon.addEventListener('keyup', function(e){
        bplafon.value = formatRupiah(this.value,'');
        kalkulatorharga();
    });
    $("#closemyCModal").click(function(){ 
        $('#chkmodel').val(0);
        $("#myCModal").modal('hide');
    });
});

</script>
<!-- Include script untuk function auto complete -->
<SCRIPT language="JavaScript" TYPE="text/javascript">
    
    function addmDetail($param){
        $('#detkubah').val($param);
        var mbplafon = $("#txtBplafon_"+$param).val();
        var mket = $("#txtKet_"+$param).val();
        var mmodel = $("#txtModel_"+$param).val();
        var mqty = $("#txtQty_"+$param).val();
        var mkelengkapan = $("#txtKel_"+$param).val();
        var md = $("#txtD_"+$param).val();
        var mdt = $("#txtDt_"+$param).val();
        var mt = $("#txtT_"+$param).val();
        var mtransport = $("#txtTransport_"+$param).val();
        var mluas = $("#txtLuas_"+$param).val();
        var mh1 = $("#txtHarga1_"+$param).val();
        var mh2 = $("#txtHarga2_"+$param).val();
        var mh3 = $("#txtHarga3_"+$param).val();
        var mchkmodel = $("#chkEnGa_"+$param).val();
        mchkhbaham(mchkmodel);

        $("#txtmbplafon").val(mbplafon);
        $("#txtmket").val(mket);
        $("#cbomodel").val(mmodel);
        $("#txtqty").val(mqty);
        $("#cbokelengkapan").val(mkelengkapan);
        $("#txtmD").val(md);
        $("#txtmDt").val(mdt);
        $("#txtmT").val(mt);
        $("#txtmongkir").val(mtransport);
        $("#idluas").val(mluas);
        $("#idharga1").val(mh1);
        $("#idharga2").val(mh2);
        $("#idharga3").val(mh3);
        $("#modalDetailkubah").modal({backdrop: false});
    }
    function addDetail($param){
        var bplafon = $("#txtmbplafon").val();
        var ket = $("#txtmket").val();
        var model = $("#cbomodel").val();
        var qty = $("#txtqty").val();
        var kelengkapan = $("#cbokelengkapan").val();
        var d = $("#txtmD").val();
        var dt = $("#txtmDt").val();
        var t = $("#txtmT").val();
        var transport = $("#txtmongkir").val();
        var l = $("#idluas").val();
        var m = $("#idmargin").val();
        var h1 = $("#idharga1").val();
        var h2 = $("#idharga2").val();
        var h3 = $("#idharga3").val();
        var chkmodel = modelcheck();
        mchkhbaham(chkmodel);

        $("#chkEnGa_"+$param).val(chkmodel);
        $("#txtBplafon_"+$param).val(bplafon);
        $("#txtKet_"+$param).val(ket);
        $("#txtModel_"+$param).val(model);
        $("#txtQty_"+$param).val(qty);
        $("#txtKel_"+$param).val(kelengkapan);
        $("#txtD_"+$param).val(d);
        $("#txtDt_"+$param).val(dt);
        $("#txtT_"+$param).val(t);
        $("#txtTransport_"+$param).val(transport);
        $("#txtLuas_"+$param).val(l);
        $("#txtHarga1_"+$param).val(h1);
        $("#txtHarga2_"+$param).val(h2);
        $("#txtHarga3_"+$param).val(h3);
        
        $('#chkmodel').val(0);
        $("#modalDetailkubah").modal('hide');
    }
    function modelcheck(){
        var chkEnGa = '';

        //ga,en,tm
        if ($('#chkHargaGa').is(":checked") && $('#chkHargaEn').is(":checked") && $('#chkHargaTm').is(":checked")){
            chkEnGa = '0';
        //ga,en
        }else if($('#chkHargaGa').is(":checked") && $('#chkHargaEn').is(":checked") && $('#chkHargaTm').not(":checked")){
            chkEnGa = '4';
        //ga,tm
        }else if($('#chkHargaGa').is(":checked") && $('#chkHargaEn').not(":checked") && $('#chkHargaTm').is(":checked") ){
            chkEnGa = '5';
        //en,tm
        }else if( $('#chkHargaGa').not(":checked") && $('#chkHargaEn').is(":checked") && $('#chkHargaTm').is(":checked") ){
            chkEnGa = '6';
        //ga
        }else if($('#chkHargaGa').is(":checked") && $('#chkHargaEn').not(":checked") && $('#chkHargaTm').not(":checked")){
            chkEnGa = '1';
        //en
        }else if($('#chkHargaGa').not(":checked")  && $('#chkHargaEn').is(":checked") && $('#chkHargaTm').not(":checked")){
            chkEnGa = '2';
        //tm
        }else if( $('#chkHargaGa').not(":checked") && $('#chkHargaEn').not(":checked") && $('#chkHargaTm').is(":checked") ){
            chkEnGa = '3';
        }else{
            chkEnGa = '0';
        }
        return chkEnGa;
    }
    function mchkhbaham($param){
        if ($param == 0) {
            document.getElementById("chkHargaGa").checked = true;
            document.getElementById("chkHargaEn").checked = true;
            document.getElementById("chkHargaTm").checked = true;
        }else if($param == 1){
            document.getElementById("chkHargaGa").checked = true;
            document.getElementById("chkHargaEn").checked = false;
            document.getElementById("chkHargaTm").checked = false;
        }else if($param == 2){
            document.getElementById("chkHargaEn").checked = true;
            document.getElementById("chkHargaGa").checked = false;
            document.getElementById("chkHargaTm").checked = false;
        }else if($param == 3){
            document.getElementById("chkHargaTm").checked = true;
            document.getElementById("chkHargaEn").checked = false;
            document.getElementById("chkHargaGa").checked = false;
        }else if($param == 4){
            document.getElementById("chkHargaGa").checked = true;
            document.getElementById("chkHargaEn").checked = true;
            document.getElementById("chkHargaTm").checked = false;
        }else if($param == 5){
            document.getElementById("chkHargaGa").checked = true;
            document.getElementById("chkHargaTm").checked = true;
            document.getElementById("chkHargaEn").checked = false;
        }else if($param == 6){
            document.getElementById("chkHargaEn").checked = true;
            document.getElementById("chkHargaTm").checked = true;
            document.getElementById("chkHargaGa").checked = false;

        }

    }
    function checkKubah() {
        $("#jumAddJurnal").val();
        if ($("#chkmodel").val() != 0) {
            if (confirm('Cek Nilai Transport!') == true) {
                if ($("#cbomodel").val()=='custom'){
                    $("#myCModal").modal({backdrop: false});
                    var link = window.location.href;
                    var res = link.match(/mode=edit/g);
                    $("#jumAddJurnal").val(parseInt($("#jumAddJurnal").val())+1);
                    if (res == 'mode=edit') {
                        var jumrangka = parseInt($("#norangka").val())-3;
                        for (var $k = 0; $k < jumrangka ; $k++){
                            $("#txtrangka"+$k).val($('#rangka'+$k).val());
                        }
                        $("#txtrangka1").val($('#rangka1').val());
                        $("#txtrangka2").val($('#rangka2').val());
                        $("#txtrangka3").val($('#rangka3').val());
                    }
                    $('#btnrangka').click(function(){
                        var jumrangka = parseInt($("#norangka").val())-3;
                        for (var $k = 1; $k <= jumrangka ; $k++){
                            $("#rangka"+(parseInt($k)+3)).val($('#txtrangka'+(parseInt($k)+3)).val());
                        }
                        $("#rangka1").val($('#txtrangka1').val());
                        $("#rangka2").val($('#txtrangka2').val());
                        $("#rangka3").val($('#txtrangka3').val());
                        $("#myCModal").modal('hide');
                        $("#modalDetailkubah").modal('hide');
                        addarray();
                        $('#chkmodel').val(0);
                    });
                }else{
                    addarray();
                    $('#chkmodel').val(0);
                }
            } else {
                return false;
            }
        }else{
            addDetail($('#detkubah').val());
        }

        
    }
    function deleteRow(r) {
        var param = r.split("_");
        document.getElementById(r).style.display = "none";
        $("#chkAddJurnal_"+param[1]).val('');
    }
    function prangka() {
        var norangka = parseInt($("#norangka").val())+1;
        var trow = document.createElement("DIV");
        var trow2 = document.createElement("DIV");
        var inp = document.getElementById("rangka");
        var inp2 = document.getElementById("nrangka");
        trow.innerHTML+='<input type="text" class="form-control" id="txtrangka'+norangka+'" value="">';
        trow2.innerHTML+='<input type="hidden" class="form-control" id="rangka'+norangka+'" name="rangka'+norangka+'" value="">';
        inp.appendChild(trow);
        inp2.appendChild(trow2);
        $("#norangka").val(norangka);
    }

function addarray() {
    if($("#txtmket").val()=='0' )
    {
        alert("Keterangan harus diisi!");
        $("#txtmket").focus();
        return false;
    }
    if($("#cbomodel").val()=='0' )
    {
        alert("Pilih Model Kubah!");
        $("#cbomodel").focus();
        return false;
    }
    if($("#txtmD").val()=='0' )
    {
        alert("Diameter harus diisi!");
        $("#txtmD").focus();
        return false;
    }
    if($("#txtmT").val()=='' )
    {
        alert("Tinggi harus diisi!");
        $("#txtmT").focus();
        return false;
    }
    if ($("#cbomodel").val()=='bawang'){
        if($("#txtmDt").val()=='0' ){
            alert("Diameter Tengah harus diisi!");
            $("#txtmDt").focus();
            return false;
        } 
    }
    if($("#txtmongkir").val()=='' )
    {
        alert("Biaya Transport harus diisi!");
        $("#txtmongkir").focus();
        return false;
    }
    $("#jumAddJurnal").val(parseInt($("#jumAddJurnal").val())+1);

    var bplafon = $("#txtmbplafon").val().replace(/\./g,'');
    var ket = $("#txtmket").val();
    var model = $("#cbomodel").val();
    var qty = $("#txtqty").val();
    var kelengkapan = $("#cbokelengkapan").val();
    var d = $("#txtmD").val();
    var dt = $("#txtmDt").val();
    var t = $("#txtmT").val();
    var transport = $("#txtmongkir").val();
    var l = $("#idluas").val();
    var m = $("#idmargin").val();
    var h1 = $("#idharga1").val();
    var h2 = $("#idharga2").val();
    var h3 = $("#idharga3").val();
    var gold = '0';
    var chkEnGa = modelcheck();

if ($('#chkGold').is(":checked")){
    gold = '1';
}
var tcounter = $("#jumAddJurnal").val();
var ttable = document.getElementById("kendali");
var trow = document.createElement("TR");
trow.setAttribute('id','trid_'+tcounter);

$("#modalDetailkubah").modal('hide');
document.getElementById("simpan").disabled = false;

//Kolom 1 Checkbox
var td = document.createElement("TD");
td.setAttribute("align","center");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><a class="btn btn-default btn-sm" onclick=deleteRow("trid_'+tcounter+'")><i class="fa fa-fw fa-trash"></i></a><input type="hidden" class="minimal" name="chkAddJurnal_'+tcounter+'" id="chkAddJurnal_'+tcounter+'" value="1" checked /></div>';
trow.appendChild(td);

//Kolom 2 Ket
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtKet_'+tcounter+'" id="txtKet_'+tcounter+'" class="form-control" value="'+ket+'" readonly style="min-width: 120px;"></div>';
trow.appendChild(td);

//Kolom 5 Model
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtModel_'+tcounter+'" id="txtModel_'+tcounter+'" class="form-control"  value="'+model+'" readonly style="min-width: 35px;"></div>';
trow.appendChild(td);

//Kolom 6 d
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtD_'+tcounter+'" id="txtD_'+tcounter+'" class="form-control"  value="'+d+'" readonly style="min-width: 35px;"></div>';
trow.appendChild(td);

//Kolom 7 t
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtT_'+tcounter+'" id="txtT_'+tcounter+'" class="form-control"  value="'+t+'" readonly style="min-width: 35px;"></div>';
trow.appendChild(td);

//Kolom 8 dt
var td = document.createElement("TD");
td.setAttribute("align","left");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtDt_'+tcounter+'" id="txtDt_'+tcounter+'" class="form-control"  value="'+dt+'" readonly style="min-width: 35px;"><input name="txtBplafon_'+tcounter+'" id="txtBplafon_'+tcounter+'"type="hidden" value="'+bplafon+'"><input name="txtKel_'+tcounter+'" id="txtKel_'+tcounter+'" type="hidden" value="'+kelengkapan+'"><input name="txtQty_'+tcounter+'" id="txtQty_'+tcounter+'" type="hidden" value="'+qty+'"></div>';
trow.appendChild(td);

//Kolom 9 Transport
var td = document.createElement("TD");
td.setAttribute("align","right");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtTransport_'+tcounter+'" id="txtTransport_'+tcounter+'" class="form-control" readonly  value="'+transport+'" style="min-width: 120px;"></div>';
trow.appendChild(td);

//Kolom 10 h1
var td = document.createElement("TD");
td.setAttribute("align","right");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtHarga1_'+tcounter+'" id="txtHarga1_'+tcounter+'" class="form-control" readonly value="'+h1+'"style="min-width: 120px;" ></div>';
trow.appendChild(td);

//Kolom 11 h2
var td = document.createElement("TD");
td.setAttribute("align","right");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group" ><input name="txtHarga2_'+tcounter+'" id="txtHarga2_'+tcounter+'" class="form-control" readonly value="'+h2+'"style="min-width: 120px;" ><input name="chkEnGa_'+tcounter+'" id="chkEnGa_'+tcounter+'" class="form-control" type="hidden" value="'+chkEnGa+'"><input name="txtLuas_'+tcounter+'" id="txtLuas_'+tcounter+'" class="form-control" type="hidden" value="'+l+'"><input name="chkGold_'+tcounter+'" id="chkGold_'+tcounter+'" class="form-control" type="hidden" value="'+gold+'"></div>';
trow.appendChild(td);
//Kolom 12 h3
var td = document.createElement("TD");
td.setAttribute("align","right");
td.setAttribute('onclick','addmDetail('+tcounter+');');
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtHarga3_'+tcounter+'" id="txtHarga3_'+tcounter+'" class="form-control" readonly value="'+h3+'"style="min-width: 120px;" ></div>';
trow.appendChild(td);
ttable.appendChild(trow);
}

function validasiForm(form)
{
    if(form.txtnamacust.value=='' )
    {
        alert("Nama Klien harus diisi!");
        form.txtnamacust.focus();
        return false;
    }
    if(form.txtnmasjid.value=='')
    {
        alert("Nama Madjid belum diisi!");
        $("#txtnmasjid").focus();
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
    if(form.idharga2.value=='')
    {
        alert("Isikan Detail Kubah !");
        $("#modalDetailkubah").modal({backdrop: false});
        return false;
    }
    if(form.txtmongkir.value=='')
    {
        alert("Biaya Transport belum diisi!");
        $("#txtmongkir").focus();
        return false;
    }
    return true;
}
</SCRIPT>
<section class="content-header">
    <h1>
        Surat Penawaran Harga
        <small>Detail SPH</small>
    </h1>
</section>
<form action="index2.php?page=view/sph_detail" method="post" name="frmKasKeluarDetail" onSubmit="return validasiForm(this);" autocomplete="off">
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <section class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <?php
                        if ($_GET["mode"] == "edit") {
                            echo '<h3 class="box-title">UBAH SPH</h3>';
                            echo "<input type='hidden' name='txtMode' value='Edit'>";

                            if (isset($_GET["noSph"])){
                                $noSph = secureParam($_GET["noSph"], $dbLink);
                            }else{
                                $noSph = "";
                            }

                            $q = "SELECT  ROW_NUMBER() OVER(PARTITION BY ds.model ORDER BY ds.idDsph) AS id,s.*,ds.biaya_plafon,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK ";
                            $q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
                            $q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph."'";
                            $q.= " ORDER BY s.noSph desc ";
                            $rsTemp = mysql_query($q, $dbLink);
                            if ($dataSph = mysql_fetch_array($rsTemp)) {

                                echo "<input type='hidden' name='noSph' value='" . $dataSph["noSph"] . "'>";
                            } else {
                                ?>
                                <script language="javascript">
                                    alert("Kode Tidak Valid ");
                            //history.go(-1);
                            </script>
                            <?php
                            }
                            } else {
                                $q = "SELECT * FROM aki_sph where idSph=( SELECT max(idSph) FROM aki_sph )";
                                $rsTemp = mysql_query($q, $dbLink);
                                $tglTransaksi = date("Y-m-d");
                                if ($kode_ = mysql_fetch_array($rsTemp)) {
                                    $urut = "";
                                    $noSph = "";
                                    $tglTr = substr($tglTransaksi, 0,4);
                                    $bulan = bulanRomawi(substr($tglTransaksi,5,2));
                                    if ($kode_['noSph'] != ''){
                                        $urut = substr($kode_['noSph'],0, 4);
                                        $tahun = substr($kode_['noSph'],-4);
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
                                        if ($kode_['aktif']==99) {
                                            $noSph = '0001'.'/SPH-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                        }else{
                                            $noSph = $kode.'/SPH-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                        }
                                        
                                    }else{
                                        $noSph = '0001'.'/SPH-MS/PTAKI/'.$bulan.'/'.$tglTr;
                                    }

                                }
                                echo '<h3 class="box-title">Add SPH</h3>';
                                echo "<input type='hidden' name='txtMode'  value='Add'>";
                            }
                            ?>
</div>
<div class="box-body">
    <div class="form-group" >
        <div class="input-group">
            <div class="input-group-addon">
                <label class="control-label" for="txtKodeTransaksi">SPH</label>
            </div>
            <input name="txtnoSph" id="txtnoSph" maxlength="30" class="form-control" readonly value="<?php if($_GET["mode"]=='edit'){ echo $dataSph["noSph"]; }else{echo $noSph;}?>" placeholder="Nomor otomatis dibuat">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-phone">&nbsp;&nbsp;&nbsp;<label class="control-label" for="txtKodeTransaksi">Contact</label></i>
            </div>
            <input type="phone" name="txtPhone" id="txtPhone" class="form-control" data-inputmask='"mask": "9999 9999 99999"' data-mask value="<?php  if($_GET['mode']=='edit'){echo $dataSph["no_phone"]; }?>" required>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-3" style="padding-right: 0px;padding-left: 5px;">
            <select name="cbosdr" id="cbosdr" class="form-control">
                <?php
                $selected = "";
                $n=$dataSph["nama_cust"];$nm=explode(' ',$n);
                if ($_GET['mode'] == 'edit') {
                    if ($nm[0]=="Bapak") {
                        $selected = " selected";
                        echo '<option value="Bapak "'.$selected.'>Bapak</option>';
                        echo '<option value="Ibu ">Ibu</option>';
                        echo '<option value="Perusahaan ">Perusahaan</option>';
                        echo '<option value="Panitia ">Panitia</option>';
                    }elseif ($nm[0]=="Ibu") {
                        $selected = " selected";
                        echo '<option value="Bapak ">Bapak</option>';
                        echo '<option value="Ibu "'.$selected.'>Ibu</option>';
                        echo '<option value="Perusahaan ">Perusahaan</option>';
                        echo '<option value="Panitia ">Panitia</option>';
                    }elseif ($nm[0]=="Perusahaan") {
                        $selected = " selected";
                        echo '<option value="Bapak ">Bapak</option>';
                        echo '<option value="Ibu ">Ibu</option>';
                        echo '<option value="Perusahaan "'.$selected.'>Perusahaan</option>';
                        echo '<option value="Panitia ">Panitia</option>';
                    }elseif ($nm[0]=="Panitia") {
                        $selected = " selected";
                        echo '<option value="Bapak ">Bapak</option>';
                        echo '<option value="Ibu ">Ibu</option>';
                        echo '<option value="Perusahaan ">Perusahaan</option>';
                        echo '<option value="Panitia "'.$selected.'>Panitia</option>';
                    }
                }else{
                    echo '<option value="Bapak ">Bapak</option>';
                    echo '<option value="Ibu ">Ibu</option>';
                    echo '<option value="Perusahaan ">Perusahaan</option>';
                    echo '<option value="Panitia ">Panitia</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-lg-9" style="padding-right: 0px;padding-left: 5px;">
            <input name="txtnamacust" id="txtnamacust" class="form-control" 
            value="<?php  if($_GET['mode']=='edit'){
                $n=$dataSph['nama_cust'];
                $nm=[];
                if(strpos($n, 'Bapak') !== FALSE){
                    $nm=explode('Bapak',$n);
                }elseif(strpos($n, 'Ibu') !== FALSE){
                    $nm=explode('Ibu',$n);
                }elseif(strpos($n, 'Perusahaan') !== FALSE){
                    $nm=explode('Perusahaan',$n);
                }else{
                    $nm=explode('Panitia',$n);
                }
                echo $nm[1]; 
            }?>" placeholder="Client Name">
        </div>
    </div>
    <label class="control-label" for="txtKodeTransaksi">&nbsp;</label>
    <div class="form-group">
        <div class="col-lg-3" style="padding-bottom: 10px;padding-right: 0px;padding-left: 5px;">
            <select name="cbomasjid" id="cbomasjid" class="form-control">
                <?php
                $selected = "";
                $n=$dataSph["masjid"];$nm=explode(' ',$n);
                if ($_GET['mode'] == 'edit' && $dataSph['masjid']!='') {
                    if ($nm[0]=="Masjid") {
                        $selected = " selected";
                        echo '<option value="Masjid "'.$selected.'>Masjid</option>';
                        echo '<option value="Mushola ">Mushola</option>';
                        echo '<option value="Atap ">Atap</option>';
                    }elseif ($nm[0]=="Atap") {
                        $selected = " selected";
                        echo '<option value="Masjid ">Masjid</option>';
                        echo '<option value="Mushola ">Mushola</option>';
                        echo '<option value="Atap "'.$selected.'>Atap</option>';
                    }elseif ($nm[0]=="Mushola") {
                        $selected = " selected";
                        echo '<option value="Masjid ">Masjid</option>';
                        echo '<option value="Mushola " '.$selected.'>Mushola</option>';
                        echo '<option value="Atap ">Atap</option>';
                    }
                }else{
                    echo '<option value="Masjid ">Masjid</option>';
                    echo '<option value="Mushola ">Mushola</option>';
                    echo '<option value="Atap ">Atap</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-lg-9" style="padding-right: 0px;padding-left: 5px;">
            <input name="txtnmasjid" id="txtnmasjid" class="form-control" 
            value="<?php  if($_GET['mode']=='edit' && $dataSph['masjid']!=''){
                $n=$dataSph['masjid'];
                $nm=explode(' ',$n);
                if($nm[0]== 'Masjid'){
                    $nm=explode('Masjid',$n);
                    echo $nm[1];
                }else if($nm[0]== 'Mushola'){
                    $nm=explode('Mushola',$n);
                    echo $nm[1];
                }else{
                    $nm=explode('Atap',$n);
                    echo $nm[1];
                }
               }?>">
        </div>
    </div>
    <div class="form-group">
        <div class="" style="padding-bottom: 10px;padding-right: 0px;padding-left: 5px;">
            <?php  
            $q = "SELECT * from aki_rangka where `aktif`=1 and MD5(noSph)='" . $noSph."'";
            $sql_rangka = mysql_query($q,$dbLink);
            $nor=1;
            while ($rs_rangka = mysql_fetch_array($sql_rangka)) {
                if($_GET['mode']=='edit'){
                    echo '<input type="hidden" name="rangka'.$nor.'" id="rangka'.$nor.'" value="'.$rs_rangka["rangka"].'" />';
                }
                $nor++;
            }
            $q = 'SELECT provinsi.id as idP,provinsi.name as pname,kota.id as idK, kota.name as kname FROM provinsi left join kota on provinsi.id=kota.provinsi_id ORDER BY kota.name ASC';
            $sql_provinsi = mysql_query($q,$dbLink);
            if($_GET['mode']!='edit'){
                echo ' <input type="hidden" name="rangka1" id="rangka1" value=""/>
                <input type="hidden" name="rangka2" id="rangka2" value=""/>
                <input type="hidden" name="rangka3" id="rangka3" value=""/>
                <div id="nrangka"></div>';
            }else{
                echo '<div id="nrangka"></div>';
            }
            ?>
           
            <?php 
                
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
    <div class="form-group">
        <div class="" style="padding-bottom: 10px;padding-right: 0px;padding-left: 5px;">
            <select name="cboAffiliate" id="cboAffiliate" class="form-control select2" required>
                <?php
                $selected = "";
                $n=$dataSph["affiliate"];
                if ($_GET['mode'] == 'edit') {
                    if ($n=='') {
                        echo '<option value="">Affiliate</option>';
                    }else{
                        echo '<option value="'.$n.'">'.$n.'</option>';
                    }
                }else{
                    echo '<option value="">Affiliate</option>';
                }
                    $q = "SELECT * from aki_affiliate ";
                    $sql = mysql_query($q,$dbLink);
                    while ($rs = mysql_fetch_array($sql)) {
                        echo '<option value="'.$rs["name"].'">'.$rs["name"].'</option>/>';
                    }
                ?>
            </select>
        </div>
    </div>
</div>
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
                        <th style="width: 10%">Information</th>
                        <th style="width: 8%">Model</th>
                        <th style="width: 3%">D</th>
                        <th style="width: 3%">T</th>
                        <th style="width: 3%">Dt</th>
                        <th style="width: 13%">Transport</th>
                        <th style="width: 13%">&nbspGALVALUME&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                        <th style="width: 13%">&nbspENAMEL&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                        <th style="width: 13%">&nbspSS 304 Gold&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                    </tr>
                </thead>
                <tbody id="kendali">
                    <?php
                    if ($_GET['mode']=='edit'){
                        $q = "SELECT s.*,ds.gold,ds.luas,ds.bahan,ds.biaya_plafon,ds.idDsph,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,k.name as kn ";
                        $q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
                        $q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph;
                        $q.= "' ORDER BY  ds.nomer ";
                        $rsDetilJurnal = mysql_query($q, $dbLink);
                        $iJurnal = 0;
                        while ($DetilJurnal = mysql_fetch_array($rsDetilJurnal)) {
                            $kel = '';
                            echo '<div><tr id="trid_'.$iJurnal.'" >';
                            echo '<td align="center" valign="top"><div class="form-group"><a class="btn btn-default btn-sm" onclick=deleteRow("trid_' . $iJurnal . '")><i class="fa fa-fw fa-trash"></i></a>
                            <input type="hidden" checked class="minimal"  name="chkAddJurnal_' . $iJurnal . '" id="chkAddJurnal_' . $iJurnal . '" value="1"/></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input readonly type="text" class="form-control"  name="txtKet_' . $iJurnal . '" id="txtKet_' . $iJurnal . '" value="' . $DetilJurnal["ket"] . '"style="min-width: 120px;"></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control"name="txtModel_' . $iJurnal . '" id="txtModel_' . $iJurnal . '" value="' . $DetilJurnal["model"] . '" readonly="" style="min-width: 100px;"></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control"name="txtD_' . $iJurnal . '" id="txtD_' . $iJurnal . '" value="' . ($DetilJurnal["d"]) . '" readonly="" style="min-width: 45px;"></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control"name="txtT_' . $iJurnal . '" id="txtT_' . $iJurnal . '" value="' . ($DetilJurnal["t"]) . '" readonly="" style="min-width: 45px;"></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control"name="txtDt_' . $iJurnal . '" id="txtDt_' . $iJurnal . '" value="' . ($DetilJurnal["dt"]) . '" readonly="" style="min-width: 45px;"><input type="hidden" name="txtKel_' . $iJurnal . '" id="txtKel_' . $iJurnal . '" value="' . $DetilJurnal["plafon"] . '"/><input type="hidden" name="chkEnGa_' . $iJurnal . '" id="chkEnGa_' . $iJurnal . '" value="' . $DetilJurnal["bahan"] . '"/><input type="hidden" name="txtBplafon_' . $iJurnal . '" id="txtBplafon_' . $iJurnal . '" value="' . $DetilJurnal["biaya_plafon"] . '"/><input type="hidden" name="chkGold_' . $iJurnal . '" id="chkGold_' . $iJurnal . '" value="' . $DetilJurnal["gold"] . '"/><input type="hidden" name="txtQty_' . $iJurnal . '" id="txtQty_' . $iJurnal . '" value="' . $DetilJurnal["jumlah"] . '"/></div></td>';
                                echo '<input type="hidden"  name="txtLuas_' . $iJurnal . '" id="txtLuas_' . $iJurnal . '" value="' . $DetilJurnal["luas"] . '"/>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control"  name="txtTransport_' . $iJurnal . '" id="txtTransport_' . $iJurnal . '" value="' . number_format($DetilJurnal["transport"]) . '" readonly style="text-align:right;min-width: 120px;"></div></td>';

                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control"  name="txtHarga1_' . $iJurnal . '" id="txtHarga1_' . $iJurnal . '" value="' . number_format($DetilJurnal["harga"]) . '" style="text-align:right;min-width: 120px;" readonly></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control" name="txtHarga2_' . $iJurnal . '" id="txtHarga2_' . $iJurnal . '" value="' . number_format($DetilJurnal["harga2"]) . '" style="text-align:right;min-width: 120px;" readonly ></div></td>';
                            echo '<td align="center" valign="top" onclick="addmDetail('.$iJurnal.')"><div class="form-group">
                            <input type="text" class="form-control" name="txtHarga3_' . $iJurnal . '" id="txtHarga3_' . $iJurnal . '" value="' . number_format($DetilJurnal["harga3"]) . '" style="text-align:right;min-width: 120px;" readonly ></div></td></div></tr>';
                            $iJurnal++;
                        }
                    }
                    ?>
                </tbody>
            </table>
            <input type="" value="<?php if($_GET['mode']=='edit'){echo $iJurnal;}else{echo '0';} ?>" id="jumAddJurnal" name="jumAddJurnal"/>
            
            <input type="hidden" value="" id="chkeditval" name="chkeditval"/>
            <input type="hidden" value="" id="validEdit" name="validEdit"/>
            <div class="container">
                <!-- Modal -->
                <div class="modal fade" id="modalDetailkubah" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <form action="index2.php?page=view/sph_detail" method="post" name="frmPerkiraanDetail" >
                                    <div class="box-header">
                                        <input type="" name="detkubah" id="detkubah" value="">
                                        <input type="" name="chkmodel" id="chkmodel" value="0">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <i class="ion ion-clipboard"></i>
                                        <?php
                                        if ($_GET["mode"] == "edit") {
                                            echo '<h3 class="box-title">UBAH SPH </h3>';
                                            echo "<input type='hidden' name='txtMode' value='Edit'>";
                                        } else {
                                            echo '<h3 class="box-title">PENAWARAN</h3>';
                                            echo "<input type='hidden' name='txtMode'  value='Add'>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <select name="txtmket" id="txtmket" class="form-control">
                                            <option value='Kubah Utama'>Kubah Utama</option>;
                                            <option value='Mahrab'>Mahrab</option>;
                                            <option value='Anakan'>Anakan</option>;
                                            <option value='Menara'>Menara</option>;
                                            <?php
                                            if ($_SESSION['my']->privilege == 'ADMIN' || $_SESSION['my']->name == 'Antok') {
                                                echo '<option value=Atap>Atap</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group" >
                                        <select name="cbomodel" id="cbomodel" class="form-control">
                                            <option value=setbola>Setengah Bola</option>";
                                            <option value=pinang>Pinang</option>";
                                            <option value=madinah>Madinah</option>";
                                            <option value=bawang>Bawang</option>";
                                            <?php
                                            if ($_SESSION['my']->privilege == 'ADMIN' || $_SESSION['my']->privilege == 'GODMODE' || $_SESSION['my']->name == 'Antok') {
                                                echo '<option value=custom>Custom</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="txtKeteranganKas">Kelengkapan</label>
                                        <select name="cbokelengkapan" id="cbokelengkapan" class="form-control">
                                            <option value=0>Full</option>";
                                            <option value=1>Tanpa Plafon</option>";
                                            <option value=2>Waterproof</option>";
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-3">
                                            <label class="control-label" for="txtKeteranganKas">Warna</label>
                                            <div class="input-group"><span class="input-group-addon">Gold</span><span class="input-group-addon"><input type="checkbox" id="chkGold"></span></div>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="control-label" for="txtKeteranganKas">Jumlah</label>
                                            <input type="number" min='1' name="txtqty" id="txtqty" class="form-control" value="1"
                                            value="">
                                        </div>
                                        <div class="col-lg-6" id="dt">
                                            <label class="control-label" for="txtKeteranganKas">Diameter Tengah</label><div class="input-group">
                                                <input type="text"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" name="txtmDt" id="txtmDt" class="form-control" value="0" ><span class="input-group-addon">meter</span></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Diameter</label><div class="input-group">
                                                    <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  name="txtmD" id="txtmD" class="form-control" placeholder="0"
                                                    value="0" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Tinggi</label><div class="input-group">
                                                <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  name="txtmT" id="txtmT" class="form-control" placeholder="0"
                                                value="0" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
                                            </div>
                                            <div class="col-lg-6" id="dt">
                                                <label class="control-label" for="txtKeteranganKas">Plafon Motif</label><div class="input-group"><span class="input-group-addon">Rp</span>
                                                    <input type="text" name="txtmbplafon" id="txtmbplafon" class="form-control"
                                                    value="0" onfocus="" placeholder="0" ></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Transport</label><div class="input-group"><span class="input-group-addon">Rp</span>
                                                    <input type="text" name="txtmongkir" id="txtmongkir" class="form-control"
                                                    value="0" onfocus="" placeholder="0" ></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Luas</label><div class="input-group"><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"  type="text" name="idluas" id="idluas" class="form-control" placeholder="0" 
                                                    value=""><span class="input-group-addon">m<sup>2</sup></span></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Margin</label><div class="input-group"><input type="number" value=""placeholder="0" name="idmargin" id="idmargin" min="21"  class="form-control" value="0"><span class="input-group-addon">%</span></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Harga Galvalum</label><div class="input-group"><span class="input-group-addon">Rp</span><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" name="idharga1" id="idharga1" placeholder="0"class="form-control"value="0"><span class="input-group-addon"><input type="checkbox" id="chkHargaGa"checked></span></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Harga Enamel</label><div class="input-group"><span class="input-group-addon">Rp</span><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" name="idharga2" id="idharga2" placeholder="0"class="form-control" value="0"><span class="input-group-addon"><input type="checkbox" id="chkHargaEn"checked></span></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="control-label" for="txtKeteranganKas">Harga SS 304 Gold</label><div class="input-group"><span class="input-group-addon">Rp</span><input onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" type="text" name="idharga3" id="idharga3" placeholder="0"class="form-control" value="0"><span class="input-group-addon"><input type="checkbox" id="chkHargaTm"checked></span></div>
                                            </div>
                                        </div>
                                        <div class="box-footer" style="padding-top: 10%;"></div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="myCModal" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" id="closemyCModal">&times;</button>
                                                    <h4 class="modal-title">Rangka Kubah <label id="labelclr"></label></h4>
                                                    <input type="hidden" class="form-control" id="txtnomer" value="">
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <?php 
                                                        $q="SELECT * FROM aki_rangka WHERE 1=1 and `aktif`=1 and MD5(noSph)='".$noSph."'";
                                                        $rsDetilJurnal = mysql_query($q, $dbLink);
                                                        $nor=0;
                                                        while ($DetilJurnal = mysql_fetch_array($rsDetilJurnal)) {
                                                            $nor++;
                                                            if ($_GET["mode"] == "edit") {
                                                                echo '<input type="text" class="form-control" id="txtrangka'.$nor.'" value="'.$DetilJurnal["rangka"].'">';
                                                                
                                                            }
                                                        }

                                                        if ($_GET["mode"] != "edit") {
                                                            echo '<input type="text" class="form-control" id="txtrangka1" value="Rangka primer Pipa Galvanis dengan ukuran 1,5 inchi tebal 1,6 mm" placeholder="">
                                                            <input type="text" class="form-control" id="txtrangka2" value="System Rangka Double Frame (Kremona)" placeholder="#00000">
                                                            <input type="text" class="form-control" id="txtrangka3" value="Rangka Pendukung Hollow 1,5 x 3,5 cm, tebal 0,7 mm" placeholder="">';
                                                            echo '<input type="hidden"  id="norangka" name="norangka" value="3" >';
                                                        }else{
                                                            echo '<input type="hidden"  id="norangka" name="norangka" value="'.$nor.'" >';
                                                        }
                                                        ?>
                                                        
                                                        
                                                        <div id="rangka"></div>
                                                    </div><center>
                                                    <button type="button" class="btn btn-primary" id="addrangka" onclick="prangka();"><i class="fa fa-plus"></i></button></center>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="button" class="btn btn-primary" value="Add" id="btnrangka">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn btn-primary" value="Save" onclick="checkKubah();">
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
                                            //if($_GET['mode']!='edit'){
                                                echo '<center><button type="button" class="btn btn-danger" id="btnModal">Tambah Kubah</button></center>';
                                            //} 
                                            ?></td>
                                            <td><input type="submit" class="btn btn-primary" value="Save" id="simpan"></td>
                                            <td><a href="index.php?page=html/sph_list">
                                                <button type="button" class="btn btn-default pull-right">&nbsp;&nbsp;Cancel&nbsp;&nbsp;</button>    
                                            </a></td>
                                        </tr>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>
                </form>
