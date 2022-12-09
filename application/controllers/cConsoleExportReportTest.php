<?php
require APPPATH.'controllers/cMQExportReportToolsTest.php';

class cConsoleExportReportTest extends MX_Controller{

    public function __construct() {
        parent::__construct();
    }

    public function FSxMQListener() {
        $oMQExportReportTools = new cMQExportReportToolsTest();
        $oMQExportReportTools->FSxMQConsumer();
    }

}
