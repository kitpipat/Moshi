<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mAdjustStockSub extends CI_Model {

    /**
     * Functionality : Data List Product Adjust Stock HD
     * Parameters : function parameters
     * Creator :  22/05/2019 Piya(Tiger)
     * Last Modified : -
     * Return : Data Array
     * Return Type : Array
     */
    public function FSaMAdjStkSubList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID = $paData['FNLngID'];
        $tSQL   =   "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTAjhDocNo DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT

                                ADJSTK.FTBchCode,
                                BCHL.FTBchName,
                                ADJSTK.FTAjhDocNo,
                                CONVERT(CHAR(10), ADJSTK.FDAjhDocDate, 103) AS FDAjhDocDate,
                                CONVERT(CHAR(5), ADJSTK.FDAjhDocDate, 108)  AS FTAjhDocTime,
                                ADJSTK.FTAjhStaDoc,
                                ADJSTK.FTAjhStaApv,
                                ADJSTK.FTAjhStaPrcStk,
                                ADJSTK.FTCreateBy,
                                USRL.FTUsrName AS FTCreateByName,
                                ADJSTK.FTAjhApvCode,
                                USRLAPV.FTUsrName AS FTAjhApvName

                            FROM [TCNTPdtAdjStkHD] ADJSTK WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON ADJSTK.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL WITH (NOLOCK) ON ADJSTK.FTCreateBy = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L    USRLAPV WITH (NOLOCK) ON ADJSTK.FTAjhApvCode = USRLAPV.FTUsrCode AND USRLAPV.FNLngID = $nLngID
                            
                            WHERE 1=1 ";

        $oAdvanceSearch = $paData['oAdvanceSearch'];
        
        @$tSearchList = $oAdvanceSearch['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND ((ADJSTK.FTAjhDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (CONVERT(CHAR(10),ADJSTK.FDAjhDocDate,103) LIKE '%$tSearchList%'))";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $oAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $oAdvanceSearch['tSearchBchCodeTo'];
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)){
            $tSQL .= " AND ((ADJSTK.FTBchCode BETWEEN $tSearchBchCodeFrom AND $tSearchBchCodeTo) OR (ADJSTK.FTBchCode BETWEEN $tSearchBchCodeTo AND $tSearchBchCodeFrom))";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $oAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $oAdvanceSearch['tSearchDocDateTo'];

        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((ADJSTK.FDAjhDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (ADJSTK.FDAjhDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $oAdvanceSearch['tSearchStaDoc'];
        if(!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")){
            if($tSearchStaDoc == 2){
                $tSQL .= " AND ADJSTK.FTAjhStaDoc = '$tSearchStaDoc' OR ADJSTK.FTAjhStaDoc = ''";
            }else{
                $tSQL .= " AND ADJSTK.FTAjhStaDoc = '$tSearchStaDoc'";
            }
        }

        // สถานะอนุมัติ
        $tSearchStaApprove = $oAdvanceSearch['tSearchStaApprove'];
        if(!empty($tSearchStaApprove) && ($tSearchStaApprove != "0")){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND ADJSTK.FTAjhStaApv = '$tSearchStaApprove' OR ADJSTK.FTAjhStaApv = '' ";
            }else{
                $tSQL .= " AND ADJSTK.FTAjhStaApv = '$tSearchStaApprove'";
            }
        }

        // สถานะประมวลผล
        $tSearchStaPrcStk = $oAdvanceSearch['tSearchStaPrcStk'];
        if(!empty($tSearchStaPrcStk) && ($tSearchStaPrcStk != "0")){
            
            if($tSearchStaPrcStk == 3){
                $tSQL .= " AND ADJSTK.FTAjhStaPrcStk = '$tSearchStaPrcStk' OR ADJSTK.FTAjhStaPrcStk = '' ";
            }else{
                $tSQL .= " AND ADJSTK.FTAjhStaPrcStk = '$tSearchStaPrcStk'";
            }
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
       
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMAdjStkSubGetPageAll($paData);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow/$paData['nRow']); // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            // No Data
            $aResult = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"=> 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;

    }
    
    /**
     * Functionality : All Page Of Product Adjust Stock HD
     * Parameters : function parameters
     * Creator :  12/06/2018 Piya(Tiger)
     * Last Modified : -
     * Return : Data Array
     * Return Type : Array
     */
    public function FSnMAdjStkSubGetPageAll($paData){

        $nLngID = $paData['FNLngID'];

        $tSQL = "SELECT COUNT (ADJSTK.FTAjhDocNo) AS counts
                FROM [TCNTPdtAdjStkHD] ADJSTK WITH (NOLOCK)
                LEFT JOIN TCNMBranch_L BCHL WITH (NOLOCK) ON ADJSTK.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                WHERE 1=1 ";

        $oAdvanceSearch = $paData['oAdvanceSearch'];
        @$tSearchList = $oAdvanceSearch['tSearchAll'];
        
        if(@$tSearchList != ''){
            $tSQL .= " AND ((ADJSTK.FTAjhDocNo LIKE '%$tSearchList%') OR (BCHL.FTBchName LIKE '%$tSearchList%') OR (ADJSTK.FDAjhDocDate LIKE '%$tSearchList%'))";
        }

        // จากสาขา - ถึงสาขา
        $tSearchBchCodeFrom = $oAdvanceSearch['tSearchBchCodeFrom'];
        $tSearchBchCodeTo   = $oAdvanceSearch['tSearchBchCodeTo'];
        if(!empty($tSearchBchCodeFrom) && !empty($tSearchBchCodeFrom)){
            $tSQL .= " AND ((ADJSTK.FTBchCode BETWEEN $tSearchBchCodeFrom AND $tSearchBchCodeTo) OR (ADJSTK.FTBchCode BETWEEN $tSearchBchCodeTo AND $tSearchBchCodeFrom))";
        }

        // จากวันที่ - ถึงวันที่
        $tSearchDocDateFrom = $oAdvanceSearch['tSearchDocDateFrom'];
        $tSearchDocDateTo   = $oAdvanceSearch['tSearchDocDateTo'];

        if(!empty($tSearchDocDateFrom) && !empty($tSearchDocDateTo)){
            $tSQL .= " AND ((ADJSTK.FDAjhDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateFrom 00:00:00') AND CONVERT(datetime,'$tSearchDocDateTo 23:59:59')) OR (ADJSTK.FDAjhDocDate BETWEEN CONVERT(datetime,'$tSearchDocDateTo 00:00:00') AND CONVERT(datetime,'$tSearchDocDateFrom 23:59:59')))";
        }

        // สถานะเอกสาร
        $tSearchStaDoc = $oAdvanceSearch['tSearchStaDoc'];
        if(!empty($tSearchStaDoc) && ($tSearchStaDoc != "0")){
            $tSQL .= " AND ADJSTK.FTAjhStaDoc = '$tSearchStaDoc'";
        }

        // สถานะอนุมัติ
        $tSearchStaApprove = $oAdvanceSearch['tSearchStaApprove'];
        if(!empty($tSearchStaApprove) && ($tSearchStaApprove != "0")){
            if($tSearchStaApprove == 2){
                $tSQL .= " AND ADJSTK.FTAjhStaApv = '$tSearchStaApprove' OR ADJSTK.FTAjhStaApv = '' ";
            }else{
                $tSQL .= " AND ADJSTK.FTAjhStaApv = '$tSearchStaApprove'";
            }
        }

        // สถานะประมวลผล
        $tSearchStaPrcStk = $oAdvanceSearch['tSearchStaPrcStk'];
        if(!empty($tSearchStaPrcStk) && ($tSearchStaPrcStk != "0")){
            
            if($tSearchStaPrcStk == 3){
                $tSQL .= " AND ADJSTK.FTAjhStaPrcStk = '$tSearchStaPrcStk' OR ADJSTK.FTAjhStaPrcStk = '' ";
            }else{
                $tSQL .= " AND ADJSTK.FTAjhStaPrcStk = '$tSearchStaPrcStk'";
            }
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            // No Data
            return false;
        }
    }
    
    //Functionality : Function Get Count From Temp
    //Parameters : function parameters
    //Creator : 25/01/2019 Krit
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMAdjStkSubGetCountDTTemp($paDataWhere){
        
            $tSQL = "SELECT 
                        COUNT(DOCTMP.FTXthDocNo) AS counts
                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1 = 1
                    ";

            $tAjhDocNo      = $paDataWhere['FTAjhDocNo'];
            $tXthDocKey     = $paDataWhere['FTXthDocKey'];
            $tSesSessionID  = $this->session->userdata('tSesSessionID');    

            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";

            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result_array();
                $aResult = $oDetail[0]['counts'];
            }else{
                $aResult = 0;
            }

        return $aResult;

    }

    //Functionality : Function Get Pdt From Temp
    //Parameters : function parameters
    //Creator : 25/01/2019 Krit
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMAdjStkSubSumDTTemp($paDataWhere){

        $tAjhDocNo      = $paDataWhere['FTAjhDocNo'];
        $tXthDocKey     = $paDataWhere['FTXthDocKey'];
        $tSesSessionID  = $this->session->userdata('tSesSessionID');   

        $tSQL = "SELECT SUM(FCXtdAmt) AS FCXtdAmt
                 FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                 WHERE 1 = 1
                ";
             
            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";

            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oResult = $oQuery->result_array();
            }else{
                $oResult = '';
            }


        return $oResult;

    }


    //Functionality : Function Get Pdt From Temp
    //Parameters : function parameters
    //Creator : 04/04/2019 Krit(Copter)
    //Return : array
    //Return Type : array
    public function FSaMAdjStkSubGetVatDTTemp($paDataWhere){

        $tXthDocNo      = $paDataWhere['FTAjhDocNo'];
        $tXthDocKey     = $paDataWhere['FTXthDocKey'];

        $tSQL = "SELECT DISTINCT FCXtdVatRate, 
                    SUM(FCXtdVat) AS FCXtdVat,
                    SUM(FCXtdVatable) AS FCXtdVatable
                FROM TCNTDocDTTmp WITH (NOLOCK)
                WHERE 1 = 1";

            $tSesSessionID = $this->session->userdata('tSesSessionID');    
            $tSQL .= " AND FTSessionID = '$tSesSessionID'";

            $tSQL .= " AND FTAjhDocNo = '$tXthDocNo'";

            $tSQL .= " AND FTXthDocKey = '$tXthDocKey'";

            $tSQL .= " GROUP BY FCXtdVatRate ORDER BY FCXtdVatRate ASC";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oResult = $oQuery->result_array();
            }else{
                $oResult = '';
            }

        return $oResult;

    }

    /**
     * Functionality : Function Add DT To Temp
     * Parameters : function parameters
     * Creator : 29/05/2019 Piya(Tiger)
     * Last Modified : -
     * Return : Status Add
     * Return Type : array
     */
    public function FSaMAdjStkSubInsertTmpToDT($paDataWhere){

            // ทำการลบ ใน DT ก่อนการย้าย Tmp ไป DT
            if($paDataWhere['FTAjhDocNo'] != ''){
                $this->db->where_in('FTAjhDocNo', $paDataWhere['FTAjhDocNo']);
                $this->db->delete('TCNTPdtAdjStkDT');
            }
            
            $tSQL = "INSERT TCNTPdtAdjStkDT 
                            (FTBchCode, FTAjhDocNo, FNAjdSeqNo, FTPdtCode, FTPdtName, FTPunName, FTAjdBarcode, FTPunCode,
                            FCPdtUnitFact, FTPgpChain, FTAjdPlcCode, FNAjdLayRow, FNAjdLayCol, FCAjdWahB4Adj, FCAjdSaleB4AdjC1,
                            FDAjdDateTimeC1, FCAjdUnitQtyC1, FCAjdQtyAllC1, FCAjdSaleB4AdjC2, FDAjdDateTimeC2, FCAjdUnitQtyC2, FCAjdQtyAllC2, FCAjdUnitQty,
                            FCAjdQtyAll, FCAjdQtyAllDiff, FDLastUpdOn, FTLastUpdBy, FDCreateOn, FTCreateBy) ";

            $tSQL .= "SELECT 
                            DOCTMP.FTBchCode,
                            DOCTMP.FTXthDocNo AS FTAjhDocNo,
                            ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNAjdSeqNo,
                            DOCTMP.FTPdtCode,
                            DOCTMP.FTXtdPdtName,
                            DOCTMP.FTPunName,
                            DOCTMP.FTXtdBarCode,
                            DOCTMP.FTPunCode,
                            DOCTMP.FCXtdFactor,
                            DOCTMP.FTPgpChain,
                            DOCTMP.FTAjdPlcCode,
                            DOCTMP.FNAjdLayRow,
                            DOCTMP.FNAjdLayCol,
                            DOCTMP.FCAjdWahB4Adj,
                            DOCTMP.FCAjdSaleB4AdjC1,
                            DOCTMP.FDAjdDateTimeC1,
                            DOCTMP.FCAjdUnitQtyC1,
                            DOCTMP.FCAjdQtyAllC1,
                            DOCTMP.FCAjdSaleB4AdjC2,
                            DOCTMP.FDAjdDateTimeC2,
                            DOCTMP.FCAjdUnitQtyC2,
                            DOCTMP.FCAjdQtyAllC2,
                            DOCTMP.FCAjdUnitQty,
                            DOCTMP.FCAjdQtyAll,
                            DOCTMP.FCAjdQtyAllDiff,
                            DOCTMP.FDLastUpdOn,
                            DOCTMP.FTLastUpdBy,
                            DOCTMP.FDCreateOn,
                            DOCTMP.FTCreateBy

                    FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                    WHERE 1 = 1
                    ";

                    $tAjhDocNo      = $paDataWhere['FTAjhDocNo'];
                    $tXthDocKey     = $paDataWhere['FTXthDocKey'];
                    $tSesSessionID  = $this->session->userdata('tSesSessionID');    


                    $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";

                    $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";

                    $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";

                    $tSQL .= " ORDER BY DOCTMP.FNXtdSeqNo ASC";

            $oQuery = $this->db->query($tSQL);

            if($oQuery > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add.',
                );
            }
            return $aStatus;
    }

    /**
     * Functionality : Function Get Pdt From Temp
     * Parameters : function parameters
     * Creator : 22/05/2019 Piya(Tiger)
     * Last Modified : -
     * Return : array
     * Return Type : array
     */
    public function FSaMAdjStkSubGetDTTempListPage($paData){

        try{
            $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSQL = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FNXtdSeqNo ASC) AS rtRowID,* FROM
                            (SELECT DOCTMP.FTBchCode,
                                    DOCTMP.FTXthDocNo,
                                    ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                                    DOCTMP.FTXthDocKey,
                                    DOCTMP.FTPdtCode,
                                    DOCTMP.FTXtdPdtName,
                                    DOCTMP.FTPunName,
                                    DOCTMP.FTXtdBarCode,
                                    DOCTMP.FTPunCode,
                                    DOCTMP.FCXtdFactor,
                                    DOCTMP.FTPgpChain,
                                    DOCTMP.FTAjdPlcCode,
                                    DOCTMP.FNAjdLayRow,
                                    DOCTMP.FNAjdLayCol,
                                    DOCTMP.FCAjdWahB4Adj,
                                    DOCTMP.FCAjdSaleB4AdjC1,
                                    DOCTMP.FDAjdDateTimeC1,
                                    DOCTMP.FCAjdUnitQtyC1,
                                    DOCTMP.FCAjdQtyAllC1,
                                    DOCTMP.FCAjdSaleB4AdjC2,
                                    DOCTMP.FDAjdDateTimeC2,
                                    DOCTMP.FCAjdUnitQtyC2,
                                    DOCTMP.FCAjdQtyAllC2,
                                    DOCTMP.FCAjdUnitQty,
                                    DOCTMP.FCAjdQtyAll,
                                    DOCTMP.FCAjdQtyAllDiff,

                                    DOCTMP.FDLastUpdOn,
                                    DOCTMP.FDCreateOn,
                                    DOCTMP.FTLastUpdBy,
                                    DOCTMP.FTCreateBy

                                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                                WHERE 1 = 1";

            $tAjhDocNo      = $paData['FTAjhDocNo'];
            $tXthDocKey     = $paData['FTXthDocKey'];
            $tSesSessionID  = $this->session->userdata('tSesSessionID');    
           
            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";
            
            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $tSearchList = $paData['tSearchAll'];
            
            if ($tSearchList != '') {
                $tSQL .= " AND ( DOCTMP.FTPdtCode LIKE '%$tSearchList%'";
                $tSQL .= " OR DOCTMP.FTXtdPdtName LIKE '%$tSearchList%' ";
                $tSQL .= " OR DOCTMP.FTXtdBarCode LIKE '%$tSearchList%' ";
                $tSQL .= " OR DOCTMP.FTAjdPlcCode LIKE '%$tSearchList%' )";
            }
            
            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";

            $oQuery = $this->db->query($tSQL);

            if($oQuery->num_rows() > 0){
                $aList          = $oQuery->result_array();
                $oFoundRow      = $this->FSoMAdjStkSubGetDTTempListPageAll($paData);
                $nFoundRow      = $oFoundRow[0]->counts;
                $nPageAll       = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult        = array(
                    'raItems'           => $aList,
                    'rnAllRow'          => $nFoundRow,
                    'rnCurrentPage'     => $paData['nPage'],
                    'rnAllPage'         => $nPageAll,
                    'rtCode'            => '1',
                    'rtDesc'            => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"=> 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }

            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }

    }

    //Functionality : All Page Of Product Size
    //Parameters : function parameters
    //Creator :  25/02/2019 Napat(Jame)
    //Return : object Count All Product Model
    //Return Type : Object
    public function FSoMAdjStkSubGetDTTempListPageAll($paData){
        try{

            $tSQL = "SELECT COUNT (DOCTMP.FTXthDocNo) AS counts
                     FROM TCNTDocDTTmp DOCTMP
                     WHERE 1 = 1";

            $tAjhDocNo      = $paData['FTAjhDocNo'];
            $tXthDocKey     = $paData['FTXthDocKey'];
            $tSesSessionID  = $this->session->userdata('tSesSessionID');    

            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tAjhDocNo'";
            
            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";

            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
            
            $tSearchList = $paData['tSearchAll'];
            
            if ($tSearchList != '') {
                $tSQL .= " AND ( DOCTMP.FTPdtCode LIKE '%$tSearchList%'";
                $tSQL .= " OR DOCTMP.FTXtdPdtName LIKE '%$tSearchList%' ";
                $tSQL .= " OR DOCTMP.FTXtdBarCode LIKE '%$tSearchList%' ";
                $tSQL .= " OR DOCTMP.FTAjdPlcCode LIKE '%$tSearchList%' )";
            }
            
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality : Function Get Data Pdt
    //Parameters : function parameters
    //Creator : 02/04/2019 Krit(Copter)
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMAdjStkSubGetDataPdt($paData){

        $tPdtCode       = $paData['FTPdtCode'];
        $FTPunCode      = $paData['FTPunCode'];
        $FTBarCode      = $paData['FTBarCode'];
        $nLngID         = $paData['FNLngID'];

        $tSQL = "SELECT

                    PDT.FTPdtCode,
                    PDT.FTPdtStkCode,
                    PDT.FTPdtStkControl,
                    PDT.FTPdtGrpControl,
                    PDT.FTPdtForSystem,
                    PDT.FCPdtQtyOrdBuy,
                    PDT.FCPdtCostDef,
                    PDT.FCPdtCostOth,
                    PDT.FCPdtCostStd,
                    PDT.FCPdtMin,
                    PDT.FCPdtMax,
                    PDT.FTPdtPoint,
                    PDT.FCPdtPointTime,
                    PDT.FTPdtType,
                    PDT.FTPdtSaleType,
                    PDT.FTPdtSetOrSN,
                    PDT.FTPdtStaSetPri,
                    PDT.FTPdtStaSetShwDT,
                    PDT.FTPdtStaAlwDis,
                    PDT.FTPdtStaAlwReturn,
                    PDT.FTPdtStaVatBuy,
                    PDT.FTPdtStaVat,
                    PDT.FTPdtStaActive,
                    PDT.FTPdtStaAlwReCalOpt,
                    PDT.FTPdtStaCsm,
                    PDT.FTTcgCode,
                    PDT.FTPtyCode,
                    PDT.FTPbnCode,
                    PDT.FTPmoCode,
                    PDT.FTVatCode,
                    PDT.FTEvnCode,
                    PDT.FDPdtSaleStart,
                    PDT.FDPdtSaleStop,

                    PDTL.FTPdtName,
                    PDTL.FTPdtNameOth,
                    PDTL.FTPdtNameABB,
                    PDTL.FTPdtRmk,

                    PKS.FTPunCode,
                    PKS.FCPdtUnitFact,

                    VAT.FCVatRate,
                    
                    UNTL.FTPunName,
                    
                    BAR.FTBarCode,
                    BAR.FTPlcCode,
                    PDTLOCL.FTPlcName,

                    PDTSRL.FTSrnCode,

                    PDT.FCPdtCostStd,
                    CAVG.FCPdtCostEx,
                    CAVG.FCPdtCostIn,
                    SPL.FCSplLastPrice
         
                    
                FROM TCNMPdt PDT
                LEFT JOIN TCNMPdt_L PDTL                ON PDT.FTPdtCode = PDTL.FTPdtCode   AND PDTL.FNLngID = $nLngID
                LEFT JOIN TCNMPdtPackSize  PKS          ON PDT.FTPdtCode = PKS.FTPdtCode    AND PKS.FTPunCode = '$FTPunCode'
                LEFT JOIN TCNMPdtUnit_L UNTL            ON UNTL.FTPunCode = '$FTPunCode'    AND UNTL.FNLngID = $nLngID
                LEFT JOIN TCNMPdtBar BAR                ON PKS.FTPdtCode = BAR.FTPdtCode    AND BAR.FTPunCode = '$FTPunCode' 
                LEFT JOIN TCNMPdtLoc_L PDTLOCL          ON PDTLOCL.FTPlcCode = BAR.FTPlcCode AND PDTLOCL.FNLngID = $nLngID
                LEFT JOIN (SELECT FTVatCode, FCVatRate, FDVatStart   
                           FROM TCNMVatRate WHERE GETdate()> FDVatStart) VAT
                           ON PDT.FTVatCode=VAT.FTVatCode 
                LEFT JOIN TCNTPdtSerial PDTSRL          ON PDT.FTPdtCode = PDTSRL.FTPdtCode
                LEFT JOIN TCNMPdtSpl SPL                ON PDT.FTPdtCode = SPL.FTPdtCode  AND BAR.FTBarCode = SPL.FTBarCode
                LEFT JOIN TCNMPdtCostAvg CAVG           ON PDT.FTPdtCode = CAVG.FTPdtCode
                WHERE 1 = 1 ";

            if($tPdtCode!= ""){
                $tSQL .= "AND PDT.FTPdtCode = '$tPdtCode'";
            }

            if($FTBarCode!= ""){
                $tSQL .= "AND BAR.FTBarCode = '$FTBarCode'";
            }

            $tSQL .= " ORDER BY FDVatStart DESC";

            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result();
                $aResult = array(
                    'raItem'   => $oDetail[0],
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found.',
                );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;
    }


    function FSnMAdjStkSubUpdateInlineDTTemp($aDataUpd, $aDataWhere){

        try{

            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->where('FTXthDocNo', $aDataWhere['FTAjhDocNo']);
            $this->db->where('FNXtdSeqNo', $aDataWhere['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $aDataWhere['FTXthDocKey']);
            $this->db->update('TCNTDocDTTmp', $aDataUpd);

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }


    //Functionality : Function Add DT To Temp
    //Parameters : function parameters
    //Creator : 25/01/2019 Krit
    //Last Modified : -
    //Return : Status Add
    //Return Type : array
    public function FSaMAdjStkSubInsertPDTToTemp($paData, $paDataWhere){

        if($paDataWhere['nAdjStkSubOptionAddPdt']==1){
            // นำสินค้าเพิ่มจำนวนในแถวแรก
            $tSQL = "SELECT FNXtdSeqNo, FCXtdQty FROM TCNTDocDTTmp 
                     WHERE FTBchCode = '".$paDataWhere['FTBchCode']."' 
                     AND FTXthDocNo = '".$paDataWhere['FTAjhDocNo']."'
                     AND FTXthDocKey = '".$paDataWhere['FTXthDocKey']."'
                     AND FTSessionID = '".$paDataWhere['FTSessionID']."'
                     AND FTPdtCode = '".$paData["raItem"]["FTPdtCode"]."' 
                     AND FTXtdBarCode = '".$paData["raItem"]["FTBarCode"]."' ORDER BY FNXtdSeqNo";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aResult = $oQuery->row_array();
                $tSQL = "UPDATE TCNTDocDTTmp SET
                        FCXtdQty = '".($aResult["FCXtdQty"]+1)."'
                        WHERE 
                        FTBchCode = '".$paDataWhere['FTBchCode']."' AND
                        FTXthDocNo  = '".$paDataWhere['FTAjhDocNo']."' AND
                        FNXtdSeqNo = '".$aResult["FNXtdSeqNo"]."' AND
                        FTXthDocKey = '".$paDataWhere['FTXthDocKey']."' AND
                        FTSessionID = '".$paDataWhere['FTSessionID']."' AND
                        FTPdtCode = '".$paData["raItem"]["FTPdtCode"]."' AND 
                        FTXtdBarCode = '".$paData["raItem"]["FTBarCode"]."'";
                $this->db->query($tSQL);
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{
                $paData = $paData['raItem'];

                // เพิ่ม
                $this->db->insert('TCNTDocDTTmp',array(
                
                    'FTBchCode'         => $paDataWhere['FTBchCode'],
                    'FTXthDocNo'        => $paDataWhere['FTAjhDocNo'],
                    'FNXthSeqNo'        => $paDataWhere['nCounts'],
                    'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                    'FTPdtCode'         => $paData['FTPdtCode'],
                    'FTXtdPdtName'      => $paData['FTPdtName'],
                    // 'FTXtdStkCode'      => $paData['FTPdtStkCode'],
                    'FCXtdFactor'       => $paData['FCPdtUnitFact'],
                    'FTPunCode'         => $paData['FTPunCode'],
                    'FTPunName'         => $paData['FTPunName'],
                    'FTXtdBarCode'      => $paDataWhere['FTBarCode'],
                    // 'FTXtdVatType'      => $paData['FTPdtStaVatBuy'],
                    // 'FTVatCode'         => $paData['FTVatCode'],
                    // 'FCXtdVatRate'      => $paData['FCVatRate'],
                    // 'FCXtdQty'          => 1,  // เพิ่มสินค้าใหม่
                    // 'FCXtdQtyAll'       => 1*$paData['FCPdtUnitFact'], // จากสูตร qty * fector
                    // 'FCXtdSetPrice'     => $paDataWhere['pcPrice']*1, // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
                    'FTSessionID'       => $paDataWhere['FTSessionID'],
                    'FDLastUpdOn'       => date('Y-m-d h:i:sa'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d h:i:sa'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')
                ));

                $this->db->last_query();  

                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add.',
                    );
                }
            }
        }else{
            // เพิ่มแถวใหม่
            $paData = $paData['raItem'];

            // เพิ่ม
            $this->db->insert('TCNTDocDTTmp', array(
            
                'FTBchCode'         => $paDataWhere['FTBchCode'],
                'FTXthDocNo'        => $paDataWhere['FTAjhDocNo'],
                'FNXtdSeqNo'        => $paDataWhere['nCounts'],
                'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                'FTPdtCode'         => $paData['FTPdtCode'],
                'FTXtdPdtName'      => $paData['FTPdtName'],
                // 'FTXtdStkCode'      => $paData['FTPdtStkCode'],
                // 'FCXtdStkFac'       => 1,
                'FTPunCode'         => $paData['FTPunCode'],
                'FTPunName'         => $paData['FTPunName'],
                'FCXtdFactor'     => $paData['FCPdtUnitFact'],
                'FTXtdBarCode'      => $paDataWhere['FTBarCode'],
                // 'FTXtdVatType'      => $paData['FTPdtStaVatBuy'],
                // 'FTVatCode'         => $paData['FTVatCode'],
                // 'FCXtdVatRate'      => $paData['FCVatRate'],
                // 'FCXtdQty'          => 1,  //เพิ่มสินค้าใหม่
                // 'FCXtdQtyAll'       => 1*$paData['FCPdtUnitFact'], // จากสูตร qty * fector
                // 'FCXtdSetPrice'     => $paDataWhere['pcPrice']*1, // pcPrice มาจากข้อมูลใน modal คือ (ต้อทุนต่อหน่วยเล็กสุด * fector) จะได้จากสูตร  pcPrice * rate  (rate ต้องนำมาจากสกุลเงินของ company)
                'FTSessionID'       => $paDataWhere['FTSessionID'],
                'FDLastUpdOn'       => date('Y-m-d h:i:sa'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d h:i:sa'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername')
            ));

            $this->db->last_query();  

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add.',
                );
            }
        }
        

    }


    //Functionality : Function Get Pdt From Temp
    //Parameters : function parameters
    //Creator : 25/01/2019 Krit
    //Last Modified : -
    //Return : array
    //Return Type : array
    public function FSaMAdjStkSubGetDTTemp($paDataWhere){

        $tSQL = "SELECT

                    DOCTMP.FTBchCode,
                    DOCTMP.FTXthDocNo,
                    ROW_NUMBER() OVER(ORDER BY DOCTMP.FNXtdSeqNo ASC) AS FNXtdSeqNo,
                    DOCTMP.FTXthDocKey,
                    DOCTMP.FTPdtCode,
                    DOCTMP.FTXtdPdtName,
                    DOCTMP.FTXtdStkCode,
                    /*DOCTMP.FCXtdStkFac,*/
                    DOCTMP.FTPunCode,
                    DOCTMP.FTPunName,
                    DOCTMP.FCXtdFactor,
                    DOCTMP.FTXtdBarCode,
                    DOCTMP.FTXtdVatType,
                    DOCTMP.FTVatCode,
                    DOCTMP.FCXtdVatRate,
                    DOCTMP.FCXtdQty,
                    DOCTMP.FCXtdQtyAll,
                    DOCTMP.FCXtdSetPrice,
                    DOCTMP.FCXtdAmt,
                    DOCTMP.FCXtdVat,
                    DOCTMP.FCXtdVatable,
                    DOCTMP.FCXtdNet,
                    DOCTMP.FCXtdCostIn,
                    DOCTMP.FCXtdCostEx,
                    DOCTMP.FTXtdStaPrcStk,
                    DOCTMP.FNXtdPdtLevel,
                    DOCTMP.FTXtdPdtParent,
                    DOCTMP.FCXtdQtySet,
                    DOCTMP.FTXtdPdtStaSet,
                    DOCTMP.FTXtdRmk,
                    DOCTMP.FTSessionID,

                    DOCTMP.FDLastUpdOn,
                    DOCTMP.FDCreateOn,
                    DOCTMP.FTLastUpdBy,
                    DOCTMP.FTCreateBy


                FROM TCNTDocDTTmp DOCTMP WITH (NOLOCK)
                WHERE 1 = 1
            ";

            $tXthDocNo      = $paDataWhere['FTAjhDocNo'];
            $tXthDocKey     = $paDataWhere['FTXthDocKey'];
            $tSesSessionID  = $this->session->userdata('tSesSessionID');    

           
            $tSQL .= " AND DOCTMP.FTSessionID = '$tSesSessionID'";
           
            $tSQL .= " AND DOCTMP.FTXthDocNo = '$tXthDocNo'";
            
            $tSQL .= " AND DOCTMP.FTXthDocKey = '$tXthDocKey'";
            
            $tSQL .= " ORDER BY DOCTMP.FNXtdSeqNo ASC";

            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result_array();
                $aResult = array(
                    'raItems'   => $oDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found.',
                );
            }

        return $aResult;

    }

    function FSnMAdjStkSubCheckPdtTempForTransfer($tDocNo){
        $tSQL = "SELECT COUNT(FNXtdSeqNo) AS nSeqNo FROM TCNTDocDTTmp WHERE FTXthDocKey = 'TCNTPdtTwxHD' AND FTXthDocNo = '".$tDocNo."' AND FTSessionID = '".$this->session->userdata('tSesSessionID')."'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array()["nSeqNo"];
        }else{
            return 0;
        }
    }

    function FSnMAdjStkSubCheckHaveProductInDT($ptDocNo, $ptBchCode){
        $tSQL = "SELECT COUNT(FNXtdSeqNo) AS nSeqNo FROM TCNTPdtAdjStkDT WHERE FTAjhDocNo = '".$ptDocNo."' AND FTBchCode = '".$ptBchCode."'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array()["nSeqNo"];
        }else{
            return 0;
        }
    }

    function FSaMAdjStkSubAddUpdateDocNoInDocTemp($aDataWhere){

        try{

            $this->db->set('FTXthDocNo' , $aDataWhere['FTAjhDocNo']);    
            $this->db->set('FTBchCode'  , $aDataWhere['FTBchCode']);    
            $this->db->where('FTXthDocNo','');
            $this->db->where('FTSessionID', $this->session->userdata('tSesSessionID'));
            $this->db->where('FTXthDocKey', $aDataWhere['FTXthDocKey']);
            $this->db->update('TCNTDocDTTmp');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }


    //Function : Update หลังจาก คำนวน DT เอายอดรวม มา Upd
    function FSaMAdjStkSubUpdateHDFCXthTotal($paDataUpdHD,$ptXthDocNo){

        try{
            //DT Dis
            $this->db->where('FTAjhDocNo', $ptXthDocNo);
            $this->db->update('TCNTPdtAdjStkHD',$paDataUpdHD);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }

    }

    /*Function : Update DTTmp หลังจาก Edit In line
    Parameters : function parameters
    Creator : 02/04/2019 Krit(Copter)
    Last Modified : -
    Return : array
    Return Type : array
    */
    function FSnMWTOUpdateDTTemp($aDataUpd,$paDataWhere){

        try{

            if(is_array($aDataUpd) == 1){

                //ลบ ใน Temp
                $this->db->where_in('FTSessionID', $this->session->userdata('tSesSessionID'));
                $this->db->where_in('FTXthDocKey', $paDataWhere['FTXthDocKey']);
                $this->db->where_in('FTXthDocNo', $paDataWhere['FTAjhDocNo']);
                $this->db->delete('TCNTDocDTTmp');
    
                foreach($aDataUpd as $key=>$val){
                    //เพิ่ม
                    $this->db->insert('TCNTDocDTTmp',$aDataUpd[$key]);
                    if($this->db->affected_rows() > 0){
                        $aStatus = array(
                            'rtCode' => '1',
                            'rtDesc' => 'Add Success.',
                        );
                    }else{
                        $aStatus = array(
                            'rtCode' => '905',
                            'rtDesc' => 'Error Cannot Add.',
                        );
                    }
                }

                return $aStatus;
    
            }

        }catch(Exception $Error){
            return $Error;
        }
    }


    //Function : Cancel Doc
    public function FSvMAdjStkSubCancel($paDataUpdate){
        try{
            //DT Dis
            $this->db->set('FTAjhStaDoc' , 3);
            $this->db->where('FTAjhDocNo', $paDataUpdate['FTAjhDocNo']);
            $this->db->update('TCNTPdtAdjStkHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }


    //Function : Approve Doc
    public function FSvMAdjStkSubApprove($paDataUpdate){
        try{
            //DT Dis
            $this->db->set('FTAjhStaPrcStk' , 2);
            $this->db->set('FTAjhStaApv' , 2);
            $this->db->set('FTXthApvCode' , $paDataUpdate['FTXthApvCode']);
            $this->db->where('FTAjhDocNo', $paDataUpdate['FTAjhDocNo']);

            $this->db->update('TCNTPdtAdjStkHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'OK',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Approve',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }
    }

    public function FStAdjStkSubGetShpCodeForUsrLogin($paDataShp){

        $nLngID = $paDataShp['FNLngID'];
        $tUsrLogin = $paDataShp['FTUsrCode'];
        
        if($this->session->userdata('tSesUsrLevel') == "HQ"){
            $tBchCode = "'" . FCNtGetBchInComp() . "'";
        }else{
            $tBchCode = "UGP.FTBchCode";
        }
        $tSQL = "SELECT BCH.FTBchCode,
                        BCHL.FTBchName,
                        MCHL.FTMerCode,
                        MCHL.FTMerName,
                        UGP.FTShpCode,
                        SHPL.FTShpName,
                        SHP.FTShpType,
                        USR.FTUsrCode,
                        USRL.FTUsrName,
                        USR.FTDptCode,
                        DPTL.FTDptName,
                        WAH.FTWahCode AS FTWahCode,
			WAHL.FTWahName AS FTWahName
                        /*  BCH.FTWahCode AS FTWahCode_Bch,  */
                        /*  BWAHL.FTWahName AS FTWahName_Bch  */

                FROM TCNMUser USR
                LEFT JOIN TCNMUser_L USRL ON USRL.FTUsrCode = USR.FTUsrCode AND USRL.FNLngID = $nLngID
                LEFT JOIN TCNTUsrGroup UGP ON UGP.FTUsrCode = USR.FTUsrCode
                LEFT JOIN TCNMBranch BCH ON $tBchCode = BCH.FTBchCode 
                LEFT JOIN TCNMBranch_L BCHL ON $tBchCode = BCHL.FTBchCode 
                LEFT JOIN TCNMShop SHP ON UGP.FTShpCode = SHP.FTShpCode
                LEFT JOIN TCNMShop_L SHPL ON UGP.FTShpCode = SHPL.FTShpCode AND UGP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                LEFT JOIN TCNMWaHouse WAH ON ($tBchCode = WAH.FTWahRefCode OR SHP.FTShpCode = WAH.FTWahRefCode)
                LEFT JOIN TCNMWaHouse_L WAHL ON WAH.FTWahCode = WAHL.FTWahCode AND SHP.FTShpCode = WAHL.FTShpCode AND WAHL.FNLngID = $nLngID
                LEFT JOIN TCNMMerchant_L MCHL ON SHP.FTMerCode = MCHL.FTMerCode AND  MCHL.FNLngID = $nLngID  
                LEFT JOIN TCNMUsrDepart_L DPTL ON DPTL.FTDptCode = USR.FTDptCode AND DPTL.FNLngID = $nLngID    
                WHERE USR.FTUsrCode ='".$tUsrLogin."'";
        $oQuery = $this->db->query($tSQL);
       
        if ($oQuery->num_rows() > 0){
            $oRes  = $oQuery->row_array();
            $tDataShp = $oRes;
        }else{
            $tDataShp = '';
        }

        return $tDataShp;
    }

    public function FSaMAdjStkSubGetDefOptionAdjStkSub($paData){

        $nLngID     = $paData['FNLngID'];

        $tSQL = "SELECT CON.FTSysStaUsrValue,WAHL.FTWahName
                FROM TSysConfig CON
                LEFT JOIN TCNMWaHouse_L WAHL ON CON.FTSysStaUsrValue = WAHL.FTWahCode AND WAHL.FNLngID = $nLngID
                WHERE FTSysCode='tCN_WahAdjStkSub' AND FTSysSeq='1'";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oRes  = $oQuery->result();
        $tData = $oRes[0];
        }else{
        $tData = '';
        }

        return $tData;

    }

    //Get Vat rate ของ DocNo
    public function FSaMAdjStkSubGetAddress($ptBchCode,$ptXthShipAdd,$nLngID){
    
        $tSQL = "SELECT FTAddV1No,
                        FTAddV1Soi,
                        FTAddV1Village,
                        FTAddV1Road,
                        FTAddV1SubDist,
                        FTAddV1DstCode,
                        FTAddV1PvnCode,
                        FTAddV1PostCode,
                        PVNL.FTPvnName,
                        DSTL.FTDstName,
                        SUBDSTL.FTSudName
        
                FROM TCNMAddress_L ADDL WITH (NOLOCK)

                LEFT JOIN TCNMProvince_L   PVNL WITH (NOLOCK) ON ADDL.FTAddV1PvnCode = PVNL.FTPvnCode AND PVNL.FNLngID = $nLngID
                LEFT JOIN TCNMDistrict_L   DSTL WITH (NOLOCK) ON ADDL.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = $nLngID
                LEFT JOIN TCNMSubDistrict_L SUBDSTL WITH (NOLOCK) ON ADDL.FTAddV1SubDist = SUBDSTL.FTSudCode AND SUBDSTL.FNLngID = $nLngID
                WHERE FTAddGrpType = 1
                ";

        if($ptBchCode!= ""){
            $tSQL .= " AND FTAddRefCode = '$ptBchCode'";
        }

        if($ptXthShipAdd!= ""){
            $tSQL .= " AND FNAddSeqNo = '$ptXthShipAdd'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oData    = $oQuery->result();
        }else{
            $oData = 0;
        }

        return $oData;
    }

    //Get Vat rate ของ DocNo
    public function FScMAdjStkSubGetVatRateFromDoc($ptXthDocNo){
        
        $tSQL = "SELECT ISNULL(FCXthVATRate,0) AS FCXthVATRate    
                FROM TAPTOrdHD ";

        if($ptXthDocNo!= ""){
            $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthVATRate = $oData[0]->FCXthVATRate;
        }else{
        $cXthVATRate = 0;
        }

        return $cXthVATRate;
    }

    public function FSaMAdjStkSubGetHDFCXthWpTax($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXpdWhtAmt),0) AS FCXthWpTax  
                 FROM TAPTOrdDT ";

        if($ptXthDocNo!= ""){
        $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthWpTax = $oData[0]->FCXthWpTax;
        }else{
        $cXthWpTax = 0;
        }

        return $cXthWpTax;

    }

    public function FSaMAdjStkSubGetHDFCXthNoVatAfDisChg($ptXthDocNo){

        $tSQL = "SELECT ISNULL(HDis.FCXthNoVatDisChgAvi-SUM(HDis.FCXthDisNoVat-HDis.FCXthChgNoVat),0) AS FCXthNoVatAfDisChg
                 FROM TAPTOrdHDDis HDis ";

        if($ptXthDocNo != ""){
            $tSQL .= " WHERE HDis.FTAjhDocNo = '$ptXthDocNo'";
        }

        $tSQL .= " GROUP BY HDis.FCXthNoVatDisChgAvi";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthNoVatAfDisChg = $oData[0]->FCXthNoVatAfDisChg;
        }else{
        $cXthNoVatAfDisChg = 0;
        }

        return $cXthNoVatAfDisChg;

    }

    public function FSaMAdjStkSubGetFCXthRefAEAmt($ptXthDocNo){

        $tSQL = "SELECT FCXthRefAEAmt
                 FROM TAPTOrdHD HD";

        if($ptXthDocNo != ""){
            $tSQL .= " WHERE HD.FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthRefAEAmt = $oData[0]->FCXthRefAEAmt;
        }else{
        $cXthRefAEAmt = 0;
        }

        return $cXthRefAEAmt;

    }
    
    public function FSaMAdjStkSubGetSUMFCXddDisVatMinusFCXddChgVat($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXthDisVat - FCXthChgVat),0) AS  FCXthDisRes
                 FROM TAPTOrdHDDis HDis ";

        if($ptXthDocNo != ""){
            $tSQL .= "WHERE HDis.FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthDisRes = $oData[0]->FCXthDisRes;
        }else{
        $cXthDisRes = 0;
        }

        return $cXthDisRes;

    }

    public function FSaMAdjStkSubGetHDFCXthChg($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(HDis.FCXthChgVat+HDis.FCXthChgNoVat),0) AS FCXthChg
                 FROM TAPTOrdHDDis HDis ";

        if($ptXthDocNo != ""){
            $tSQL .= "WHERE HDis.FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXpdChg = $oData[0]->FCXthChg;
        }else{
        $cXpdChg = 0;
        }

        return $cXpdChg;

    }

    public function FSaMAdjStkSubGetHDFCXthDis($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(HDis.FCXthDisVat+HDis.FCXthDisNoVat),0) AS FCXthDis
                 FROM TAPTOrdHDDis HDis ";

        if($ptXthDocNo != ""){
            $tSQL .= "WHERE HDis.FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthDis = $oData[0]->FCXthDis;
        }else{
        $cXthDis = 0;
        }

        return $cXthDis;

    }

    public function FSaMAdjStkSubGetHDFCXthNoVatDisChgAvi($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXpdNet),0) AS FCXthNoVatDisChgAvi  
                 FROM TAPTOrdDT ";

        if($ptXthDocNo!= ""){
        $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'
                  AND FTXpdStaAlwDis='1' 
                  AND FTXpdVatType ='2'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthTotal = $oData[0]->FCXthNoVatDisChgAvi;
        }else{
        $cXthTotal = 0;
        }

        return $cXthTotal;

    }

    public function FSaMAdjStkSubGetHDFTXthDisChgTxt($ptXthDocNo){

        $tSQL = "SELECT FTXthDisChgTxt 
                 FROM TAPTOrdHDDis ";

        if($ptXthDocNo!= ""){
        $tSQL .= " WHERE FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthTotal = $oData;
        }else{
        $cXthTotal = 0;
        }

        return $cXthTotal;

    }

    public function FSaMAdjStkSubGetHDFCXthVatDisChgAvi($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXpdNet),0) AS FCXthVatDisChgAvi  
                 FROM TAPTOrdDT ";

        if($ptXthDocNo!= ""){
        $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'
                  AND FTXpdStaAlwDis = '1' 
                  AND FTXpdVatType ='1'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthTotal = $oData[0]->FCXthVatDisChgAvi;
        }else{
        $cXthTotal = 0;
        }

        return $cXthTotal;

    }

    public function FSaMAdjStkSubGetHDFCXthNoVatNoDisChg($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXpdNet),0)AS FCXthNoVatNoDisChg  
                 FROM TAPTOrdDT ";

        if($ptXthDocNo!= ""){
        $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'
                    AND FTXpdStaAlwDis='2' 
                    AND FTXpdVatType ='2'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthTotal = $oData[0]->FCXthNoVatNoDisChg;
        }else{
        $cXthTotal = 0;
        }

        return $cXthTotal;

    }

    public function FSaMAdjStkSubGetHDFCXthVatNoDisChg($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXpdNet),0)AS FCXthVatNoDisChg  
                 FROM TAPTOrdDT ";

        if($ptXthDocNo!= ""){
        $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'
                  AND FTXpdStaAlwDis='2'
                  AND FTXpdVatType ='1' ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
        $oData    = $oQuery->result();
        $cXthTotal = $oData[0]->FCXthVatNoDisChg;
        }else{
        $cXthTotal = 0;
        }

        return $cXthTotal;

    }

    public function FSaMAdjStkSubGetHDFCXthTotal($ptXthDocNo){

        $tSQL = "SELECT ISNULL(SUM(FCXpdNet),0) AS FCXthTotal
                 FROM TAPTOrdDT 
                ";

        if($ptXthDocNo!= ""){
            $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oData    = $oQuery->result();
            $cXthTotal = $oData[0]->FCXthTotal;
        }else{
            $cXthTotal = 0;
        }

        return $cXthTotal;

    }

    public function FCNxAdjStkSubGetvatInOrEx($ptXthDocNo){

        $tSQL = "SELECT FTXthVATInOrEx
                 FROM TAPTOrdHD 
                ";

        if($ptXthDocNo!= ""){
            $tSQL .= "WHERE FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $tXthVATInOrEx = $oDetail[0]->FTXthVATInOrEx;
        }else{
            $tXthVATInOrEx = '';
        }

        return $tXthVATInOrEx;
    }


    public function FSaMAdjStkSubGetRteFacHD($ptXthDocNo){

        $tSQL = "SELECT FCXthRteFac
                 FROM TAPTOrdHD ORDHD
                 WHERE 1 = 1
                ";

        if($ptXthDocNo!= ""){
            $tSQL .= "AND ORDHD.FTAjhDocNo = '$ptXthDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $cXthRteFac = $oDetail[0]->FCXthRteFac;
        }else{
            $cXthRteFac = '';
        }

        return $cXthRteFac;

    }

    public function FSaMAdjStkSubGetPdtIntoTableDT($paData){

        $tPdtCode   = $paData['FTPdtCode'];
        $FTPunCode   = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL = "SELECT

                    PDT.FTPdtCode,
                    PDT.FTPdtStkControl,
                    PDT.FTPdtGrpControl,
                    PDT.FTPdtForSystem,
                    PDT.FCPdtQtyOrdBuy,
                    PDT.FCPdtCostDef,
                    PDT.FCPdtCostOth,
                    PDT.FCPdtCostStd,
                    PDT.FCPdtMin,
                    PDT.FCPdtMax,
                    PDT.FTPdtPoint,
                    PDT.FCPdtPointTime,
                    PDT.FTPdtType,
                    PDT.FTPdtSaleType,
                    PDT.FTPdtSetOrSN,
                    PDT.FTPdtStaSetPri,
                    PDT.FTPdtStaSetShwDT,
                    PDT.FTPdtStaAlwDis,
                    PDT.FTPdtStaAlwReturn,
                    PDT.FTPdtStaVat,
                    PDT.FTPdtStaActive,
                    PDT.FTPdtStaAlwReCalOpt,
                    PDT.FTPdtStaCsm,
                    /*PDT.FTShpCode,*/
                    PDT.FTPdtRefShop,
                    PDT.FTTcgCode,
                    PDT.FTPtyCode,
                    PDT.FTPbnCode,
                    PDT.FTPmoCode,
                    PDT.FTVatCode,
                    PDT.FTEvnCode,
                    PDT.FDPdtSaleStart,
                    PDT.FDPdtSaleStop,

                    PDTL.FTPdtName,
                    PDTL.FTPdtNameOth,
                    PDTL.FTPdtNameABB,
                    PDTL.FTPdtRmk,

                    PKS.FTPunCode,
                    PKS.FCPdtUnitFact,

                    VAT.FCVatRate,
                    
                    UNTL.FTPunName,
                    
                    BAR.FTBarCode,

                    PDTSRL.FTSrnCode,

                    PDT.FCPdtCostStd,
                    CAVG.FCPdtCostEx,
                    CAVG.FCPdtCostIn,
                    SPL.FCSplLastPrice
         
                    
                FROM TCNMPdt PDT
                LEFT JOIN TCNMPdt_L PDTL                ON PDT.FTPdtCode = PDTL.FTPdtCode   AND PDTL.FNLngID = $nLngID
                LEFT JOIN TCNMPdtPackSize  PKS          ON PDT.FTPdtCode = PKS.FTPdtCode    AND PKS.FTPunCode = $FTPunCode
                LEFT JOIN TCNMPdtUnit_L UNTL            ON UNTL.FTPunCode = $FTPunCode      AND UNTL.FNLngID = $nLngID
                LEFT JOIN TCNMPdtBar BAR                ON PKS.FTPdtCode = BAR.FTPdtCode    AND BAR.FTPunCode = $FTPunCode
                LEFT JOIN (SELECT FTVatCode,FCVatRate,FDVatStart   
                           FROM TCNMVatRate WHERE GETdate()> FDVatStart) VAT
                           ON PDT.FTVatCode=VAT.FTVatCode 
                LEFT JOIN TCNTPdtSerial PDTSRL          ON PDT.FTPdtCode = PDTSRL.FTPdtCode
                LEFT JOIN TCNMPdtSpl SPL                ON PDT.FTPdtCode = SPL.FTPdtCode  AND BAR.FTBarCode = SPL.FTBarCode
                LEFT JOIN TCNMPdtCostAvg CAVG           ON PDT.FTPdtCode = CAVG.FTPdtCode

                WHERE PDT.FTPdtForSystem = '1'
                AND PDT.FTPdtType IN('1','2','4')
                AND PDT.FTPdtStaActive = '1'
                AND SPL.FTSplStaAlwAdjStkSub = '1'
                -- AND '2018/08/06' Between SPL.FDPdtAlwOrdStart AND SPL.FDPdtAlwOrdStop
                -- AND SPL.FTPdtStaAlwOrdSun='1'
                ";


            if($tPdtCode!= ""){
            $tSQL .= " AND PDT.FTPdtCode = '$tPdtCode'";
            }

            $tSQL .= " ORDER BY FDVatStart DESC";
            
            $oQuery = $this->db->query($tSQL);
            
            if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItem'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
            }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;

    }

    public function FSaMAdjStkSubGetPdtDetail($paData){

        $tPdtCode   = $paData['FTPdtCode'];
        $FTPunCode   = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL = "SELECT

                    PDT.FTPdtCode,
                    PDT.FTPdtStkControl,
                    PDT.FTPdtGrpControl,
                    PDT.FTPdtForSystem,
                    PDT.FCPdtQtyOrdBuy,
                    PDT.FCPdtCostDef,
                    PDT.FCPdtCostOth,
                    PDT.FCPdtCostStd,
                    PDT.FCPdtMin,
                    PDT.FCPdtMax,
                    PDT.FTPdtPoint,
                    PDT.FCPdtPointTime,
                    PDT.FTPdtType,
                    PDT.FTPdtSaleType,
                    PDT.FTPdtSetOrSN,
                    PDT.FTPdtStaSetPri,
                    PDT.FTPdtStaSetShwDT,
                    PDT.FTPdtStaAlwDis,
                    PDT.FTPdtStaAlwReturn,
                    PDT.FTPdtStaVat,
                    PDT.FTPdtStaActive,
                    PDT.FTPdtStaAlwReCalOpt,
                    PDT.FTPdtStaCsm,
                    /*PDT.FTShpCode,*/
                    PDT.FTPdtRefShop,
                    PDT.FTTcgCode,
                    PDT.FTPtyCode,
                    PDT.FTPbnCode,
                    PDT.FTPmoCode,
                    PDT.FTVatCode,
                    PDT.FTEvnCode,
                    PDT.FDPdtSaleStart,
                    PDT.FDPdtSaleStop,

                    PDTL.FTPdtName,
                    PDTL.FTPdtNameOth,
                    PDTL.FTPdtNameABB,
                    PDTL.FTPdtRmk,

                    PDTPCKSZE.FCPdtUnitFact,

                    UNTL.FTPunName,

                    BAR.FTBarCode,

                    PDTSRL.FTSrnCode
                    
                FROM TCNMPdt PDT
                LEFT JOIN TCNMPdt_L PDTL                ON PDT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
                LEFT JOIN TCNMPdtPackSize  PDTPCKSZE    ON PDT.FTPdtCode = PDTPCKSZE.FTPdtCode AND PDTPCKSZE.FTPunCode = $FTPunCode
                LEFT JOIN TCNMPdtUnit_L UNTL            ON UNTL.FTPunCode = $FTPunCode
                LEFT JOIN TCNMPdtBar BAR                ON PDT.FTPdtCode = BAR.FTPdtCode AND BAR.FTPunCode = $FTPunCode
                LEFT JOIN TCNTPdtSerial PDTSRL          ON PDT.FTPdtCode = PDTSRL.FTPdtCode 
                WHERE 1=1 ";

            if($tPdtCode!= ""){
            $tSQL .= "AND PDT.FTPdtCode = '$tPdtCode'";
            }

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItem'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
            }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;

    }

    //Functionality : Search Subdistrict By ID
    //Parameters : function parameters
    //Creator : 12/06/2018 Wasin
    //Last Modified : -
    //Return : Data Array
    //Return Type : Array
    public function FSaMSDTSearchByID($paData){

        $tSudCode   = $paData['FTSudCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL = "SELECT
                    SDT.FTSudCode       AS rtSudCode,
                    SDT.FTDstCode       AS rtDstCode,
                    DSTL.FTDstName      AS rtDstName,
                    SDT.FTSudLatitude   AS rtSudLatitude,
                    SDT.FTSudLongitude  AS rtSudLongitude,
                    SDTL.FTSudName      AS rtSudName,
                    PVNL.FTPvnCode      AS rtPvnCode,
                    PVNL.FTPvnName      AS rtPvnName
                FROM TCNMSubDistrict SDT
                LEFT JOIN TCNMSubDistrict_L SDTL ON SDT.FTSudCode = SDTL.FTSudCode AND SDTL.FNLngID = $nLngID
                LEFT JOIN TCNMDistrict  DST ON SDT.FTDstCode = DST.FTDstCode
                LEFT JOIN TCNMDistrict_L DSTL ON SDT.FTDstCode = DSTL.FTDstCode AND DSTL.FNLngID = $nLngID
                LEFT JOIN TCNMProvince_L PVNL ON DST.FTPvnCode = PVNL.FTPvnCode AND PVNL.FNLngID = $nLngID
                WHERE 1=1 ";

        if($tSudCode!= ""){
            $tSQL .= "AND SDT.FTSudCode = '$tSudCode'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Search AdjStkSub By ID
     * Parameters : function parameters
     * Creator : 22/05/2019 Piya(Tiger)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMAdjStkSubGetHD($paData){

        $tAjhDocNo  = $paData['FTAjhDocNo'];
        $nLngID     = $paData['FNLngID'];
        
        $tSQL = "SELECT
                    ADJSTK.FTBchCode,
                    ADJSTK.FTAjhDocNo,
                    ADJSTK.FTAjhDocType,
                    CONVERT(CHAR(5), ADJSTK.FDAjhDocDate, 108) AS FTAjhDocTime,
                    ADJSTK.FDAjhDocDate,
                    ADJSTK.FTAjhBchTo,
                    ADJSTK.FTAjhMerchantTo,
                    ADJSTK.FTAjhShopTo,
                    ADJSTK.FTAjhPosTo,
                    ADJSTK.FTAjhWhTo,
                    ADJSTK.FTAjhPlcCode,
                    ADJSTK.FTDptCode,
                    ADJSTK.FTUsrCode,
                    ADJSTK.FTRsnCode,
                    ADJSTK.FTAjhRmk,
                    ADJSTK.FNAjhDocPrint,
                    ADJSTK.FTAjhApvSeqChk,
                    ADJSTK.FTAjhApvCode,
                    ADJSTK.FTAjhStaApv,
                    ADJSTK.FTAjhStaPrcStk,
                    ADJSTK.FTAjhStaDoc,
                    ADJSTK.FNAjhStaDocAct,
                    ADJSTK.FTAjhDocRef,
                    /*ADJSTK.FTAjhStaDelMQ,*/
                    ADJSTK.FDLastUpdOn,
                    ADJSTK.FTLastUpdBy,
                    ADJSTK.FDCreateOn,
                    ADJSTK.FTCreateBy,
                    
                    BCHLDOC.FTBchName,
                    BCHLTO.FTBchName AS FTAjhBchNameTo,
                    MCHLTO.FTMerName AS FTAjhMerchantNameTo,
                    USRLCREATE.FTUsrName AS FTCreateByName,
                    USRLKEY.FTUsrName AS FTUsrName,
                    USRAPV.FTUsrName AS FTAjhStaApvName,
                    DPTL.FTDptName,
                    SHPLTO.FTShpName AS FTAjhShopNameTo,
                    WAHLTO.FTWahName AS FTAjhWhNameTo,
                    POSVDTO.FTPosComName AS FTAjhPosNameTo
                    
                FROM [TCNTPdtAdjStkHD] ADJSTK

                LEFT JOIN TCNMBranch_L      BCHLDOC ON ADJSTK.FTBchCode = BCHLDOC.FTBchCode AND BCHLDOC.FNLngID = $nLngID
                LEFT JOIN TCNMBranch_L      BCHLTO ON ADJSTK.FTAjhBchTo = BCHLTO.FTBchCode AND BCHLTO.FNLngID = $nLngID
                LEFT JOIN TCNMMerchant_L    MCHLTO ON ADJSTK.FFAjhMerchantTo = MCHLTO.FTMerCode AND MCHLTO.FNLngID = $nLngID    
                LEFT JOIN TCNMUser_L        USRLCREATE ON ADJSTK.FTCreateBy = USRLCREATE.FTUsrCode AND USRLCREATE.FNLngID = $nLngID
                LEFT JOIN TCNMUser_L        USRLKEY ON ADJSTK.FTUsrCode = USRLKEY.FTUsrCode AND USRLKEY.FNLngID = $nLngID
                LEFT JOIN TCNMUser_L        USRAPV ON ADJSTK.FTAjhApvCode = USRAPV.FTUsrCode AND USRAPV.FNLngID = $nLngID
                LEFT JOIN TCNMUsrDepart_L   DPTL ON ADJSTK.FTDptCode = DPTL.FTDptCode AND DPTL.FNLngID = $nLngID
                LEFT JOIN TCNMShop_L        SHPLTO ON ADJSTK.FTAjhShopTo = SHPLTO.FTShpCode AND SHPLTO.FNLngID = $nLngID
                LEFT JOIN TCNMWaHouse_L     WAHLTO ON ADJSTK.FTAjhWhTo = WAHLTO.FTWahCode AND SHPLTO.FTShpCode = WAHLTO.FTShpCode AND WAHLTO.FNLngID = $nLngID
                LEFT JOIN TCNMPosLastNo     POSVDTO WITH (NOLOCK) ON ADJSTK.FTAjhPosTo = POSVDTO.FTPosCode   
                 
                WHERE 1=1 ";
      
        if($tAjhDocNo != ""){
            $tSQL .= "AND ADJSTK.FTAjhDocNo = '$tAjhDocNo'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();

            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            // Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Data List Subdistrict
    //Parameters : function parameters
    //Creator :  12/06/2018 Wasin
    //Last Modified : -
    //Return : Data Array
    //Return Type : Array
    public function FSaMAdjStkSubGetDT($paData){

        $tXthDocNo = $paData['FTAjhDocNo'];

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTAjhDocNo ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT

                                    TWFDT.FTBchCode,
                                    TWFDT.FTAjhDocNo,
                                    TWFDT.FNXtdSeqNo,
                                    TWFDT.FTPdtCode,
                                    TWFDT.FTXtdPdtName,
                                    TWFDT.FTXtdStkCode,
                                    /*TWFDT.FCXtdStkFac,*/
                                    TWFDT.FTPunCode,
                                    TWFDT.FTPunName,
                                    TWFDT.FCXtdFactor,
                                    TWFDT.FTXtdBarCode,
                                    TWFDT.FTXtdVatType,
                                    TWFDT.FTVatCode,
                                    TWFDT.FCXtdVatRate,
                                    TWFDT.FCXtdQty,
                                    TWFDT.FCXtdQtyAll,
                                    TWFDT.FCXtdSetPrice,
                                    TWFDT.FCXtdAmt,
                                    TWFDT.FCXtdVat,
                                    TWFDT.FCXtdVatable,
                                    TWFDT.FCXtdNet,
                                    TWFDT.FCXtdCostIn,
                                    TWFDT.FCXtdCostEx,
                                    TWFDT.FTXtdStaPrcStk,
                                    TWFDT.FNXtdPdtLevel,
                                    TWFDT.FTXtdPdtParent,
                                    TWFDT.FCXtdQtySet,
                                    TWFDT.FTXtdPdtStaSet,
                                    TWFDT.FTXtdRmk,
                                    TWFDT.FDLastUpdOn,
                                    TWFDT.FTLastUpdBy,
                                    TWFDT.FDCreateOn,
                                    TWFDT.FTCreateBy

                            FROM [TCNTPdtAdjStkDT] TWFDT
                            ";
        
       
        if(@$tXthDocNo != ''){
            $tSQL .= " WHERE (TWFDT.FTAjhDocNo = '$tXthDocNo')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'rtCode' => '1',
                'raItems'   => $oDetail,
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;

    }


    //Functionality : Function Add DT To Temp
    //Parameters : function parameters
    //Creator : 03/04/2019 Krit(Copter)
    //Last Modified : -
    //Return : Status Add
    //Return Type : array
    public function FSaMAdjStkSubInsertDTToTemp($paData, $paDataWhere){
      

        if($paData['rtCode'] == 1){

            $paData = $paData['raItems'];

            //ลบ ใน Temp
            if($paData[0]['FTAjhDocNo'] != ''){
                $this->db->where_in('FTXthDocNo', $paData[0]['FTAjhDocNo']);
                $this->db->where_in('FTSessionID', $this->session->userdata('tSesSessionID'));
                $this->db->delete('TCNTDocDTTmp');
            }

            foreach($paData as $key=>$val){

                //เพิ่ม
                $this->db->insert('TCNTDocDTTmp',array(
                
                    'FTBchCode'         => $val['FTBchCode'],
                    'FTXthDocNo'        => $val['FTAjhDocNo'],
                    'FNXtdSeqNo'        => $val['FNXtdSeqNo'],
                    'FTXthDocKey'       => $paDataWhere['FTXthDocKey'],
                    'FTPdtCode'         => $val['FTPdtCode'],
                    'FTXtdPdtName'      => $val['FTXtdPdtName'],
                    'FTXtdStkCode'      => $val['FTXtdStkCode'],
                    //'FCXtdStkFac'       => $val['FCXtdStkFac'],
                    'FTPunCode'         => $val['FTPunCode'],
                    'FTPunName'         => $val['FTPunName'],
                    'FCXtdFactor'       => $val['FCXtdFactor'],
                    'FTXtdBarCode'      => $val['FTXtdBarCode'],
                    'FTXtdVatType'      => $val['FTXtdVatType'],
                    'FTVatCode'         => $val['FTVatCode'],
                    'FCXtdVatRate'      => $val['FCXtdVatRate'],
                    'FCXtdQty'          => $val['FCXtdQty'],
                    'FCXtdQtyAll'       => $val['FCXtdQtyAll'],
                    'FCXtdSetPrice'     => $val['FCXtdSetPrice'],
                    'FCXtdAmt'          => $val['FCXtdAmt'],
                    'FCXtdVat'          => $val['FCXtdVat'],
                    'FCXtdVatable'      => $val['FCXtdVatable'],
                    'FCXtdNet'          => $val['FCXtdNet'],
                    'FCXtdCostIn'       => $val['FCXtdCostIn'],
                    'FCXtdCostEx'       => $val['FCXtdCostEx'],
                    'FTXtdStaPrcStk'    => $val['FTXtdStaPrcStk'],
                    'FNXtdPdtLevel'     => $val['FNXtdPdtLevel'],
                    'FTXtdPdtParent'    => $val['FTXtdPdtParent'],
                    'FCXtdQtySet'       => $val['FCXtdQtySet'],
                    'FTXtdPdtStaSet'    => $val['FTXtdPdtStaSet'],
                    'FTXtdRmk'          => $val['FTXtdRmk'],
                    'FTSessionID'       => $this->session->userdata('tSesSessionID'),

                    'FDLastUpdOn'       => date('Y-m-d h:i:sa'),
                    'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'        => date('Y-m-d h:i:sa'),
                    'FTCreateBy'        => $this->session->userdata('tSesUsername')

                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add.',
                    );
                }
                
            }

        }

    }


    //Functionality : Data List DT Dis
    //Parameters : function parameters
    //Creator :  03/09/2018 Krit(Copter)
    //Last Modified : -
    //Return : Data Array
    //Return Type : Array
    public function FSaMAdjStkSubGetHDRef($paData){

        $tXthDocNo = $paData['FTAjhDocNo'];

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tSQL   = "SELECT
                    AdjStkSubR.FTBchCode,
                    AdjStkSubR.FTAjhDocNo,
                    AdjStkSubR.FTXthCtrName,
                    AdjStkSubR.FDXthTnfDate,
                    AdjStkSubR.FTXthRefTnfID,
                    AdjStkSubR.FTXthRefVehID,
                    AdjStkSubR.FTXthQtyAndTypeUnit,
                    AdjStkSubR.FNXthShipAdd,
                    AdjStkSubR.FTViaCode,
                    TADD.FNAddSeqNo,
                    TADD.FTAddV1No,
                    TADD.FTAddV1Soi,
                    TADD.FTAddV1Village,
                    TADD.FTAddV1Road,
                    TSUD.FTSudName,
                    TDST.FTDstName,
                    TPVC.FTPvnName,
                    TADD.FTAddV1PostCode
                    FROM [TCNTPdtTwxHDRef] AdjStkSubR
                    LEFT JOIN TCNMAddress_L TADD ON AdjStkSubR.FNXthShipAdd = TADD.FNAddSeqNo
                    LEFT JOIN TCNMSubDistrict_L TSUD ON TADD.FTAddV1SubDist = TSUD.FTSudCode
                    LEFT JOIN TCNMDistrict_L TDST ON TADD.FTAddV1DstCode = TDST.FTDstCode
                    LEFT JOIN TCNMProvince_L TPVC ON TADD.FTAddV1PvnCode = TPVC.FTPvnCode
                    ";
        
       
        if(@$tXthDocNo != ''){
            $tSQL .= " WHERE (AdjStkSubR.FTAjhDocNo = '$tXthDocNo')";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode' => '1',
                'rtDesc' => 'OK.',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;

    }


    //Functionality : Data List DT Dis
    //Parameters : function parameters
    //Creator :  03/09/2018 Krit(Copter)
    //Last Modified : -
    //Return : Data Array
    //Return Type : Array
    public function FSaMAdjStkSubGetTfwDTDis($paData){

        $tXthDocNo = $paData['FTAjhDocNo'];

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tSQL   = "SELECT
                        DTD.FTBchCode,
                        DTD.FTAjhDocNo,
                        DTD.FNXpdSeqNo,
                        DTD.FDXddDateIns,
                        DTD.FNXpdStaDis,
                        DTD.FCXddDisChgAvi,
                        DTD.FTXddDisChgTxt,
                        DTD.FCXddDis,
                        DTD.FCXddChg,
                        DTD.FTXddUsrApv
                FROM [TAPTOrdDTDis] DTD
                ";
        
       
        if(@$tXthDocNo != ''){
            $tSQL .= " WHERE (DTD.FTAjhDocNo = '$tXthDocNo')";
        }

        $tSQL .= " ORDER BY FDXddDateIns ASC";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;

    }

    //Functionality : Function Add/Update Master
    //Parameters : function parameters
    //Creator : 12/06/2018 wasin
    //Last Modified : -
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMAdjStkSubAddUpdateHD($paData){
        
        try{
            // Update Master
            $this->db->set('FTBchCode', $paData['FTBchCode']);
            $this->db->set('FDAjhDocDate', $paData['FDAjhDocDate']);
            $this->db->set('FTAjhApvSeqChk', $paData['FTAjhApvSeqChk']);
            $this->db->set('FNAjhStaDocAct', $paData['FNAjhStaDocAct']);
            $this->db->set('FDLastUpdOn', $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy', $paData['FTLastUpdBy']);

            $this->db->where('FTAjhDocNo', $paData['FTAjhDocNo']);
            $this->db->update('TCNTPdtAdjStkHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                // Add Master
                $this->db->insert('TCNTPdtAdjStkHD',array(
                    
                    'FTBchCode' => $paData['FTBchCode'],
                    'FTAjhDocNo' => $paData['FTAjhDocNo'],
                    'FNAjhDocType' => $paData['FNAjhDocType'],
                    'FTAjhDocType' => $paData['FTAjhDocType'],
                    'FDAjhDocDate' => $paData['FDAjhDocDate'],
                    'FTAjhBchTo' => $paData['FTAjhBchTo'],
                    'FFAjhMerchantTo' => $paData['FFAjhMerchantTo'],
                    'FTAjhShopTo' => $paData['FTAjhShopTo'],
                    'FTAjhPosTo' => $paData['FTAjhPosTo'],
                    'FTAjhWhTo' => $paData['FTAjhWhTo'],
                    'FTAjhPlcCode' => $paData['FTAjhPlcCode'],
                    'FTDptCode' => $paData['FTDptCode'],
                    'FTUsrCode' => $paData['FTUsrCode'],
                    'FTRsnCode' => $paData['FTRsnCode'],
                    'FTAjhRmk' => $paData['FTAjhRmk'],
                    'FTAjhApvSeqChk' => $paData['FTAjhApvSeqChk'],
                    'FNAjhStaDocAct' => $paData['FNAjhStaDocAct'],
                    'FDLastUpdOn' => $paData['FDLastUpdOn'],
                    'FDCreateOn' => $paData['FDCreateOn'],
                    'FTCreateBy' => $paData['FTCreateBy'],
                    'FTLastUpdBy' => $paData['FTLastUpdBy']

                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }


    //Update HD After process  
    public function FSaMAdjStkSubUpdateOrdHD($paData,$paWhere){

        try{
            //DT Dis
            $this->db->set('FCXthTotal' , $paData['FCXthTotal']);
            $this->db->set('FCXthVatNoDisChg' , $paData['FCXthVatNoDisChg']);
            $this->db->set('FCXthNoVatNoDisChg' , $paData['FCXthNoVatNoDisChg']);
            $this->db->set('FCXthVatDisChgAvi' , $paData['FCXthVatDisChgAvi']);
            $this->db->set('FCXthNoVatDisChgAvi' , $paData['FCXthNoVatDisChgAvi']);
            $this->db->set('FTXthDisChgTxt' , $paData['FTXthDisChgTxt']);
            $this->db->set('FCXthDis' , $paData['FCXthDis']);
            $this->db->set('FCXthChg' , $paData['FCXthChg']);
            $this->db->set('FCXthRefAEAmt' , $paData['FCXthRefAEAmt']);
            $this->db->set('FCXthVatAfDisChg' , $paData['FCXthVatAfDisChg']);
            $this->db->set('FCXthNoVatAfDisChg' , $paData['FCXthNoVatAfDisChg']);
            $this->db->set('FCXthAfDisChgAE' , $paData['FCXthAfDisChgAE']);
            $this->db->set('FTXthWpCode' , $paData['FTXthWpCode']);
            $this->db->set('FCXthVat' , $paData['FCXthVat']);
            $this->db->set('FCXthVatable' , $paData['FCXthVatable']);
            $this->db->set('FCXthGrandB4Wht' , $paData['FCXthGrandB4Wht']);
            // $this->db->set('FCXthWpTax' , $paData['FCXthWpTax']);
            $this->db->set('FCXthGrand' , $paData['FCXthGrand']);
            $this->db->set('FTXthGndText' , $paData['FTXthGndText']);
            $this->db->set('FCXthLeft' , $paData['FCXthLeft']);

            $this->db->where('FTAjhDocNo', $paWhere['FTAjhDocNo']);
            $this->db->update('TAPTOrdHD');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update HD.',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '903',
                    'rtDesc' => 'Not Update HD.',
                );
            }
            return $aStatus;

        }catch(Exception $Error){
            return $Error;
        }

    }


    //Functionality : Function Add/Update OrdHDSpl
    //Parameters : function parameters
    //Creator : 03/08/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Add/Update Master
    //Return Type : array
    /*public function FSaMAdjStkSubAddUpdateHDRef($paData){

        try{
            //Update Master
            $this->db->set('FTBchCode'              , $paData['FTBchCode']);
            $this->db->set('FTAjhDocNo'             , $paData['FTAjhDocNo']);
            $this->db->set('FTXthCtrName'           , $paData['FTXthCtrName']);
            $this->db->set('FDXthTnfDate'           , $paData['FDXthTnfDate']);
            $this->db->set('FTXthRefTnfID'          , $paData['FTXthRefTnfID']);
            $this->db->set('FTXthRefVehID'          , $paData['FTXthRefVehID']);
            $this->db->set('FTXthQtyAndTypeUnit'    , $paData['FTXthQtyAndTypeUnit']);
            $this->db->set('FNXthShipAdd'           , $paData['FNXthShipAdd']);
            $this->db->set('FTViaCode'              , $paData['FTViaCode']);

            $this->db->where('FTAjhDocNo'           , $paData['FTAjhDocNo']);
            $this->db->update('TCNTPdtTwxHDRef');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNTPdtTwxHDRef',array(

                    'FTBchCode'             => $paData['FTBchCode'],
                    'FTAjhDocNo'            => $paData['FTAjhDocNo'],
                    'FTXthCtrName'          => $paData['FTXthCtrName'],
                    'FDXthTnfDate'          => $paData['FDXthTnfDate'],
                    'FTXthRefTnfID'         => $paData['FTXthRefTnfID'],
                    'FTXthRefVehID'         => $paData['FTXthRefVehID'],
                    'FTXthQtyAndTypeUnit'   => $paData['FTXthQtyAndTypeUnit'],
                    'FNXthShipAdd'          => $paData['FNXthShipAdd'],
                    'FTViaCode'             => $paData['FTViaCode']

                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }*/

    public function FSxMClearPdtInTmp(){
        $tSQL = "DELETE FROM TCNTDocDTTmp WHERE FTSessionID = '" . $this->session->userdata('tSesSessionID') . "' AND FTXthDocKey = 'TCNTPdtAdjStkHD'";
        $this->db->query($tSQL);
    }

    //Functionality : Function Add/Update OrdHDDis
    //Parameters : function parameters
    //Creator : 12/08/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMAdjStkSubAddUpdateHDDis($paData){

        //Add Master
        $this->db->insert('TAPTOrdHDDis',array(

            'FTBchCode'             => $paData['FTBchCode'],
            'FTAjhDocNo'            => $paData['FTAjhDocNo'],
            'FDXthDateIns'          => $paData['FDXthDateIns'],
            'FTXthDisChgTxt'        => $paData['FTXthDisChgTxt'],
            'FNXthStaDis'           => $paData['FNXthStaDis'],
            'FCXthVatDisChgAvi'     => $paData['FCXthVatDisChgAvi'],
            'FCXthNoVatDisChgAvi'   => $paData['FCXthNoVatDisChgAvi'],
            'FCXthDis'              => $paData['FCXthDis'],
            'FCXthChg'              => $paData['FCXthChg'],
            'FCXthDisVat'           => $paData['FCXthDisVat'],
            'FCXthDisNoVat'         => $paData['FCXthDisNoVat'],
            'FCXthChgVat'           => $paData['FCXthChgVat'],
            'FCXthChgNoVat'         => $paData['FCXthChgNoVat'],
            'FTXthUsrApv'           => $paData['FTXthUsrApv'],

        ));
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Master Success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add/Edit Master.',
            );
        }

}

    //Functionality : Function Add/Update OrdDT
    //Parameters : function parameters
    //Creator : 03/08/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMAdjStkSubAddUpdateOrdDT($paData){

        //Add Master
        $this->db->insert('TCNTPdtAdjStkDT',array(

            'FTBchCode'             => $paData['FTBchCode'],
            'FTAjhDocNo'            => $paData['FTAjhDocNo'],
            'FNXpdSeqNo'            => $paData['FNXpdSeqNo'],
            'FTPdtCode'             => $paData['FTPdtCode'],
            'FTXpdPdtName'          => $paData['FTXpdPdtName'],
            'FTXpdStkCode'          => $paData['FTXpdStkCode'],
            'FCXpdStkFac'           => $paData['FCXpdStkFac'],
            'FTPunCode'             => $paData['FTPunCode'],
            'FTPunName'             => $paData['FTPunName'],
            'FCXpdFactor'           => $paData['FCXpdFactor'],
            'FTXpdBarCode'          => $paData['FTXpdBarCode'],
            'FTSrnCode'             => $paData['FTSrnCode'],
            'FTXpdVatType'          => $paData['FTXpdVatType'],
            'FTVatCode'             => $paData['FTVatCode'],
            'FCXpdVatRate'          => $paData['FCXpdVatRate'],
            'FTXpdSaleType'         => $paData['FTXpdSaleType'],
            'FCXpdSalePrice'        => $paData['FCXpdSalePrice'],
            'FCXpdQty'              => $paData['FCXpdQty'],
            'FCXpdQtyAll'           => $paData['FCXpdQtyAll'],
            'FCXpdSetPrice'         => $paData['FCXpdSetPrice'],
            'FCXpdAmt'              => $paData['FCXpdAmt'],
            'FCXpdDisChgAvi'        => $paData['FCXpdDisChgAvi'],
            'FTXpdDisChgTxt'        => $paData['FTXpdDisChgTxt'],
            'FCXpdDis'              => $paData['FCXpdDis'],
            'FCXpdChg'              => $paData['FCXpdChg'],
            'FCXpdNet'              => $paData['FCXpdNet'],
            'FCXpdNetAfHD'          => $paData['FCXpdNetAfHD'],
            'FCXpdNetEx'            => $paData['FCXpdNetEx'],
            'FCXpdVat'              => $paData['FCXpdVat'],
            'FCXpdVatable'          => $paData['FCXpdVatable'],
            'FCXpdWhtAmt'           => $paData['FCXpdWhtAmt'],
            'FTXpdWhtCode'          => $paData['FTXpdWhtCode'],
            'FCXpdWhtRate'          => $paData['FCXpdWhtRate'],
            'FCXpdCostIn'           => $paData['FCXpdCostIn'],
            'FCXpdCostEx'           => $paData['FCXpdCostEx'],
            'FTXpdStaPdt'           => $paData['FTXpdStaPdt'],
            'FCXpdQtyLef'           => $paData['FCXpdQtyLef'],
            'FCXpdQtyRfn'           => $paData['FCXpdQtyRfn'],
            'FTXpdStaPrcStk'        => $paData['FTXpdStaPrcStk'],
            'FTXpdStaAlwDis'        => $paData['FTXpdStaAlwDis'],
            'FNXpdPdtLevel'         => $paData['FNXpdPdtLevel'],
            'FTXpdPdtParent'        => $paData['FTXpdPdtParent'],
            'FCXpdQtySet'           => $paData['FCXpdQtySet'],
            'FTPdtStaSet'           => $paData['FTPdtStaSet'],
            'FTXpdRmk'              => $paData['FTXpdRmk'],
            'FDLastUpdOn'           => $paData['FDLastUpdOn'],
            'FTLastUpdBy'           => $paData['FTLastUpdBy'],
            'FDCreateOn'            => $paData['FDCreateOn'],
            'FTCreateBy'            => $paData['FTCreateBy']


        ));
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Master Success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Add/Edit Master.',
            );
        }

    }


    //Functionality : Function Update OrdDT DisChgTxt,Dis,Chg
    //Parameters : function parameters
    //Creator : 05/08/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Update Master
    //Return Type : array
    public function FSaMAdjStkSubUpdateOrdDT($paData,$paWhere){

            try{
                //DT Dis
                $this->db->set('FTXpdDisChgTxt' , $paData['FTXpdDisChgTxt']);
                $this->db->set('FCXpdDis' , $paData['FCXpdDis']);
                $this->db->set('FCXpdChg' , $paData['FCXpdChg']);
                $this->db->set('FCXpdNet' , $paData['FCXpdNet']);
                $this->db->set('FCXpdNetAfHD' , $paData['FCXpdNetAfHD']);
                $this->db->set('FCXpdVat' , $paData['FCXpdVat']);
                $this->db->set('FCXpdVatable' , $paData['FCXpdVatable']);
                $this->db->set('FCXpdWhtAmt' , $paData['FCXpdWhtAmt']);
                $this->db->set('FCXpdCostIn' , $paData['FCXpdCostIn']);
                $this->db->set('FCXpdCostEx' , $paData['FCXpdCostEx']);
                $this->db->set('FCXpdQtyLef' , $paData['FCXpdQtyLef']);
                $this->db->set('FCXpdNetEx' , $paData['FCXpdNetEx']);

                $this->db->where('FTAjhDocNo', $paWhere['FTAjhDocNo']);
                $this->db->where('FNXpdSeqNo', $paWhere['FNXpdSeqNo']);
                $this->db->update('TAPTOrdDT');
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Update DT.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '903',
                        'rtDesc' => 'Not Update DT.',
                    );
                }
                return $aStatus;
            }catch(Exception $Error){
                return $Error;
            }

    }


    //Functionality : Function Add/Update TAPTOrdDTDis
    //Parameters : function parameters
    //Creator : 03/08/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Add/Update Master
    //Return Type : array
    public function FSaMAdjStkSubAddUpdateOrdDTDis($paData){

            //Add Master
            $this->db->insert('TAPTOrdDTDis',array(

                'FTBchCode'             => $paData['FTBchCode'],
                'FTAjhDocNo'            => $paData['FTAjhDocNo'],
                'FNXpdSeqNo'            => $paData['FNXpdSeqNo'],
                'FDXddDateIns'          => $paData['FDXddDateIns'],
                'FNXpdStaDis'           => $paData['FNXpdStaDis'],
                'FCXddDisChgAvi'        => $paData['FCXddDisChgAvi'],
                'FTXddDisChgTxt'        => $paData['FTXddDisChgTxt'],
                'FCXddDis'              => $paData['FCXddDis'],
                'FCXddChg'              => $paData['FCXddChg'],
                'FTXddUsrApv'           => $paData['FTXddUsrApv']

            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Master Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
            }

    }


    //Functionality : Delete DT
    //Parameters : function parameters
    //Creator : 17/07/2018 Krit
    //Return : response
    //Return Type : array
    public function FSnMPMTDelPcoDT($ptXthDocNo){

        $this->db->where_in('FTAjhDocNo', $ptXthDocNo);
        $this->db->delete('TAPTOrdDT');

        if($this->db->affected_rows() > 0){
            //Success
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }


    //Functionality : Delete DT Dis
    //Parameters : function parameters
    //Creator : 17/07/2018 Krit
    //Return : response
    //Return Type : array
    public function FSnMPMTDelPcoDTDis($ptXthDocNo){

        $this->db->where_in('FTAjhDocNo', $ptXthDocNo);
        $this->db->delete('TAPTOrdDTDis');

        if($this->db->affected_rows() > 0){
            //Success
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }


    //Functionality : Delete DT Dis
    //Parameters : function parameters
    //Creator : 17/07/2018 Krit
    //Return : response
    //Return Type : array
    public function FSnMPMTDelPcoHDDis($ptXthDocNo){

        $this->db->where_in('FTAjhDocNo', $ptXthDocNo);
        $this->db->delete('TAPTOrdHDDis');

        if($this->db->affected_rows() > 0){
            //Success
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }


    //Functionality : Functio Add/Update Lang
    //Parameters : function parameters
    //Creator :  12/06/2018 wasin
    //Last Modified : -
    //Return : Status Add Update Lang
    //Return Type : Array
    public function FSaMSDTAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTSudName' , $paData['FTSudName']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTSudCode', $paData['FTSudCode']);
            $this->db->update('TCNMSubDistrict_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                //Add Lang
                $this->db->insert('TCNMSubDistrict_L',array(
                    'FTSudCode' => $paData['FTSudCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTSudName' => $paData['FTSudName']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Delete PurchaseOrder
    //Parameters : function parameters
    //Creator : 29/08/2018 Krit(Copter)
    //Last Modified : -
    //Return : Array Status Delete
    //Return Type : array
    public function FSnMAdjStkSubDel($paData){
        try{
            $this->db->trans_begin();

            $this->db->where_in('FTXthDocNo', $paData['FTAjhDocNo']);
            $this->db->delete('TCNTPdtAdjStkHD');

            /*$this->db->where_in('FTXthDocNo', $paData['FTAjhDocNo']);
            $this->db->delete('TCNTPdtTwxHDRef');*/

            $this->db->where_in('FTAjhDocNo', $paData['FTAjhDocNo']);
            $this->db->delete('TCNTPdtAdjStkDT');

            $this->db->where_in('FTXthDocNo', $paData['FTAjhDocNo']);
            $this->db->delete('TCNTDocDTTmp');
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Delete PurchaseOrder
    //Parameters : function parameters
    //Creator : 04/04/2019 Krit(Copter)
    //Last Modified : -
    //Return : Array Status Delete
    //Return Type : array
    public function FSnMAdjStkSubDelDTTmp($paData){
        try{
            $this->db->trans_begin();

            $this->db->where_in('FTXthDocNo', $paData['FTAjhDocNo']);
            $this->db->where_in('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where_in('FTPdtCode',  $paData['FTPdtCode']);
            $this->db->where_in('FTSessionID',$paData['FTSessionID']);
            $this->db->delete('TCNTDocDTTmp');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }


    //Functionality : Multi Pdt Del Temp
    //Parameters : function parameters
    //Creator : 25/03/2019 Krit(Copter)
    //Return : Status Delete
    //Return Type : array
    public function FSaMAdjStkSubPdtTmpMultiDel($paData){
        try{
            $this->db->trans_begin();

            //Del DTTmp
            $this->db->where('FTXthDocNo', $paData['FTAjhDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->where('FTXthDocKey', $paData['FTXthDocKey']);
            $this->db->delete('TCNTDocDTTmp');

            //Del DTDisTmp
            /*$this->db->where('FTXthDocNo', $paData['FTAjhDocNo']);
            $this->db->where('FNXtdSeqNo', $paData['FNXtdSeqNo']);
            $this->db->delete('TCNTDocDTDisTmp');*/
              
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete Item.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Complete.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }


    public function FSnMAdjStkSubGetDocType($ptTableName){

        $tSQL = "SELECT FNSdtDocType FROM TSysDocType WHERE FTSdtTblName='$ptTableName'";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $nDetail = $oDetail[0]->FNSdtDocType;
          
        }else{
            $nDetail = '';
        }

        return $nDetail;
       
    }


    // public function FSaMTWFGeTInforTwxHD($ptDocNo){
    //     $tSQL = "SELECT * FROM TCNTPdtTwxHD WHERE FTAjhDocNo = '".$ptDocNo."'";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         return $oQuery->row_array();
    //     }else{
    //         return false;
    //     }
    // }

    // public function FSaMTWFGeTInforTwxHDRef($ptDocNo){
    //     $tSQL = "SELECT * FROM TCNTPdtTwxHDRef WHERE FTAjhDocNo = '".$ptDocNo."'";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         return $oQuery->row_array();
    //     }else{
    //         return false;
    //     }
    // }

    // public function FSaMTWFGeTInforTwxDT($ptDocNo){
    //     $tSQL = "SELECT * FROM TCNTPdtTwxDT WHERE FTAjhDocNo = '".$ptDocNo."'";
    //     $oQuery = $this->db->query($tSQL);
    //     if($oQuery->num_rows() > 0){
    //         return $oQuery->result_array();
    //     }else{
    //         return false;
    //     }
    // }

    
    public function FSxMAdjStkSubClearDocTemForChngCdt($pInforData){
        $tSQL = "DELETE FROM TCNTDocDTTmp 
                 WHERE FTBchCode = '".$pInforData["tbrachCode"]."' AND
                       FTXthDocNo = '".$pInforData["tFTXthDocNo"]."' AND
                       FTXthDocKey = '".$pInforData["tDockey"]."' AND
                       FTSessionID = '".$pInforData["tSession"]."'
                ";
        $this->db->query($tSQL);
    }
    
    /**
     * Functionality : Checkduplicate
     * Parameters : function parameters
     * Creator : 28/05/2019 Piya(Tiger)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSnMAdjStkSubCheckDuplicate($ptCode){
        $tSQL = "SELECT COUNT(FTAjhDocNo)AS counts
                 FROM TCNTPdtAdjStkHD
                 WHERE FTAjhDocNo = '$ptCode'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

}






















































































