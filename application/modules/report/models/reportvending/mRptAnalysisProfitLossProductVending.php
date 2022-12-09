<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptAnalysisProfitLossProductVending extends CI_Model {
    
   //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 04/04/2019 Witsarut(Bell)
    //Last Modified : - 
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreCReport($paDataFilter){

        $tCallStore = "{ CALL SP_RPTxPaymentSum2001002(?,?,?,?,?,?,?,?,?,?,?,?,?) }";

        $aDataStore = array(
            'pnLngID'           => $paDataFilter['nLangID'],
            'pnComName'         => $paDataFilter['tCompName'],   
            'ptRptCode'         => $paDataFilter['tRptCode'],
            'ptUsrSession'      => $paDataFilter['tUserSessionID'],
            'ptRcvF'            => $paDataFilter['tRcvCodeFrom'],
            'ptRcvT'            => $paDataFilter['tRcvCodeTo'],
            'ptBchF'            => $paDataFilter['tBchCodeFrom'],
            'ptBchT'            => $paDataFilter['tBchCodeTo'], 
            'ptShpF'            => $paDataFilter['tShpCodeFrom'],
            'ptShpT'            => $paDataFilter['tShpCodeTo'],    
            'ptDocDateF'        => $paDataFilter['tDateFrom'],
            'ptDocDateT'        => $paDataFilter['tDateTo'],
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
        $tUserSession   = $paDataWhere['tUserSessionID'];
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];

        $tSQL = " SELECT c. * FROM(
            SELECT  ROW_NUMBER() OVER(ORDER BY rtRptCode ASC) AS rtRowID,* FROM (
                SELECT TOP 100 PERCENT
                    FTPdtCode       AS rtPdtCode,
                    FTPdtName       AS rtPdtName,
                    FTChainName     AS rtChainName,
                    FCXsdSaleQty    AS rtSaleQty,
                    FCPdtCost       AS rtPdtCost,
                    FCXshGrand      AS rtGrand,
                    FCXsdProfit     AS rtProfit,
                    FCXsdProfitPercent  AS rtProfitPercent,
                    FCXsdSalePercent    AS rtSalePercent,
                    FTComName       AS rtComname,
                    FTRptCode       AS rtRptCode,
                    FTUsrSession    AS rtUsrSession
            FROM TRPTVDTSaleProfitTmp 
            WHERE 1 = 1
            AND FTUsrSession = '$tUserSession' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'
            ORDER BY 
            FTPdtCode ASC
            ) Base ) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1] ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataRpt       = $oQuery->result_array();
            $nCountRowRpt   = $this->FSnMCountRowInTemp($paDataWhere);
            $nFoundRow      = $nCountRowRpt;
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
                'rnAllPage'     => 0,
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

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Witsarut(Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    // public function FSnMCountDataReportAll($paDataWhere){
    //     $tUserSession    = $paDataWhere['tUserSession'];
    //     $tCompName       = $paDataWhere['tCompName'];
    //     $tRptCode        = $paDataWhere['tRptCode'];

    //     $tSQL    =   "   SELECT 
    //                         COUNT(PFTMP.FTRptCode) AS rnCountPage
    //                     FROM TRPTVDTSaleProfitTmp AS PFTMP WITH(NOLOCK)
    //                     WHERE 1 = 1
    //                     AND FTUsrSession    = '$tUserSession'
    //                     AND FTComName       = '$tCompName'
    //                     AND FTRptCode       = '$tRptCode'
    //     ";
    //     $oQuery         = $this->db->query($tSQL);
    //     $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
    //     unset($oQuery);
    //     return $nRptAllRecord;

    // }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Pap
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountRowInTemp($paDataWhere){
        
        $tUserSession    = $paDataWhere['tUserSessionID'];
        $tCompName       = $paDataWhere['tCompName'];
        $tRptCode        = $paDataWhere['tRptCode'];

        $tSQL    =   "   SELECT 
                            COUNT(PFTMP.FTRptCode) AS rnCountPage
                        FROM TRPTVDTSaleProfitTmp AS PFTMP WITH(NOLOCK)
                        WHERE 1 = 1
                        AND FTUsrSession    = '$tUserSession'
                        AND FTComName       = '$tCompName'
                        AND FTRptCode       = '$tRptCode'
        ";
        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }


    // Functionality: To Get data SumFootReport
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Witsarut(Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMGetDataSumFootReport($paDataWhere,$paDataFilter){
        $aRowLen        = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']); 
        $tUserSession   = $paDataWhere['tUserSessionID'];
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];

        $tSQL = "  SELECT
                        
                        NULLIF(SUM(FCXsdSaleQty),0)         AS FCXsdSaleQty_SumFooter,
                        NULLIF(SUM(FCPdtCost),0) 	        AS FCPdtCost_SumFooter,
                        NULLIF(SUM(FCXshGrand),0) 	        AS FCXshGrand_SumFooter,
                        NULLIF(SUM(FCXsdProfit),0)          AS FCXsdProfit_SumFooter,

                        -- NULLIF(SUM(FCXsdProfitPercent),0)  AS FCXsdProfitPercent_SumFooter,
                        -- NULLIF(SUM(FCXsdSalePercent),0)  AS FCXsdSalePercent_SumFooter,

                        ((NULLIF(SUM(FCXsdProfit),0)  /  NULLIF(SUM(FCPdtCost),0)) * 100)  AS  FCXsdProfitPercent_SumFooter,
                        ((NULLIF(SUM(FCXsdProfit),0) / NULLIF(SUM(FCXshGrand),0)) * 100) AS FCXsdSalePercent_SumFooter

                    FROM TRPTVDTSaleProfitTmp AS PFTMP WITH(NOLOCK)
                    WHERE 1 = 1 
                    AND FTUsrSession = '$tUserSession' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return array();
        }
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
