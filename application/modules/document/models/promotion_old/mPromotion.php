<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mPromotion extends CI_Model {

    /**
     * Functionality : Checkduplicate
     * Parameters : function parameters
     * Creator : 03/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTTSysPmtSearchByID($paData){

        $tSpmCode   = $paData['tSpmCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL = "SELECT SPMT.FTSpmCode,
                        SPMT.FTSpmType,
                        SPMT.FTSpmStaGrpBuy,
                        SPMT.FTSpmStaBuy,
                        SPMT.FTSpmStaGrpRcv,
                        SPMT.FTSpmStaRcv,
                        SPMT.FTSpmStaGrpBoth,
                        SPMT.FTSpmStaGrpReject,
                        SPMT.FTSpmStaAllPdt,
                        SPMT.FTSpmStaExceptPmt,
                        SPMT.FTSpmStaGetNewPri,
                        SPMT.FTSpmStaGetDisAmt,
                        SPMT.FTSpmStaGetDisPer,
                        SPMT.FTSpmStaGetPoint,
                        SPMT.FTSpmStaRcvFree,
                        SPMT.FTSpmStaAlwOffline,
                        SPMT.FTSpmStaChkLimitGet,
                        SPMT.FTSpmStaChkCst,
                        SPMT.FTSpmStaChkCstDOB,
                        SPMT.FTSpmStaUseRange,
                        SPMT.FNSpmLimitGrpRcv,

                        SPMTL.FTSpmName,
                        SPMTL.FTSpmRmk

                FROM [TSysPmt] SPMT
                LEFT JOIN [TSysPmt_L] SPMTL ON SPMT.FTSpmCode = SPMTL.FTSpmCode AND SPMTL.FNLngID = $nLngID
                 WHERE SPMT.FTSpmCode = '$tSpmCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }
    
    /**
     * Functionality : Search Rate By ID
     * Parameters : function parameters
     * Creator : 04/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTGetPdtPmtHD($paData){

        $tPmhCode   = $paData['FTPmhCode'];
        
        $tSQL = "SELECT
                    PMT.FTPmhCode       AS rtPmhCode,
                    PMT.FTPmhName       AS rtPmhName,
                    PMT.FTPmhNameSlip   AS rtPmhNameSlip,
                    PMT.FTSpmCode       AS rtSpmCode,
                    PMT.FTSpmType       AS rtSpmType,
                    PMT.FDPmhDStart     AS rdPmhDStart,
                    PMT.FDPmhDStop      AS rdPmhDStop,
                    CONVERT(CHAR(5), PMT.FDPmhTStart,108) AS rdPmhTStart, 
                    CONVERT(CHAR(5), PMT.FDPmhTStop,108) AS rdPmhTStop,
                    PMT.FTPmhClosed     AS rtPmhClosed,
                    PMT.FTPmhStatus     AS rtPmhStatus,
                    PMT.FTPmhRetOrWhs   AS rtPmhRetOrWhs,
                    PMT.FTPmhRmk        AS rtPmhRmk,
                    PMT.FTPmhStaPrcDoc  AS rtPmhStaPrcDoc,
                    PMT.FNPmhStaAct     AS rnPmhStaAct,
                    PMT.FTUsrCode       AS rtUsrCode,
                    PMT.FTPmhApvCode    AS rtPmhApvCode,

                    PMT.FTPmhBchTo          AS rtPmhBchTo,
                    BCHL.FTBchName          AS rtBchName,

                    PMT.FTPmhZneTo          AS rtPmhZneTo,
                    ZNEL.FTZneName          AS rtZneName,

                    PMT.FTPmhStaExceptPmt   AS rtPmhStaExceptPmt,
                    PMT.FTSpmStaRcvFree     AS rtSpmStaRcvFree,
                    PMT.FTSpmStaAlwOffline  AS rtSpmStaAlwOffline,
                    PMT.FTSpmStaChkLimitGet AS rtSpmStaChkLimitGet,
                    PMT.FNPmhLimitNum       AS rnPmhLimitNum,
                    PMT.FTPmhStaLimit       AS rtPmhStaLimit,
                    PMT.FTPmhStaLimitCst    AS rtPmhStaLimitCst,
                    PMT.FTSpmStaChkCst      AS rtSpmStaChkCst,
                    PMT.FNPmhCstNum         AS rnPmhCstNum,
                    PMT.FTSpmStaChkCstDOB   AS rtSpmStaChkCstDOB,
                    PMT.FNPmhCstDobNum      AS rnPmhCstDobNum,
                    PMT.FNPmhCstDobPrev     AS rnPmhCstDobPrev,
                    PMT.FNPmhCstDobNext     AS rnPmhCstDobNext,
                    PMT.FTSpmStaUseRange    AS rtSpmStaUseRange,
                    PMT.FTSplCode           AS rtSplCode,
                    PMT.FDPntSplStart       AS rdPntSplStart,    
                    PMT.FDPntSplExpired     AS rdPntSplExpired,

                    PMT.FTPmgCode           AS rtPmgCode,
                    CSTGRPL.FTCgpName       AS rtCgpName,

                    PMT.FTAggCode           AS rtAggCode,
                    SPLL.FTSplName          AS rtSplName


                 FROM [TCNTPdtPmtHD]      PMT
                 LEFT JOIN [TCNTPdtPmtDT] PMTDT ON PMT.FTPmhCode = PMTDT.FTPmhCode
                 LEFT JOIN [TCNTPdtPmtCD] PMTCD ON PMT.FTPmhCode = PMTCD.FTPmhCode

                 LEFT JOIN [TCNMZone]     ZNE ON PMT.FTPmhZneTo = ZNE.FTZneCode
                 LEFT JOIN [TCNMZone_L]   ZNEL ON ZNE.FTZneChain = ZNEL.FTZneChain
                 LEFT JOIN [TCNMBranch_L] BCHL ON PMT.FTPmhBchTo = BCHL.FTBchCode
                 LEFT JOIN [TCNMCstGrp_L] CSTGRPL ON PMT.FTPmgCode = CSTGRPL.FTCgpCode
                 LEFT JOIN [TCNMSpl_L]    SPLL ON PMT.FTSplCode = SPLL.FTSplCode
                 
                 WHERE 1=1 ";
        
        if($tPmhCode != ""){
            $tSQL .= "AND PMT.FTPmhCode = '$tPmhCode'";
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

    /**
     * Functionality : Search Promotion Condition 
     * Parameters : function parameters
     * Creator : 21/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTPdtPmtCD($paData){

        $tPmhCode   = $paData['FTPmhCode'];
        
        $tSQL = "SELECT CD.FTBchCode,
                        CD.FTPmhCode,
                        CD.FNPmcSeq,
                        CD.FTSpmCode,
                        CD.FTPmcGrpCode,
                        CD.FTPmcGrpName,
                        CD.FTPmcStaGrpCond,
                        CD.FCPmcPerAvgDis,
                        CD.FCPmcBuyAmt,
                        CD.FCPmcBuyQty,
                        CD.FCPmcBuyMinQty,
                        CD.FCPmcBuyMaxQty,
                        CD.FDPmcBuyMinTime,
                        CD.FDPmcBuyMaxTime,
                        CD.FCPmcGetCond,
                        CD.FCPmcGetValue,
                        CD.FCPmcGetQty,
                        CD.FTSpmStaBuy,
                        CD.FTSpmStaRcv,
                        CD.FTSpmStaAllPdt
                        
                 FROM [TCNTPdtPmtCD] CD
                 

                 WHERE 1=1 ";
        
        if($tPmhCode != ""){
            $tSQL .= "AND CD.FTPmhCode = '$tPmhCode'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
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

    /**
     * Functionality : Search Promotion Detail 
     * Parameters : function parameters
     * Creator : 21/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTPdtPmtDT($paData){

        $tPmhCode   = $paData['FTPmhCode'];
        
        $tSQL = "SELECT DISTINCT
                        DT.FTBchCode,
                        DT.FTPmhCode,
                        DT.FNPmdSeq,
                        DT.FTSpmCode,
                        DT.FTPmdGrpType,
                        DT.FTPmdGrpCode,
                        DT.FTPmdGrpName,
                        DT.FTPdtCode,
                        PDTL.FTPdtName,
                        DT.FTPunCode,
                        PDTUL.FTPunName,
                        DT.FCPmdSetPriceOrg,

                        (SELECT TOP 1 BAR.FTBarCode FROM TCNMPdtBar BAR WHERE BAR.FTPdtCode = DT.FTPdtCode) AS FTBarCode 

                        
                 FROM [TCNTPdtPmtDT] DT
                 LEFT JOIN TCNMPdt_L PDTL ON DT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID = '1'
                 LEFT JOIN TCNMPdtUnit_L PDTUL ON DT.FTPunCode = PDTUL.FTPunCode  AND PDTUL.FNLngID = '1'
                 LEFT JOIN TCNMPdtPackSize PPS ON DT.FTPdtCode = PPS.FTPdtCode AND PPS.FTPunCode = '001'
                 LEFT JOIN TCNMPdtBar BAR ON PPS.FTPdtCode = BAR.FTPdtCode AND PPS.FTPunCode = BAR.FTPunCode
                 WHERE 1=1 ";
        
        if($tPmhCode != ""){
            $tSQL .= "AND DT.FTPmhCode = '$tPmhCode'";
        }


        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
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

    /**
     * Functionality : Search Promotion Detail 
     * Parameters : function parameters
     * Creator : 21/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTPdtPmtDTGrpBoth($paData){

        $tPmhCode   = $paData['FTPmhCode'];
        
        $tSQL = "SELECT DISTINCT 
                        DT.FTBchCode,
                        DT.FTPmhCode,
                        DT.FNPmdSeq,
                        DT.FTSpmCode,
                        DT.FTPmdGrpType,
                        DT.FTPmdGrpCode,
                        DT.FTPmdGrpName,
                        DT.FTPdtCode,
                        PDTL.FTPdtName,
                        DT.FTPunCode,
                        PDTUL.FTPunName,
                        DT.FCPmdSetPriceOrg,

                        (SELECT TOP 1 BAR.FTBarCode FROM TCNMPdtBar BAR WHERE BAR.FTPdtCode = DT.FTPdtCode) AS FTBarCode 
                        
                 FROM [TCNTPdtPmtDT] DT
                 LEFT JOIN TCNMPdt_L PDTL ON DT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID = '1'
                 LEFT JOIN TCNMPdtUnit_L PDTUL ON DT.FTPunCode = PDTUL.FTPunCode  AND PDTUL.FNLngID = '1'
                 LEFT JOIN TCNMPdtPackSize PPS ON DT.FTPdtCode = PPS.FTPdtCode AND PPS.FTPunCode = '001'
                 LEFT JOIN TCNMPdtBar BAR ON PPS.FTPdtCode = BAR.FTPdtCode AND PPS.FTPunCode = BAR.FTPunCode
                 
                 WHERE FTPmdGrpCode LIKE '%GrpBoth%' ";
        
        if($tPmhCode != ""){
            $tSQL .= "AND DT.FTPmhCode = '$tPmhCode'";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
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
    
    /**
     * Functionality : list Rate
     * Parameters : function parameters
     * Creator :  11/05/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
       
        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTPmhCode ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT

                                    PMT.FTPmhCode,
                                    PMT.FTPmhName,
                                    PMT.FTPmhNameSlip,
                                    PMT.FTSpmCode,
                                    PMT.FTSpmType,
                                    CONVERT(CHAR(10),PMT.FDPmhDStart,103)   AS FDPmhDStart,
                                    CONVERT(CHAR(10),PMT.FDPmhDStop,103)    AS FDPmhDStop,
                                    CONVERT(CHAR(5), PMT.FDPmhTStart,108)   AS FDPmhTStart, 
                                    CONVERT(CHAR(5), PMT.FDPmhTStop,108)    AS FDPmhTStop

                            FROM [TCNTPdtPmtHD] PMT
                            WHERE 1=1 ";
        
        @$tSearchList = $paData['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND (PMT.FTPmhCode LIKE '%$tSearchList%'";
            $tSQL .= "      OR PMT.FTPmhName LIKE '%$tSearchList%'";
            $tSQL .= "      OR PMT.FTPmhNameSlip LIKE '%$tSearchList%'";
            $tSQL .= "      OR PMT.FDPmhDStart LIKE '%$tSearchList%'";
            $tSQL .= "      OR PMT.FDPmhDStop LIKE '%$tSearchList%')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
   
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMPMTGetPageAll($tSearchList);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
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
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : list Rate
     * Parameters : function parameters
     * Creator :  11/05/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSaMPMTTSysPmtList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID = $paData['FNLngID'];
        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTSpmCode ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT

                                    SPMT.FTSpmCode,
                                    SPMTL.FTSpmName

                            FROM [TSysPmt] SPMT
                            LEFT JOIN [TSysPmt_L] SPMTL ON SPMT.FTSpmCode = SPMTL.FTSpmCode AND SPMTL.FNLngID = $nLngID
                            WHERE 1=1 ";
        
        @$tSearchList = $paData['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND (SPMT.FTSpmCode LIKE '%$tSearchList%'";
            $tSQL .= "      OR SPMTL.FTSpmName LIKE '%$tSearchList%')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
   
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMPMTGetPageTSysPmtAll($tSearchList,$nLngID);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
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
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /**
     * Functionality : Update Creditcard
     * Parameters : function parameters
     * Creator : 03/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : response
     * Return Type : Array
     */
    public function FSaMPMTAddUpdatePmtCD($paData){

                //Add Master
                $this->db->insert('TCNTPdtPmtCD',array(

                    'FTBchCode'             => $paData['FTBchCode'],
                    'FTPmhCode'             => $paData['FTPmhCode'],
                    
                    'FNPmcSeq'              => $paData['FNPmcSeq'],
                    'FTSpmCode'             => $paData['FTSpmCode'],
                    'FTPmcGrpCode'          => $paData['FTPmcGrpCode'],
                    'FTPmcGrpName'          => $paData['FTPmcGrpName'],
                    'FTPmcStaGrpCond'       => $paData['FTPmcStaGrpCond'],
                    'FCPmcPerAvgDis'        => $paData['FCPmcPerAvgDis'],
                    'FCPmcGetCond'          => $paData['FCPmcGetCond'],
                    'FCPmcBuyAmt'           => $paData['FCPmcBuyAmt'],
                    'FCPmcBuyQty'           => $paData['FCPmcBuyQty'],
                    'FCPmcBuyMinQty'        => $paData['FCPmcBuyMinQty'],
                    'FCPmcBuyMaxQty'        => $paData['FCPmcBuyMaxQty'],

                    'FCPmcGetValue'         => $paData['FCPmcGetValue'],
                    'FCPmcGetQty'           => $paData['FCPmcGetQty'],
                    'FTSpmStaBuy'           => $paData['FTSpmStaBuy'],
                    'FTSpmStaRcv'           => $paData['FTSpmStaRcv'],
                    'FTSpmStaAllPdt'        => $paData['FTSpmStaAllPdt'],
                   
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
    
    /**
     * Functionality : Update Detail Promotion
     * Parameters : function parameters
     * Creator : 03/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : response
     * Return Type : Array
     */
    public function FSaMPMTAddUpdatePmtDT($paData){

        //Add Master
        $this->db->insert('TCNTPdtPmtDT',array(

            'FTBchCode'             => $paData['FTBchCode'],
            'FTPmhCode'             => $paData['FTPmhCode'],
            'FNPmdSeq'              => $paData['FNPmdSeq'],
            'FTSpmCode'             => $paData['FTSpmCode'],
            'FTPmdGrpType'          => $paData['FTPmdGrpType'],
            'FTPmdGrpCode'          => $paData['FTPmdGrpCode'],
            'FTPmdGrpName'          => $paData['FTPmdGrpName'],
            'FTPdtCode'             => $paData['FTPdtCode'],
            'FTPunCode'             => $paData['FTPunCode'],
            'FCPmdSetPriceOrg'      => $paData['FCPmdSetPriceOrg'],

            // 'FDDateIns'             => $paData['FDDateIns'],
            // 'FTTimeIns'             => $paData['FTTimeIns'],
            // 'FTWhoIns'              => $paData['FTWhoIns']
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
    
    /**
     * Functionality : Update Detail Promotion
     * Parameters : function parameters
     * Creator : 03/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : response
     * Return Type : Array
     */
    public function FSaMPMTAddUpdatePmtGrpJoin($paData){

        //Add Master
        $this->db->insert('TCNTPdtPmtDT',array(

            'FTBchCode'             => $paData['FTBchCode'],
            'FTPmhCode'             => $paData['FTPmhCode'],
            'FNPmdSeq'              => $paData['FNPmdSeq'],
            'FTSpmCode'             => $paData['FTSpmCode'],
            'FTPmdGrpType'          => $paData['FTPmdGrpType'],
            'FTPmdGrpName'          => $paData['FTPmdGrpName'],
            'FTPdtCode'             => $paData['FTPdtCode'],
            'FTPunCode'             => $paData['FTPunCode'],
            'FCPmdSetPriceOrg'      => $paData['FCPmdSetPriceOrg'],

            // 'FDDateIns'             => $paData['FDDateIns'],
            // 'FTTimeIns'             => $paData['FTTimeIns'],
            // 'FTWhoIns'              => $paData['FTWhoIns']

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

    /**
     * Functionality : Update Creditcard
     * Parameters : function parameters
     * Creator : 03/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : response
     * Return Type : Array
     */
    public function FSaMPMTAddUpdatePmtHD($paData){
        try{
            //Update MasterMaster
            $this->db->set('FTBchCode' , $paData['FTBchCode']);
            $this->db->set('FTPmhName' , $paData['FTPmhName']);
            $this->db->set('FTPmhNameSlip' , $paData['FTPmhNameSlip']);
            $this->db->set('FTSpmCode' , $paData['FTSpmCode']);
            $this->db->set('FTSpmType' , $paData['FTSpmType']);
            $this->db->set('FDPmhDStart' , $paData['FDPmhDStart']);
            $this->db->set('FDPmhDStop' , $paData['FDPmhDStop']);
            $this->db->set('FDPmhTStart' , $paData['FDPmhTStart']);
            $this->db->set('FDPmhTStop' , $paData['FDPmhTStop']);
            $this->db->set('FTPmhClosed' , $paData['FTPmhClosed']);
            $this->db->set('FTPmhStatus' , $paData['FTPmhStatus']);
            $this->db->set('FTPmhRetOrWhs' , $paData['FTPmhRetOrWhs']);
            $this->db->set('FTPmhRmk' , $paData['FTPmhRmk']);
            $this->db->set('FTPmhStaPrcDoc' , $paData['FTPmhStaPrcDoc']);
            $this->db->set('FNPmhStaAct' , $paData['FNPmhStaAct']);
            $this->db->set('FTUsrCode' , $paData['FTUsrCode']);
            $this->db->set('FTPmhApvCode' , $paData['FTPmhApvCode']);
            $this->db->set('FTPmhBchTo' , $paData['FTPmhBchTo']);
            $this->db->set('FTPmhZneTo' , $paData['FTPmhZneTo']);
            $this->db->set('FTPmhStaExceptPmt' , $paData['FTPmhStaExceptPmt']);
            $this->db->set('FTSpmStaRcvFree' , $paData['FTSpmStaRcvFree']);
            $this->db->set('FTSpmStaAlwOffline' , $paData['FTSpmStaAlwOffline']);
            $this->db->set('FTSpmStaChkLimitGet' , $paData['FTSpmStaChkLimitGet']);
            $this->db->set('FNPmhLimitNum' , $paData['FNPmhLimitNum']);
            $this->db->set('FTPmhStaLimit' , $paData['FTPmhStaLimit']);
            $this->db->set('FTPmhStaLimitCst' , $paData['FTPmhStaLimitCst']);
            $this->db->set('FTSpmStaChkCst' , $paData['FTSpmStaChkCst']);
            $this->db->set('FNPmhCstNum' , $paData['FNPmhCstNum']);
            $this->db->set('FTSpmStaChkCstDOB' , $paData['FTSpmStaChkCstDOB']);
            $this->db->set('FNPmhCstDobNum' , $paData['FNPmhCstDobNum']);
            $this->db->set('FNPmhCstDobPrev' , $paData['FNPmhCstDobPrev']);
            $this->db->set('FNPmhCstDobNext' , $paData['FNPmhCstDobNext']);
            $this->db->set('FTSpmStaUseRange' , $paData['FTSpmStaUseRange']);
            $this->db->set('FTPmgCode' , $paData['FTPmgCode']);
            $this->db->set('FTSplCode' , $paData['FTSplCode']);
            $this->db->set('FDPntSplStart' , $paData['FDPntSplStart']);
            $this->db->set('FDPntSplExpired' , $paData['FDPntSplExpired']);
            $this->db->set('FTAggCode' , $paData['FTAggCode']);
            
            // $this->db->set('FDDateUpd' , $paData['FDDateUpd']);
            // $this->db->set('FTTimeUpd' , $paData['FTTimeUpd']);
            // $this->db->set('FTWhoUpd' , $paData['FTWhoUpd']);
            $this->db->where('FTPmhCode', $paData['FTPmhCode']);
            $this->db->update('TCNTPdtPmtHD');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNTPdtPmtHD',array(

                    'FTPmhCode'             => $paData['FTPmhCode'],
                    'FTBchCode'             => $paData['FTBchCode'],
                    'FTPmhName'             => $paData['FTPmhName'],
                    'FTPmhNameSlip'         => $paData['FTPmhNameSlip'],
                    'FTSpmCode'             => $paData['FTSpmCode'],
                    'FTSpmType'             => $paData['FTSpmType'],
                    'FDPmhDStart'           => $paData['FDPmhDStart'],
                    'FDPmhDStop'            => $paData['FDPmhDStop'],
                    'FDPmhTStart'           => $paData['FDPmhTStart'],
                    'FDPmhTStop'            => $paData['FDPmhTStop'],
                    'FTPmhClosed'           => $paData['FTPmhClosed'],
                    'FTPmhStatus'           => $paData['FTPmhStatus'],
                    'FTPmhRetOrWhs'         => $paData['FTPmhRetOrWhs'],
                    'FTPmhRmk'              => $paData['FTPmhRmk'],
                    'FTPmhStaPrcDoc'        => $paData['FTPmhStaPrcDoc'],
                    'FNPmhStaAct'           => $paData['FNPmhStaAct'],
                    'FTUsrCode'             => $paData['FTUsrCode'],
                    'FTPmhApvCode'          => $paData['FTPmhApvCode'],
                    'FTPmhBchTo'            => $paData['FTPmhBchTo'],
                    'FTPmhZneTo'            => $paData['FTPmhZneTo'],
                    'FTPmhStaExceptPmt'     => $paData['FTPmhStaExceptPmt'],
                    'FTSpmStaRcvFree'       => $paData['FTSpmStaRcvFree'],
                    'FTSpmStaAlwOffline'    => $paData['FTSpmStaAlwOffline'],
                    'FTSpmStaChkLimitGet'   => $paData['FTSpmStaChkLimitGet'],
                    'FNPmhLimitNum'         => $paData['FNPmhLimitNum'],
                    'FTPmhStaLimit'         => $paData['FTPmhStaLimit'],
                    'FTPmhStaLimitCst'      => $paData['FTPmhStaLimitCst'],
                    'FTSpmStaChkCst'        => $paData['FTSpmStaChkCst'],
                    'FNPmhCstNum'           => $paData['FNPmhCstNum'],
                    'FTSpmStaChkCstDOB'     => $paData['FTSpmStaChkCstDOB'],
                    'FNPmhCstDobNum'        => $paData['FNPmhCstDobNum'],
                    'FNPmhCstDobPrev'       => $paData['FNPmhCstDobPrev'],
                    'FNPmhCstDobNext'       => $paData['FNPmhCstDobNext'],
                    'FTSpmStaUseRange'      => $paData['FTSpmStaUseRange'],
                    'FTPmgCode'             => $paData['FTPmgCode'],
                    'FTSplCode'             => $paData['FTSplCode'],
                    'FDPntSplStart'         => $paData['FDPntSplStart'],
                    'FDPntSplExpired'       => $paData['FDPntSplExpired'],
                    'FTAggCode'             => $paData['FTAggCode'],
                    
                    // 'FDDateIns'             => $paData['FDDateIns'],
                    // 'FTTimeIns'             => $paData['FTTimeIns'],
                    // 'FTWhoIns'              => $paData['FTWhoIns']
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

    /**
     * Functionality : Update Lang Bank
     * Parameters : function parameters
     * Creator : 02/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : response
     * Return Type : num
     */
    public function FSaMPMTAddUpdateLang($paData){

        try{
            //Update Lang
            $this->db->set('FTRteName', $paData['FTRteName']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTRteCode', $paData['FTRteCode']);
            $this->db->update('TFNMRate_L');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TFNMRate_L',array(
                    'FTRteCode' => $paData['FTRteCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTRteName' => $paData['FTRteName'],
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

    /**
     * Functionality : All Page Of Rate
     * Parameters : function parameters
     * Creator :  11/05/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSnMPMTGetPageAll($ptSearchList){
        
        $tSQL = "SELECT COUNT (PMT.FTPmhCode) AS counts
                 FROM TCNTPdtPmtHD PMT
                 WHERE 1=1 ";
        
        if($ptSearchList != ''){
            $tSQL .= " AND (PMT.FTPmhCode LIKE '%$ptSearchList%'";
            $tSQL .= "      OR PMT.FTPmhName LIKE '%$ptSearchList%'";
            $tSQL .= "      OR PMT.FTPmhNameSlip LIKE '%$ptSearchList%'";
            $tSQL .= "      OR PMT.FDPmhDStart LIKE '%$ptSearchList%'";
            $tSQL .= "      OR PMT.FDPmhDStop LIKE '%$ptSearchList%')";
        }
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    /**
     * Functionality : All Page Of Rate
     * Parameters : function parameters
     * Creator :  11/05/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSnMPMTGetPageTSysPmtAll($ptSearchList,$ptLngID){
        
        $tSQL = "SELECT COUNT (SPMT.FTSpmCode) AS counts
                 FROM TSysPmt SPMT
                 LEFT JOIN [TSysPmt_L] SPMTL ON SPMT.FTSpmCode = SPMTL.FTSpmCode AND SPMTL.FNLngID = $ptLngID
                 WHERE 1=1 ";
        
        if($ptSearchList != ''){
            $tSQL .= " AND (SPMT.FTSpmCode LIKE '%$ptSearchList%'";
            $tSQL .= "      OR SPMTL.FTSpmName LIKE '%$ptSearchList%')";
        }
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }
    
    /**
     * Functionality : Checkduplicate
     * Parameters : function parameters
     * Creator : 03/07/2018 Krit(Copter)
     * Last Modified : -
     * Return : data
     * Return Type : Array
     */
    public function FSnMPMTCheckDuplicate($ptPmhCode){
        $tSQL = "SELECT COUNT(FTPmhCode)AS counts
                 FROM TCNTPdtPmtHD
                 WHERE FTPmhCode = '$ptPmhCode'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }
    
    /**
     * Functionality : Delete Rate
     * Parameters : function parameters
     * Creator : 14/05/2018 wasin
     * Return : response
     * Return Type : array
     */
    public function FSnMPMTDel($paData){

        try{
            $this->db->trans_begin();

            $this->db->where_in('FTPmhCode', $paData['FTPmhCode']);
            $this->db->delete('TCNTPdtPmtHD');
    
            $this->db->where_in('FTPmhCode', $paData['FTPmhCode']);
            $this->db->delete('TCNTPdtPmtDT');
    
            $this->db->where_in('FTPmhCode', $paData['FTPmhCode']);
            $this->db->delete('TCNTPdtPmtCD');

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

    /**
     * Functionality : Delete
     * Parameters : function parameters
     * Creator : 14/05/2018 wasin
     * Return : response
     * Return Type : array
     */
    public function FSnMPMTDelPmtCD($ptPmhCode){

        $this->db->where_in('FTPmhCode', $ptPmhCode);
        $this->db->delete('TCNTPdtPmtCD');

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

    /**
     * Functionality : Delete DT
     * Parameters : function parameters
     * Creator : 17/07/2018 Krit
     * Return : response
     * Return Type : array
     */
    public function FSnMPMTDelPmtDT($ptPmhCode){

        $this->db->where_in('FTPmhCode', $ptPmhCode);
        $this->db->delete('TCNTPdtPmtDT');

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
}


