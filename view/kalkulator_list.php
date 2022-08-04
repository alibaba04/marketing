<?php
//=======  : Alibaba
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/kalkulator_list";

//Periksa hak user pada modul/menu ini
$judulMenu = 'Kalkulator';
$hakUser = getUserPrivilege($curPage);

if ($hakUser < 10) {
    session_unregister("my");
    echo "<p class='error'>";
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
    echo "</p>";
}

//Periksa apakah merupakan proses headerless (tambah, edit atau hapus) dan apakah hak user cukup
if (substr($_SERVER['PHP_SELF'], -10, 10) == "index2.php" && $hakUser == 90) {

    require_once("./class/c_jurnalumum.php");
    $tmpJurnalUmum = new c_jurnalumum;

    //Jika Mode Tambah/Add
    if ($_POST["txtMode"] == "Add") {
        $pesan = $tmpJurnalUmum->add($_POST);
    }

    //Jika Mode Ubah/Edit
    if ($_POST["txtMode"] == "Edit") {
        $pesan = $tmpJurnalUmum->edit($_POST);
    }
    
    //Jika Mode Upload
    if ($_POST["txtMode"] == "Upload") {
        $pesan = $tmpJurnalUmum->upload($_POST);
    }

    //Jika Mode Hapus/Delete
    if ($_GET["txtMode"] == "Delete") {
        $pesan = $tmpJurnalUmum->delete($_GET["kode"]);
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
    var th1 = 0;
    var th2 = 0;
    $(function () {
        $('#tglTransaksi').daterangepicker({ 
            locale: { format: 'DD-MM-YYYY' } });
    });
    function convertToRupiah(angka)
    {
        var rupiah = '';        
        var angkarev = angka.toString().split('').reverse().join('');
        for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            return rupiah.split('',rupiah.length-1).reverse().join('');
    }
    function convertToAngka(rupiah)
    {
        var a = rupiah.replace(/[^0-9\.]+/g, '');
        var result = a.replace(/\./g,'');
        return (result);
    }
    function kalkulatorharga(){
        var a = $('#txtongkir').val();
        var v = a.replace(/[^0-9\.]+/g, '');
        var d_ongkir = v.replace(/\./g,'');
        $.post("function/ajax_function.php",{ fungsi: "kalkulator",model:$('#cbomodel').val(),d:$('#txtD').val(),t:$('#txtT').val(),dt:$('#txtDT').val(),kel:$('#cbokelengkapan').val(),ongkir:d_ongkir,margin:$('#idmargin').val(),bplafon:0},function(data)
        {
            $('#idluas').val(data.luas);
            $('#idmargin').attr("placeholder", data.margin);
            $('#idharga1').val(data.hargaga);
            $('#idharga2').val(data.hargass);
            $('#idharga3').val(data.hargaen);
        },"json");
    }
    function kalkulatorhargaMIN(){
        var a = $('#txtongkir').val();
        var v = a.replace(/[^0-9\.]+/g, '');
        var d_ongkir = v.replace(/\./g,'');

        $.post("function/ajax_function.php",{ fungsi: "kalkulator",d:$('#txtD').val(),t:$('#txtT').val(),dt:$('#txtDT').val(),kel:$('#cbokelengkapan').val(),ongkir:d_ongkir,margin:30,bplafon:0},function(data)
        {
            $('#idharga1').val(data.harga);
            $('#idharga2').val(data.harga2);
            $('#idharga3').val(data.harga3);
           
        },"json");
    }
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
            $("#cbomodel").change(function(){ 
                var cbomodel = $("#cbomodel").val(); 
                var dt = $("#txtDT").val(); 
                if(cbomodel == 'bawang'){
                    $("#dt :input").prop("readonly", false);
                }else{
                    $("#dt :input").prop("readonly", true);
                    $("#txtDT").val(0); 
                }
            });
            $("#txtqty").change(function(){ 
                kalkulatorharga();
                kalkulatorhargaMIN();
            });
            $("#txtD").keyup(function(){ 
                var cbomodel = $("#cbomodel").val(); 
                var d = $("#txtD").val();
                if(cbomodel == 'bawang'){
                    var dt = (0.23*(d))+parseFloat(d);
                    var resdt = dt.toString().length;
                    if (resdt > 5) {
                        $("#txtDT").val(dt.toFixed(3));
                    }else{
                        $("#txtDT").val(dt);
                    }
                    $("#txtT").val(d);
                }else if(cbomodel == 'pinang'){
                    $("#txtT").val(d);
                }else if(cbomodel == 'madinah'){
                    $("#txtT").val(d*0.75);
                }else if(cbomodel == 'setbola'){
                    $("#txtT").val(d*0.5);
                }

                kalkulatorharga();
                kalkulatorhargaMIN();
            });
            $("#txtT").keyup(function(){ 
                kalkulatorharga();
                kalkulatorhargaMIN();
            });
            $("#idmargin").keyup(function(){ 
                kalkulatorharga();
                kalkulatorhargaMIN();
            });
            
            $("#cbokelengkapan").change(function(){ 
                kalkulatorharga();
                kalkulatorhargaMIN();
            });

            var rupiah = document.getElementById('txtongkir');
            rupiah.addEventListener('keyup', function(e){
                rupiah.value = formatRupiah(this.value,'');
                kalkulatorharga();
                kalkulatorhargaMIN();
            });
        });
    </script>
    <!-- End of Script Tanggal -->
    <section class="content-header">
        <h1>
            KALKULATOR
            <small>KALKULATOR</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Input</li>
            <li class="active">Kalkulator</li>
        </ol>
    </section>
    <!-- Modal-->

    <section class="content">
        <!-- Main row -->
        <div class="row">
            <section class="col-lg-7">
                <div class="box box-primary">
                    <div class="box-header">
                        <form action="index2.php?page=view/sph_detail" method="post" name="frmPerkiraanDetail" >
                            <div class="box-header">
                                <i class="ion ion-clipboard"></i>
                                <h3 class="box-title">KALKULATOR PENAWARAN</h3>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="cbomodel" id="cbomodel" class="form-control">
                                    <option value="0">--Pilih Model Kubah--</option>
                                    <?php
                                    $selected = "";
                                    if ($_GET['mode'] == 'edit') {
                                        if ($dataSph['model']=="bawang") {
                                            $selected = " selected";
                                            echo "<option value=bawang" . $selected . ">Bawang</option>";
                                            echo "<option value=pinang>Pinang</option>";
                                            echo "<option value=madinah>Madinah</option>";
                                            echo "<option value=setbola>Setengah Bola</option>";
                                        }elseif ($dataSph['model']=="pinang") {
                                            $selected = " selected";
                                            echo "<option value=bawang>Bawang</option>";
                                            echo "<option value=pinang" . $selected . ">Pinang</option>";
                                            echo "<option value=madinah>Madinah</option>";
                                            echo "<option value=setbola>Setengah Bola</option>";
                                        }elseif ($dataSph['model']=="madinah") {
                                            $selected = " selected";
                                            echo "<option value=bawang>Bawang</option>";
                                            echo "<option value=pinang>Pinang</option>";
                                            echo "<option value=madinah" . $selected . ">Madinah</option>";
                                            echo "<option value=setbola>Setengah Bola</option>";
                                        }elseif ($dataSph['model']=="setbola") {
                                            $selected = " selected";
                                            echo "<option value=bawang>Bawang</option>";
                                            echo "<option value=pinang>Pinang</option>";
                                            echo "<option value=madinah>Madinah</option>";
                                            echo "<option value=setbola" . $selected . ">Setengah Bola</option>";
                                        }
                                    }else{
                                        echo "<option value=bawang>Bawang</option>";
                                        echo "<option value=pinang>Pinang</option>";
                                        echo "<option value=madinah>Madinah</option>";
                                        echo "<option value=setbola>Setengah Bola</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label class="control-label" for="txtKeteranganKas">Jumlah</label>
                                    <input type="number" min='1' name="txtqty" id="txtqty" class="form-control" value="1"
                                    value="<?php if($_GET["mode"]=='edit'){ echo $dataSph["qty"]; }?>">
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label" for="txtKeteranganKas">Kelengkapan</label>
                                    <select name="cbokelengkapan" id="cbokelengkapan" class="form-control">
                                        <?php
                                        $selected = "";
                                        if ($_GET['mode'] == 'edit') {
                                            if ($dataSph['plafon']==0) {
                                                $selected = " selected";
                                                echo "<option value=0" . $selected . ">Full</option>";
                                                echo "<option value=1>Tanpa Plafon</option>";
                                                echo "<option value=2>Waterproof</option>";
                        //echo "<option value=3>Pelapisan</option>";
                                            }elseif ($dataSph['plafon']==1) {
                                                $selected = " selected";
                                                echo "<option value=0>Full</option>";
                                                echo "<option value=1" . $selected . ">Tanpa Plafon</option>";
                                                echo "<option value=2>Waterproof</option>";
                        //echo "<option value=3>Pelapisan</option>";
                                            }elseif ($dataSph['plafon']==2) {
                                                $selected = " selected";
                                                echo "<option value=0>Full</option>";
                                                echo "<option value=1>Tanpa Plafon</option>";
                                                echo "<option value=2" . $selected . ">Waterproof</option>";
                        //echo "<option value=3>Pelapisan</option>";
                    }/*elseif ($dataSph['plafon']==3) {
                        $selected = " selected";
                        echo "<option value=0>Full</option>";
                        echo "<option value=1>Tanpa Plafon</option>";
                        echo "<option value=2>Waterproof</option>";
                        echo "<option value=3" . $selected . ">Pelapisan</option>";
                    }*/
                }else{
                    echo "<option value=0>Full</option>";
                    echo "<option value=1>Tanpa Plafon</option>";
                    echo "<option value=2>Waterproof</option>";
                    //echo "<option value=3>Pelapisan</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-lg-6">
            <label class="control-label" for="txtKeteranganKas">Diameter</label><div class="input-group">
                <input type="number" min='0'step="0.1" name="txtD" id="txtD" class="form-control" placeholder="0"
                value="" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
            </div>
            <div class="col-lg-6">
                <label class="control-label" for="txtKeteranganKas">Tinggi</label><div class="input-group">
                    <input type="number" min='0' step="0.1" name="txtT" id="txtT" class="form-control" placeholder="0"
                    value="" onfocus="this.value=''"><span class="input-group-addon">meter</span></div>
                </div>
                <div class="col-lg-6" id="dt">
                    <label class="control-label" for="txtKeteranganKas">Diameter Tengah</label><div class="input-group">
                        <input type="number" readonly min='0' step="0.1" name="txtDT" id="txtDT" class="form-control" value="0" ><span class="input-group-addon">meter</span></div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label" for="txtKeteranganKas">Transport</label><div class="input-group"><span class="input-group-addon">Rp</span>
                            <input type="text" name="txtongkir" id="txtongkir" class="form-control"
                            onfocus="this.value=''" placeholder="0" ></div>
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label" for="txtKeteranganKas">Luas</label><div class="input-group"><input readonly type="text" name="idluas" id="idluas" class="form-control" placeholder="0" 
                                value=""><span class="input-group-addon">m<sup>2</sup></span></div>
                            </div>
                            <div class="col-lg-6">
                                <label class="control-label" for="txtKeteranganKas">Margin</label><div class="input-group"><input type="text" value=""placeholder="0" name="idmargin" id="idmargin" class="form-control" value="0"><span class="input-group-addon">%</span></div>
                            </div>
                            <div class="box-footer" style="padding-top: 10%;"></div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </section>
                <section class="col-lg-4 connectedSortable">
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="ion ion-clipboard"></i>
                        </div><ol style="margin-right: 30px;">
                            <div class="box-primary">
                                <label class="control-label" for="txtKeteranganKas">Harga Galvalum (Terendah)</label><div class="input-group"><span class="input-group-addon">Rp</span><input readonly type="text" name="idharga1" id="idharga1" placeholder="0"class="form-control"value=""></div>
                            </div><br><br>
                            <div class="box-primary">
                                <label class="control-label" for="txtKeteranganKas">Harga Stainless Gold (Terendah)</label><div class="input-group"><span class="input-group-addon">Rp</span><input readonly type="text" name="idharga2" id="idharga2" placeholder="0"class="form-control"value=""></div>
                            </div><br><br>
                            <div class="box-primary">
                                <label class="control-label" for="txtKeteranganKas">Harga Enamel (Terendah)</label><div class="input-group"><span class="input-group-addon">Rp</span><input readonly type="text" name="idharga3" id="idharga3" placeholder="0"class="form-control"
                                value=""></div>
                                </div>
                            </ol>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </section>
