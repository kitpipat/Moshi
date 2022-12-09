<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptOpenSysAdmin extends CI_Model {
    
    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 04/04/2019 Wasin(Yoshi)
    //Last Modified :
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreReport($paDataFilter){
     
        $tCallStore  =  "{ CALL SP_RPTxLKOpenByAdmin3001002(?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(

            'pnLngID'          => $paDataFilter['nLangID'],
            'pnComName'        => $paDataFilter['tCompName'],
            'ptRptCode'        => $paDataFilter['tRptCode'],
            'ptUsrSession'     => $paDataFilter['tUserSession'],
            'ptMerF'           => $paDataFilter['tMerCodeFrom'],
            'ptMerT'           => $paDataFilter['tMerCodeTo'],
            'ptPosF'           => $paDataFilter['tPosCodeFrom'],
            'ptPosT'           => $paDataFilter['tPosCodeTo'],
            'ptShpF'           => $paDataFilter['tShopCodeFrom'],
            'ptShpT'           => $paDataFilter['tShopCodeTo'],
            'ptDocDateF'       => $paDataFilter['tDocDateFrom'],
            'ptDocDateT'       => $paDataFilter['tDocDateTo'],      
            'FNResult'         => 0,
        );

        $oQuery   = $this->db->query($tCallStore,$aDataStore);
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
    // Creator: 04/04/2019 Wasin(Yoshi)
    // Last Modified : 11/04/2019 Wasin(Yoshi)
    // Return : Get Data Rpt Temp
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere, $paDataFilter){
        
        $aRowLen    = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);
        $tCompName  = $paDataWhere['tCompName'];
        $tRptCode   = $paDataWhere['tRptCode'];
        $tSessionID = $paDataWhere['tUserSession'];

        $tSQL = " SELECT c. * FROM(
                     SELECT  ROW_NUMBER() OVER(ORDER BY rtRptCode ASC) AS rtRowID,* FROM (
                         SELECT TOP 100 PERCENT
                            ADMINHISTMP.FTBchCode       AS rtBchCode,
                            ADMINHISTMP.FTShpCode       AS rtShpCode,
                            ADMINHISTMP.FTPosCode       AS rtPosCode,
                            ADMINHISTMP.FDHisDateTime   AS rtDateTime,
                            ADMINHISTMP.FTRakCode       AS rtRakCode,      
                            ADMINHISTMP.FTRakName       AS rtRakName,
                            ADMINHISTMP.FNHisLayNo      AS rtHisLayNo,
                            ADMINHISTMP.FTCreateName    AS rtCreateName,
                            ADMINHISTMP.FTHisUsrName    AS rtHisUsrName,
                            CASE 
                                WHEN ADMINHISTMP.FTLayStaUse = 1 THEN '".language('report/report/report','tRptStaUse')."'
                                WHEN ADMINHISTMP.FTLayStaUse = 2 THEN '".language('report/report/report','tRptStaNotUse')."'
                                WHEN ADMINHISTMP.FTLayStaUse = 2 THEN '".language('report/report/report','tRptCloseStaUse')."'
                            ELSE '".language('report/report/report','tRptStaEmpty')."' END AS rtLayStause,
                            ADMINHISTMP.FTHisRsnCode    AS rtHisRsnCode,
                            ADMINHISTMP.FTHisRsnName    AS rtHisRsnName,
                            ADMINHISTMP.FTHisUsrCode    AS rtHisUsrCode,
                            USRL.FTUsrName              AS rtUsrName,
                            ADMINHISTMP.FTUsrSession    AS rtUsrSession,
                            ADMINHISTMP.FTComName       AS rtComname,
                            ADMINHISTMP.FTRptCode       AS rtRptCode
                        FROM TRPTAdminHisTmp ADMINHISTMP
                        LEFT JOIN TCNMUser_L USRL ON ADMINHISTMP.FTHisUsrCode = USRL.FTUsrCode AND USRL.FNLngID = 1
                        WHERE 1 = 1
                        AND ADMINHISTMP.FTUsrSession = '$tSessionID' AND ADMINHISTMP.FTComName = '$tCompName' AND ADMINHISTMP.FTRptCode = '$tRptCode'
                        ORDER BY 
                        ADMINHISTMP.FTBchCode ASC,
                        ADMINHISTMP.FTShpCode ASC,
                        ADMINHISTMP.FTPosCode ASC,
                        ADMINHISTMP.FDHisDateTime ASC,
                        ADMINHISTMP.FTRakCode ASC,
                        ADMINHISTMP.FNHisLayNo ASC
                        
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
   

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMCountDataReportAll($paDataWhere){

        $tSessionID = $paDataWhere['tUserSession'];
        $tCompName  = $paDataWhere['tCompName'];
        $tRptCode   = $paDataWhere['tRptCode'];
        $tSQL       = "SELECT 
                            ADMINHISTMP.FTBchCode       AS rtBchCode,
                            ADMINHISTMP.FTShpCode       AS rtShpCode,
                            ADMINHISTMP.FTPosCode       AS rtPosCode,
                            ADMINHISTMP.FDHisDateTime   AS rtDateTime,
                            ADMINHISTMP.FTRakCode       AS rtRakCode,      
                            ADMINHISTMP.FTRakName       AS rtRakName,
                            ADMINHISTMP.FNHisLayNo      AS rtHisLayNo,
                            ADMINHISTMP.FTLayStaUse     AS rtLayStause,
                            ADMINHISTMP.FTHisRsnCode    AS rtHisRsnCode,
                            ADMINHISTMP.FTHisRsnName    AS rtHisRsnName,
                            ADMINHISTMP.FTHisUsrCode    AS rtHisUsrCode,
                            ADMINHISTMP.FTHisUsrName    AS rtHisUsrName,
                            USRL.FTUsrName              AS rtUsrName,
                            ADMINHISTMP.FTUsrSession    AS rtUsrSession,
                            ADMINHISTMP.FTComName       AS rtComname,
                            ADMINHISTMP.FTRptCode       AS rtRptCode
                        FROM TRPTAdminHisTmp ADMINHISTMP
                        LEFT JOIN TCNMUser_L USRL ON ADMINHISTMP.FTHisUsrCode = USRL.FTUsrCode AND USRL.FNLngID = 1
                        WHERE 1 = 1
                        AND ADMINHISTMP.FTUsrSession = '$tSessionID' AND ADMINHISTMP.FTComName = '$tCompName' AND ADMINHISTMP.FTRptCode = '$tRptCode'
                        ORDER BY 
                        ADMINHISTMP.FTBchCode ASC,
                        ADMINHISTMP.FTShpCode ASC,
                        ADMINHISTMP.FTPosCode ASC,
                        ADMINHISTMP.FDHisDateTime ASC,
                        ADMINHISTMP.FTRakCode ASC,
                        ADMINHISTMP.FNHisLayNo ASC ";
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
