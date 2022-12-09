<?php
    $aDataTextRef   = $aAddressCom['aDataTextRef'];
    $tCompAndBranch = $aAddressCom['tCompName'];
    $aFilterReport  = $aAddressCom['aFilterReport'];
    $tHomeNo        = $aAddressCom['tHomeNO'].'  '.$aAddressCom['tSoi'];
    $tRoad          = $aAddressCom['tRoad'].' '.$aAddressCom['tDstName'].' '.$aAddressCom['tPrvName']. ' '.$aAddressCom['tPostCode'];
    $tTel           = $aAddressCom['tTel'];
    $tFaxNo         = $aAddressCom['tFax'];
    $tBranch         = $aAddressCom['tBchName'];
    $tLabeDataPrint = $tRptDatePrint;
    $tLabeTimePrint = $tLabeTimePrint;
    // $tAddressLine   = "-";
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <title><?php echo $tTiltleReport; ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0"/>

    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>application/modules/common/assets/images/AdaLogo.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>application/modules/common/assets/images/AdaLogo.png">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/vendor/bootstrap/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/vendor/bootstrap/css/bootstrap.custom.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/vendor/linearicons/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/vendor/chartist/css/chartist-custom.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/cropper.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/globalcss/ContactFrom/main.css">
    <link rel="stylesheet" href="<?php echo base_url('application/modules/common/assets/vendor/loading-bar/loading-bar.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/demo.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/localcss/ada.layout.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/localcss/ada.menu.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/localcss/ada.fonts.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/common/assets/css/localcss/ada.component.css">

    <!-- JS Script -->
    <script src="<?php echo base_url(); ?>application/modules/common/assets/vendor/jquery/jquery.js"></script>
    <script src="<?php echo base_url(); ?>application/modules/common/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!-- JS Custom AdaSoft -->
    <script src="<?php echo base_url(); ?>application/modules/common/assets/src/jCommon.js"></script>
    <script src="<?php echo base_url(); ?>application/modules/common/assets/src/jPageControll.js"></script>
    <script src="<?php echo base_url(); ?>application/modules/common/assets/src/jBrowseModal_New.js"></script>
    <script src="<?php echo base_url(); ?>application/modules/common/assets/src/jAjaxErrorHandle.js"></script>
    <script src="<?php echo base_url(); ?>application/modules/common/assets/src/jTempImage.js"></script>
