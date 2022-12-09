<?php
require_once APPPATH.'controllers/cMQExportReportTools.php';

class cConsoleExportReport extends MX_Controller{

    public function __construct() {
        parent::__construct();
    }

    /**
     * Functionality : เริ่มทำงาน Console
     * Creator : 09/08/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FSxMQListener() {
        $oMQExportReportTools = new cMQExportReportTools();
        $oMQExportReportTools->FSxMQConsumer();
    }

}
