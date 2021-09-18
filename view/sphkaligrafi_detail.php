<?php
/* ==================================================
//=======  : Alibaba
==================================================== */
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/sphkaligrafi_detail";
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
    require_once("./class/c_sphkaligrafi.php");
    $tmpsph = new c_sphkaligrafi;
//Jika Mode Tambah/Add
    if ($_POST["txtMode"] == "Add") {
        $folderUpload = "../uploads/";
        $namaBaru='';
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
        }
        $pesan = $tmpsph->addsphk($_POST, $namaBaru);
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
        $(".select2").select2();
    });
    
</script>
<!-- Include script untuk function auto complete -->
<SCRIPT language="JavaScript" TYPE="text/javascript">
    var tcounter = 0;
    function hitluas(tcounter){
        var luas = $("#txtD_"+tcounter).val() * $("#txtT_"+tcounter).val() * 3.14;
        $("#txtLuas_"+tcounter).val(luas);
    }
    function addarray() {
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

        if($("#txtBiayaPlafon").val()=='' )
        {
            alert("Biaya Transport harus diisi!");
            $("#txtBiayaPlafon").focus();
            return false;
        }

        var bplafon = $("#txtBiayaPlafon").val().replace(/\./g,'');
        var d = $("#txtD").val();
        var t = $("#txtT").val();
        var ppn = 1;
        var transport = 1;
        if ($('#chkTransport').is(":checked") || $('#chkPPN').is(":checked")){
            ppn = '1';
            transport = '1';
        }else{
            ppn = '0';
            transport = '0';
        }
        var luas = d * t * 3.14;
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
            $("#txtD_"+$('#validEdit').val()).val( $("#txtD").val());
            $("#txtT_"+$('#validEdit').val()).val($('#txtT').val());
            $("#txtBplafon_"+$('#validEdit').val()).val($('#txtBiayaPlafon').val());
            $("#chkEnGa_"+$('#validEdit').val()).val(chkEnGa);
        }else{

            var ttable = document.getElementById("kendali");
            var trow = document.createElement("TR");

            trow.setAttribute('id','trid_'+tcounter);
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

var td = document.createElement("TD");
td.setAttribute("align","left");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input type="number" name="txtD_'+tcounter+'" id="txtD_'+tcounter+'" class="form-control"  value="0" style="min-width: 35px;" onKeyUp="hitluas('+tcounter+')"></div>';
trow.appendChild(td);

var td = document.createElement("TD");
td.setAttribute("align","left");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input type="number" name="txtT_'+tcounter+'" id="txtT_'+tcounter+'" class="form-control"  value="0" style="min-width: 35px;" onKeyUp="hitluas('+tcounter+')"></div>';
trow.appendChild(td);

var td = document.createElement("TD");
td.setAttribute("align","right");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input name="txtBplafon_'+tcounter+'" id="txtBplafon_'+tcounter+'" class="form-control" onkeydown="return numbersonly(this, event);" value="0"style="min-width: 120px;" ></div>';
trow.appendChild(td);

var td = document.createElement("TD");
td.setAttribute("align","left");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input type="checkbox" class="minimal" name="chkPPN_'+tcounter+'" id="chkPPN_'+tcounter+'" value="1" checked /> <label>PPN 10%</label></div>';
trow.appendChild(td);

var td = document.createElement("TD");
td.setAttribute("align","left");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group" ><input type="checkbox" class="minimal" name="chkTransport_'+tcounter+'" id="chkTransport_'+tcounter+'" value="1" checked /> <label>Harga termasuk Biaya Transport</label></div>';
trow.appendChild(td);

var td = document.createElement("TD");
td.setAttribute("align","left");
td.style.verticalAlign = 'top';
td.innerHTML+='<div class="form-group"><input disabled type="file" name="listGambar[]" accept="image/*" multiple></div>';
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
        Surat Penawaran Harga Kaligrafi
        <small>Detail SPH Kaligrafi</small>
    </h1>
</section>
<form action="index2.php?page=view/sphkaligrafi_detail" method="post" name="frmKasKeluarDetail" enctype="multipart/form-data" onSubmit="return validasiForm(this);" autocomplete="off">
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
                            $q= " SELECT s1.*,'Kaligrafi' as model,ds1.d,ds1.t,'-' as dt,'-' as plafon,ds1.harga,'-' as harga2,'-' as jumlah,'-' as ket,'-' as transport, u1.kodeUser, u1.nama, p1.name as pn, k1.name as kn ";
                            $q.= "FROM aki_sph s1 right join aki_dkaligrafi ds1 on s1.noSph=ds1.noSph left join aki_user u1 on s1.kodeUser=u1.kodeUser left join provinsi p1 on s1.provinsi=p1.id LEFT join kota k1 on s1.kota=k1.id ";
                            $q.= "WHERE 1=1 and MD5(s1.noSph)='" . $noSph."'";
                            $q.= " ORDER BY idSph desc ";
                            /*
                            $q = "SELECT  ROW_NUMBER() OVER(PARTITION BY ds.model ORDER BY ds.idDsph) AS id,s.*,ds.biaya_plafon,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,p.id as idP,k.name as kn,k.id as idK ";
                            $q.= "FROM aki_sph s right join aki_dkaligrafi ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
                            $q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph."'";
                            $q.= " ORDER BY s.noSph desc ";*/
                            $rsTemp = mysql_query($q, $dbLink);
                            if ($dataSph = mysql_fetch_array($rsTemp)) {

                                echo "<input type='hidden' name='noSph' value='" . $dataSph["noSph"] . "'>";
                            } else {
                                ?>
                                <script language="javascript">
                                    alert($q);
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
            $noSph = $kode.'/SPH-MS/PTAKI/'.$bulan.'/'.$tglTr;

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
        <label class="control-label" for="txtKodeTransaksi">Number SPH</label>
        <input name="txtnoSph" id="txtnoSph" maxlength="30" class="form-control" 
        readonly value="<?php if($_GET["mode"]=='edit'){ echo $dataSph["noSph"]; }else{echo $noSph;}?>" placeholder="Nomor otomatis dibuat">
    </div>
    <label class="control-label" for="txtKeteranganKas">Client</label>
    <div class="form-group">
        <div class="col-lg-3" style="padding-bottom: 10px;padding-right: 0px;padding-left: 5px;">
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
            value="<?php  if($_GET['mode']=='edit'){$n=$dataSph['nama_cust']; $nm=explode(' ',$n);echo $nm[1]; }?>" placeholder="Client Name">
        </div>
    </div>
    <label class="con trol-label" for="txtTglTransaksi">&nbsp;</label>
    <div class="form-group">
            <input name="txtket" id="txtket" class="form-control" 
            value="<?php   if($_GET["mode"]=='edit'){ echo $dataSph["masjid"]; }?>" placeholder="Keterangan" >
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
                        <th style="width: 3%"><i class='fa fa-edit'></i></th>
                        <th style="width: 8%">Diameter</th>
                        <th style="width: 8%">Tinggi</th>
                        <th style="width: 20%">Harga Kaligrafi</th>
                        <th style="width: 8%">PPN</th>
                        <th style="width: 18%">Transport</th>
                        <th style="width: 40%">File Desain </th>
                    </tr>
                </thead>
                <tbody id="kendali">
                    <?php
                    if ($_GET['mode']=='edit'){
                        $q= " SELECT s1.*,ds1.*";
                        $q.= "FROM aki_sph s1 right join aki_dkaligrafi ds1 on s1.noSph=ds1.noSph left join aki_user u1 on s1.kodeUser=u1.kodeUser left join provinsi p1 on s1.provinsi=p1.id LEFT join kota k1 on s1.kota=k1.id ";
                        $q.= "WHERE 1=1 and MD5(s1.noSph)='" . $noSph."'";
                        $q.= " ORDER BY idSph desc ";

                        /*$q = "SELECT s.*,ds.luas,ds.bahan,ds.biaya_plafon,ds.idDsph,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.harga3,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,k.name as kn ";
                        $q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
                        $q.= "WHERE 1=1 and MD5(s.noSph)='" . $noSph;
                        $q.= "' ORDER BY  ds.nomer ";*/
                        $rsDetilJurnal = mysql_query($q, $dbLink);
                        $iJurnal = 0;
                        while ($DetilJurnal = mysql_fetch_array($rsDetilJurnal)) {
                            $kel = '';
                            echo '<div><tr id="trid_'.$iJurnal.'" >';
                            echo '<td align="center" valign="top" ><div class="form-group">
                            <input type="checkbox" class="minimal" checked name="chkEdit_' . $iJurnal . '" id="chkEdit_' . $iJurnal . '" value="' . $DetilJurnal["idDkaligrafi"] . '" /></div></td>';
                            echo '<td align="center" valign="top"><div class="form-group">
                            <input type="text" class="form-control"name="txtD_' . $iJurnal . '" id="txtD_' . $iJurnal . '" value="' . ($DetilJurnal["d"]) . '" readonly="" style="min-width: 45px;"></div></td>';
                            echo '<td align="center" valign="top"><div class="form-group">
                            <input type="text" class="form-control"name="txtT_' . $iJurnal . '" id="txtT_' . $iJurnal . '" value="' . ($DetilJurnal["t"]) . '" readonly="" style="min-width: 45px;"></div></td>';
                            echo '<td align="center" valign="top"><div class="form-group">
                            <input type="text" class="form-control"  name="txtHarga1_' . $iJurnal . '" id="txtHarga1_' . $iJurnal . '" value="' . number_format($DetilJurnal["harga"]) . '" style="text-align:right;min-width: 120px;" readonly></div></td>';
                            echo '<td align="center" valign="top"><div class="form-group">
                            <input type="text" class="form-control"  name="txtHarga1_' . $iJurnal . '" id="txtHarga1_' . $iJurnal . '" value="' . number_format($DetilJurnal["ppn"]) . '" style="text-align:right;min-width: 120px;" readonly></div></td>';
                            echo '<td align="center" valign="top"><div class="form-group">
                            <input type="text" class="form-control"  name="txtHarga1_' . $iJurnal . '" id="txtHarga1_' . $iJurnal . '" value="' . number_format($DetilJurnal["transport"]) . '" style="text-align:right;min-width: 120px;" readonly></div></td>';
                            echo '<td align="center" valign="top"><div class="form-group">
                            <input type="text" class="form-control"  name="txtHarga1_' . $iJurnal . '" id="txtHarga1_' . $iJurnal . '" value="' . ($DetilJurnal["kaligrafi"]) . '" style="text-align:right;min-width: 120px;" readonly></div></td>';


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
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <form action="index2.php?page=view/sphkaligrafi_detail" method="post" name="frmPerkiraanDetail" >
                                    <div class="box-header">
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
                                        <div class="col-lg-12">
                                            <label class="control-label" for="txtKeteranganKas">Harga</label><div class="input-group"><span class="input-group-addon">Rp</span>
                                                <input type="text" name="txtBiayaPlafon" id="txtBiayaPlafon" class="form-control"
                                                value="0" onfocus="" placeholder="0" ></div>
                                        </div>
                                    </div>
                                    <label class="control-label" for="txtTglTransaksi">&nbsp;</label>
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <label for="exampleInputFile">File Desain </label>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="input-group"><input type="checkbox" id="chkPPN" checked><label class="control-label" for="chkPPN">&nbsp;&nbsp;Harga termasuk PPN</label></input></div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="input-group"><input type="checkbox" id="chkTransport"checked><label class="control-label" for="chkTransport">&nbsp;&nbsp;Harga termasuk Biaya Transport</label></div>
                                        </div>
                                    </div>
                                    <div class="box-footer" style="padding-top: 1%;"></div>
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
                                                echo '<center><button type="button" class="btn btn-danger" onclick="addarray()">Tambah Kubah</button></center>';
                                            } 
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
