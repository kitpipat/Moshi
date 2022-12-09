<!doctype html>
<html lang="th" class="fullscreen-bg">
<head>
    <title>Login | Moshi Moshi</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url();?>application/modules/authen/assets/images/AdaLogo.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url();?>application/modules/authen/assets/images/AdaLogo.png">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/vendor/bootstrap/css/bootstrap.custom.css">
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/vendor/linearicons/style.css">
    <link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/vendor/chartist/css/chartist-custom.css">
    <!-- MAIN CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/css/main.css">
    <!-- Login CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/css/localcss/ada.login.css">
    <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
    <link rel="stylesheet" href="<?php echo base_url();?>application/modules/authen/assets/css/demo.css">
    <!-- jquery -->
    <script src="<?php echo base_url();?>application/modules/authen/assets/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url();?>application/modules/authen/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <style>
        body{
            background-image: url('application/modules/authen/assets/images/bg/Moshi-Backoffice.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .xWLoginBox{
            border-radius: 5px !important;
        }

        .xWLoginBox {
            -webkit-box-shadow: 0px 0px 105px -23px rgba(0,0,0,1);
            -moz-box-shadow: 0px 0px 105px -23px rgba(0,0,0,1);
            box-shadow: 0px 0px 43px -25px rgba(0,0,0,1)
        }
    </style>
</head>
<body>
    <!-- WRAPPER -->
    <div id="wrapper">
        <div class="vertical-align-wrap">
            <div class="vertical-align-middle">
                <div class="auth-box lockscreen clearfix xWLoginBox">
                    <div class="content">
                        <div class="logo text-center">
                            <img src="<?php echo base_url();?>application/modules/authen/assets/images/AdaStatDose.PNG" alt="Ada Logo">
                        </div>
                        <form class="form-auth-small" onclick="JSxCheckLogin();" action="Checklogin" method="POST">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="signin-email" class="control-label sr-only"><?php echo language('authen/login/login', 'tUsernameCode');?></label>
                                        <input type="text" required oninvalid="this.setCustomValidity('<?php echo language('authen/login/login', 'tRequireUsr');?>')" oninput="setCustomValidity('')" class="form-control xWCtlForm" id="oetUsername" name="oetUsername" placeholder="<?php echo language('authen/login/login', 'tUsernameCode');?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="signin-password" class="control-label sr-only"><?php echo language('authen/login/login', 'tPassword');?></label>
                                        <input type="password" required oninvalid="this.setCustomValidity('<?php echo language('authen/login/login', 'tRequirePw');?>')" oninput="setCustomValidity('')" class="form-control xWCtlForm" id="oetPassword" name="oetPassword" placeholder="<?php echo language('authen/login/login', 'tPassword');?>">
                                        <input type="hidden" id="oetPasswordhidden" name="oetPasswordhidden">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group clearfix">
                                        <label class="fancy-checkbox element-left">
                                            <input type="checkbox">
                                            <span><?php echo language('authen/login/login', 'tRememberMe');?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="dropdown" style="float:right;">
                                        <?php
                                            $nPicLang = @$_SESSION["tLangEdit"];
                                            if($nPicLang == ''){
                                                $nPicLang = '1';
                                            }
                                        ?>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                            <!-- <img src="<?php echo  base_url('application/modules/authen/assets/images/use/').$nPicLang.'.png' ?>" style="height: 20px; width: 20px;">  -->
                                            <?php echo language('authen/login/login', 'tLanguageType');?> <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo  base_url('ChangeLang/th/1'); ?>">
                                                    <!-- <img src="<?php echo  base_url('application/modules/authen/assets/images/flags/th.png')?>" style="height: 20px; width: 20px;"> -->
                                                    <?php echo language('authen/login/login', 'tLanguageType1');?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo  base_url('ChangeLang/en/2'); ?>">
                                                    <!-- <img src="<?php echo  base_url('application/modules/authen/assets/images/flags/en.png')?>" style="height: 20px; width: 20px;"> -->
                                                    <?php echo language('authen/login/login', 'tLanguageType2');?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <button type="submit" class="btn xWCtlBtn"><span style="color:#0081c2;"><?php echo language('authen/login/login', 'tLogin');?></span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->
</body>
</html>
<!--Key Password-->
<script type="text/javascript" src="<?php echo base_url() ?>application/modules/authen/assets/js/global/PasswordAES128/aes.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>application/modules/authen/assets/js/global/PasswordAES128/cAES128.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>application/modules/authen/assets/js/global/PasswordAES128/AESKeyIV.js"></script>
<script>
    function JSxCheckLogin(){
        var tOldPassword = $('#oetPassword').val();
        var tEncPassword = JCNtAES128EncryptData(tOldPassword, tKey, tIV);
        $('#oetPasswordhidden').val(tEncPassword);
    }
</script>


