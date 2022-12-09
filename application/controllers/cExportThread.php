<?php
require_once(APPPATH . 'controllers/ExportThreadAbstract.php');

class cExportThread extends ExportThreadAbstract{
    
    public $oRptExport;
    public $oModelReport;
    public $oMsg;
    public $tExpType;
    public $nFile;
    public $bIsLastFile;
    public $tPatternName;
    public $nTotalFile;
    public $tCreateDate;
    public $tExprDate;
    public $tComName;
    public $tUserCode;
    public $tUserSessionID;
    public $tRptCode;
    public $tPublishName;
    public $oHisReport;
    public $oPublish;


    public function __construct($paParams = []){
        $this->oRptExport = isset($paParams['oRptExport']) ? $paParams['oRptExport'] : null;
        $this->oModelReport = isset($paParams['oModelReport']) ? $paParams['oModelReport'] : null;
        $this->tExpType = isset($paParams['tExpType']) ? $paParams['tExpType'] : '';
        $this->nFile = isset($paParams['nFile']) ? $paParams['nFile'] : 0;
        $this->oMsg = isset($paParams['oMsg']) ? $paParams['oMsg'] : null;
        
        $this->bIsLastFile = isset($paParams['bIsLastFile']) ? $paParams['bIsLastFile'] : null;
        $this->tPatternName = isset($paParams['tPatternName']) ? $paParams['tPatternName'] : null;
        $this->nTotalFile = isset($paParams['nTotalFile']) ? $paParams['nTotalFile'] : null;
        $this->tCreateDate = isset($paParams['tCreateDate']) ? $paParams['tCreateDate'] : null;
        $this->tExprDate = isset($paParams['tExprDate']) ? $paParams['tExprDate'] : null;
        $this->tComName = isset($paParams['tComName']) ? $paParams['tComName'] : null;
        $this->tUserCode = isset($paParams['tUserCode']) ? $paParams['tUserCode'] : null;
        $this->tUserSessionID = isset($paParams['tUserSessionID']) ? $paParams['tUserSessionID'] : null;
        $this->tRptCode = isset($paParams['tRptCode']) ? $paParams['tRptCode'] : null;
        $this->tPublishName = isset($paParams['tPublishName']) ? $paParams['tPublishName'] : null;
        $this->oHisReport = isset($paParams['oHisReport']) ? $paParams['oHisReport'] : null;
        $this->oPublish = isset($paParams['oPublish']) ? $paParams['oPublish'] : null;
    }
    
    function run(){
        echo " \n\n [/] Thread Run" , "\n";
        for($i=0; $i<5; $i++){
            echo rand(), "\n";
        }
        
        require_once REPORTPATH.'controllers/reportsale/cRptSalePayment.php';
        $aRenderExcelParams = [
            'nFile' => $this->nFile,
            'bIsLastFile' => $this->bIsLastFile,
            'tFileName' => $this->tPatternName.'_'.$this->nFile,
        ];
        $aExportParams = [
            'tCompName' => $this->tComName,
            'tRptCode' => $this->tRptCode,
            'tUserSessionID' => $this->tUserSessionID,
            'tRptExpType' => $this->tRptExpType,
            'nPerFile' => $this->nPerFile,
            'tUserCode' => $this->tUserCode
        ];
        $oRptExport = new cRptSalePayment($aExportParams);
        var_dump($oRptExport->test());
        $aRenderExcelStatus = $oRptExport->FSvCCallRptRenderExcel($aRenderExcelParams);
        var_dump($aRenderExcelStatus);
        
        switch($this->tExpType) {
            case 'excel' : {
                $aRenderExcelParams = [
                    'nFile' => $this->nFile,
                    'bIsLastFile' => $this->bIsLastFile,
                    'tFileName' => $this->tPatternName.'_'.$this->nFile,
                ];
                $aRenderExcelStatus = $this->oRptExport->FSvCCallRptRenderExcel($aRenderExcelParams);
                var_dump($aRenderExcelStatus);
                if($aRenderExcelStatus['nStaExport'] == 1) {
                    echo " [/] Export Success: $this->nFile/$this->nTotalFile" , "\n";

                    /*===== Begin Update FNSuccessFile ===========================*/
                    $aEditStaSuccessFileParams = [
                        'FTComName' => $this->tComName,
                        'FTUsrCode' => $this->tUserCode,
                        'FTUsrSession' => $this->tUserSessionID,
                        'FTRptCode' => $this->tRptCode,
                        'FDCreateDate' => $this->tCreateDate, // วันที่ออกรายงาน
                        'FDExprDate' => $this->tExprDate, // ระเวลาหมดอายุ
                        'FTExpType' => $this->tRptExpType, // ประเภทการออกรายงาน : exel, pdf

                        'FNSuccessFile' => $this->nFile, // ไฟล์ที่ทำสำเร็จ
                    ];
                    $this->oHisReport->FSaMHISRPTEditStaSuccessFile($aEditStaSuccessFileParams);
                    /*===== End Update FNSuccessFile =============================*/

                    /*===== Begin Send Progress ==================================*/
                    $aRenderMsg = [
                        'ptComName' => $this->tComName,
                        'ptRptCode' => $this->tRptCode,
                        'ptUserSessionID' => $this->tUserSessionID,
                        'ptRptExpType' => $this->tRptExpType,
                        'pnPerFile' => $this->nPerFile,
                        'ptUserCode' => $this->tUserCode,
                        "pnSuccessFile" => $this->nFile,
                        "pnTotalFile" => $this->nTotalFile,
                        "pnStaZip" => 0,
                        // "ptPathFile" => ""
                    ];
                    $aPublishParams = [
                        'tMsgFormat' => 'json',
                        'tQname' => $this->tPublishName,
                        'tMsg' => json_encode($aRenderMsg)
                    ];
                    $this->oPublish->FSxMQPublish($aPublishParams);
                    echo " [/] Update StaSuccessFile Success: $this->nFile/$this->nTotalFile" , "\n";
                    /*===== End Send Progress ====================================*/
                }else{
                    echo " [X] Export Fail: $this->nFile/$this->nTotalFile" , "\n";
                }
                break;
            }
        }
    }
    
}
