<?php
/* ==================================================
  //=======  : Alibaba
==================================================== */
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');
$curPage = "view/harga_detail";

//Periksa hak user pada modul/menu ini
$judulMenu = 'Pengaturan User';
$hakUser = getUserPrivilege($curPage);

if ($hakUser != 90 ) {
    unset($_SESSION['my']);
    echo "<p class='error'>";
    die('User anda tidak terdaftar untuk mengakses halaman ini!');
    echo "</p>";
}
?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () { 
        $("#myModal").modal({backdrop: 'static'});
        $('#btnClose').click(function(){
            location.href='index.php';
        });
        $('#btnSave').click(function(){
            $("#modal-pass").modal({backdrop: 'static'});
        });
        $('#btnSubmit').click(function(){
            var password = $('#txtPass').val();
            $.post("function/ajax_function.php",{ fungsi: "cekpass", kodeUser:"alibaba",pass:password } ,function(data)
            {
                if(data=='yes') {
                    $.post("function/ajax_function.php",{ fungsi: "updatehpp", txtpinang1:$('#txtpinang1').val(),txtpinang2:$('#txtpinang2').val(),txtpinang3:$('#txtpinang3').val(), txtmadina1:$('#txtmadina1').val(),txtmadina2:$('#txtmadina2').val(),txtmadina3:$('#txtmadina3').val(),txtbawang1:$('#txtbawang1').val(),txtbawang2:$('#txtbawang2').val(),txtbawang3:$('#txtbawang3').val(),txtsetbola1:$('#txtsetbola1').val(),txtsetbola2:$('#txtsetbola2').val(),txtsetbola3:$('#txtsetbola3').val()} ,function(data)
                    {
                        if(data=='yes') {
                            toastr.success('Sukses Update Harga Awal . . . .')
                            $("#modal-pass").modal('hide');
                        }else{
                            toastr.error('Gagal Update Harga Awal . . . .')
                        }
                    });
                }else{
                    toastr.error('Gagal !!!<br> Password LitBank Level 1 Salah . . . .')
                    $("#txtPass").focus();
                }
            });
        });
    });
</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnClose">&times;</button>
                <h4 class="modal-title">Set Harga Awal</h4>
            </div>
            <div class="modal-body">
                
                <div class="modal-header" style="background-color: #fafafa;">
                    <?php
                    $q= "SELECT * FROM `aki_hpp` where id='1000' and aktif=0";
                    $rsTemp = mysql_query($q, $dbLink);
                    if ($hpppinang = mysql_fetch_array($rsTemp)) {}
                    ?>
                    <div class="modal-header">
                        <center><h4 class="modal-title">Pinang</h4></center>
                    </div>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Galvalume</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtpinang1" id="txtpinang1" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hpppinang['full']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Stainless Gold</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtpinang2" id="txtpinang2" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hpppinang['waterproof']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Enamel</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtpinang3" id="txtpinang3" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hpppinang['tplafon']);?>">
                        </div>
                    </section>
                </div>
                <div class="modal-header" style="background-color: #f0f0f0;">
                    <?php
                    $q= "SELECT * FROM `aki_hpp` where id='3000' and aktif=0";
                    $rsTemp = mysql_query($q, $dbLink);
                    if ($hppmadina = mysql_fetch_array($rsTemp)) {}
                    ?>
                    <div class="modal-header">
                        <center><h4 class="modal-title">Madina</h4></center>
                    </div>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Galvalume</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtmadina1" id="txtmadina1" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppmadina['full']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Stainless Gold</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtmadina2" id="txtmadina2" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppmadina['waterproof']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Bawang</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtmadina3" id="txtmadina3" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppmadina['tplafon']);?>">
                        </div>
                    </section>
                </div>
                <div class="modal-header" style="background-color: #fafafa;" >
                    <?php
                    $q= "SELECT * FROM `aki_hpp` where id='5000' and aktif=0";
                    $rsTemp = mysql_query($q, $dbLink);
                    if ($hppbawang = mysql_fetch_array($rsTemp)) {}
                    ?>
                    <div class="modal-header">
                        <center><h4 class="modal-title">Bawang</h4></center>
                    </div>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Galvalume</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtbawang1" id="txtbawang1" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppbawang['full']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Stainless Gold</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtbawang2" id="txtbawang2" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppbawang['waterproof']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Enamel</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtbawang3" id="txtbawang3" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppbawang['tplafon']);?>">
                        </div>
                    </section>
                </div>
                <div class="modal-header" style="background-color: #f0f0f0;">
                    <?php
                    $q= "SELECT * FROM `aki_hpp` where id='7000' and aktif=0";
                    $rsTemp = mysql_query($q, $dbLink);
                    if ($hppsbola = mysql_fetch_array($rsTemp)) {}
                    ?>
                    <div class="modal-header">
                        <center><h4 class="modal-title">Set. Bola</h4></center>
                    </div>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Galvalume</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtsetbola1" id="txtsetbola1" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppsbola['full']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Stainless Gold</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtsetbola2" id="txtsetbola2" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppsbola['waterproof']);?>">
                        </div>
                    </section>
                    <section class="col-lg-4">
                        <label class="control-label" for="txtTglTransaksi">Enamel</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label class="control-label" for="txtTglTransaksi">Rp.</label>
                            </div>
                            <input type="text" name="txtsetbola3" id="txtsetbola3" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" value="<?php echo ($hppsbola['tplafon']);?>">
                        </div>
                    </section>
                </div>
            </div>
            <div class="modal-footer">
            <?php
                echo '<button type="button" class="btn btn-primary" id="btnSave">Save changes</button>';
            ?>
            </div>
        </div>
    </div>
</div> 
<div class="modal fade" id="modal-pass">
    <div class="modal-dialog">
        <div class="modal-content bg-secondary">
            <div class="modal-header">
                <h4 class="modal-title">Input Password Level 1 (Owner)</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <div class="input-group-addon">
                        <label class="control-label" for="txtTglTransaksi">Password</label>
                    </div>
                    <input type="password" name="txtPass" id="txtPass" class="form-control">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSubmit">Save changes</button>
            </div>
        </div>
    </div>
</div>