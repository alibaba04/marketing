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
                    $.post("function/ajax_function.php",{ fungsi: "updatehpp", txtLessGa1:$('#txtLessGa1').val(),txtLessGa2:$('#txtLessGa2').val(),txtLessGa3:$('#txtLessGa3').val(),txtLessEn1:$('#txtLessEn1').val(),txtLessEn2:$('#txtLessEn2').val(),txtLessEn3:$('#txtLessEn2').val(),txtGreaterGa1:$('#txtGreaterGa1').val(),txtGreaterGa2:$('#txtGreaterGa2').val(),txtGreaterGa3:$('#txtGreaterGa3').val(),txtGreaterEn1:$('#txtGreaterEn1').val(),txtGreaterEn2:$('#txtGreaterEn2').val(),txtGreaterEn3:$('#txtGreaterEn3').val() } ,function(data)
                    {
                        if(data=='yes') {
                            toastr.success('Sukses Update Harga Awal . . . .')
                            $("#modal-pass").modal('hide');
                        }else{
                            toastr.error('Gagal Update Harga Awal . . . .')
                        }
                    });
                }else{
                    toastr.error('Gagal !!!<br> Password Level 1 Salah . . . .')
                    $("#txtPass").focus();
                }
            });
        });
    });
</script>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="btnClose">&times;</button>
                <h4 class="modal-title">Cut Off</h4>
            </div>
            <div class="modal-body">
                
                <div class="modal-header">
                    <p>Data sebe</p>
                    <div class="input-group-addon">
                        <button type="button" class="btn btn-primary" id="btnSave">Cut Off</button>
                    </div>
                </div>
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