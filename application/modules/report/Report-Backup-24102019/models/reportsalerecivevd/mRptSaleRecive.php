<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptSaleRecive extends CI_Model {


    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 10/07/2019 Saharat(Golf)
    //Last Modified :
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreReport($paDataFilter){
        // print_r($paDataFilter['tDocDateFrom']);
        // var_dump($paDataFilter['tDocDateFrom']);
        // exit;
        $tCallStore = "{ CALL SP_RPTxPaymentDET2001003(?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID'           => $paDataFilter['nLangID'],
            'ptComName'         => $paDataFilter['tCompName'],
            'ptRptCode'         => $paDataFilter['tRptCode'],
            'ptUserSession'     => $paDataFilter['tSessionID'],
            'ptRcvF'            => $paDataFilter['tRcvCodeFrom'],
            'ptRcvT'            => $paDataFilter['tRcvCodeTo'],
            'ptBchF'            => $paDataFilter['tBchCodeFrom'],
            'ptBchT'            => $paDataFilter['tBchCodeTo'],
            'ptShpF'            => $paDataFilter['tShopCodeFrom'],
            'ptShpT'            => $paDataFilter['tShopCodeTo'],
            'ptDocDateF'        => $paDataFilter['tDocDateFrom'],
            'pthDocDateT'       => $paDataFilter['tDocDateTo'],
            'FNResult'          => 0
            // 'tErr'              => 0,
            // 'ptMerF'            => $paDataFilter['tMerCodeFrom'],
            // 'ptMerT'            => $paDataFilter['tMerCodeTo'],
        );
        // echo '<pre>';
        // print_r($aDataStore);
        // exit;
        $oQuery = $this->db->query($tCallStore,$aDataStore);
        if($oQuery != FALSE){
            unset($oQuery);
            return 1;
        }else{
            unset($oQuery);
            return 0;
        }
    }

    // Functionality: Get Data Report
    // Parameters:  Function Parameter
    // Creator: 10/07/2019 Saharat(Golf)
    // Last Modified : 
    // Return : Get Data Rpt Temp
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere){
      
        $nPage          = $paDataWhere['nPage'];
        // Call Data Pagination 
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        // print_r($aPagination);
        // exit;
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];
        
        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tSession    = $paDataWhere['tUsrSessionID'];

        		
        // Set Priority
        // $tComName = 'ADA090-Arm';
        // $tRptCode = '002001003';
        // $tSession = '00720190812043054';
        
        //Set Priority
         //Set Priority
         $aDta = $this->FMxMRPTSetPriorityGroup($tComName,$tRptCode,$tSession);
         // L = List ???????????????????????????????????????
         // A = SaleDT
         // S = Misures Summary    
         $tSQL = " SELECT L.*,
                   T.FCXrcNetFooter
           
                   FROM   (SELECT    ROW_NUMBER() OVER(ORDER BY FTRcvCode) AS RowID , 
                                               A.*,
                                               S.FNRptGroupMember,
                                               S.FCSdtSubQty
 
                                     FROM TRPTVDSalRCTmp A
                                     --Calculate Misures TRPTSalRCTmp
                                     LEFT JOIN 
                                     (SELECT    FTRcvCode AS FTRcvCode_SUM,
                                                COUNT(FTRcvCode) AS FNRptGroupMember,
                                                SUM(FCXrcNet) AS FCSdtSubQty
                                      FROM TRPTVDSalRCTmp 
                                                WHERE FTComName    = '$tComName'
                                                AND   FTRptCode    = '$tRptCode'
                                                AND   FTUsrSession = '$tSession'
                                     GROUP BY FTRcvCode
 
                                     ) S ON A.FTRcvCode = S.FTRcvCode_SUM
                                               WHERE A.FTComName = '$tComName'
                                               AND   A.FTRptCode = '$tRptCode'
                                               AND   A.FTUsrSession = '$tSession'
                                     --End Calculate Misures
                                     ) L ";
          
                 //Join ????????????????????? Summaty Footer
                 $tSQL.= " LEFT JOIN ";
 
                 //Check ??????????????????????????????????????????????????????????????????????????? ????????????????????????????????????????????????????????????????????? Sum footer ???????????????????????? 
                 if($nPage == $nTotalPage){
 
                     $tSQL.= " (SELECT FTUsrSession AS  FTUsrSession_Footer, 
                                          SUM(FCXrcNet) AS FCXrcNetFooter
                                FROM   TRPTVDSalRCTmp
                                WHERE  FTComName     = '$tComName'
                                AND    FTRptCode     = '$tRptCode'
                                AND    FTUsrSession  = '$tSession'
                                GROUP BY FTUsrSession) T ON L.FTUsrSession = T.FTUsrSession_Footer ";
 
                 }else{
 
                      //???????????????????????????????????? Select 0 ???????????????????????? Join ???????????????????????????????????????????????? Sum 
                      $tSQL.= " (SELECT '$tSession' AS FTUsrSession_Footer,
                                '0' AS FCXrcNetFooter ) T ON  L.FTUsrSession = T.FTUsrSession_Footer";
                 }
                 
                 // WHERE ???????????????????????? Page
                 $tSQL.= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
                 //???????????? Order by ???????????????????????????????????????
                 $tSQL.= " ORDER BY L.FTRcvCode ";
                //  echo '<pre>';
                //  print_r($tSQL );
                //  exit;
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData  = $oQuery->result_array();
         
        }else{
            $aData  = NULL;
        }

        $aErrorList =   array(
            "nErrInvalidPage"   =>  ""
        );

        $aResualt= array(
            "aPagination"   =>  $aPagination,
            "aRptData"      =>  $aData,
            "aError"        =>  $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return  $aResualt;
    }
    

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 22/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array    
    public function FSaMCountDataReportAll($paDataWhere){
        $tUserCode  = $paDataWhere['tUserCode'];
        $tCompName  = $paDataWhere['tCompName'];
        $tRptCode   = $paDataWhere['tRptCode'];
        $tUserSession  = $paDataWhere['tUserSession'];
        $tSQL       = " SELECT 
                            FTRcvName  AS rtRcvName,
                            FTXshDocNo AS rtRcvDocNo,
                            FDCreateOn AS rtRcvCreateOn,
                            FCXrcNet   AS rtRcvrcNet 
                    FROM TRPTVDSalRCTmp  
                    WHERE 1 = 1 AND FTUsrSession = '$tUserSession' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'";
        $oQuery     = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    public function FMaMRPTPagination($paDataWhere){

        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        
        // $tComName       = 'ADA084';
        // $tRptCode       = '002002003';
        // $tUsrSession    = 'SESS00000001';
        $tSQL           =   "   SELECT
                                    COUNT(RCV.FTRcvCode) AS rnCountPage
                                FROM TRPTVDSalRCTmp RCV WITH(NOLOCK)
                                WHERE 1=1
                                AND RCV.FTComName    = '$tComName'
                                AND RCV.FTRptCode    = '$tRptCode'
                                AND RCV.FTUsrSession = '$tUsrSession'
                                
                            ";
        $oQuery         = $this->db->query($tSQL);
        $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        $nPrevPage      = $nPage-1;
        $nNextPage      = $nPage+1;
        $nRowIDStart    = (($nPerPage*$nPage)-$nPerPage); //RowId Start
        if($nRptAllRecord<=$nPerPage){
            $nTotalPage = 1;
        }else if(($nRptAllRecord % $nPerPage)==0){
            $nTotalPage = ($nRptAllRecord/$nPerPage) ;
        }else{
            $nTotalPage = ($nRptAllRecord/$nPerPage)+1;
            $nTotalPage = (int)$nTotalPage;
        }

        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if($nRowIDEnd > $nRptAllRecord){
            $nRowIDEnd = $nRptAllRecord;
        }

        $aRptMemberDet = array(
            "nTotalRecord"  =>  $nRptAllRecord,
            "nTotalPage"    =>  $nTotalPage,
            "nDisplayPage"  =>  $paDataWhere['nPage'],
            "nRowIDStart"   =>  $nRowIDStart,
            "nRowIDEnd"     =>  $nRowIDEnd,
            "nPrevPage"     =>  $nPrevPage,
            "nNextPage"     =>  $nNextPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    
    public function FMxMRPTSetPriorityGroup($ptComName,$ptRptCode,$ptUsrSession){

        $tSQL = "UPDATE TRPTVDSalRCTmp 
		SET FNRowPartID = B.PartID
		FROM( SELECT ROW_NUMBER() OVER(PARTITION BY FTRcvCode ORDER BY FTRcvCode ASC) AS PartID , 
		FTRptRowSeq  FROM TRPTVDSalRCTmp
        WHERE   TRPTVDSalRCTmp.FTComName     = '$ptComName' 
        AND TRPTVDSalRCTmp.FTRptCode     = '$ptRptCode'
        AND TRPTVDSalRCTmp.FTUsrSession  = '$ptUsrSession'
        ) B
        
        WHERE TRPTVDSalRCTmp.FTRptRowSeq  = B.FTRptRowSeq 
        AND TRPTVDSalRCTmp.FTComName     = '$ptComName' 
        AND TRPTVDSalRCTmp.FTRptCode     = '$ptRptCode'
        AND TRPTVDSalRCTmp.FTUsrSession  = '$ptUsrSession' ";

        $this->db->query($tSQL);

   }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Witsarut(Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountDataReportAll($paDataWhere){
        $tSessionID      = $paDataWhere['tSessionID'];
        $tCompName       = $paDataWhere['tCompName'];
        $tRptCode        = $paDataWhere['tRptCode'];
        $tSQL    =   "   SELECT 
                             COUNT(DTTMP.FTRptCode) AS rnCountPage
                         FROM TRPTVDSalRCTmp AS DTTMP WITH(NOLOCK)
                         WHERE 1 = 1
                         AND FTUsrSession    = '$tSessionID'
                         AND FTComName       = '$tCompName'
                         AND FTRptCode       = '$tRptCode'
         ";
         $oQuery         = $this->db->query($tSQL);
         $nRptAllRecord  = $oQuery->row_array()['rnCountPage'];
         unset($oQuery);
         return $nRptAllRecord;
     }


}