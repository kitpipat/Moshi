<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mBrowserPDTCallView extends CI_Model {

    //#################################################### PDT VIEW HQ #################################################### 

    //PDT - สำหรับ VIEW HQ + ข้อมูล
    public function FSaMGetProductHQ($ptFilter,$ptLeftJoinPrice,$paData,$pnTotalResult){
        try{
            $tBchSession        = $this->session->userdata("tSesUsrBchCom");
            $tShpSession        = $this->session->userdata("tSesUsrShpCode");
            $tMerSession        = $this->session->userdata("tSesUsrMerCode");
            $aRowLen            = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID             = $this->session->userdata("tLangEdit");

            if($paData['aPriceType'][0] == 'Pricesell'){
                //ถ้าเป็นราคาขาย
                $tSelectFiledPrice = 'VPP.FCPgdPriceNet,VPP.FCPgdPriceRet,VPP.FCPgdPriceWhs';
            }else if($paData['aPriceType'][0] == 'Price4Cst'){
                $tSelectFiledPrice = '  0 AS FCPgdPriceNet ,
                                        0 AS FCPgdPriceWhs ,
                                        CASE 
                                            WHEN ISNULL(PCUS.FCPgdPriceRet,0) <> 0 THEN PCUS.FCPgdPriceRet
                                            WHEN ISNULL(PBCH.FCPgdPriceRet,0) <> 0 THEN PBCH.FCPgdPriceRet
                                            WHEN ISNULL(PEMPTY.FCPgdPriceRet,0) <> 0 THEN PEMPTY.FCPgdPriceRet
                                            ELSE 0 
                                        END AS FCPgdPriceRet ';
            }else if($paData['aPriceType'][0] == 'Cost'){
                //ถ้าเป็นราคาทุน
                $tSelectFiledPrice  = "VPC.FCPdtCostStd , VPC.FCPdtCostAVGIN , ";
                $tSelectFiledPrice .= "VPC.FCPdtCostAVGEx , VPC.FCPdtCostLast, ";
                $tSelectFiledPrice .= "VPC.FCPdtCostFIFOIN , VPC.FCPdtCostFIFOEx ";
            }

            //ไม่ได้ส่งผู้จำหน่ายมา
            if($paData['tSPL'] == '' || $paData['tSPL'] == null){
                $tSqlSPL        = " ROW_NUMBER() OVER (PARTITION BY ProductM.FTPdtCode ORDER BY ProductM.FTPdtCode) AS FNPdtPartition , ";
                //$tSqlWHERESPL   = " AND Products.FNPdtPartition = 1";
                $tSqlWHERESPL   = '';
            }else{
                $tSqlSPL        = '';
                $tSqlWHERESPL   = '';
            }

            //Call View Sql
            $tSQL       = "SELECT c.* FROM ( ";
            $tSQL      .= "SELECT";
            $tSQL      .= " ROW_NUMBER() OVER(ORDER BY Products.FTPdtCode ASC) AS FNRowID , Products.* FROM (";
            $tSQL      .= "SELECT";
            $tSQL      .= "$tSqlSPL";
            $tSQL      .= " ProductM.*, ".$tSelectFiledPrice." FROM ( ";
            $tSQL      .= "SELECT * from VCN_ProductsHQ ";
            $tSQL      .= " WHERE  FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ) AS ProductM";
            $tSQL      .= $ptLeftJoinPrice;
            $tSQL      .= " ) AS Products WHERE 1=1 ";
            $tSQL      .= $ptFilter;
            $tSQL      .= $tSqlWHERESPL;
            $tSQL      .= " ) AS c ";
            $tSQL      .= " WHERE 1=1 ";
            $tSQL      .= "AND c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();

                if($paData['nPage'] == 1){
                    //ถ้าเป็น page 1 ต้องวิ่งไปหาทั้งหมด
                    $oFoundRow  = $this->FSnMSPRGetPageAllByPDTHQ($tSQL,$ptFilter);
                    $nFoundRow  = $oFoundRow;
                    // $nFoundRow  = $oFoundRow[0]['FNPDTCount'];
                }else{
                    //ถ้า page 2 3 4 5 6 7 8 9 เราอยู่เเล้ว ว่ามัน total_page เท่าไหร่
                    $nFoundRow = $pnTotalResult;
                }

                $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                    'sql'           => $tSQL
                );
            }else{
                //No Data
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                    'sql'           => $tSQL
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Count PDT - สำหรับ VIEW HQ + จำนวนเเถว
    public function FSnMSPRGetPageAllByPDTHQ($tSQL,$ptFilter){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT FTPDTCode FROM ";
        $tSQL       .= " ( ";
        $tSQL       .= "SELECT Products.* FROM VCN_ProductsHQ as Products WHERE  FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID'";
        $tSQL       .= " ) AS Products WHERE 1=1 ";
        $tSQL       .= $ptFilter;

        // $tSQL       = "SELECT FTPdtCode from VCN_ProductsHQ as Products WHERE 1=1 ";
        // $tSQL       .= $ptFilter;
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->num_rows();
        }else{
            return false;
        }
    }

    //#################################################### END PDT VIEW HQ ################################################


    //#################################################### PDT VIEW BCH ###################################################

    //PDT - สำหรับ VIEW BCH + ข้อมูล
    public function FSaMGetProductBCH($ptFilter,$ptLeftJoinPrice,$paData,$pnTotalResult){
        try{
            
            $tBchSession        = $this->session->userdata("tSesUsrBchCom");
            $tShpSession        = $this->session->userdata("tSesUsrShpCode");
            $tMerSession        = $this->session->userdata("tSesUsrMerCode");
            $aRowLen            = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID             = $this->session->userdata("tLangEdit");

            //หาว่า brach นี้ mer อะไร
            if($paData['tBCH'] == ''){
                $tBCH   = $tBchSession;
            }else{
                $tBCH   = $paData['tBCH']; 
            }
            // $aMercode   = $this->FSaFindMerCodeByBCH($tBCH);
            // $tMER       = '';
            // for($i=0; $i<count($aMercode); $i++){
            //     $tMER = $aMercode[0]['FTMerCode'];
            // }

            if($paData['aPriceType'][0] == 'Pricesell'){
                //ถ้าเป็นราคาขาย
                $tSelectFiledPrice = 'VPP.FCPgdPriceNet,VPP.FCPgdPriceRet,VPP.FCPgdPriceWhs';
            }else if($paData['aPriceType'][0] == 'Price4Cst'){
                $tSelectFiledPrice = '  0 AS FCPgdPriceNet ,
                                        0 AS FCPgdPriceWhs ,
                                        CASE 
                                            WHEN ISNULL(PCUS.FCPgdPriceRet,0) <> 0 THEN PCUS.FCPgdPriceRet
                                            WHEN ISNULL(PBCH.FCPgdPriceRet,0) <> 0 THEN PBCH.FCPgdPriceRet
                                            WHEN ISNULL(PEMPTY.FCPgdPriceRet,0) <> 0 THEN PEMPTY.FCPgdPriceRet
                                            ELSE 0 
                                        END AS FCPgdPriceRet ';
            }else if($paData['aPriceType'][0] == 'Cost'){
                //ถ้าเป็นราคาทุน
                $tSelectFiledPrice  = "VPC.FCPdtCostStd , VPC.FCPdtCostAVGIN , ";
                $tSelectFiledPrice .= "VPC.FCPdtCostAVGEx , VPC.FCPdtCostLast, ";
                $tSelectFiledPrice .= "VPC.FCPdtCostFIFOIN , VPC.FCPdtCostFIFOEx ";
            }

            //ไม่ได้ส่งผู้จำหน่ายมา
            if($paData['tSPL'] == '' || $paData['tSPL'] == null){
                $tSqlSPL        = " ROW_NUMBER() OVER (PARTITION BY ProductM.FTPdtCode ORDER BY ProductM.FTPdtCode) AS FNPdtPartition , ";
                $tSqlWHERESPL   = "";
            }else{
                $tSqlSPL        = '';
                $tSqlWHERESPL   = '';
            }

            //Call View Sql
            $tSQL       = " SELECT ProductM.* , $tSelectFiledPrice FROM (";
            $tSQL       .= "SELECT SS.* FROM (";
            $tSQL       .= "SELECT ROW_NUMBER() OVER(ORDER BY FTPdtCode ASC) AS FNRowID , Products.* ";
            $tSQL       .= "FROM (";
            $tSQL       .= "SELECT * ";
            $tSQL       .= "FROM VCN_ProductsBranch";
            $tSQL       .= " WHERE FTPdtSpcBch = '$tBCH' OR ISNULL(FTPdtSpcBch, '') = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            $tSQL       .= ") Products ";
            $tSQL       .= "WHERE 1 = 1 ";
            $tSQL       .= "$ptFilter";
            $tSQL       .= ") AS SS WHERE SS.FNRowID > $aRowLen[0] AND SS.FNRowID <= $aRowLen[1] ";
            $tSQL       .= ") AS ProductM ";
            $tSQL       .= "$ptLeftJoinPrice";

            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();

                if($paData['nPage'] == 1){
                    //ถ้าเป็น page 1 ต้องวิ่งไปหาทั้งหมด
                    $oFoundRow  = $this->FSnMSPRGetPageAllByPDT($tSQL,$tBCH,$ptFilter);
                    $nFoundRow  = $oFoundRow;
                    // $nFoundRow  = $oFoundRow[0]['FNPDTCount'];
                }else{
                    //ถ้า page 2 3 4 5 6 7 8 9 เราอยู่เเล้ว ว่ามัน total_page เท่าไหร่
                    $nFoundRow = $pnTotalResult;
                }

                $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                    'sql'           => $tSQL,
                    'nTotalResult'  => $nFoundRow
                );
            }else{
                //No Data
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                    'sql'           => $tSQL
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Count PDT - สำหรับ VIEW BCH + จำนวนเเถว
    public function FSnMSPRGetPageAllByPDT($tSQL,$ptBCH,$ptFilter){
        $nLngID     = $this->session->userdata("tLangEdit");
        $tSQL       = "SELECT FTPDTCode FROM ";
        $tSQL       .= " ( ";
        $tSQL       .= "SELECT Products.* FROM VCN_ProductsBranch as Products WHERE FTPdtSpcBch = '$ptBCH' 
                        AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID'
                        OR ISNULL(FTPdtSpcBch, '') = '' ";
        $tSQL       .= " ) AS Products WHERE 1=1 ";
        $tSQL       .= $ptFilter;

        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->num_rows();
        }else{
            return false;
        }
    }

    //#################################################### END PDT VIEW BCH ################################################


    //#################################################### PDT VIEW SHP #################################################### 

    //PDT - สำหรับ VIEW SHOP + ข้อมูล
    public function FSaMGetProductSHP($ptFilter,$ptLeftJoinPrice,$paData,$pnTotalResult){
        try{
            $tBCH               = $paData['tBCH'];
            $tShpSession        = $this->session->userdata("tSesUsrShpCode");
            $tMerSession        = $this->session->userdata("tSesUsrMerCode");
            $aRowLen            = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID             = $this->session->userdata("tLangEdit");

            if($paData['aPriceType'][0] == 'Pricesell' || $paData['aPriceType'][0] == 'Price4Cst'){
                //ถ้าเป็นราคาขาย
                $tSelectFiledPrice = 'VPP.FCPgdPriceNet,VPP.FCPgdPriceRet,VPP.FCPgdPriceWhs';
            }else if($paData['aPriceType'][0] == 'Cost'){
                //ถ้าเป็นราคาทุน
                $tSelectFiledPrice  = "VPC.FCPdtCostStd , VPC.FCPdtCostAVGIN , ";
                $tSelectFiledPrice .= "VPC.FCPdtCostAVGEx , VPC.FCPdtCostLast, ";
                $tSelectFiledPrice .= "VPC.FCPdtCostFIFOIN , VPC.FCPdtCostFIFOEx ";
            }

            
            $tSQL       = "SELECT c.* FROM ( ";
            $tSQL      .= "SELECT ROW_NUMBER() OVER(ORDER BY Products.FTPdtCode ASC) AS FNRowID , Products.* FROM (";
            $tSQL      .= "SELECT ProductM.*, ".$tSelectFiledPrice." FROM ( ";
            $tSQL      .= "SELECT * FROM VCN_ProductShop ";

            if($paData['tSHP'] != '' && $paData['tMER'] != ''){
                //มี SHP มี MER
                $tSHP       = $paData['tSHP'];
                $tMER       = $paData['tMER'];
                $tSQL      .= " WHERE FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            }else if($paData['tSHP'] != '' && $paData['tMER'] == ''){
                //มี SHP ไม่มี MER
                $tSHP       = $paData['tSHP'];

                //หา MER 
                $aFindMer   = $this->FSaFindMerCodeBySHP($tSHP,$tBCH);
                $tMER       = '';
                for($i=0; $i<count($aFindMer); $i++){
                    $tMER   = $aFindMer[0]['FTMerCode'];
                }
                $tSQL      .= " WHERE FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            }else if($paData['tSHP'] == '' && $paData['tMER'] != ''){
                //ไม่มี SHP มี MER
                $tSHP       = '';
                $tMER       = $paData['tMER'];
                $tSQL      .= " WHERE FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID'  ";
            }else{
                //ไม่มี SHP ไม่มี MER
                $tSHP       = $tShpSession;
                $tMER       = $tMerSession;
                $tSQL      .= " WHERE FTShpCode = '$tSHP' AND FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
            }

            $tSQL      .= " UNION SELECT * 
            FROM VCN_ProductShop
            WHERE FTShpCode = '$tSHP'
            AND FNLngIDPdt = '$nLngID'
            AND FNLngIDUnit = '$nLngID' 
            ) AS ProductM ";

            $tSQL      .= $ptLeftJoinPrice;
            $tSQL      .= " ) AS Products WHERE 1=1 ";
            $tSQL      .= $ptFilter;
            $tSQL      .= " ) AS c ";
            $tSQL      .= " WHERE 1=1 ";
            $tSQL      .= "AND c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();

                if($paData['nPage'] == 1){
                    //ถ้าเป็น page 1 ต้องวิ่งไปหาทั้งหมด
                    $oFoundRow  = $this->FSnMSPRGetPageAllBySHP($tSQL,$ptFilter,$tSHP,$tMER,$nLngID);
                    $nFoundRow  = $oFoundRow;
                    // $nFoundRow  = $oFoundRow[0]['FNPDTCount'];
                }else{
                    //ถ้า page 2 3 4 5 6 7 8 9 เราอยู่เเล้ว ว่ามัน total_page เท่าไหร่
                    $nFoundRow = $pnTotalResult;
                }

                $nPageAll   = ceil($nFoundRow/$paData['nRow']); 
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                    'sql'           => $tSQL
                );
            }else{
                //No Data
                $aResult    = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found',
                    'sql'           => $tSQL
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Count PDT - สำหรับ VIEW SHOP + จำนวนเเถว
    public function FSnMSPRGetPageAllBySHP($tSQL,$ptFilter,$tSHP,$tMER,$nLngID){
        $tSQL       = "SELECT FTPdtCode FROM VCN_ProductShop as Products WHERE 1=1 ";

        if($tSHP != '' && $tMER != ''){
            //มี SHP มี MER
            $tSHP       = $tSHP;
            $tMER       = $tMER;
            $tSQL      .= " AND FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
        }else if($tSHP != '' && $tMER == ''){
            //มี SHP ไม่มี MER
            $tSHP       = $tSHP;
            $tMER       = $tMER;
            $tSQL      .= " AND FTMerCode = '$tMER' AND FTShpCode = '' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
        }else if($tSHP == '' && $tMER != ''){
            //ไม่มี SHP มี MER
            $tSHP       = $tSHP;
            $tMER       = $tMER;
            $tSQL      .= " AND FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID'  ";
        }else{
            //ไม่มี SHP ไม่มี MER
            $tSHP       = $this->session->userdata("tSesUsrShpCode");
            $tMER       = $this->session->userdata("tSesUsrMerCode");
            $tSQL      .= " AND FTShpCode = '$tSHP' AND FTMerCode = '$tMER' AND FNLngIDPdt = '$nLngID' AND FNLngIDUnit = '$nLngID' ";
        }

        $tSQL       .= $ptFilter;
        $oQuery     = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->num_rows();
        }else{
            return false;
        }
    }

    //#################################################### END PDT VIEW SHP #################################################

    
    //Get หาต้นทุนใช้แบบไหน
    public function FSnMGetTypePrice($tSyscode,$tSyskey,$tSysseq){
        $tSQL = "SELECT FTSysStaDefValue,FTSysStaUsrValue
            FROM  TSysConfig 
            WHERE 
                FTSysCode = '$tSyscode' AND 
                FTSysKey = '$tSyskey' AND 
                FTSysSeq = '$tSysseq'
            ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0){
            $oRes  = $oQuery->result();
            if($oRes[0]->FTSysStaUsrValue != ''){
                $tDataSavDec = $oRes[0]->FTSysStaUsrValue;
            }else{
                $tDataSavDec = $oRes[0]->FTSysStaDefValue;    
            }
        }else{
            //Decimal Default = 2 
            $tDataSavDec = 2;
        }
        return $tDataSavDec;
    }

    //Get vat จาก company กรณีที่ไม่มี supplier ส่งมา
    public function FSaMGetWhsInorExIncompany(){
        $tSQL           = "SELECT TOP 1 FTCmpRetInOrEx FROM TCNMComp";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }

    //Get vat จาก sup
    public function FSaMGetWhsInorExInSupplier($pnCode){
        $tSQL           = "SELECT FTSplStaVATInOrEx FROM TCNMSpl WHERE FTSplCode = '$pnCode'";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }

    //////////// ระดับบาร์โค๊ด //////////////

    //get Barcode - COST
    public function FSnMGetBarcodeCOST($paData,$aNotinItem){
        $FNLngID        = $paData['FNLngID'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $FTPunCode      = $paData['FTPunCode'];
        $nLngID         = $this->session->userdata("tLangEdit");
        $tVatInorEx     = $paData['tVatInorEx'];
        $tFTSplCode     = $paData['FTSplCode'];

        if($tFTSplCode == '' || $tFTSplCode == null){
            $tSQL_BarSPL    = "ROW_NUMBER() OVER (PARTITION BY BAR.FTBarCode ORDER BY BAR.FTBarCode) AS FNPdtPartition ,";
            $tSQL_SPL       = " AND A.FNPdtPartition = 1";
        }else{
            $tSQL_BarSPL    = "ROW_NUMBER() OVER (PARTITION BY BAR.FTBarCode ORDER BY BAR.FTBarCode) AS FNPdtPartition ,";
            $tSQL_SPL       = " AND A.FNPdtPartition = 1 AND FTSplCode = '$tFTSplCode' ";
        }

        $tSQL =  "SELECT A.* FROM ( ";
        $tSQL .= "SELECT BAR.* , ";
        $tSQL .= "$tSQL_BarSPL";
        $tSQL .= "      FCPdtCostStd , 
                        FCPdtCostAVGIN ,
                        FCPdtCostAVGEX ,
                        FCPdtCostLast ,
                        FCPdtCostFIFOIN ,
                        FCPdtCostFIFOEX ,
                        FTPdtStaVatBuy ,
                        FTPdtStaVat ,
                        FTPdtStaActive ,
                        FTPdtSetOrSN ,
                        FTPgpChain ,
                        FTPtyCode ,
                        FCPdtCookTime ,
                        FCPdtCookHeat ,
                        FNLngIDPdt ,
                        FNLngIDUnit , 
                        FTPunName ,
                        FCPdtUnitFact ,
                        FTPdtSpcBch ,
                        FTMerCode ,
                        FTShpCode ,
                        FTMgpCode ,
                        FTBuyer  FROM( ";
        $tSQL .=  "SELECT * FROM VCN_ProductBar BAR WHERE ";
        $tSQL .= "BAR.FTPdtCode = '$FTPdtCode' AND ";
        $tSQL .= "BAR.FTPunCode = '$FTPunCode' ";
        $tSQL .= ") AS BAR ";
        $tSQL .= "LEFT JOIN VCN_ProductCost PRI ON BAR.FTPdtCode = PRI.FTPdtCode ";
        $tSQL .= "LEFT JOIN VCN_ProductsHQ PDT ON  BAR.FTPdtCode = PDT.FTPdtCode AND BAR.FTPunCode = PDT.FTPunCode ";
        $tSQL .= "WHERE BAR.FNLngPdtBar = '$nLngID'";
        $tSQL .= "AND PDT.FNLngIDPdt = '$nLngID'";
        $tSQL .= "AND PDT.FNLngIDUnit = '$nLngID' ) A WHERE 1=1 ";

        $tSQL .= $tSQL_SPL;
        
        //ไม่เอาสินค้าอะไรบ้าง
        if($aNotinItem != '' || $aNotinItem != null){
            $tNotinItem     = '';
            $aNewNotinItem  = explode(',',$aNotinItem);

            for($i=0; $i<count($aNewNotinItem); $i++){
                $aNewPDT  = explode(':::',$aNewNotinItem[$i]);
                $tNotinItem .=  "'".$aNewPDT[1]."'" . ',';
                if($i == count($aNewNotinItem)-1){
                    $tNotinItem = substr($tNotinItem,0,-1);
                }
            }
            $tSQL .= "AND (A.FTBarCode NOT IN ($tNotinItem))";
        }   

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        }else{
            return false;
        }
    }

    //get Barcode - Price Sell
    public function FSnMGetBarcodePriceSELL($paData,$aNotinItem){
        $FNLngID        = $paData['FNLngID'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $FTPunCode      = $paData['FTPunCode'];
        $nLngID         = $this->session->userdata("tLangEdit");
        $tFTSplCode     = $paData['FTSplCode'];
        
        if($tFTSplCode == '' || $tFTSplCode == null){
            $tSQL_BarSPL    = "ROW_NUMBER() OVER (PARTITION BY BAR.FTBarCode ORDER BY BAR.FTBarCode) AS FNPdtPartition ,";
            $tSQL_SPL       = " AND A.FNPdtPartition = 1";
        }else{
            $tSQL_BarSPL    = "ROW_NUMBER() OVER (PARTITION BY BAR.FTBarCode ORDER BY BAR.FTBarCode) AS FNPdtPartition ,";
            $tSQL_SPL       = " AND A.FNPdtPartition = 1 AND FTSplCode = '$tFTSplCode' ";
        }

        $tSQL =  "SELECT A.* FROM ( ";
        $tSQL .= "SELECT BAR.* , ";
        $tSQL .= "$tSQL_BarSPL";
        $tSQL .= "      FNRowPart ,
                        FDPghDStart , 
                        FCPgdPriceNet , 
                        FCPgdPriceRet ,
                        FCPgdPriceWhs ,
                        FTPdtStaVatBuy , 
                        FTPdtStaVat , 
                        FTPdtStaActive , 
                        FTPdtSetOrSN ,
                        FTPgpChain , 
                        FTPtyCode , 
                        FCPdtCookTime ,
                        FCPdtCookHeat , 
                        FNLngIDPdt , 
                        FNLngIDUnit , 
                        FTPunName , 
                        FCPdtUnitFact ,
                        FTPdtSpcBch , 
                        FTMerCode , 
                        FTShpCode ,
                        FTMgpCode , 
                        FTBuyer  FROM( ";
        $tSQL .=  "SELECT * FROM VCN_ProductBar BAR WHERE ";
        $tSQL .= "BAR.FTPdtCode = '$FTPdtCode' AND ";
        $tSQL .= "BAR.FTPunCode = '$FTPunCode' ) AS BAR ";
        $tSQL .= "LEFT JOIN VCN_Price4PdtActive PRI ON BAR.FTPdtCode = PRI.FTPdtCode ";
        $tSQL .= "AND BAR.FTPunCode = PRI.FTPunCode ";
        $tSQL .= "INNER JOIN VCN_ProductsHQ PDT ";
        $tSQL .= "ON BAR.FTPdtCode = PDT.FTPdtCode AND BAR.FTPunCode = PDT.FTPunCode ";
        $tSQL .= "WHERE BAR.FNLngPdtBar = '$nLngID' AND PDT.FNLngIDUnit = '$nLngID' AND PDT.FNLngIDPdt = '$nLngID'  ) A WHERE 1=1 ";
        
        $tSQL .= $tSQL_SPL;
        //ไม่เอาสินค้าอะไรบ้าง
        if($aNotinItem != '' || $aNotinItem != null){
            $tNotinItem     = '';
            $aNewNotinItem  = explode(',',$aNotinItem);

            for($i=0; $i<count($aNewNotinItem); $i++){
                $aNewPDT  = explode(':::',$aNewNotinItem[$i]);
                if($FTPdtCode == $aNewPDT[0]){
                    $tNotinItem .=  "'".$aNewPDT[1]."'" . ',';
                }

                if($i == count($aNewNotinItem)-1){
                    $tNotinItem = substr($tNotinItem,0,-1);
                }
            }
            if($tNotinItem == ''){
                $tSQL .= " ";
            }else{
                $tSQL .= " AND (A.FTBarCode NOT IN ($tNotinItem)) ORDER BY A.FTBarCode ASC ";
            }
        }     

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        }else{
            return false;
        }
    }

    //หาว่า BARCODE หรือ PLC นี้อยู่ใน PDT อะไร
    public function FSnMFindPDTByBarcode($tTextSearch,$tTypeSearch){
        $nLngID  = $this->session->userdata("tLangEdit");
        $tSQL    = "SELECT FTPdtCode , FTPunCode , FTBarCode FROM VCN_ProductBar WHERE 1=1";

        if($tTypeSearch == 'FINDBARCODE'){
            $tSQL    .=  " AND FTBarCode = '$tTextSearch' ";
        }else if($tTypeSearch == 'FINDPLCCODE'){
            $tSQL    .=  " AND FTPlcName LIKE '%$tTextSearch%' ";
        }
    
        $tSQL    .= "AND FNLngPdtBar = '$nLngID' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        }else{
            return false;
        }
    }

    //หาว่า shop นี้ Mercode อะไร
    public function FSaFindMerCodeBySHP($tSHP,$tBCH){
        $tSQL    = "SELECT FTShpCode , FTMerCode FROM TCNMShop WHERE ";
        $tSQL    .= "FTShpCode = '$tSHP' AND FTBchCode = '$tBCH' ";
        $tSQL    .= "ORDER BY FTMerCode ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        }else{
            return false;
        }
    }

    //หาว่า bracnh นี้ Mercode อะไร
    public function FSaFindMerCodeByBCH($tBCH){
        $tSQL    = "SELECT DISTINCT FTMerCode FROM TCNMShop WHERE ";
        $tSQL    .= "FTBchCode = '$tBCH' ";
        $tSQL    .= "ORDER BY FTMerCode ASC ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result_array();
        }else{
            return false;
        }
    }

}