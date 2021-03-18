<?php
/* ==================================================
  //=======  : Alibaba
  ==================================================== */
//Memastikan file ini tidak diakses secara langsung (direct access is not allowed)
defined('validSession') or die('Restricted access');

define('txtUserID', 'txtUserID'); 
if (isset($_POST[txtUserID])) {
    require_once('./class/c_login.php');
    $tmpLogin = new c_login();
//    if (strtoupper($_POST["captcha_code"]) != strtoupper($_SESSION['captcha_id']) && !reserved_ip($_SERVER['REMOTE_ADDR'])) {
//        header("Location:index.php?page=login_detail&eventCode=30"); 	
//        exit;
//    } 
    $tempResult = $tmpLogin->validateUser($_POST[txtUserID]);
    if ($tempResult == 'Sukses') {
        header("Location:index.php");
        exit;
    } else {
        header("Location:index.php?page=login_detail&eventCode=" . $tempResult);
        exit;
    }
} else {
    ?>


    <div class="login-box">
        <div class="login-logo">
            <img src="dist/img/logo-qoobah2.png" width="240" height="200">
<!--             <br />
            <h5>AKI</h5>
            <h5>-</h5>
 -->            
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
<!--            <p class="login-box-msg">Log in to start your session</p>-->
            <p class="login-box-msg">
                <font style="color:#FF0000;">
                <?php
                define('eventCode','eventCode');
                if (isset($_GET['eventCode'])){
                    switch ($_GET['eventCode']) {
                        case 10:
                            echo('User ID atau Password tidak valid!');
                            break;
                        case 20:
                            echo('Log out Success!');
                            unset($_SESSION['my']);
                            break;
                        case 30:
                            echo('User ID atau Password tidak valid!');
                            break;
                        case 90:
                            echo('Log In ...');
                            unset($_SESSION['my']);
                            break;
                        default:
                            echo('Log In!');
                            break;
                    }
                }else{
                    echo('SIGN IN');
                }
                ?>
                </font>
            </p>

            <form id="loginform" name="loginform" action="index2.php?page=login_detail" method="post">
                <div class="form-group has-feedback">
                    <input type="text" name="txtUserID" id="txtUserID" class="form-control" placeholder="Username" style="border-radius: 4px;">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="txtPassword" id="txtPassword" class="form-control" placeholder="Password" style="border-radius: 4px;">
                    <span class="glyphicon glyphicon-wrench form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" style="border-radius: 4px;">SIGN IN</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <footer class="login-footer" style="padding-right: 20px;">

        <div class="pull-right hidden-xs" style="color: white;">
            <b>Marketing App</b> 2.0.0 &nbsp;&nbsp;<strong>Created by: <a href="http://instagram.com/baihaqial" style="color: white;">alibaba</a>.
            </div>
            <strong>.</strong>
            <!-- <strong>Copyright &copy; 2020 <a href="http://instagram.com/baihaqial">alibaba's</a>.</strong> All rights reserved.  -->

        </footer>
    <!-- /.login-box -->

    <?php
}

?>