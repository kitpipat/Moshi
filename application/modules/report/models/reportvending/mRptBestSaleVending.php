<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptBestSaleVending extends CI_Model {
    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 04/04/2019 Witsarut(Bell)
    //Last Modified : - 
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreCReport($paDataFilter){


      $tCallStore = "{ CALL SP_RPTxVDDailyByPdtBstQty2001004(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";

    $aDataStore = array(
        'pnLngID'           => $paDataFilter['nLangID'],
        'pnComName'         => $paDataFilter['tCompName'],   
        'ptRptCode'         => $paDataFilter['tRptCode'],
        'ptUsrSession'      => $paDataFilter['tSessionID'],
        'pnTop'             => $paDataFilter['tPriority'],
        'ptBchF'            => $paDataFilter['tBchCodeFrom'],
        'ptBchT'            => $paDataFilter['tBchCodeTo'],
        'ptMerF'            => $paDataFilter['tMerCodeFrom'], 
        'ptMerT'            => $paDataFilter['tMerCodeTo'],
        'ptShpF'            => $paDataFilter['tRptShpCodeFrom'],    
        'ptShpT'            => $paDataFilter['tShpCodeTo'],
        'ptPosF'            => $paDataFilter['tPosCodeFrom'],
        'ptPosT'            => $paDataFilter['tPosCodeTo'],
        'ptPdtCodeF'        => $paDataFilter['tPdtCodeFrom'],
        'ptPdtCodeT'        => $paDataFilter['tPdtCodeTo'],
        'ptPdtChanF'        => $paDataFilter['tPdtGrpCodeFrom'],
        'ptPdtChanT'        => $paDataFilter['tPdtGrpCodeTo'],
        'ptPdtTypeF'        => $paDataFilter['tPdtTypeCodeFrom'],
        'ptPdtTypeT'        => $paDataFilter['tPdtTypeCodeTo'],
        'ptDocDateF'        => $paDataFilter['tDocDateFrom'],
        'ptDocDateT'        => $paDataFilter['tDocDateTo'],
        'FNResult'          => 0,
    );
        $oQuery = $this->db->query($tCallStore,$aDataStore);
        if($oQuery !== FALSE){
            unset($oQuery);
            return 1;
        }else{
            unset($oQuery);
            return 0;
        }
    }

    // Functionality: Get Data Report
    // Parameters:  Function Parameter
    // Creator: 04/04/2019 Witsarut(Bell)
    // Last Modified : -
    // Return : Get Data Rpt Temp
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere,$paDataFilter){

        $aRowLen        = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);

        $tSessionID         = $paDataWhere['tUserSessionID'];
        $tCompName          = $paDataWhere['tCompName'];
        $tRptCode           = $paDataWhere['tRptCode'];

        $tSQL = " SELECT c. * FROM(
                SELECT  ROW_NUMBER() OVER(ORDER BY rtRptCode ASC) AS rtRowID,* FROM (
                    SELECT TOP 100 PERCENT
                        FTPdtCode       AS rtPdtCode,
                        FTPdtName       AS rtPdtName,
                        FTPdtChainName  AS rtPdtChainName,
                        FCXsdQty        AS rtQty,
                        FCXsdNet        AS rtNet,
                        FCXsdDisChg     AS rtDisChg,
                        FCXsdGrandTotal AS rtGrandTotal,
                        FTUsrSession    AS rtUsrSession,
                        FTComName       AS rtComname,
                        FTRptCode       AS rtRptCode
                FROM TRPTVDTopSaleTmp 
                WHERE 1 = 1
                AND FTUsrSession = '$tSessionID' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'
                ORDER BY 
                FTPdtCode ASC
                ) Base ) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1] ";
        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0){
            $aDataRpt       = $oQuery->result_array();
            $oCountRowRpt   = $this->FSaMCountDataReportAll($paDataWhere);
            $nFoundRow      = $oCountRowRpt;
            $nPageAll       = ceil($nFoundRow / $paDataWhere['nRow']);
            $aReturnData    = array(
                'raItems'       => $aDataRpt,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aReturnData    = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
            unset($oQuery);
            unset($oCountRowRpt);
            unset($nFoundRow);
            unset($nPageAll);
            return $aReturnData; 
       
    }

    // Functionality: To Get data SumFootReport
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Witsarut(Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMGetDataSumFootReport($paDataWhere,$paDataFilter){
        
        $aRowLen        = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);

        $tSessionID    = $paDataWhere['tUserSessionID'];
        $tCompName     = $paDataWhere['tCompName'];
        $tRptCode      = $paDataWhere['tRptCode'];

        $tSQL   =   "   SELECT
                            ISNULL(SUM(FCXsdQty),0) 		AS FCXsdQty_SumFooter,
                            ISNULL(SUM(FCXsdNet),0) 		AS FCXsdNet_SumFooter,
                            ISNULL(SUM(FCXsdDisChg),0) 	    AS FCXsdDisChg_SumFooter,
                            ISNULL(SUM(FCXsdGrandTotal),0) 	AS FCXsdGrandTotal_SumFooter
                        FROM TRPTVDTopSaleTmp
                        WHERE 1 = 1 
                        AND FTComName = '".$tCompName."' AND FTRptCode = '".$tRptCode."' AND FTUsrSession = '".$tSessionID."'
                    ";
     
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return array();
        }
    }
 
     // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Witsarut(Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMCountDataReportAll($paDataWhere){
        
        $tSessionID         = $paDataWhere['tUserSessionID'];
        $tCompName          = $paDataWhere['tCompName'];
        $tRptCode           = $paDataWhere['tRptCode'];

                $tSQL   = "SELECT 
                            FTPdtCode       AS rtPdtCode,
                            FTPdtName       AS rtPdtName,
                            FTPdtChainName  AS rtPdtChainName,
                            FCXsdQty        AS rtQty,
                            FCXsdNet        AS rtNet,
                            FCXsdDisChg     AS rtDisChg,
                            FCXsdGrandTotal AS rtGrandTotal,
                            FTUsrSession    AS rtUsrSession,
                            FTComName       AS rtComname,
                            FTRptCode       AS rtRptCode
                        FROM TRPTVDTopSaleTmp
                        WHERE 1 = 1
                        AND FTUsrSession = '$tSessionID' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'
                        ORDER BY 
                        FTPdtCode ASC";
    
        $oQuery     = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

     // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 23/08/2019 pap
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountRowInTemp($paDataWhere){
        
        $tSessionID         = $paDataWhere['tUserSessionID'];
        $tCompName          = $paDataWhere['tCompName'];
        $tRptCode           = $paDataWhere['tRptCode'];

                $tSQL   = "SELECT 
                            FTPdtCode       AS rtPdtCode,
                            FTPdtName       AS rtPdtName,
                            FTPdtChainName  AS rtPdtChainName,
                            FCXsdQty        AS rtQty,
                            FCXsdNet        AS rtNet,
                            FCXsdDisChg     AS rtDisChg,
                            FCXsdGrandTotal AS rtGrandTotal,
                            FTUsrSession    AS rtUsrSession,
                            FTComName       AS rtComname,
                            FTRptCode       AS rtRptCode
                        FROM TRPTVDTopSaleTmp
                        WHERE 1 = 1
                        AND FTUsrSession = '$tSessionID' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'
                        ORDER BY 
                        FTPdtCode ASC";
    
        $oQuery     = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    // Functionality: Get Data address
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
     public function FSaMCMPAddress($paData){
        try{
            $tRefCode   = $paData['tAddRef']; 
            $nLngID     = $paData['nLangID'];
            $tSQL = "SELECT
                        ADDL.FTAddRefCode       AS rtAddRefCode,
                        ADDL.FTAddTaxNo         AS rtAddTaxNo,
                        ADDL.FTAddVersion       AS rtAddVersion,
                        ADDL.FTAddV1No          AS rtAddV1No,
                        ADDL.FTAddV1Soi         AS rtAddV1Soi,
                        ADDL.FTAddV1Village     AS rtAddV1Village,
                        ADDL.FTAddV1Road        AS rtAddV1Road,
                        ADDL.FTAddV1SubDist     AS rtAddV1SubDist,
                        SUBDSTL.FTSudName       AS rtAddV1SudName,
                        ADDL.FTAddV1DstCode     AS rtAddV1DstCode,
                        DSTL.FTDstName          AS rtAddV1DstName,
                        ADDL.FTAddV1PvnCode     AS rtAddV1PvnCode,
                        PVNL.FTPvnName          AS rtAddV1PvnName,
                        ADDL.FTAddCountry       AS rtAddV1CntName,
                        ADDL.FTAddV1PostCode    AS rtAddV1PostCode,
                        ADDL.FTAddV2Desc1       AS rtAddV2Desc1,
                        ADDL.FTAddV2Desc2       AS rtAddV2Desc2,
                        ADDL.FTAddWebsite       AS rtAddWebsite,
                        ADDL.FTAddLongitude     AS rtAddLongitude,
                        ADDL.FTAddLatitude      AS rtAddLatitude

                    FROM [TCNMAddress_L] ADDL
                    LEFT JOIN [TCNMSubDistrict_L] SUBDSTL ON ADDL.FTAddV1SubDist = SUBDSTL.FTSudCode AND SUBDSTL.FNLngID = $nLngID
                    LEFT JOIN [TCNMDistrict_L] DSTL ON ADDL.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = $nLngID
                    LEFT JOIN [TCNMProvince_L] PVNL ON ADDL.FTAddV1PvnCode = PVNL.FTPvnCode AND PVNL.FNLngID = $nLngID
                    WHERE 1=1  AND ADDL.FNLngID = $nLngID AND ADDL.FTAddRefCode = '$tRefCode' 
                    "; 
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $oList = $oQuery->result();
                $aResult = array(
                    'raItems'   => $oList[0],
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode'    => '800',
                    'rtDesc'    => 'data not found'
                );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;
        }catch(Exception $Error){
            return $Error;
        }
     }
}