</head>
<body class="xCNBody">
    <style>
        .xWRptNavGroup{
            padding: 12px 15px !important
        }

        .xWBTNRptPrintPreview {
            width: 40%;
            font-size: 20px;
        }

        #odvRvwMainMenu{
            margin-top:70px;
        }
        label{
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
        }
        body{
            color: #333;
        }
        .xCNFooterRpt {
            border-bottom : 7px double #ddd;
        }

        .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
            border: 0px transparent !important;
        }

        .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
            border-top: 1px solid black !important;
            border-bottom : 1px solid black !important;
            background-color: #CFE2F3 !important;
        }

        .table>tbody>tr.xCNTrSubFooter{
            border-top: 1px solid black !important;
            border-bottom : 1px solid black !important;
            background-color: #CFE2F3 !important;
        }

        .table>tbody>tr.xCNTrFooter{
            border-top: 1px solid black !important;
            background-color: #CFE2F3 !important;
            border-bottom : 6px double black !important;
        }
        @media print {
            #oliRptTitle {
                display: none !important;
            }
        }
    </style>
    <nav class="navbar navbar-default navbar-fixed-top" style="height:70px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="xWRptNavGroup">
                        <button type="button" id="obtPrintViewHtml" class="btn btn-primary xWBTNRptPrintPreview">
                            <?php echo language('report/report/report','tRptPrintHtml');?>
                        </button>
                        <script type="text/javascript">
                            $('#obtPrintViewHtml').click(function(){
                                $(this).hide();
                                window.print();
                                $(this).show();
                            });
                        </script>
                    </div>
                </div>
                <div class="xCNFooterReport">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="xWPageReport btn-toolbar pull-right" style="padding:20px 29px;">
                               



                            </div>
                        </div>
                    </div>             
                </div>
            </div>
        </div>
    </nav>    
    <div class="odvMainContent main">
        
        <div id="odvRvwMainMenu" class="main-menu clearfix">
            <div class="xCNMrgNavMenu">
                <div class="row xCNavRow">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <ol id="oliMenuNav" class="breadcrumb xCNBCMenu">
                            <li id="oliRptTitle"><?php echo language('report/report/report','tRptViewer') ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNMenuCump xCNRvwBrowseLine" id="odvMenuCump">
        </div>
        <div class="main-content">
            <div id="odvContentPageRptViewer" class="panel panel-headline"> 
                <div class="panel-body">
                   <div class="row">
                        <div class="col-xs-12 text-center">
                            <label class="xCNRptTitle"><?php echo $tTiltleReport; ?></label>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="text-left">
                                        <label class="xCNRptLabel"><?php echo $tCompAndBranch; ?></label>
                                    </div>
                                    <div class="text-left">
                                        <label class="xCNRptLabel"><?php echo $tRoad ;?></label>
                                    </div>
                                    <div class="text-left">
                                        <Label class="xCNRptLabel"><?php echo $tTel ;?></label>
                                    </div>
                                    <div class="text-left">
                                        <label class="xCNRptLabel"><?php echo $tFaxNo; ?></label>
                                    </div>
                                    <div class="text-left">
                                        <label class="xCNRptLabel"><?php echo $tBranch; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                </div>
                                <div class="col-xs-6">
                                    <div class="text-right">
                                        <label class="xCNRptLabel"><?php echo $tLabeDataPrint.' '.$tLabeTimePrint ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="xCNContentReport">
                        <div id="odvRptTableAdvance" class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%;  padding: 15px;"><?php echo  language('report/report/report','tRptRentAmtFolCourSerailPos');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%; padding: 10px;"><?php echo  language('report/report/report','tRptRentAmtFolCourUser');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%; padding: 10px;"><?php echo  language('report/report/report','tRptRentAmtFolCourDocno');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%;  padding: 10px;"><?php  echo  language('report/report/report','tRptRentAmtFolCourDocDate');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%;  padding: 15px;"><?php echo  language('report/report/report','tRptRentAmtFolCourDateGet');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%; padding: 10px;"><?php echo  language('report/report/report','tRptRentAmtFolCourLoginTo');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%; padding: 10px;"><?php echo  language('report/report/report','tRptRentAmtFolCourStaPayment');?></th>
                                        <th nowrap class="text-center xCNTextBold" style="width:12%;  padding: 10px;"><?php  echo  language('report/report/report','tRptRentAmtFolCourAmtPayment');?></th>
                                    

                                    
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="ofmRptSubmitClickPage" method="post" target="_self">
       
    </form>

    <!-- Overlay Data Viewer -->
    <div class="xCNOverlayLodingData" style="z-index: 7000;">
        <img src="<?php echo base_url();?>application/modules/common/assets/images/ada.loading.gif" class="xWImgLoading">
        <div id="odvOverLayContentForLongTimeLoading" style="display: none;"><?php echo language('common/main/main', 'tLodingDataReport'); ?></div>
    </div>
    
    <script>
                //Next page by report
                function JSvClickPageReport(ptPage){
                    var nAllPage = '';
                    var nPageCurrent = '';
                    switch (ptPage) {
                        case 'next': //กดปุ่ม Next
                            $('.xWBtnNext').addClass('disabled');
                            nPageOld = $('.xWPageReport .active').text(); // Get เลขก่อนหน้า
                            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                            nPageCurrent = nPageNew;
                            break;
                        case 'previous': //กดปุ่ม Previous
                            nPageOld = $('.xWPageReport .active').text(); // Get เลขก่อนหน้า
                            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                            nPageCurrent = nPageNew;
                            break;
                        case 'first': //กดปุ่ม First
                            nPageCurrent = 1;
                            break;
                        case 'last': //กดปุ่ม Last
                            nPageCurrent = nAllPage;
                            break;    
                        default:
                            nPageCurrent = ptPage;
                    }

                    JCNvCallDataReportPageClick(nPageCurrent);
                }
            </script>
            
    <script type="text/javascript">
        // Function Call Data Rpt
        function JCNvCallDataReportPageClick(pnPageCurrent){
            var tRptRote = $('#ohdRptRoute').val();
            $('#ohdRptCurrentPage').val(pnPageCurrent);
            $('#ofmRptSubmitClickPage').attr('action',tRptRote+'ClickPage');
            $('#ofmRptSubmitClickPage').submit();
            $('#ofmRptSubmitClickPage').attr('action','javascript:void(0)');
        }
    </script>
</body>

</html>













