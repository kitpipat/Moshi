<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mProduct extends CI_Model {

    // Functionality: ดึงข้อมูลสินค้า
    // Parameters: array
    // Creator: 31/01/2019 wasin(Yoshi)
    // Return: ข้อมูลสินค้าแบบ Array
	// ReturnType: Object Array
    public function FSaMPDTGetDataTable($paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID         = (empty($paData['FNLngID']))? 1 : $paData['FNLngID'];
        $tSearch        = $paData['tSearchAll'];
        $tPdtForSys     = $paData['tPdtForSys'];
        // if(is_numeric($tSearch)){
        //     $tSearch='';
        //     $tSearchPrice = $paData['tSearchAll'];
        // }else{
        //     $tSearch=$paData['tSearchAll'];
        //     $tSearchPrice = '';
        // }
        if(isset($tPdtForSys) && !empty($tPdtForSys)){
            $tPdtForSysLine1    = " AND PDT.FTPdtForSystem = ".$paData['tPdtForSys'];
            $tPdtForSysLine2    = " AND PD1.FTPdtForSystem = ".$paData['tPdtForSys'];
        }else{
            $tPdtForSysLine1    = "";
            $tPdtForSysLine2    = "";
        }   


        /* |-----------------------------------------------------------------------------------------------| */
        /* |                            สิทธิในการมองเห็นสินค้า CR.wat  Edit By.IcePun                          | */
        /* |-----------------------------------------------------------------------------------------------| */
        /* | */     $tSesUsrLevel               = $this->session->userdata('tSesUsrLevel');             // | */ 
        /* | */     $tSessionMerCode            = $this->session->userdata('tSesUsrMerCode');           // | */ 
        /* | */     $tSessionShopCode           = $this->session->userdata('tSesUsrShpCode');           // | */ 
        /* | */     $tSessionBchCode            = $this->session->userdata('tSesUsrBchCode');           // | */ 
        /* | */     $tWHEREPermission_BCH       = '';                                                   // | */ 
        /* | */     $tWHEREPermission_SHP       = '';                                                   // | */     
        /* | */     $tWHEREPermission_InOrEx    = '';                                                   // | */     
        /* | */                                                                                         // | */ 
        /* | */     //PERMISSION BCH    : ต้องเห็นสินค้าที่ผูกสาขา และสินค้าที่ไม่ผูกอะไรเลย(HQ)                    // | */ 
        /* | */     if($tSesUsrLevel == 'BCH'){                                                         // | */ 
     // /* | */         $tWHEREPermission_BCH    = " AND ISNULL(PDLSPC.FTBchCode,'') ";                 // | */ 
     // /* | */         $tWHEREPermission_BCH   .= "  IN ('','$tSessionBchCode') ";                     // | */ 
        /* | */         $tWHEREPermission_BCH    = " AND ISNULL(PSC.FTBchCode,'') ";                    // | */ 
        /* | */         $tWHEREPermission_BCH   .= "  IN ('','$tSessionBchCode') ";                     // | */ 
        /* | */         $tWHEREPermission_BCH   .= "  AND ISNULL(PSC.FTCtyCode,'') = ''";               // | */ 
        /* | */         $tWHEREPermission_BCH   .= "  AND ISNULL(PSC.FTAgnCode,'') = ''";               // | */ 
        /* | */         $tWHEREPermission_BCH   .= "  AND ISNULL(PSC.FTMerCode,'') = ''";               // | */ 
        /* | */         $tWHEREPermission_BCH   .= "  AND ISNULL(PSC.FTShpCode,'') = ''";               // | */ 
        /* | */     }                                                                                   // | */ 
        /* | */                                                                                         // | */
        /* | */     //PERMISSION SHP    : ต้องเห็นสินค้าระดับร้านค้า และ สินค้าของกลุ่มธุรกิจที่ไม่ได้ผูกร้านค้า           // | */ 
        /* | */     if($tSesUsrLevel == 'SHP'){                                                         // | */ 
     // /* | */         $tWHEREPermission_SHP  = " AND ( PDLSPC.FTBchCode = '$tSessionBchCode' )";      // | */ 
     // /* | */         $tWHEREPermission_SHP  .= " AND ";                                              // | */ 
     // /* | */         $tWHEREPermission_SHP  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";      // | */ 
     // /* | */         $tWHEREPermission_SHP  .= " ISNULL(PDLSPC.FTShpCode,'') = '' ) ";               // | */ 
     // /* | */         $tWHEREPermission_SHP  .= " OR ";                                               // | */ 
     // /* | */         $tWHEREPermission_SHP  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";      // | */ 
     // /* | */         $tWHEREPermission_SHP  .= " PDLSPC.FTShpCode = '$tSessionShopCode' )";          // | */ 
        /* | */         $tWHEREPermission_SHP  = " AND ( PSC.FTBchCode = '$tSessionBchCode' )";         // | */ 
        /* | */         $tWHEREPermission_SHP  .= " AND ";                                              // | */ 
        /* | */         $tWHEREPermission_SHP  .= " ( PSC.FTMerCode = '$tSessionMerCode' AND ";         // | */ 
        /* | */         $tWHEREPermission_SHP  .= " ISNULL(PSC.FTShpCode,'') = '' ) ";                  // | */ 
        /* | */         $tWHEREPermission_SHP  .= " OR ";                                               // | */ 
        /* | */         $tWHEREPermission_SHP  .= " ( PSC.FTMerCode = '$tSessionMerCode' AND ";         // | */ 
        /* | */         $tWHEREPermission_SHP  .= " PSC.FTShpCode = '$tSessionShopCode' )";             // | */
        /* | */     }                                                                                   // | */
        /* | */                                                                                         // | */     
        /* | */     if($tSesUsrLevel != 'HQ'){                                                          // | */  
        /* | */         $tWHEREPermission_InOrEx  = " AND ISNULL(PSC.FTPdtStaInOrEx,'') <> '2'";        // | */ 
        /* | */     }                                                                                   // | */             
        /* |-----------------------------------------------------------------------------------------------| */

        // Stagement Query Product
        $tSQL       =   "   SELECT
                                P.*
                            FROM (
                                --หาข้อมูลสินค้าสินค้าและบาร์โค้ด
                                SELECT DISTINCT
                                    PDT.*,
                                    ISNULL(PRI.FCPgdPriceNet,0) AS FCPgdPriceNet,
                                    ISNULL(PRI.FCPgdPriceRet,0) AS FCPgdPriceRet,
                                    ISNULL(PRI.FCPgdPriceWhs,0) AS FCPgdPriceWhs 
                                FROM (
                                    SELECT c.* FROM(
										SELECT 
                                            DENSE_RANK() OVER (ORDER BY FTPdtCode ASC) AS FNRowID,
                                            ROW_NUMBER() OVER (PARTITION BY FTPdtCode ORDER BY FTPdtCode ASC) AS MergeColumTD,
                                            * 
                                            FROM (
                                            SELECT DISTINCT
                                                PDT.FTPdtForSystem,
                                                PIMG.FTImgObj,
                                                PDT.FTPdtCode,
                                                PDTL.FTPdtName,
                                                PPCZ.FTPunCode,
                                                PUNL.FTPunName,
                                                PBAR.FTBarCode,
                                                PGL.FTPgpName,
                                                PGL.FTPgpChainName,
                                                PTL.FTPtyName
                                            FROM   TCNMPdt PDT WITH(NOLOCK)
                                            LEFT JOIN TCNMPdt_L         PDTL    WITH(NOLOCK)    ON PDT.FTPdtCode    = PDTL.FTPdtCode    AND PDTL.FNLngID    = $nLngID
                                            --LEFT JOIN TCNMPdtSpcBch     PDLSPC  WITH(NOLOCK)    ON PDT.FTPdtCode    = PDLSPC.FTPdtCode
                                            LEFT JOIN TCNMPdtSpcZone    PDLZNE  WITH(NOLOCK)    ON PDT.FTPdtCode    = PDLZNE.FTPdtCode
                                            --LEFT JOIN TCNMZoneObj       ZNEOBJ  WITH(NOLOCK)    ON PDLZNE.FTZneCode = ZNEOBJ.FTZneChain
                                            LEFT JOIN TCNMPdtPackSize   PPCZ    WITH(NOLOCK)    ON PDT.FTPdtCode    = PPCZ.FTPdtCode 
                                            LEFT JOIN TCNMPdtBar        PBAR    WITH(NOLOCK)    ON PDT.FTPdtCode    = PBAR.FTPdtCode    AND PPCZ.FTPunCode  = PBAR.FTPunCode 
                                            LEFT JOIN TCNMImgPdt        PIMG    WITH(NOLOCK)    ON PDT.FTPdtCode    = PIMG.FTImgRefID   AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = $nLngID
                                            LEFT JOIN TCNMPdtType_L     PTL     WITH(NOLOCK)    ON PDT.FTPtyCode    = PTL.FTPtyCode     AND PTL.FNLngID     = $nLngID
                                            LEFT JOIN TCNMPdtUnit_L     PUNL    WITH(NOLOCK)    ON PPCZ.FTPunCode   = PUNL.FTPunCode    AND PUNL.FNLngID    = $nLngID
                                            LEFT JOIN TCNMZone_L        ZNEL    WITH(NOLOCK)    ON PDLZNE.FTZneCode = ZNEL.FTZneChain   AND ZNEL.FNLngID    = $nLngID
                                            LEFT JOIN TCNMPdtGrp_L      PGL     WITH(NOLOCK)    ON PGL.FTPgpChain   = PDT.FTPgpChain
                                            LEFT JOIN 
												(
													SELECT DISTINCT 
														PZN.FTPdtCode,
														ZOB.FTZneChain,
                                                        PZN.FTPdtStaInOrEx,
                                                        -- ZOB.FTZneTable,
														-- ZOB.FTZneRefCode
														CASE WHEN ZOB.FTZneTable = 'TCNMCountry'    THEN ZOB.FTZneRefCode ELSE '' END AS FTCtyCode,
														CASE WHEN ZOB.FTZneTable = 'TCNMAgency'     THEN ZOB.FTZneRefCode ELSE '' END AS FTAgnCode,
														CASE WHEN ZOB.FTZneTable = 'TCNMBranch'     THEN ZOB.FTZneRefCode ELSE '' END AS FTBchCode,
														CASE WHEN ZOB.FTZneTable = 'TCNMShop'       THEN ZOB.FTZneRefCode ELSE '' END AS FTShpCode,
														CASE WHEN ZOB.FTZneTable = 'TCNMMerchant'   THEN ZOB.FTZneRefCode ELSE '' END AS FTMerCode
													FROM TCNMPdtSpcZone PZN
													INNER JOIN TCNMZoneObj ZOB ON PZN.FTZneCode = ZOB.FTZneChain 
												) PSC ON PDT.FTPdtCode = PSC.FTPdtCode
                                            WHERE 1=1
                                            ".$tPdtForSysLine1."
                                            ".$tWHEREPermission_BCH."
                                            ".$tWHEREPermission_SHP."
                                            ".$tWHEREPermission_InOrEx."
                        ";
                    if(isset($tSearch) && !empty($tSearch)){
                        $tSQL   .= " AND (PDT.FTPdtCode   COLLATE THAI_BIN LIKE '%$tSearch%' OR PDTL.FTPdtName COLLATE THAI_BIN LIKE '%$tSearch%' OR PUNL.FTPunName COLLATE THAI_BIN LIKE '%$tSearch%' 
                                                    OR PGL.FTPgpName  COLLATE THAI_BIN LIKE '%$tSearch%' OR PGL.FTPgpChainName COLLATE THAI_BIN LIKE '%$tSearch%' OR PTL.FTPtyName COLLATE THAI_BIN LIKE '%$tSearch%'
                                                    OR PBAR.FTBarCode  COLLATE THAI_BIN LIKE '%$tSearch%'
                                                    OR ZNEL.FTZneName  COLLATE THAI_BIN LIKE '%$tSearch%')";
                    }
                                            
                    $tSQL .= "      ) Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] 
                                ) AS PDT
                                /** หาราคาสินค้าล่าสุด (ราคาขาย) **/
                                LEFT JOIN (
                                    SELECT PRI4PDT.* FROM (
                                        SELECT 
                                               ROW_NUMBER() OVER(PARTITION BY FTPdtCode,FTPunCode ORDER BY FTPdtCode ASC ,FDPghDStart DESC) AS FNRowPart,
                                               FTPdtCode,FTPunCode,
                                               CONVERT(VARCHAR(16),FDPghDStart,121) AS FDPghDStart,
                                               FCPgdPriceNet,FCPgdPriceRet,FCPgdPriceWhs
                                        FROM   TCNTPdtPrice4PDT
                                        WHERE  FDPghDStart  <= CONVERT(VARCHAR(10),GETDATE(),121) ) PRI4PDT
                                    WHERE  FNRowPart = 1
                                ) AS PRI
                                ON PRI.FTPdtCode = PDT.FTPdtCode AND PDT.FTPunCode = PRI.FTPunCode
                                /** กรณีต้องการ Filter ระบบสินค้าให้ส่งพารามิตเตอร์ระบบสินค้ามา **/
                                WHERE 1=1 $tPdtForSysLine1";
                                if(is_numeric($tSearch)){
                                    $tSQL .= "  OR  ( PRI.FCPgdPriceRet = $tSearch  )";
                                }
                $tSQL .= "  ) AS P ORDER BY P.FTPdtCode ASC";

                // if(!empty($tSearch)){
                //     $tSearchGetall = $tSearch;
                // }else{
                //     $tSearchGetall = $tSearchPrice;
                // }

                // print_r($tSQL);
        $oQuery = $this->db->query($tSQL);           
        if ($oQuery->num_rows() > 0) {
            $aList          = $oQuery->result_array();
            $aFoundRow      = $this->FSaMPDTGetPageAll($tSearch,$tPdtForSys,$nLngID);
            $nFoundRow      = ($aFoundRow['rtCode'] == '1')? $aFoundRow['rtCountData'] : 0;
            // หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $nPageAll       = ceil($nFoundRow/$paData['nRow']);
            $aDataReturn    = array(
                // 'tSQL'          => $tSQL,
                'raItems'       => $aList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    = array(
                // 'tSQL'          => $tSQL,
				'rnAllRow'      => 0,
				'rnCurrentPage' => $paData['nPage'],
				"rnAllPage"     => 0,
				'rtCode'        => '800',
				'rtDesc'        => 'data not found',
			);
        }
        return $aDataReturn;
    }

    // Functionality : All Page Of Product
    // Parameters : function parameters
    // Creator :  31/01/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : data
    // Return Type : Array
    public function FSaMPDTGetPageAll($ptSearch,$ptPdtForSys,$ptLngID){
        // echo $ptSearch;
        // if(is_numeric($ptSearch)){
        //     // echo 'X';
        //     $ptSearch='';
        //     $tSearchPrice = $ptSearch;
           
        // }else{
        //     // echo 'XA';
        //     $ptSearch=$ptSearch;
        //     $tSearchPrice = '';
        // }

        $tSQL   = "	SELECT
                        ROW_NUMBER() OVER(ORDER BY PDT.FTPdtCode ASC) AS counts,
                        PDT.FTPdtCode
                    FROM TCNMPdt PDT WITH(NOLOCK)
                   -- LEFT JOIN TCNMPdtSpcBch     PDLSPC  WITH(NOLOCK)    ON PDT.FTPdtCode    = PDLSPC.FTPdtCode
                    LEFT JOIN TCNMPdt_L         PDTL    WITH(NOLOCK)    ON PDT.FTPdtCode    = PDTL.FTPdtCode    AND PDTL.FNLngID    = $ptLngID
                    LEFT JOIN TCNMPdtPackSize   PPCZ    WITH(NOLOCK)    ON PDT.FTPdtCode    = PPCZ.FTPdtCode 
                    LEFT JOIN TCNMPdtBar        PBAR    WITH(NOLOCK)    ON PDT.FTPdtCode    = PBAR.FTPdtCode    AND PPCZ.FTPunCode  = PBAR.FTPunCode 
                    LEFT JOIN TCNMImgPdt        PIMG    WITH(NOLOCK)    ON PDT.FTPdtCode    = PIMG.FTImgRefID   AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = $ptLngID
                    LEFT JOIN TCNMPdtType_L     PTL     WITH(NOLOCK)    ON PDT.FTPdtType    = PTL.FTPtyCode     AND PTL.FNLngID     = $ptLngID
                    LEFT JOIN TCNMPdtUnit_L     PUNL    WITH(NOLOCK)    ON PPCZ.FTPunCode   = PUNL.FTPunCode    AND PUNL.FNLngID    = $ptLngID
                    LEFT JOIN TCNMPdtGrp_L      PGL     WITH(NOLOCK)    ON PGL.FTPgpChain   = PDT.FTPgpChain
                    LEFT JOIN 
                        (
                            SELECT DISTINCT 
                                PZN.FTPdtCode,
                                ZOB.FTZneChain,
                                PZN.FTPdtStaInOrEx,
                                CASE WHEN ZOB.FTZneTable = 'TCNMCountry'    THEN ZOB.FTZneRefCode ELSE '' END AS FTCtyCode,
                                CASE WHEN ZOB.FTZneTable = 'TCNMAgency'     THEN ZOB.FTZneRefCode ELSE '' END AS FTAgnCode,
                                CASE WHEN ZOB.FTZneTable = 'TCNMBranch'     THEN ZOB.FTZneRefCode ELSE '' END AS FTBchCode,
                                CASE WHEN ZOB.FTZneTable = 'TCNMShop'       THEN ZOB.FTZneRefCode ELSE '' END AS FTShpCode,
                                CASE WHEN ZOB.FTZneTable = 'TCNMMerchant'   THEN ZOB.FTZneRefCode ELSE '' END AS FTMerCode
                            FROM TCNMPdtSpcZone PZN
                            INNER JOIN TCNMZoneObj ZOB ON PZN.FTZneCode = ZOB.FTZneChain 
                        ) PSC ON PDT.FTPdtCode = PSC.FTPdtCode
                    LEFT JOIN (
                                    SELECT PRI4PDT.* FROM (
                                        SELECT 
                                               ROW_NUMBER() OVER(PARTITION BY FTPdtCode,FTPunCode ORDER BY FTPdtCode ASC ,FDPghDStart DESC) AS FNRowPart,
                                               FTPdtCode,FTPunCode,
                                               CONVERT(VARCHAR(16),FDPghDStart,121) AS FDPghDStart,
                                               FCPgdPriceNet,FCPgdPriceRet,FCPgdPriceWhs
                                        FROM   TCNTPdtPrice4PDT
                                        WHERE  FDPghDStart  <= CONVERT(VARCHAR(10),GETDATE(),121) ) PRI4PDT
                                    WHERE  FNRowPart = 1
                                ) AS PRI
                                ON PRI.FTPdtCode = PDT.FTPdtCode AND PPCZ.FTPunCode = PRI.FTPunCode
                    WHERE 1=1 ";

        // SEARCH PRODUCT
        if(isset($ptSearch) && !empty($ptSearch)){
            $tSQL   .= "  AND (PDT.FTPdtCode   COLLATE THAI_BIN LIKE '%$ptSearch%' OR PDTL.FTPdtName COLLATE THAI_BIN LIKE '%$ptSearch%' OR PUNL.FTPunName COLLATE THAI_BIN LIKE '%$ptSearch%' 
                          OR PGL.FTPgpName  COLLATE THAI_BIN LIKE '%$ptSearch%' OR PGL.FTPgpChainName COLLATE THAI_BIN LIKE '%$ptSearch%' OR PTL.FTPtyName COLLATE THAI_BIN LIKE '%$ptSearch%'
                          OR PBAR.FTBarCode  COLLATE THAI_BIN LIKE '%$ptSearch%' 
                         
                          )";
            if(is_numeric($ptSearch)){
                $tSQL   .= " OR PRI.FCPgdPriceRet = '$ptSearch' ";
                    }
    }
        
        // if(!empty($tSearchPrice)){
        //     echo 'X';
        //     $tSQL .= "  AND  ( PRI.FCPgdPriceRet = $tSearchPrice OR PDT.FTPdtCode  COLLATE THAI_BIN LIKE '%$tSearchPrice%' )";
        // }
        // Product For System
        if(isset($ptPdtForSys) && !empty($ptPdtForSys)){
            $tSQL   .= " AND PDT.FTPdtForSystem = '$ptPdtForSys'";
        }

        // echo $tSQL;
        // /* |-------------------------------------------------------------------------------------------| */
        // /* |                            สิทธิในการมองเห็นสินค้า CR.wat                                      | */
        // /* |-------------------------------------------------------------------------------------------| */
        // /* | */     $tSesUsrLevel           = $this->session->userdata('tSesUsrLevel');             // | */ 
        // /* | */     $tSessionMerCode        = $this->session->userdata('tSesUsrMerCode');           // | */ 
        // /* | */     $tSessionShopCode       = $this->session->userdata('tSesUsrShpCode');           // | */ 
        // /* | */     $tSessionBchCode        = $this->session->userdata('tSesUsrBchCode');           // | */ 
        // /* | */                                                                                     // | */ 
        // /* | */     //PERMISSION BCH    : ต้องเห็นสินค้าที่ผูกสาขา และสินค้าที่ไม่ผูกอะไรเลย(HQ)                // | */ 
        // /* | */     if($tSesUsrLevel == 'BCH'){                                                     // | */ 
        // /* | */         $tSQL   .= " AND ISNULL(PDLSPC.FTBchCode,'') ";                             // | */ 
        // /* | */         $tSQL   .= "  IN ('','$tSessionBchCode') ";                                 // | */ 
        // /* | */     }                                                                               // | */ 
        // /* | */                                                                                     // | */ 
        // /* | */     //PERMISSION SHP    : ต้องเห็นสินค้าระดับร้านค้า และ สินค้าของกลุ่มธุรกิจที่ไม่ได้ผูกร้านค้า       // | */ 
        // /* | */     if($tSesUsrLevel == 'SHP'){                                                     // | */ 
        // /* | */         $tSQL  .= " AND ( PDLSPC.FTBchCode = '$tSessionBchCode' ) ";                // | */ 
        // /* | */         $tSQL  .= " AND ";                                                          // | */ 
        // /* | */         $tSQL  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";                  // | */ 
        // /* | */         $tSQL  .= " ISNULL(PDLSPC.FTShpCode,'') = '' ) ";                           // | */ 
        // /* | */         $tSQL  .= " OR ";                                                           // | */ 
        // /* | */         $tSQL  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";                  // | */ 
        // /* | */         $tSQL  .= " PDLSPC.FTShpCode = '$tSessionShopCode' )";                      // | */ 
        // /* | */     }                                                                               // | */             
        // /* |-------------------------------------------------------------------------------------------| */

          /* |-----------------------------------------------------------------------------------------------| */
        /* |                            สิทธิในการมองเห็นสินค้า CR.wat  Edit By.IcePun                          | */
        /* |-----------------------------------------------------------------------------------------------| */
        /* | */     $tSesUsrLevel               = $this->session->userdata('tSesUsrLevel');             // | */ 
        /* | */     $tSessionMerCode            = $this->session->userdata('tSesUsrMerCode');           // | */ 
        /* | */     $tSessionShopCode           = $this->session->userdata('tSesUsrShpCode');           // | */ 
        /* | */     $tSessionBchCode            = $this->session->userdata('tSesUsrBchCode');           // | */ 
        /* | */                                                                                         // | */ 
        /* | */     //PERMISSION BCH    : ต้องเห็นสินค้าที่ผูกสาขา และสินค้าที่ไม่ผูกอะไรเลย(HQ)                    // | */ 
        /* | */     if($tSesUsrLevel == 'BCH'){                                                         // | */ 
        /* | */         $tSQL   .= " AND ISNULL(PSC.FTBchCode,'') ";                                    // | */ 
        /* | */         $tSQL   .= "  IN ('','$tSessionBchCode') ";                                     // | */ 
        /* | */     }                                                                                   // | */ 
        /* | */                                                                                         // | */
        /* | */     //PERMISSION SHP    : ต้องเห็นสินค้าระดับร้านค้า และ สินค้าของกลุ่มธุรกิจที่ไม่ได้ผูกร้านค้า           // | */ 
        /* | */     if($tSesUsrLevel == 'SHP'){                                                         // | */ 
        /* | */         $tSQL  .= " AND ( PSC.FTBchCode = '$tSessionBchCode' )";                        // | */ 
        /* | */         $tSQL  .= " AND ";                                                              // | */ 
        /* | */         $tSQL  .= " ( PSC.FTMerCode = '$tSessionMerCode' AND ";                         // | */ 
        /* | */         $tSQL  .= " ISNULL(PSC.FTShpCode,'') = '' ) ";                                  // | */ 
        /* | */         $tSQL  .= " OR ";                                                               // | */ 
        /* | */         $tSQL  .= " ( PSC.FTMerCode = '$tSessionMerCode' AND ";                         // | */ 
        /* | */         $tSQL  .= " PSC.FTShpCode = '$tSessionShopCode' )";                             // | */
        /* | */     }                                                                                   // | */
        /* | */                                                                                         // | */     
        /* | */     if($tSesUsrLevel != 'HQ'){                                                          // | */  
        /* | */         $tSQL  .= " AND ISNULL(PSC.FTPdtStaInOrEx,'') <> '2'";                          // | */ 
        /* | */     }                                                                                   // | */             
        /* |-----------------------------------------------------------------------------------------------| */


        $tSQL   .= " GROUP BY PDT.FTPdtCode";
        // print_r($tSQL);
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $oQuery->num_rows(),
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        return $aDataReturn;
    }

    // Functionality : Select Data Event Not Sale
    // Parameters : function parameters
    // Creator : 07/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
	// Return Type : Array
    public function FSaMPDTEvnNotSaleByID($paData){
        $tEvnCode   = $paData['FTEvnCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            PNSE.FTEvnCode,
                            PNSE.FNEvnSeqNo,
                            PNSE.FTEvnType,
                            PNSE.FTEvnStaAllDay,
                            PNSE.FDEvnDStart,
                            PNSE.FTEvnTStart,
                            PNSE.FDEvnDFinish,
                            PNSE.FTEvnTFinish,
                            PNSE_L.FTEvnName
                        FROM [TCNMPdtNoSleByEvn]  PNSE WITH (NOLOCK)
                        LEFT JOIN TCNMPdtNoSleByEvn_L PNSE_L WITH (NOLOCK) ON PNSE.FTEvnCode = PNSE_L.FTEvnCode AND PNSE_L.FNLngID = $nLngID
                        WHERE PNSE.FTEvnCode = '$tEvnCode'
                        ORDER BY PNSE.FNEvnSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'	=> $aDetail,
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Select Data Product Set
    // Parameters : function parameters
    // Creator : 07/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
	// Return Type : Array
    public function FSaMPDTGetDataPdtSet($paData){
        $tSQL_Config    = "SELECT FTSysStaUsrValue FROM TsysConfig WHERE FTSysCode='tCN_Cost' AND FTSysSeq='1'";
        $oQuery_Config  = $this->db->query($tSQL_Config);
        $aDataConfig    = $oQuery_Config->result_array();

        $tSQL = "SELECT
                PSET.FTPdtCodeSet,
                PSET.FCPstQty,
                PDT_L.FTPdtName,
                COST.FCPdtCostAVGIN,
                COST.FCPdtCostLast,
                COST.FCPdtCostStd,
                COST.FCPdtCostFIFOIN,
                PRI.FCPgdPriceNet,
                PRI.FCPgdPriceWhs,
                BAR.FTPunCode ,
                PDT.FTPdtForSystem
            FROM 
                TCNTPdtSet PSET WITH(NOLOCK)
            LEFT JOIN TCNMPdt                   PDT   WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT.FTPdtCode
            LEFT JOIN TCNMPdt_L                 PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT_L.FTPdtCode   AND PDT_L.FNLngID =  $paData[FNLngID]
            LEFT JOIN TCNMPdtBar        BAR   WITH(NOLOCK) ON PSET.FTPdtCodeSet = BAR.FTPdtCode
            LEFT JOIN VCN_ProductCost           COST  WITH(NOLOCK) ON PSET.FTPdtCodeSet = COST.FTPdtCode
            LEFT JOIN VCN_Price4PdtActive       PRI   WITH(NOLOCK) ON PSET.FTPdtCodeSet = PRI.FTPdtCode AND BAR.FTPunCode = PRI.FTPunCode AND PRI.FNRowPart = 1
        ";
        if(isset($paData['FTPdtCode'])){
            $tSQL .= " AND PSET.FTPdtCode    = '$paData[FTPdtCode]'";
        }

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = array(
                'tSQL'          => $tSQL,
                'aItems'	    => $oQuery->result_array(),
                'tStaUsrValue'  => $aDataConfig[0]['FTSysStaUsrValue'],
                'tCode'	        => '1',
                'tDesc'	        => 'success'
            );
        }else{
            $aResult = array(
                'tSQL'      => $tSQL,
                'tCode'	    => '800',
                'tDesc'	    => 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Select Data Product Unit
    // Parameters : function parameters
    // Creator : 08/02/2019 wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data Query For Database
	// Return Type : Array
    public function FSaMPDTGetDataPdtUnit($paData){
        $FTPunCode  = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            PUN.FTPunCode,
                            PUN_L.FTPunName
                        FROM TCNMPdtUnit PUN WITH (NOLOCK)
                        LEFT JOIN TCNMPdtUnit_L PUN_L WITH (NOLOCK) ON  PUN.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $nLngID
                        WHERE 1=1 AND PUN.FTPunCode = '$FTPunCode' ";
        $oQuery     = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->row_array();
            $aResult = array(
                'raItems'	=> $aDetail,
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTGetDataMasTemp($paData){
        // $FTPunCode  = $paData['FTPunCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            MTT.FTPdtCode,
                            MTT.FTPunCode,
                            MTT.FTPunName,
                            cast(MTT.FCPdtUnitFact as decimal(10,2)) AS FCPdtUnitFact,
                            MTT.FTPdtGrade,
                            MTT.FCPdtWeight,
                            MTT.FTClrCode,
                            MTT.FTClrName,
                            MTT.FTPszCode,
                            MTT.FTPszName,
                            MTT.FTPdtUnitDim,
                            MTT.FTPdtPkgDim,
                            MTT.FTPdtStaAlwPick,
                            MTT.FTPdtStaAlwPoHQ,
                            MTT.FTPdtStaAlwBuy,
                            MTT.FTPdtStaAlwSale,
                            (	SELECT TOP 1 P4PDT.FCPgdPriceRet
                                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                                WHERE 1=1
                                AND ((CONVERT(VARCHAR(19),GETDATE(),121) >= CONVERT(VARCHAR(19),P4PDT.FDPghDStart,121)) AND (CONVERT(VARCHAR(19),GETDATE(),121) <= CONVERT(VARCHAR(19),P4PDT.FDPghDStop,121)))
                                AND P4PDT.FTPghDocType = 1 AND P4PDT.FTPdtCode = MTT.FTPdtCode AND P4PDT.FTPunCode = MTT.FTPunCode
                                ORDER BY P4PDT.FDPghDStart DESC
                            ) FCPgdPriceRet,
                            (	SELECT TOP 1 P4PDT.FCPgdPriceWhs
                                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                                WHERE 1=1
                                AND ((CONVERT(VARCHAR(19),GETDATE(),121) >= CONVERT(VARCHAR(19),P4PDT.FDPghDStart,121)) AND (CONVERT(VARCHAR(19),GETDATE(),121) <= CONVERT(VARCHAR(19),P4PDT.FDPghDStop,121)))
                                AND P4PDT.FTPghDocType = 1 AND P4PDT.FTPdtCode = MTT.FTPdtCode AND P4PDT.FTPunCode = MTT.FTPunCode
                                ORDER BY P4PDT.FDPghDStart DESC
                            ) FCPgdPriceWhs,
                            (	SELECT TOP 1 P4PDT.FCPgdPriceNet
                                FROM TCNTPdtPrice4PDT P4PDT WITH (NOLOCK)
                                WHERE 1=1
                                AND ((CONVERT(VARCHAR(19),GETDATE(),121) >= CONVERT(VARCHAR(19),P4PDT.FDPghDStart,121)) AND (CONVERT(VARCHAR(19),GETDATE(),121) <= CONVERT(VARCHAR(19),P4PDT.FDPghDStop,121)))
                                AND P4PDT.FTPghDocType = 1 AND P4PDT.FTPdtCode = MTT.FTPdtCode AND P4PDT.FTPunCode = MTT.FTPunCode
                                ORDER BY P4PDT.FDPghDStart DESC
                            ) FCPgdPriceNet,
                            (SELECT COUNT(BAR.FTBarCode) FROM TsysMasTmp BAR WITH (NOLOCK) WHERE BAR.FTMttTableKey=MTT.FTMttTableKey AND BAR.FTMttRefKey = 'TCNMPdtBar' AND BAR.FTPdtCode=MTT.FTPdtCode AND BAR.FTPunCode=MTT.FTPunCode AND BAR.FTMttSessionID=MTT.FTMttSessionID) AS nCountBarCode
                        FROM TsysMasTmp MTT WITH (NOLOCK)
                        WHERE 1=1";

        if(isset($paData['FTMttTableKey'])){
            $FTMttTableKey = $paData['FTMttTableKey'];
            $tSQL  .= " AND FTMttTableKey = '$FTMttTableKey'";
        }
        if(isset($paData['FTMttRefKey'])){
            $FTMttRefKey = $paData['FTMttRefKey'];
            $tSQL  .= " AND FTMttRefKey = '$FTMttRefKey'";
        }
        if(isset($paData['FTPdtCode'])){
            $FTPdtCode = $paData['FTPdtCode'];
            $tSQL  .= " AND FTPdtCode = '$FTPdtCode'";
        }
        if(isset($paData['FTMttSessionID'])){
            $FTMttSessionID = $paData['FTMttSessionID'];
            $tSQL  .= " AND FTMttSessionID = '$FTMttSessionID'";
        }

        $oQuery     = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDetail    = $oQuery->result_array();
            $aResult = array(
                'raItems'	=> $aDetail,
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTDelDataMasTemp($paPdtWhere){
        if($paPdtWhere['DeleteType'] == 1){
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->where('FTPunCode', $paPdtWhere['FTPunCode']);
            $this->db->delete('TsysMasTmp');
        }else{
            $this->db->where('FTMttSessionID', $paPdtWhere['FTMttSessionID']);
            $this->db->where('FTMttTableKey', $paPdtWhere['FTMttTableKey']);
            // $this->db->where('FTMttRefKey', $paPdtWhere['FTMttRefKey']);
            // $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
            $this->db->delete('TsysMasTmp');
        }
    }
//ตัวอย่าง
    public function FSaMPDTInsertPackSizeMasTemp($paData){
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];

        $tSQL           = "INSERT INTO TsysMasTmp (FTMttTableKey,FTMttRefKey,FTMttSessionID,FTPdtCode,FTPunCode,FTPunName,FCPdtUnitFact,FTPdtGrade,FCPdtWeight,FTClrCode,FTClrName,FTPszCode,FTPszName,FTPdtUnitDim,FTPdtPkgDim,FTPdtStaAlwPick,FTPdtStaAlwPoHQ,FTPdtStaAlwBuy,FTPdtStaAlwSale,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT 
                                '$FTMttTableKey' 	 AS FTMttTableKey,
                                '$FTMttRefKey'       AS FTMttRefKey,
                                '$FTMttSessionID'    AS FTMttSessionID,
                                PKS.FTPdtCode,
                                UNI_L.FTPunCode,
                                UNI_L.FTPunName,
                                PKS.FCPdtUnitFact,
                                PKS.FTPdtGrade,
                                PKS.FCPdtWeight,
                                PKS.FTClrCode,
                                CLR_L.FTClrName,
                                PKS.FTPszCode,
                                PSZ_L.FTPszName,
                                PKS.FTPdtUnitDim,
                                PKS.FTPdtPkgDim,
                                PKS.FTPdtStaAlwPick,
                                PKS.FTPdtStaAlwPoHQ,
                                PKS.FTPdtStaAlwBuy,
                                PKS.FTPdtStaAlwSale,
                                '$dDate' AS FDLastUpdOn,
                                '$dDate' AS FDCreateOn,
                                '$tUser' AS FTLastUpdBy,
                                '$tUser' AS FTCreateBy
                            FROM TCNMPdtPackSize PKS
                            LEFT JOIN TCNMPdtUnit_L  UNI_L ON UNI_L.FTPunCode = PKS.FTPunCode AND UNI_L.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtColor_L CLR_L ON CLR_L.FTClrCode = PKS.FTClrCode AND CLR_L.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtSize_L  PSZ_L ON PSZ_L.FTPszCode = PKS.FTPszCode AND PSZ_L.FNLngID = $nLngID
                            WHERE PKS.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
        if($oQuery > 0){
            $aResult = array(
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTInsertBarCodeMasTemp($paData){
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $FTMttSessionID = $paData['FTMttSessionID'];
        $dDate          = $paData['dDate'];
        $tUser          = $paData['tUser'];
        $tSQL           = "INSERT INTO TsysMasTmp (FTMttTableKey,FTMttRefKey,FTMttSessionID,FTPdtCode,FTBarCode,FTPunCode,FTPlcCode,FTPlcName,FTSplCode,FTSplName,FTSplStaAlwPO,FTBarStaUse,FTBarStaAlwSale,FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                            SELECT
                                    '$FTMttTableKey'    AS FTMttTableKey,
                                    '$FTMttRefKey'      AS FTMttRefKey,
                                    '$FTMttSessionID'   AS FTMttSessionID,
                                    PBAR.FTPdtCode,
                                    PBAR.FTBarCode,
                                    PPSZ.FTPunCode,
                                    PLCL.FTPlcCode,
                                    PLCL.FTPlcName,
                                    SPL_L.FTSplCode,
                                    SPL_L.FTSplName,
                                    SPL.FTSplStaAlwPO,
                                    PBAR.FTBarStaUse,
                                    PBAR.FTBarStaAlwSale,
                                    '$dDate' AS FDLastUpdOn,
                                    '$dDate' AS FDCreateOn,
                                    '$tUser' AS FTLastUpdBy,
                                    '$tUser' AS FTCreateBy
                            FROM TCNMPdtPackSize PPSZ
                            LEFT JOIN TCNMPdtBar PBAR 	    ON PPSZ.FTPdtCode = PBAR.FTPdtCode AND PBAR.FTPunCode = PPSZ.FTPunCode
                            LEFT JOIN TCNMPdtLoc_L PLCL    ON PBAR.FTPlcCode = PLCL.FTPlcCode AND PLCL.FNLngID = $nLngID
                            LEFT JOIN TCNMPdtSpl SPL        ON SPL.FTPdtCode = PPSZ.FTPdtCode AND SPL.FTBarCode = PBAR.FTBarCode
                            LEFT JOIN TCNMSpl_L SPL_L       ON SPL_L.FTSplCode = SPL.FTSplCode AND SPL_L.FNLngID = $nLngID
                            WHERE PPSZ.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
        if($oQuery > 0){
            $aResult = array(
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Chack Barcode Duplicate in DB
    // Parameters: Data BarCode
    // Creator: 12/02/2018 wasin
    // Return: 
	// ReturnType:  Array
    public function FSaMStaChkBarcode($ptPdtCode,$ptBarCode){
        $tSQL   = " SELECT 
                        COUNT(PBAR.FTBarCode) AS Counts
                    FROM TCNMPdtBar PBAR WITH (NOLOCK)
                    WHERE 1=1 AND PBAR.FTPdtCode = '$ptPdtCode' AND PBAR.FTBarCode = '$ptBarCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail    = $oQuery->row_array();
            $aResult    =  array(
                'raItems'   => $aDetail,
                'rtCode'    => '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Check Product Duplicate In DB
    // Parameters : function parameters
    // Creator : 18/02/2019 Wasin(Yoshi)
    // Return : Data Check Product Duplicate
    // Return Type : Array
    public function FSaMPDTCheckDuplicate($ptPdtCode){
        $tSQL   =   "SELECT COUNT(PDT.FTPdtCode) AS counts
					 FROM TCNMPdt PDT WITH (NOLOCK)
                     WHERE PDT.FTPdtCode = '$ptPdtCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            $aResult    =  array(
                'rnCountPdt'    => $aDataQuery['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Function Add Product 
    // Parameters : function parameters
    // Creator : 18/02/2019 Wasin(Yoshi)
    // Return : Array Status Add Product (TCNMPdt)
	// Return Type : array
    public function FSaMPDTAddUpdateMaster($paPdtWhere,$paPdtData){
        // Delete Spc Bch
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->delete('TCNMPdtSpcBch');

        // Update TCNMPdt
        $aDataUpdate    = array_merge($paPdtData,array(
            'FDLastUpdOn'   => date('Y-m-d h:i:s'),
            'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
        ));
        $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
        $this->db->update('TCNMPdt',$aDataUpdate);
        if($this->db->affected_rows() > 0){
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        }else{
            // Add TCNMPdt
            $aDataInsert = array_merge($paPdtWhere,$paPdtData,array(
                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d h:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername')
            ));
            $this->db->insert('TCNMPdt',$aDataInsert);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            }else{
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    // Functionality : Function Add Product Lang
    // Parameters : function parameters
    // Creator : 18/02/2019 Wasin(Yoshi)
    // Return : Array Status Add Product (TCNMPdt_L)
	// Return Type : array
    public function FSaMPDTAddUpdateLang($paPdtWhere,$paPdtDataLang){ 
        // Update TCNMPdt_L

        $this->db->where('FNLngID',$paPdtWhere['FNLngID']);
        $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
        $this->db->update('TCNMPdt_L',$paPdtDataLang);

        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Lang Success.',
            );
        }else{
            // Add TCNMPdt_L
            $aDataInsertLang    = array_merge($paPdtWhere,$paPdtDataLang);
            $this->db->insert('TCNMPdt_L',$aDataInsertLang);
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Lang Success',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product Lang.',
                );
            }
        }
        return $aStatus;
    }

    // Functionality: Functio Add/Update PackSize
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update PackSize
    // ReturnType: Array
    public function FSxMPDTAddUpdatePackSize($paPdtWhere,$paPackSizeWhere){
        $FTPdtCode        = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey    = $paPackSizeWhere['FTMttTableKey'];
        $FTMttRefKey      = $paPackSizeWhere['FTMttRefKey'];
        $FTMttSessionID   = $paPackSizeWhere['FTMttSessionID'];
        // Delete Pack Size
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtPackSize');
        // $tSQLDel = "DELETE FROM TCNMPdtPackSize WHERE FTPdtCode = '$FTPdtCode'";
        // $oQuery = $this->db->query($tSQLDel);

        $tSQL = "INSERT INTO TCNMPdtPackSize (FTPdtCode,
                    FTPunCode,
                    FCPdtUnitFact,
                    FTPdtGrade,
                    FCPdtWeight,
                    FTClrCode,
                    FTPszCode,
                    FTPdtUnitDim,
                    FTPdtPkgDim,
                    FTPdtStaAlwPick,
                    FTPdtStaAlwPoHQ,
                    FTPdtStaAlwBuy,
                    FTPdtStaAlwSale,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy)
                SELECT
                    FTPdtCode,
                    FTPunCode,
                    FCPdtUnitFact,
                    FTPdtGrade,
                    FCPdtWeight,
                    FTClrCode,
                    FTPszCode,
                    FTPdtUnitDim,
                    FTPdtPkgDim,
                    FTPdtStaAlwPick,
                    FTPdtStaAlwPoHQ,
                    FTPdtStaAlwBuy,
                    FTPdtStaAlwSale,
                    FDLastUpdOn,
                    FDCreateOn,
                    FTLastUpdBy,
                    FTCreateBy
                FROM TsysMasTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTMttSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        // print_r($tSQL);
        // exit;
        if($oQuery > 0){
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;

        // if(is_array($paDataPackSize) && !empty($paDataPackSize)){
        //     foreach($paDataPackSize AS $nKey => $aValue){
        //         $aDataPsz   = array(
        //             'FTPdtCode'         => $paPdtWhere['FTPdtCode'],
        //             'FTPunCode'         => $aValue['tPdtPunCode'],
        //             'FCPdtUnitFact'     => (isset($aValue['tPdtUnitFact']) && !empty($aValue['tPdtUnitFact']))? $aValue['tPdtUnitFact'] : 1,
        //             'FTPdtGrade'        => $aValue['tPdtGrade'],
        //             'FCPdtWeight'       => (isset($aValue['tPdtWeight']) && !empty($aValue['tPdtWeight']))? $aValue['tPdtWeight'] : 0,
        //             'FTClrCode'         => $aValue['tPdtClrCode'],
        //             'FTPszCode'         => $aValue['tPdtSizeCode'],
        //             'FTPdtUnitDim'      => $aValue['tPdtUnitDim'],
        //             'FTPdtPkgDim'       => $aValue['tPdtPkgDim'],
        //             'FTPdtStaAlwPick'   => $aValue['tPdtStaAlwPick'],
        //             'FTPdtStaAlwPoHQ'   => $aValue['tPdtStaAlwPoHQ'],
        //             'FTPdtStaAlwBuy'    => $aValue['tPdtStaAlwBuy'],
        //             'FTPdtStaAlwSale'   => $aValue['tPdtStaAlwSale'],
        //             'FDLastUpdOn'       => date('Y-m-d h:i:s'),
        //             'FDCreateOn'        => date('Y-m-d h:i:s'),
        //             'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
        //             'FTCreateBy'        => $this->session->userdata('tSesUsername')
        //         );
        //         $this->db->insert('TCNMPdtPackSize',$aDataPsz);
        //     }
        // }
        // return;
    }

    // Functionality: Get Last Seq PlcCode In BarCode
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Array Last Seq
	// ReturnType: Array
    public function FSaMPDTGetPlcSeq($ptPdtCode,$ptPlcCode){
        $tSQL   = " SELECT TOP 1 PBAR.FNPldSeq 
                    FROM TCNMPdtBar PBAR
                    WHERE PBAR.FTPdtCode = '$ptPdtCode' AND PBAR.FTPlcCode = '$ptPlcCode'
                    ORDER BY PBAR.FNPldSeq DESC ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery->row_array();
    }

    // Functionality: Functio Add/Update BarCode
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi) - Modifly: 26/06/2562 Napat(Jame)
    // Return: Status Add Update BarCode
	// ReturnType: Array
    public function FSxMPDTAddUpdateBarCode($paPdtWhere,$paDataBarCode){
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey      = $paDataBarCode['FTMttTableKey'];
        $FTMttRefKey        = $paDataBarCode['FTMttRefKey'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtBar');

        $tSQL = "INSERT INTO TCNMPdtBar (FTPdtCode,
                        FTBarCode,
                        FTPunCode,
                        FTBarStaUse,
                        FTBarStaAlwSale,
                        FTPlcCode,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy)
                SELECT FTPdtCode,
                        FTBarCode,
                        FTPunCode,
                        FTBarStaUse,
                        FTBarStaAlwSale,
                        FTPlcCode,
                        FDLastUpdOn,
                        FTLastUpdBy,
                        FDCreateOn,
                        FTCreateBy
                FROM TsysMasTmp
                WHERE FTPdtCode='$FTPdtCode' AND FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTMttSessionID='$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        // print_r($tSQL);
        // exit;
        if($oQuery > 0){
            $aResult    =  array(
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
        // Delete BarCode All
        // $this->db->where_in('FTPdtCode', $paPdtWhere['FTPdtCode']);
        // $this->db->delete('TCNMPdtBar');
        // if(is_array($paDataPackSize) && !empty($paDataPackSize)){
        //     // Loop Data Pack Size
        //     foreach($paDataPackSize AS $nKey => $aPackSize){
        //         $tPdtPunCode   = $aPackSize['tPdtPunCode'];
        //         // Loop Data BarCode Where In PackSize
        //         if(isset($aPackSize['oDataBarCode']) && !empty($aPackSize['oDataBarCode'])){
        //             foreach($aPackSize['oDataBarCode'] AS $nKey => $aBarCode){
        //                 if(isset($aBarCode['tPdtPlcCode']) && !empty($aBarCode['tPdtPlcCode'])){
        //                     $tPlcCode       = $aBarCode['tPdtPlcCode'];
        //                     $aPlcLastSeq    = $this->FSaMPDTGetPlcSeq($paPdtWhere['FTPdtCode'],$tPlcCode);
        //                     $nPlcLastSeq    = (!empty($aPlcLastSeq['FNPldSeq']))? $aPlcLastSeq['FNPldSeq'] : 0 ;
        //                 }else{
        //                     $tPlcCode      = null;
        //                     $nPlcLastSeq	= null;
        //                 }

        //                 $aDataBarCode   = array(
        //                     'FTPdtCode'         => $paPdtWhere['FTPdtCode'],
        //                     'FTBarCode'         => $aBarCode['tPdtBarCode'],
        //                     'FTPunCode'         => $tPdtPunCode,
        //                     'FTBarStaUse'       => $aBarCode['tPdtBarStaUse'],
        //                     'FTBarStaAlwSale'   => $aBarCode['tPdtBarStaAlwSale'],
        //                     'FTPlcCode'         => $tPlcCode,
        //                     'FNPldSeq'          => $nPlcLastSeq + 1,
        //                     'FDLastUpdOn'       => date('Y-m-d h:i:s'),
        //                     'FDCreateOn'        => date('Y-m-d h:i:s'),
        //                     'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
        //                     'FTCreateBy'        => $this->session->userdata('tSesUsername')
        //                 );
        //                 $this->db->insert('TCNMPdtBar',$aDataBarCode);
        //             }
        //         }
        //     }
        // }
        // return;
    }

    public function FSaMPDTCheckBarCodeByID($paData){
        $tSQL = "SELECT FTBarCode 
        FROM TsysMasTmp 
        WHERE 1=1 
        AND FTPdtCode = '$paData[FTPdtCode]' 
        AND FTBarCode = '$paData[FTBarCode]'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTCheckBarOldCodeByID($paData){
        $tSQL = "SELECT FTBarCode 
        FROM TsysMasTmp 
        WHERE 1=1 
        AND FTPdtCode = '$paData[FTPdtCode]' 
        AND FTBarCode = '$paData[tOldBarCode]'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult    =  array(
                'tSQL'      => $tSQL,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'tSQL'      => $tSQL,
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }
    
    public function FSxMPDTAddUpdateBarCodeByID($paDataPackSize){
        $FTMttTableKey          = $paDataPackSize['FTMttTableKey'];
        $FTMttRefKey            = $paDataPackSize['FTMttRefKey'];
        $FTPdtCode              = $paDataPackSize['FTPdtCode'];
        $FTBarCode              = $paDataPackSize['FTBarCode'];
        $tOldBarCode            = $paDataPackSize['tOldBarCode'];
        $FTPunCode              = $paDataPackSize['FTPunCode'];
        $FTSplCode              = $paDataPackSize['FTSplCode'];
        $FTSplName              = $paDataPackSize['FTSplName'];
        $FTPlcCode              = $paDataPackSize['FTPlcCode'];
        $FTPlcName              = $paDataPackSize['FTPlcName'];
        $FTBarStaUse            = $paDataPackSize['FTBarStaUse'];
        $FTBarStaAlwSale        = $paDataPackSize['FTBarStaAlwSale'];
        $FTSplStaAlwPO          = $paDataPackSize['FTSplStaAlwPO'];
        $FTMttSessionID         = $paDataPackSize['FTMttSessionID'];

        $tSQL       = "SELECT FTBarCode
                        FROM TsysMasTmp
                        WHERE FTMttTableKey         = '$FTMttTableKey'
                         AND FTMttRefKey            = '$FTMttRefKey'
                         AND FTPdtCode              = '$FTPdtCode'
                         AND FTBarCode              = '$tOldBarCode'
                         AND FTMttSessionID         = '$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataBarCode = array(
                'FTBarCode'         => $FTBarCode,
                'FTSplCode'         => $FTSplCode,
                'FTSplName'         => $FTSplName,
                'FTPlcCode'         => $FTPlcCode,
                'FTPlcName'         => $FTPlcName,
                'FTBarStaUse'       => $FTBarStaUse,
                'FTBarStaAlwSale'   => $FTBarStaAlwSale,
                'FTSplStaAlwPO'     => $FTSplStaAlwPO,
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
            );
            $this->db->where('FTMttTableKey',$FTMttTableKey);
            $this->db->where('FTMttRefKey',$FTMttRefKey);
            $this->db->where('FTPdtCode',$FTPdtCode);
            $this->db->where('FTPunCode',$FTPunCode);
            $this->db->where('FTBarCode',$tOldBarCode);
            $this->db->update('TsysMasTmp',$aDataBarCode);
        }else{
            $aDataBarCode   = array(
                'FTMttTableKey'     => $FTMttTableKey,
                'FTMttRefKey'       => $FTMttRefKey,
                'FTPdtCode'         => $FTPdtCode,
                'FTBarCode'         => $FTBarCode,
                'FTPunCode'         => $FTPunCode,
                'FTSplCode'         => $FTSplCode,
                'FTSplName'         => $FTSplName,
                'FTPlcCode'         => $FTPlcCode,
                'FTPlcName'         => $FTPlcName,
                'FTBarStaUse'       => $FTBarStaUse,
                'FTBarStaAlwSale'   => $FTBarStaAlwSale,
                'FTSplStaAlwPO'     => $FTSplStaAlwPO,
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FDCreateOn'        => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
                'FTMttSessionID'    => $FTMttSessionID
            );
            $this->db->insert('TsysMasTmp',$aDataBarCode);
        }

        
        $aResult    =  array(
            'rtCode'    => '1',
            'rtDesc'    => 'success'
        );


        // $aDataBarCode   = array(
        //     'FTPdtCode'         => $paPdtWhere['FTPdtCode'],
        //     'FTBarCode'         => $paDataPackSize['FTBarCode'],
        //     'FTPunCode'         => $paDataPackSize['FTPunCode'],
        //     'FTBarStaUse'       => $FTBarStaUse,
        //     'FTBarStaAlwSale'   => $FTBarStaAlwSale,
        //     'FTPlcCode'         => $tPlcCode,
        //     'FNPldSeq'          => $nPlcLastSeq + 1,
        //     'FDLastUpdOn'       => date('Y-m-d h:i:s'),
        //     'FDCreateOn'        => date('Y-m-d h:i:s'),
        //     'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
        //     'FTCreateBy'        => $this->session->userdata('tSesUsername')
        // );
        // $this->db->insert('TCNMPdtBar',$aDataBarCode);

        // if($paDataPackSize['FTSplStaAlwPO']=="on"){ $FTSplStaAlwPO = 1; }else{ $FTSplStaAlwPO = NULL; }
        // $aDataSpl   = array(
        //     'FTPdtCode'         => $paPdtWhere['FTPdtCode'],
        //     'FTBarCode'         => $paDataPackSize['FTBarCode'],
        //     'FTSplCode'         => $paDataPackSize['FTSplCode'],
        //     'FTSplStaAlwPO'     => $FTSplStaAlwPO
        // );
        // $this->db->insert('TCNMPdtSpl',$aDataSpl);
        return $aResult;
    }

    // Functionality: Functio Add/Update Supplier
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update Supplier
	// ReturnType: Array
    public function FSxMPDTAddUpdateSupplier($paPdtWhere,$paDataBarCode){
        $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey      = $paDataBarCode['FTMttTableKey'];
        $FTMttRefKey        = $paDataBarCode['FTMttRefKey'];
        $FTMttSessionID     = $paDataBarCode['FTMttSessionID'];

        // Delete Supplier All
        $this->db->where('FTPdtCode', $FTPdtCode);
        $this->db->delete('TCNMPdtSpl');

        $tSQL = "INSERT INTO TCNMPdtSpl (FTPdtCode,
                        FTBarCode,
                        FTSplCode,
                        FTSplStaAlwPO)
                SELECT FTPdtCode,
                        FTBarCode,
                        FTSplCode,
                        FTSplStaAlwPO
                FROM TsysMasTmp
                WHERE FTPdtCode='$FTPdtCode' 
                AND FTMttTableKey='$FTMttTableKey' 
                AND FTMttRefKey='$FTMttRefKey' 
                AND FTMttSessionID='$FTMttSessionID' 
                AND FTSplCode!=''";
        $oQuery = $this->db->query($tSQL);
        if($oQuery > 0){
            $aResult    =  array(
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;

        // if(is_array($paDataPackSize) && !empty($paDataPackSize)){
        //     // Loop Data Pack Size
        //     foreach($paDataPackSize AS $nKey => $aPackSize){
        //        if(isset($aPackSize['oDataSupplier']) && !empty($aPackSize['oDataSupplier'])){
        //             foreach($aPackSize['oDataSupplier'] AS $nKey => $aSupplier){
        //                 $aDataSupllier  = array(
        //                     'FTPdtCode'     => $paPdtWhere['FTPdtCode'],
        //                     'FTBarCode'     => $aSupplier['tPdtBarCode'],
        //                     'FTSplCode'     => $aSupplier['tPdtSplCode'],
        //                     'FTSplStaAlwPO' => $aSupplier['tPdtStaAlwPO'],
        //                 );
        //                 $this->db->insert('TCNMPdtSpl',$aDataSupllier);
        //             }
        //        }
        //     }
        // }
        // return;
    }

    public function FSxMPDTAddUpdateSpcBch($aDataSpcBch){
        // $FTPdtCode          = $paPdtWhere['FTPdtCode'];
        // $FTBchCode          = $aDataSpcBch['FTBchCode'];
        // $FTMerCode          = $aDataSpcBch['FTMerCode'];
        // $FTShpCode          = $aDataSpcBch['FTShpCode'];
        // $FTMgpCode          = $aDataSpcBch['FTMgpCode'];

        // // Delete Supplier All
        // $this->db->where('FTPdtCode', $FTPdtCode);
        // $this->db->delete('TCNMPdtSpcBch');
        // switch($tType){
        //     case "update":
        //         $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
        //         $this->db->update('TCNMPdtSpcBch',$aDataSpcBch);
        //     break;
        //     case "insert":
        //         $this->db->insert('TCNMPdtSpcBch',$aDataSpcBch);
        //     break;
        // }
        $this->db->insert('TCNMPdtSpcBch',$aDataSpcBch);
        
        // $tSQL = "INSERT INTO TCNMPdtSpcBch (FTPdtCode,FTBchCode,FTMerCode,FTShpCode,FTMgpCode) VALUES ('$FTPdtCode','$FTBchCode','$FTMerCode','$FTShpCode','$FTMgpCode')";
        // $oQuery = $this->db->query($tSQL);
        // if($oQuery > 0){
        //     $aResult    =  array(
        //         'rtCode'    => '1',
        //         'rtDesc'    => 'success'
        //     );
        // }else{
        //     $aResult =  array(
        //         'rtCode'	=> '800',
        //         'rtDesc'	=> 'data not found'
        //     );
        // }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    // Functionality: Functio Add/Update Product Set
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update Supplier
	// ReturnType: Array
    public function FSxMPDTAddUpdatePdtSet($paPdtWhere,$paPdtDataAllSet){
        // Delete Product Set All
        $this->db->where_in('FTPdtCode',$paPdtWhere['FTPdtCode']);
        $this->db->delete('TCNTPdtSet');

        if(is_array($paPdtDataAllSet) && !empty($paPdtDataAllSet)){
            // Loop Data Pack Size
            foreach($paPdtDataAllSet['oPdtCodeSetData'] AS $nKey => $aDataPdtSet){
                $aDataPdtSet    = array(
                    'FTPdtCode'     => $paPdtWhere['FTPdtCode'],
                    'FTPdtCodeSet'  => $aDataPdtSet['tPdtCodeSetCode'],
                    'FCPstQty'      => $aDataPdtSet['tPdtCodeSetQty'],
                    'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                    'FDCreateOn'    => date('Y-m-d h:i:s'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                    'FTCreateBy'    => $this->session->userdata('tSesUsername')
                );
                $this->db->insert('TCNTPdtSet',$aDataPdtSet); 
            }
            $aDataStaUpdPdtSet = array(
                'FTPdtSetOrSN'      => 2,
                'FTPdtStaSetPri'    => $paPdtDataAllSet['tPdtStaSetPri'],
                'FTPdtStaSetShwDT'  => $paPdtDataAllSet['tPdtStaSetShwDT'],
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
            $this->db->update('TCNMPdt',$aDataStaUpdPdtSet);
        }else{
            $aDataStaUpdPdtSet = array(
                'FTPdtSetOrSN'      => 1,
                'FTPdtStaSetPri'    => NULL,
                'FTPdtStaSetShwDT'  => NULL,
                'FDLastUpdOn'       => date('Y-m-d h:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
            $this->db->update('TCNMPdt',$aDataStaUpdPdtSet);
        }
        return;
    }

    // Functionality: Functio Add/Update Product Event Not Sale
    // Parameters: function parameters
    // Creator:  18/02/2019 Wasin(Yoshi)
    // Return: Status Add Update Supplier
	// ReturnType: Array
    public function FSxMPDTAddUpdatePdtEvnNosale($paPdtWhere,$ptPdtEvnNotSale){
        if(isset($ptPdtEvnNotSale) && !empty($ptPdtEvnNotSale)){
            $aPdtEvnNoSale  =   array(
                'FTEvnCode'     => $ptPdtEvnNotSale,
                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
			$this->db->update('TCNMPdt',$aPdtEvnNoSale);
        }else{
            $aPdtEvnNoSale  =   array(
                'FTEvnCode'     => NULL,
                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
            $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
			$this->db->update('TCNMPdt',$aPdtEvnNoSale);
        }
        return;
    }


    //  ============================ Edit Modal ============================================================

    // Functionality: Func. Get Data Image By ID Product
    // Parameters: function parameters
    // Creator:  20/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataImgByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $tSQL       = " SELECT 
                            IMGPDT.FNImgID,
                            IMGPDT.FTImgRefID,
                            IMGPDT.FNImgSeq,
                            IMGPDT.FTImgObj
                        FROM TCNMImgPdt IMGPDT
                        WHERE 1=1 
                        AND IMGPDT.FTImgRefID   = '$tPdtCode'
                        AND IMGPDT.FTImgTable   = 'TCNMPdt'
                        AND IMGPDT.FTImgKey     = 'master'
                        ORDER BY IMGPDT.FNImgSeq ASC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Func. Get Data Info By ID Product
    // Parameters: function parameters
    // Creator:  20/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataInfoByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        // PDT.FTBchCode,BCHL.FTBchName,
        // ,PDT.FTShpCode
        // PDT.FTPdtRefShop,MERL.FTMerName, 
        
        //PDT.FTPdtStkCode
        $tSQL       = " SELECT
                            PDT.FTPdtCode,PDTL.FTPdtName,PDTL.FTPdtNameOth,PDTL.FTPdtNameABB,PDT.FTPdtStkControl,PDT.FTPdtGrpControl,PDT.FTPdtForSystem,
                            PDT.FCPdtQtyOrdBuy,PDT.FCPdtCostDef,PDT.FCPdtCostOth,PDT.FCPdtCostStd,PDT.FCPdtMax,PDT.FTPdtPoint,PDT.FCPdtPointTime,PDT.FTPdtType,
                            PDT.FTPdtSaleType,PDT.FTPdtSetOrSN,PDT.FTPdtStaAlwDis,PDT.FTPdtStaAlwReturn,PDT.FTPdtStaVatBuy,PDT.FTPdtStaVat,PDT.FTPdtStaActive,PDT.FTPdtStaAlwReCalOpt,
                            PDT.FTPdtStaCsm,PDT.FTTcgCode,TCGL.FTTcgName,PDT.FTPgpChain,PGPL.FTPgpChainName,
                            PDT.FTPtyCode,PTYL.FTPtyName,PDT.FTPbnCode,PBNL.FTPbnName,PDT.FTPmoCode,PMOL.FTPmoName,PDT.FTVatCode,cast(VAT.FCVatRate as decimal(10,2)) AS FCVatRate,

                            PDT.FTPdtStaSetPri,PDT.FTPdtStaSetShwDT, --Napat(Jame) 13/11/2019
                            PDT.FTPdtType,PDT.FTPdtSaleType, --Napat(Jame) 10/09/2019
                            SPC.FCPdtMin, --Napat(Jame) 17/09/2019 ย้ายจาก TCNMPdt ไป TCNMPdtSpcBch

                            BCHL.FTBchName,MERL.FTMerName,SHPL.FTShpName,
                            BCHL.FTBchCode,MERL.FTMerCode,SHPL.FTShpCode,
                            MPGL.FTMgpCode,MPGL.FTMgpName,

                            CONVERT(CHAR(10),PDT.FDPdtSaleStart,126)    AS FDPdtSaleStart,
                            CONVERT(CHAR(10),PDT.FDPdtSaleStop,126)     AS FDPdtSaleStop,
                            PDTL.FTPdtRmk 
                        FROM TCNMPdt PDT
                        LEFT JOIN TCNMPdt_L         PDTL    ON PDT.FTPdtCode    = PDTL.FTPdtCode    AND PDTL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtTouchGrp_L TCGL    ON PDT.FTTcgCode    = TCGL.FTTcgCode    AND TCGL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtGrp_L      PGPL    ON PDT.FTPgpChain   = PGPL.FTPgpChain   AND PGPL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtType_L     PTYL    ON PDT.FTPtyCode    = PTYL.FTPtyCode    AND PTYL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtBrand_L    PBNL    ON PDT.FTPbnCode    = PBNL.FTPbnCode    AND PBNL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtModel_L    PMOL    ON PDT.FTPmoCode    = PMOL.FTPmoCode    AND PMOL.FNLngID = $nLngID
                        LEFT JOIN TCNMVatRate       VAT     ON PDT.FTVatCode    = VAT.FTVatCode
                        LEFT JOIN TCNMPdtSpcBch     SPC     ON SPC.FTPdtCode    = PDT.FTPdtCode
                        LEFT JOIN TCNMBranch_L      BCHL    ON SPC.FTBchCode    = BCHL.FTBchCode    AND BCHL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerchant_L    MERL    ON SPC.FTMerCode    = MERL.FTMerCode    AND MERL.FNLngID = $nLngID
                        LEFT JOIN TCNMShop_L        SHPL    ON SPC.FTShpCode    = SHPL.FTShpCode  AND  SPC.FTBchCode = SHPL.FTBchCode  AND SHPL.FNLngID = $nLngID
                        LEFT JOIN TCNMMerPdtGrp_L   MPGL    ON SPC.FTMgpCode    = MPGL.FTMgpCode    AND MPGL.FNLngID = $nLngID
                        WHERE 1=1 AND PDT.FTPdtCode = '$tPdtCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTGetDataRentalByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            RNT.FTPdtRentType,
                            RNT.FTPdtStaReqRet,
                            RNT.FTPdtStaPay,
                            RNT.FCPdtDeposit,
                            RNT.FTShpCode,
                            SHPL.FTShpName
                        FROM TRTMPdtRental RNT
                        LEFT JOIN TCNMShop_L SHPL ON RNT.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID = $nLngID
                        WHERE 1=1 AND RNT.FTPdtCode = '$tPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->row_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Func. Get Data PackSize By ID Product
    // Parameters: function parameters
    // Creator:  21/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataPackSizeByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT DISTINCT
                            PPSZ.FTPdtCode,PPSZ.FTPunCode,PUNL.FTPunName,cast(PPSZ.FCPdtUnitFact as decimal(10,2)) AS FCPdtUnitFact,PPSZ.FTPdtGrade,PPSZ.FCPdtWeight,PPSZ.FTClrCode,
                            PCLL.FTClrName,PPSZ.FTPszCode,PSZL.FTPszName,PPSZ.FTPdtUnitDim,PPSZ.FTPdtPkgDim,PPSZ.FTPdtStaAlwPick,
                            PPSZ.FTPdtStaAlwPoHQ,PPSZ.FTPdtStaAlwBuy,PPSZ.FTPdtStaAlwSale,
                            ISNULL(P4PDT.FCPgdPriceRet,0) AS FCPgdPriceRet,
                            ISNULL(P4PDT.FCPgdPriceWhs,0) AS FCPgdPriceWhs,
                            ISNULL(P4PDT.FCPgdPriceNet,0) AS FCPgdPriceNet
                        FROM TCNMPdtPackSize PPSZ
                        LEFT JOIN TCNMPdtUnit_L	PUNL        ON PPSZ.FTPunCode = PUNL.FTPunCode  AND PUNL.FNLngID	= $nLngID
                        LEFT JOIN TCNMPdtColor_L PCLL       ON PPSZ.FTClrCode = PCLL.FTClrCode  AND PCLL.FNLngID = $nLngID
                        LEFT JOIN TCNMPdtSize_L PSZL        ON PPSZ.FTPszCode = PSZL.FTPszCode  AND PSZL.FNLngID = $nLngID
                        LEFT JOIN TCNTPdtPrice4PDT P4PDT	ON PPSZ.FTPdtCode = P4PDT.FTPdtCode AND PPSZ.FTPunCode = P4PDT.FTPunCode 
                        AND ((CONVERT(VARCHAR(19),GETDATE(),103) >= CONVERT(VARCHAR(19),P4PDT.FDPghDStart,103)) AND (CONVERT(VARCHAR(19),GETDATE(),103) <= CONVERT(VARCHAR(19),P4PDT.FDPghDStop,103)))
                        AND P4PDT.FTPghDocType = 1
                        WHERE 1=1 
                        AND PPSZ.FTPdtCode = '$tPdtCode'
                        ORDER BY PPSZ.FTPunCode ASC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Func. Get Data BarCode By ID Product
    // Parameters: function parameters
    // Creator:  21/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataBarCodeByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT DISTINCT
                            PBAR.FTPdtCode,
                            PBAR.FTBarCode,
                            PBAR.FTPunCode,
                            PBAR.FNPldSeq,
                            PBAR.FTPlcCode,
                            PLCL.FTPlcName,
                            PBAR.FTBarStaUse,
                            PBAR.FTBarStaAlwSale
                        FROM TCNMPdtPackSize PPSZ
                        LEFT JOIN TCNMPdtBar PBAR 	ON PPSZ.FTPdtCode = PBAR.FTPdtCode AND PPSZ.FTPunCode = PBAR.FTPunCode
                        LEFT JOIN TCNMPdtLoc_L PLCL	ON PBAR.FTPlcCode = PLCL.FTPlcCode AND PLCL.FNLngID = $nLngID
                        WHERE 1=1 AND PPSZ.FTPdtCode = '$tPdtCode'
                        ORDER BY PBAR.FTPunCode ASC,PBAR.FNPldSeq ASC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Func. Get Data Supplier By ID Product
    // Parameters: function parameters
    // Creator:  21/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataSupplierByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            PPSZ.FTPdtCode,
                            PPSZ.FTPunCode,
                            PBAR.FTBarCode,
                            PSPL.FTSplCode,
                            SPLL.FTSplName,
                            PSPL.FTSplStaAlwPO
                        FROM TCNMPdtPackSize PPSZ
                        LEFT JOIN TCNMPdtBar PBAR ON PPSZ.FTPdtCode = PBAR.FTPdtCode AND PPSZ.FTPunCode = PBAR.FTPunCode
                        LEFT JOIN TCNMPdtSpl PSPL	ON PPSZ.FTPdtCode = PSPL.FTPdtCode AND PBAR.FTBarCode = PSPL.FTBarCode
                        LEFT JOIN TCNMSpl_L  SPLL	ON PSPL.FTSplCode	= SPLL.FTSplCode AND SPLL.FNLngID = $nLngID
                        WHERE 1=1 AND PPSZ.FTPdtCode = '$tPdtCode'
                        ORDER BY PPSZ.FTPunCode ASC , PBAR.FNPldSeq ASC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Func. Get Data Product Set By ID Product
    // Parameters: Array ข้อมูลสินค้าเซ็ท
    // Creator:  22/02/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataPdtSetByID($paDataWhere){
        // $tPdtCode       = $paDataWhere['FTPdtCode'];
        // $nLngID         = $paDataWhere['FNLngID'];
        // $tSQLPdtSetHD   = " SELECT PDT.FTPdtStaSetPri,PDT.FTPdtStaSetShwDT FROM TCNMPdt PDT WHERE PDT.FTPdtCode = '$tPdtCode' ";
        // $tSQLPdtSetDT   = "	SELECT
        //                         PSHD.*,ISNULL(PRI4PDT.FCPgdPriceRet,0) AS FCPgdPriceRet,
        //                         ISNULL(PRI4PDT.FCPgdPriceWhs,0) AS FCPgdPriceWhs,
        //                         ISNULL(PRI4PDT.FCPgdPriceNet,0) AS FCPgdPriceNet
        //                     FROM (
        //                         SELECT PSET.FTPdtCode,PSDT.FTPdtCodeSet,PDTL.FTPdtName AS FTPdtNameSet,PSET.FCPstQty,PSDT.FTPunCode,PUNL.FTPunName,PSDT.FCPdtUnitFact,PSDT.FNPszRank
        //                         FROM(
        //                             SELECT PPS.*
        //                             FROM (
        //                                 SELECT
        //                                     FTPdtCode AS FTPdtCodeSet,
        //                                     FTPunCode,
        //                                     FCPdtUnitFact,
        //                                     DENSE_RANK() OVER (PARTITION BY FTPdtCode ORDER BY FCPdtUnitFact ASC) AS FNPszRank
        //                                 FROM TCNMPdtPackSize ) PPS
        //                             WHERE PPS.FTPdtCodeSet IN( SELECT FTPdtCodeSet FROM TCNTPdtSet WHERE FTPdtcode = '$tPdtCode') AND  PPS.FNPszRank = 1
        //                         ) PSDT
        //                         LEFT JOIN TCNTPdtSet    PSET ON PSDT.FTPdtCodeSet   = PSET.FTPdtCodeSet
        //                         LEFT JOIN TCNMPdt_L     PDTL ON PSDT.FTPdtCodeSet   = PDTL.FTPdtCode AND PDTL.FNLngID   = $nLngID
        //                         LEFT JOIN TCNMPdtUnit_L	PUNL ON PSDT.FTPunCode      = PUNL.FTPunCode AND PUNL.FNLngID   = $nLngID
        //                     ) PSHD
        //                     LEFT JOIN (
        //                         SELECT PRIPACK.*
        //                         FROM (
        //                             SELECT
        //                                 DENSE_RANK() OVER (PARTITION BY P4PDT.FTPdtcode,P4PDT.FTPunCode ORDER BY P4PDT.FDPghDStart DESC) AS FNPszRank,
        //                                 P4PDT.FTPdtCode,
        //                                 P4PDT.FTPunCode,
        //                                 P4PDT.FCPgdPriceNet,
        //                                 P4PDT.FCPgdPriceRet,
        //                                 P4PDT.FCPgdPriceWhs
        //                             FROM TCNTPdtPrice4PDT P4PDT
        //                             WHERE P4PDT.FTPdtCode IN (SELECT FTPdtCodeSet FROM TCNTPdtSet WHERE FTPdtcode = '$tPdtCode')
        //                             AND ((CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),P4PDT.FDPghDStart,121)) AND (CONVERT(VARCHAR(10),GETDATE(),121) <= CONVERT(VARCHAR(10),P4PDT.FDPghDStop,121)))
        //                             AND P4PDT.FTPghDocType = 1
        //                         ) PRIPACK
        //                         WHERE PRIPACK.FNPszRank = 1
        //                     ) PRI4PDT ON PSHD.FTPdtCodeSet = PRI4PDT.FTPdtCode AND PSHD.FTPunCode = PRI4PDT.FTPunCode ";

        // $oQueryPdtHD = $this->db->query($tSQLPdtSetHD);
        // $oQueryPdtDT = $this->db->query($tSQLPdtSetDT);

        // if($oQueryPdtHD->num_rows() > 0 && $oQueryPdtDT->num_rows() > 0){
        //     $aDataPdtHD     = $oQueryPdtHD->result_array();
        //     $aDataPdtDT     = $oQueryPdtDT->result_array();
        //     $aResult    =  array(
        //         'raDataPdtHD'   => $aDataPdtHD,
        //         'raDataPdtDT'   => $aDataPdtDT,
        //         'rtCode'        => '1',
        //         'rtDesc'        => 'success'
        //     );
        // }else{
        //     $aResult =  array(
        //         'rtCode'	=> '800',
        //         'rtDesc'	=> 'data not found'
        //     );
        // }
        // return $aResult;

        $tSQL = "SELECT TOP 1
                    PUN_L.FTPunName,
                    PSET.FTPdtCodeSet,
                    PSET.FCPstQty,
                    PDT_L.FTPdtName,
                    0 AS FCPdtCostStd,
                    (SELECT TOP 1 FCPgdPriceNet FROM VCN_Price4PdtActive WITH(NOLOCK) WHERE FTPdtCode = PSET.FTPdtCodeSet)                  AS FCPgdPriceNet,
                    (SELECT TOP 1 FCPgdPriceNet FROM VCN_Price4PdtActive WITH(NOLOCK) WHERE FTPdtCode = PSET.FTPdtCodeSet) * PSET.FCPstQty  AS tSumPrice
                FROM 
                    TCNTPdtSet PSET WITH(NOLOCK)
                LEFT JOIN TCNMPdt_L         PDT_L WITH(NOLOCK) ON PSET.FTPdtCodeSet = PDT_L.FTPdtCode AND PDT_L.FNLngID = $paDataWhere[FNLngID]
                LEFT JOIN TCNMPdtBar        BAR   WITH(NOLOCK) ON PSET.FTPdtCodeSet = BAR.FTPdtCode
                LEFT JOIN TCNMPdtUnit_L     PUN_L WITH(NOLOCK) ON BAR.FTPunCode = PUN_L.FTPunCode AND PUN_L.FNLngID = $paDataWhere[FNLngID]
                WHERE PSET.FTPdtCode      = '$paDataWhere[FTPdtCode]'
                  AND PSET.FTPdtCodeSet   = '$paDataWhere[FTPdtCodeSet]'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = array(
                'tSQL'      => $tSQL,
                'aItems'	=> $oQuery->result_array(),
                'tCode'	    => '1',
                'tDesc'	    => 'success'
            );
        }else{
            $aResult = array(
                'tSQL'      => $tSQL,
                'tCode'	    => '800',
                'tDesc'	    => 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality: Func. Get Data Product Set By ID Product
    // Parameters: function parameters
    // Creator:  22/02/2019 Wasin(Yoshi)
    // Return: Array Data Image
    // ReturnType: Array
    public function FSaMPDTGetDataEvnNoSaleByID($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            PNSE.FTEvnCode,
                            PNSE.FNEvnSeqNo,
                            PNSE.FTEvnType,
                            PNSE.FTEvnStaAllDay,
                            PNSE.FDEvnDStart,
                            PNSE.FTEvnTStart,
                            PNSE.FDEvnDFinish,
                            PNSE.FTEvnTFinish,
                            PNSE_L.FTEvnName
                        FROM TCNMPdt PDT
                        INNER JOIN TCNMPdtNoSleByEvn     PNSE    ON PDT.FTEvnCode    = PNSE.FTEvnCode
                        INNER JOIN TCNMPdtNoSleByEvn_L   PNSE_L	ON PNSE.FTEvnCode   = PNSE_L.FTEvnCode  AND PNSE_L.FNLngID = $nLngID
                        WHERE PDT.FTPdtCode = '$tPdtCode'
                        ORDER BY PNSE.FNEvnSeqNo ASC ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Delete Product
    // Parameters : function parameters
    // Creator :  30/08/2018 wasin
    // Return : Status Delete Product
	// Return Type : Array
    public function FSaMPdtDeleteAll($paDataDel){
     
        try{
            $this->db->trans_begin();

            // Delete Table PDT
			$this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
			$this->db->delete('TCNMPdt');

            // Delete Table PDT_L
			$this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
			$this->db->delete('TCNMPdt_L');

            // Delete Table PackSize
			$this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
			$this->db->delete('TCNMPdtPackSize');

            // Delete Table BarCode
			$this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtBar');
            
            // Delete Table Rental
			$this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
            $this->db->delete('TRTMPdtRental');

            // Delete Table SpcBch
            $this->db->where_in('FTPdtCode', $paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtSpcBch');

            // Delete Table Set
			$this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
            $this->db->delete('TCNTPdtSet');
            
            // Delete Table Product Supplier
            $this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtSpl');

            // Delete Table Product TCNMPDTDrug
            // Create By WItsarut 21/01/2020
            $this->db->where_in('FTPdtCode',$paDataDel['FTPdtCode']);
            $this->db->delete('TCNMPdtDrug');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
					'rtCode' => '500',
					'rtDesc' => 'Error Cannot Delete Product.',
				);
            }else{
                $this->db->trans_commit();
				$aStatus = array(
					'rtCode' => '1',
					'rtDesc' => 'Delete Product Success.',
				);
            }
        }catch(Exception $Error){
            $aStatus = array(
                'rtCode' => '500',
                'rtDesc' => $Error->getMessage()
            );
        }
        return $aStatus;
    }

    public function FSaMPDTGetDataTableBarCodeByID($paDataWhere){
        $FTMttTableKey   = $paDataWhere['FTMttTableKey'];
        $FTMttRefKey     = $paDataWhere['FTMttRefKey'];
        $FTPdtCode       = $paDataWhere['FTPdtCode'];
        $FTPunCode       = $paDataWhere['FTPunCode'];
        $FTMttSessionID  = $paDataWhere['FTMttSessionID'];
        $tSQL       = "SELECT 
                            FTPdtCode,
                            FTBarCode,
                            FTPunCode,
                            FTPlcCode,
                            FTPlcName,
                            FTSplCode,
                            FTSplName,
                            FTSplStaAlwPO,
                            FTBarStaUse,
                            FTBarStaAlwSale
                        FROM TsysMasTmp
                        WHERE FTMttTableKey = '$FTMttTableKey' 
                        AND FTMttRefKey     = '$FTMttRefKey' 
                        AND FTPdtCode       = '$FTPdtCode' 
                        AND FTPunCode       = '$FTPunCode' 
                        AND FTMttSessionID  = '$FTMttSessionID'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aResult    =  array(
                'raItems'   => $aDataQuery,
                'rtCode'    => '1',
                'rtDesc'    => 'success'
            );
        }else{
            $aResult =  array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSxMPDTDeleteBarCode($paDataDel){
        // Delete Table PDT
        $this->db->where('FTMttTableKey',$paDataDel['FTMttTableKey']);
        $this->db->where('FTMttRefKey',$paDataDel['FTMttRefKey']);
        $this->db->where('FTPdtCode',$paDataDel['FTPdtCode']);
        $this->db->where('FTBarCode',$paDataDel['FTBarCode']);
        $this->db->where('FTPunCode',$paDataDel['FTPunCode']);
        $this->db->where('FTMttSessionID',$paDataDel['FTMttSessionID']);
        $this->db->delete('TsysMasTmp');
    }

    public function FSaMPDTCheckMasTempDuplicate($paData){
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $FTPunCode      = $paData['FTPunCode'];
        $FTMttSessionID = $paData['FTMttSessionID'];

        $tSQL           = "SELECT * FROM TsysMasTmp WHERE FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTPdtCode='$FTPdtCode' AND FTPunCode='$FTPunCode' AND FTMttSessionID='$FTMttSessionID'";
        $oQuery         = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = array(
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    public function FSaMPDTAddPackSizeByIDMasTemp($paDataAdd){
        $this->db->insert('TsysMasTmp',$paDataAdd);
        if($this->db->affected_rows() > 0){
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Add Product Success',
            );
        }else{
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error Cannot Add/Update Product.',
            );
        }
        return $aStatus;
    }

    public function FSaMPDTUpdatePackSizeByIDMasTemp($paPdtWhere,$paDataUpdate){
        $this->db->where('FTMttTableKey',$paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttRefKey',$paPdtWhere['FTMttRefKey']);
        $this->db->where('FTMttSessionID',$paPdtWhere['FTMttSessionID']);
        $this->db->where('FTPdtCode',$paPdtWhere['FTPdtCode']);
        $this->db->where('FTPunCode',$paPdtWhere['FTPunCode']);
        $this->db->update('TsysMasTmp',$paDataUpdate);
        if($this->db->affected_rows() > 0){
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update Product Success',
            );
        }else{
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update Product',
            );
        }
        return $aStatus;
    }

    public function FSxMPDTUpdatePdtCodeMasTmp($paPdtWhere,$paPdtUpdate){
        $this->db->where('FTMttTableKey',$paPdtWhere['FTMttTableKey']);
        $this->db->where('FTMttSessionID',$paPdtWhere['FTMttSessionID']);
        $this->db->update('TsysMasTmp',$paPdtUpdate);
        if($this->db->affected_rows() > 0){
            $aStatus    = array(
                'rtCode' => '1',
                'rtDesc' => 'Update FTPdtCode Table MasTmp Success',
            );
        }else{
            $aStatus    = array(
                'rtCode' => '800',
                'rtDesc' => 'Error Update FTPdtCode Table MasTmp',
            );
        }
        return $aStatus;
    }

    public function FSxMPDTAddUpdateRental($paDataAddUpdateRental){
        $FTPdtCode = $paDataAddUpdateRental['FTPdtCode'];
        $tSQL   =   "SELECT FTPdtCode
					 FROM TRTMPdtRental WITH (NOLOCK)
                     WHERE FTPdtCode = '$FTPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $this->db->where('FTPdtCode',$FTPdtCode);
            $this->db->update('TRTMPdtRental',$paDataAddUpdateRental);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update TRTMPdtRental Success',
                );
            }else{
                $aStatus    = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Error Update TRTMPdtRental',
                );
            }
        }else{
            $this->db->insert('TRTMPdtRental',$paDataAddUpdateRental);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            }else{
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    public function FSxMPDTAutoAddBarCodeAndUnit($paData){
        $tSQL   =   "SELECT TOP 1 FTPunCode FROM TCNMPdtUnit WITH (NOLOCK) ORDER BY FTPunCode ASC";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataQuery = $oQuery->result_array();
            $aDataAddPackSize = array(
                'FTPdtCode'     => $paData['FTPdtCode'],
                'FTPunCode'     => $aDataQuery[0]['FTPunCode'],
                'FCPdtUnitFact' => 1,
                'FCPdtWeight'   => 0
            );
            $aDataAddBarCode = array(
                'FTPdtCode'         => $paData['FTPdtCode'],
                'FTBarCode'         => $paData['FTPdtCode'],
                'FTPunCode'         => $aDataQuery[0]['FTPunCode'],
                'FTBarStaUse'       => '1',
                'FTBarStaAlwSale'   => '1'
            );

            $this->db->insert('TCNMPdtPackSize',$aDataAddPackSize);
            $this->db->insert('TCNMPdtBar',$aDataAddBarCode);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Success',
                );
            }else{
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error FSxMPDTAutoAddBarCodeAndUnit',
                );
            }
        
        }else{
            $aStatus    = array(
                'rtCode' => '801',
                'rtDesc' => 'Error FSxMPDTAutoAddBarCodeAndUnit',
            );
        }
        return $aStatus;
    }


    //get VatRate Company
    public function FSaMPDTGetVatRateCpn(){
        $tSQL   = "SELECT 
                        EMP.FTVatCode,
                        cast(EVT.FCVatRate as decimal(10,2)) AS FCVatRate,
                        EVT.FDVatStart
                from TCNMComp EMP
                LEFT JOIN TCNMVatRate EVT ON EMP.FTVatCode = EVT.FTVatCode
                WHERE 1=1 
                AND CONVERT(VARCHAR(10),GETDATE(),121) >= CONVERT(VARCHAR(10),EVT.FDVatStart,121)
                ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDetail = $oQuery->result_array();
            $aResult = array(
                'raItems'	=> $aDetail,
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    // Functionality : Get Data History In PI 
    // Parameters : function parameters
    // Creator :  16/09/2019 wasin(Yoshi)
    // Return : Array Data History
	// Return Type : Array
    public function FSaMPDTGetDataHistoryPI($paDataWhere){
        $tPdtCode   = $paDataWhere['FTPdtCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            THPIHD.FTXphDocNo,
                            CONVERT(VARCHAR(10),THPIHD.FDXphDocDate,121) AS FDXphDocDate,
                            THPIDT.FTPdtCode,
                            THPIDT.FTXpdPdtName,
                            SPL.FTSplCode,
                            SPL_L.FTSplName,
                            THPIHD.FTXphRefExt,
                            THPIDT.FTPunCode,
                            THPIDT.FTPunName,
                            THPIDT.FCXpdQty,
                            THPIDT.FCXpdQtyAll,
                            THPIDT.FCXpdAmtB4DisChg,
                            THPIDT.FCXpdDis,
                            THPIDT.FCXpdChg,
                            THPIDT.FCXpdNet,
                            THPIDT.FCXpdNetAfHD
                        FROM TAPTPiDT       THPIDT  WITH(NOLOCK)
                        LEFT JOIN TAPTPiHD  THPIHD  WITH(NOLOCK) ON THPIDT.FTBchCode    = THPIHD.FTBchCode  AND THPIDT.FTXphDocNo = THPIHD.FTXphDocNo
                        LEFT JOIN TCNMSpl   SPL     WITH(NOLOCK) ON THPIHD.FTSplCode    = SPL.FTSplCode
                        LEFT JOIN TCNMSpl_L SPL_L   WITH(NOLOCK) ON SPL.FTSplCode       = SPL_L.FTSplCode   AND SPL_L.FNLngID = '$nLngID'
                        WHERE 1=1
                        AND THPIDT.FTPdtCode  = '$tPdtCode'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataReturn    = $oQuery->result_array();
        }else{
            $aDataReturn    = array();
        }
        return $aDataReturn;
    }

    //Functionality : get all row 
    //Parameters : -
    //Creator : 18/09/2019 saharat(Golf)
    //Return : array result from db
    //Return Type : array
    public function FSnMPdtGetAllNumRow($ptData){
        $tPdtForSystem          = $ptData['tPdtForSystem'];
        $tLangEdit              = $this->session->userdata('tLangEdit'); 

        $tSQL   = "	SELECT ROW_NUMBER() OVER(ORDER BY PDT.FTPdtCode ASC) AS FNAllNumRow,
                PDT.FTPdtCode
                FROM TCNMPdt PDT WITH(NOLOCK)
                LEFT JOIN TCNMPdtSpcBch     PDLSPC  WITH(NOLOCK)    ON PDT.FTPdtCode    = PDLSPC.FTPdtCode
                LEFT JOIN TCNMPdt_L         PDTL    WITH(NOLOCK)    ON PDT.FTPdtCode    = PDTL.FTPdtCode    AND PDTL.FNLngID    = '$tLangEdit'
                LEFT JOIN TCNMPdtPackSize   PPCZ    WITH(NOLOCK)    ON PDT.FTPdtCode    = PPCZ.FTPdtCode 
                LEFT JOIN TCNMPdtBar        PBAR    WITH(NOLOCK)    ON PDT.FTPdtCode    = PBAR.FTPdtCode    AND PPCZ.FTPunCode  = PBAR.FTPunCode 
                LEFT JOIN TCNMImgPdt        PIMG    WITH(NOLOCK)    ON PDT.FTPdtCode    = PIMG.FTImgRefID   AND PIMG.FTImgTable = 'TCNMPdt' AND PIMG.FNImgSeq = '$tLangEdit'
                LEFT JOIN TCNMPdtType_L     PTL     WITH(NOLOCK)    ON PDT.FTPdtType    = PTL.FTPtyCode     AND PTL.FNLngID     = '$tLangEdit'
                LEFT JOIN TCNMPdtUnit_L     PUNL    WITH(NOLOCK)    ON PPCZ.FTPunCode   = PUNL.FTPunCode    AND PUNL.FNLngID    = '$tLangEdit'
                LEFT JOIN TCNMPdtGrp_L      PGL     WITH(NOLOCK)    ON PGL.FTPgpChain   = PDT.FTPgpChain
                WHERE 1=1 
                AND PDT.FTPdtForSystem = '$tPdtForSystem' ";

            /* |-------------------------------------------------------------------------------------------| */
            /* |                            สิทธิในการมองเห็นสินค้า CR.wat                                      | */
            /* |-------------------------------------------------------------------------------------------| */
            /* | */     $tSesUsrLevel           = $this->session->userdata('tSesUsrLevel');             // | */ 
            /* | */     $tSessionMerCode        = $this->session->userdata('tSesUsrMerCode');           // | */ 
            /* | */     $tSessionShopCode       = $this->session->userdata('tSesUsrShpCode');           // | */ 
            /* | */     $tSessionBchCode        = $this->session->userdata('tSesUsrBchCode');           // | */  
            /* | */                                                                                     // | */ 
            /* | */     //PERMISSION BCH    : ต้องเห็นสินค้าที่ผูกสาขา และสินค้าที่ไม่ผูกอะไรเลย(HQ)                // | */ 
            /* | */     if($tSesUsrLevel == 'BCH'){                                                     // | */ 
            /* | */         $tSQL   .= " AND ISNULL(PDLSPC.FTBchCode,'') ";                             // | */ 
            /* | */         $tSQL   .= "  IN ('','$tSessionBchCode') ";                                 // | */ 
            /* | */     }                                                                               // | */ 
            /* | */                                                                                     // | */ 
            /* | */     //PERMISSION SHP    : ต้องเห็นสินค้าระดับร้านค้า และ สินค้าของกลุ่มธุรกิจที่ไม่ได้ผูกร้านค้า       // | */ 
            /* | */     if($tSesUsrLevel == 'SHP'){                                                     // | */ 
            /* | */         $tSQL  .= " AND ( PDLSPC.FTBchCode = '$tSessionBchCode' )";                 // | */ 
            /* | */         $tSQL  .= " AND ";                                                          // | */ 
            /* | */         $tSQL  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";                  // | */ 
            /* | */         $tSQL  .= " ISNULL(PDLSPC.FTShpCode,'') = '' ) ";                           // | */ 
            /* | */         $tSQL  .= " OR ";                                                           // | */ 
            /* | */         $tSQL  .= " ( PDLSPC.FTMerCode = '$tSessionMerCode' AND ";                  // | */ 
            /* | */         $tSQL  .= " PDLSPC.FTShpCode = '$tSessionShopCode' )";                      // | */ 
            /* | */     }                                                                               // | */             
            /* |-------------------------------------------------------------------------------------------| */
            
            // Where Product Branch
            // $tWherePdtBranch    = "";
            // $tSessionBchCode    = $this->session->userdata('tSesUsrBchCode');
            // if(isset($tSessionBchCode) && !empty($tSessionBchCode)){
            // $tSQL    .= " AND PDLSPC.FTBchCode = '$tSessionBchCode' ";
            // }

            // // Where Product Merchant
            // $tWherePdtMerChant  = "";
            // $tSessionMerCode    = $this->session->userdata('tSesUsrMerCode');
            // if(isset($tSessionMerCode) && !empty($tSessionMerCode)){
            // $tSQL    .= " AND PDLSPC.FTMerCode = '$tSessionMerCode' ";
            // }

            // // Where Product Shop
            // $tWherePdtShop      = "";
            // $tSessionShopCode   = $this->session->userdata('tSesUsrShpCode');
            // if(isset($tSessionShopCode) && !empty($tSessionShopCode)){
            // $tSQL  .= " AND PDLSPC.FTShpCode = '$tSessionShopCode' ";
            // }

            $tSQL   .= " GROUP BY PDT.FTPdtCode";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aDetail = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $oQuery->num_rows(),
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Add Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtSet($paPdtSetData,$paPdtWhere){
        // Update TCNMPdt
        $aDataUpdate    = array_merge($paPdtSetData,array(
            'FDLastUpdOn'   => date('Y-m-d h:i:s'),
            'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
        ));
        $this->db->where('FTPdtCode', $paPdtWhere['FTPdtCode']);
        $this->db->where('FTPdtCodeSet', $paPdtWhere['FTPdtCodeSet']);
        $this->db->update('TCNTPdtSet',$aDataUpdate);
        if($this->db->affected_rows() > 0){
            $aStatus    = array(
                'tCode' => '1',
                'tDesc' => 'Update Product Set Success',
            );
        }else{
            // Add TCNMPdt
            $aDataInsert = array_merge($paPdtWhere,$paPdtSetData,array(
                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d h:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername')
            ));
            $this->db->insert('TCNTPdtSet',$aDataInsert);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'tCode' => '1',
                    'tDesc' => 'Add Product Set Success',
                );
            }else{
                $aStatus    = array(
                    'tCode' => '801',
                    'tDesc' => 'Error Cannot Add/Update Product Set.',
                );
            }
        }
        return $aStatus;
    }

    //Functionality : Update Status Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtStaSet($aPdtSetWhere){
        $tSQL       = "SELECT FTPdtCode FROM TCNTPdtSet WHERE FTPdtCode='$aPdtSetWhere[FTPdtCode]'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataUpdate    = array(
                'FTPdtSetOrSN'  => '2',
                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
        }else{
            $aDataUpdate    = array(
                'FTPdtSetOrSN'  => '1',
                'FDLastUpdOn'   => date('Y-m-d h:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername')
            );
        }

        $this->db->where('FTPdtCode',$aPdtSetWhere['FTPdtCode']);
        $this->db->update('TCNMPdt',$aDataUpdate);
        if($this->db->affected_rows() > 0){
            $aDataReturn    = array(
                'tCode' => '1',
                'tDesc' => 'Update product set success',
            );
        }else{
            $aDataReturn    = array(
                'tCode' => '801',
                'tDesc' => 'Eoor update product set',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Delete Product Set
    //Parameters : -
    //Creator : 08/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTDelPdtSet($paDataDel){
        $this->db->where('FTPdtCode',$paDataDel['FTPdtCode']);
        $this->db->where('FTPdtCodeSet', $paDataDel['FTPdtCodeSet']);
        $this->db->delete('TCNTPdtSet');
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $aStatus = array(
                'tCode' => '500',
                'tDesc' => 'Error Cannot Delete Product.',
            );
        }else{
            $this->db->trans_commit();
            $aStatus = array(
                'tCode' => '1',
                'tDesc' => 'Delete Product Success.',
            );
        }
        return $aStatus;
    }

    //Functionality : Update FTPdtStaSetPri
    //Parameters : FTPdtCode,FTPdtStaSetPri
    //Creator : 13/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtSetPri($paDataUpd,$paDataWhere){
        $this->db->where('FTPdtCode',$paDataWhere['FTPdtCode']);
        $this->db->update('TCNMPdt',$paDataUpd);
        if($this->db->affected_rows() > 0){
            $aDataReturn    = array(
                'tCode' => '1',
                'tDesc' => 'Update product set success',
            );
        }else{
            $aDataReturn    = array(
                'tCode' => '801',
                'tDesc' => 'Eoor update product set',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Update FTPdtStaSetShwDT
    //Parameters : FTPdtCode,FTPdtStaSetShwDT
    //Creator : 13/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTUpdPdtStaSetShwDT($paDataUpd,$paDataWhere){
        $this->db->where('FTPdtCode',$paDataWhere['FTPdtCode']);
        $this->db->update('TCNMPdt',$paDataUpd);
        if($this->db->affected_rows() > 0){
            $aDataReturn    = array(
                'tCode' => '1',
                'tDesc' => 'Update product set success',
            );
        }else{
            $aDataReturn    = array(
                'tCode' => '801',
                'tDesc' => 'Eoor update product set',
            );
        }
        return $aDataReturn;
    }

    //Functionality : Get Data Other product from TCNTPdtSet
    //Parameters : -
    //Creator : 14/11/2019 Napat(Jame)
    //Return : status
    //Return Type : array
    public function FSaMPDTGetOthPdt(){
        $tSQL = "SELECT FTPdtCode FROM TCNTPdtSet WITH(NOLOCK) GROUP BY FTPdtCode";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataReturn    = array(
                'aItems'    => $oQuery->result_array(),
                'tCode'     => '1',
                'tDesc'     => 'Select success',
            );
        }else{
            $aDataReturn    = array(
                'aItems'    => array(),
                'tCode'     => '801',
                'tDesc'     => 'Eoor select',
            );
        }
        return $aDataReturn;
    }
    /*
    //Functionality :GetData กำหนดเงื่อนไขการควบคุมสต๊อก
    //Parameters : -
    //Creator : 20/1/2020 nonapwich(petch)
    //Return : status
    //Return Type : object
    */
    public function FSaMPDTGetDataPdtSpcWah($paDataWhereSpcWah){
        
        // $FTPdtCode      = $paDataWhereSpcWah['FTPdtCode'];

        $tSQL = "SELECT   Temp.FTPdtCode AS TmpFTPdtCode,
        Temp.FTBchCode AS TmpFTBchCode,
        Temp.FTWahCode AS TmpFTWahCode,
        Temp.FCSpwQtyMin AS TmpFCSpwQtyMin,
        Temp.FCSpwQtyMax AS TmpFCSpwQtyMax,
        Temp.FTSpwRmk AS TmpFTSpwRmk FROM  TsysMasTmp Temp   
        "  ;

        $oQuery = $this->db->query($tSQL);
        return $oQuery->result_array(); 

    }
  
    public function FSaMPDTChkChainPdtSet($paDataWhere){
        $tSQL = "SELECT 
                    PSET.FTPdtCode,
                    PDT_L.FTPdtName
                 FROM TCNTPdtSet PSET WITH(NOLOCK) 
                 LEFT JOIN TCNMPdt_L PDT_L ON PSET.FTPdtCode = PDT_L.FTPdtCode AND PDT_L.FNLngID = $paDataWhere[FNLngID]
                 WHERE PSET.FTPdtCodeSet = '$paDataWhere[FTPdtCode]'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aDataReturn    = array(
                'aItems'    => $oQuery->result_array(),
                'tCode'     => '1',
                'tDesc'     => 'Select success',
            );
        }else{
            $aDataReturn    = array(
                'aItems'    => array(),
                'tCode'     => '801',
                'tDesc'     => 'Eoor select',
            );
        }
        return $aDataReturn;
    }

    /*
    //Functionality :  insert into  กำหนดเงื่อนไขการควบคุมสต๊อก
    //Parameters : -
    //Creator : 16/1/2020
    //Return : status
    //Return Type : array
    */
    public function FSaMPDTSpcWahInsertData($paData){
        
        $FTPdtCode      = $paData['FTPdtCode'];

        $tSQL    = "INSERT INTO TsysMasTmp Temp ()
        SELECT 
         
            Temp.FTPdtCode,
            Temp.FTBchCode,
            Temp.FTWahCode,
            Temp.FCSpwQtyMin,
            Temp.FCSpwQtyMax,
            Temp.FTSpwRmk
         
      
        WHERE Temp.FTPdtCode = '$FTPdtCode'";

        $oQuery         = $this->db->query($tSQL);
        if($oQuery > 0){
            $aResult = array(
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

     /*
    //Functionality : del
    //Parameters : -
    //Creator : 16/1/2020
    //Return : status
    //Return Type : array
    */
    public function FSaMPDTDelPdtSpcWah($paDataDel){

        $this->db->where('FTPdtCode',$paDataDel['FTPdtCode']);
        $this->db->where('FTBchCode', $paDataDel['FTBchCode']);
        $this->db->where('FTWahCode', $paDataDel['FTWahCode']);
        $this->db->delete('TsysMasTmp');

    }

    /*
    //Functionality : update pdtSpcWah
    //Parameters : -
    //Creator : 16/1/2020
    //Return : status
    //Return Type : array
    */

    public function FSaMPDTUpdatePdtSpcWah($paDataAddUpdatePdtSpcWah){

        $FTPdtCode = $paDataAddUpdatePdtSpcWah['FTPdtCode'];
        $FTBchCode = $paDataAddUpdatePdtSpcWah['FTBchCode'];
        $FTWahCode = $paDataAddUpdatePdtSpcWah['FTWahCode'];
        $tSQL   =   "SELECT FTPdtCode , FTBchCode , FTWahCode ,FCSpwQtyMin ,FCSpwQtyMax ,FTSpwRmk
                        FROM TsysMasTmp WITH (NOLOCK)
                        WHERE FTPdtCode = '$FTPdtCode'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $this->db->where('FTPdtCode',$FTPdtCode);
            $this->db->update('TsysMasTmp',$paDataAddUpdatePdtSpcWah);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update TsysMasTmp Success',
                );
            }else{
                $aStatus    = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Error Update TsysMasTmp',
                );
            }
        }else{
            $this->db->insert('TsysMasTmp',$paDataAddUpdatePdtSpcWah);
            if($this->db->affected_rows() > 0){
                $aStatus    = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Add Product Success',
                );
            }else{
                $aStatus    = array(
                    'rtCode' => '801',
                    'rtDesc' => 'Error Cannot Add/Update Product.',
                );
            }
        }
        return $aStatus;
    }

    //Functionality : Update&insert TsysMasTmp
    //Parameters : function parameters
    //Creator : 23/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsAddEditTemp($paData){
        try{    
            //Update Master
                $this->db->set('FTMttSessionID' , $paData['FTMttSessionID']);
                $this->db->set('FTPdtCode' , $paData['FTPdtCode']);
                $this->db->set('FTBchCode' , $paData['FTBchCode']);
                $this->db->set('FTWahCode' , $paData['FTWahCode']);
                $this->db->set('FCSpwQtyMin' , $paData['FCSpwQtyMin']);
                $this->db->set('FCSpwQtyMax' , $paData['FCSpwQtyMax']);
                $this->db->set('FTSpwRmk' , $paData['FTSpwRmk']);

                $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
                $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);

                $this->db->where('FTBchCode', $paData['FTBchCode']);
                $this->db->where('FTWahCode', $paData['FTWahCode']);
                $this->db->where('FTPdtCode', $paData['FTPdtCode']);
                $this->db->update('TsysMasTmp');
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Update Master Success',
                    );
            }else{
            //Add Master
              $this->db->insert('TsysMasTmp',array(
                    'FTMttTableKey'  => $paData['FTMttTableKey'],
                    'FTMttSessionID' => $paData['FTMttSessionID'],
                    'FTPdtCode'      => $paData['FTPdtCode'],
                    'FTBchCode'      => $paData['FTBchCode'],
                    'FTWahCode'      => $paData['FTWahCode'],
                    'FCSpwQtyMin'    => $paData['FCSpwQtyMin'],
                    'FCSpwQtyMax'    => $paData['FCSpwQtyMax'],
                    'FTSpwRmk'       => $paData['FTSpwRmk'],

                    'FDLastUpdOn'   => $paData['FDLastUpdOn'],
                    'FDCreateOn'    => $paData['FDCreateOn'],
                    'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                    'FTCreateBy'    => $paData['FTCreateBy'],
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

    //Functionality : List Data  StockConditions 
    //Parameters : function parameters
    //Creator : 21/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tPdtCode       = $paData['FTPdtCode'];
        $tSessionID     = $paData['FTMttSessionID'];
        $nLngID         = $paData['FNLngID'];
        $tTableKey      = $paData['FTMttTableKey'];

        $tSQL   = "SELECT c.* FROM( SELECT  ROW_NUMBER() OVER(ORDER BY FTBchCode ASC) AS FNRowID,* FROM
                  (SELECT  DISTINCT
                         TMT.FTPdtCode,
                         TMT.FTWahCode,
                         TMT.FTBchCode,
                         TMT.FCSpwQtyMin,
                         TMT.FCSpwQtyMax,
                         TMT.FTMttTableKey,
                         PDTL.FTPdtName,
                         BCHL.FTBchName,
                         WAHL.FTWahName
                
                        FROM TsysMasTmp TMT
                        LEFT JOIN TCNMPdt_L PDTL     ON TMT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMBranch_L BCHL  ON TMT.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMWaHouse_L WAHL ON TMT.FTWahCode = WAHL.FTWahCode AND TMT.FTBchCode = WAHL.FTBchCode  AND WAHL.FNLngID   =  $nLngID 
                        WHERE 1=1 
                        AND TMT.FTPdtCode       = '$tPdtCode'
                        AND TMT.FTMttSessionID  = '$tSessionID'
                        AND TMT.FTMttTableKey   = '$tTableKey' ";


        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
   
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMPDTStockConditionsGetPageAll($nLngID);
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

    //Functionality : All Page Of StockConditions
    //Parameters : function parameters
    //Creator :  23/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMPDTStockConditionsGetPageAll($paData){
        $nLngID   = $paData;
        $tSQL = "SELECT COUNT (TMT.FTMttTableKey) AS counts
                 FROM TsysMasTmp TMT
                        LEFT JOIN TCNMPdt_L PDTL     ON TMT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMBranch_L BCHL  ON TMT.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMWaHouse_L WAHL ON TMT.FTWahCode = WAHL.FTWahCode  AND TMT.FTBchCode = WAHL.FTBchCode  AND WAHL.FNLngID   =  $nLngID 
                 WHERE 1=1 ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    //Functionality : ดึงข้อมูล StockConditions ไปแก้ไข 
    //Parameters : function parameters
    //Creator : 23/01/2020 saharat(Golโ)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsGetDataByID($paData){
        $tPdtCode   = $paData['FTPdtCode'];
        $tBchCode   = $paData['FTBchCode'];
        $tWahCode   = $paData['FTWahCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT 
                         TMT.FTPdtCode,
                         TMT.FTWahCode,
                         TMT.FTBchCode,
                         TMT.FCSpwQtyMin,
                         TMT.FCSpwQtyMax,
                         TMT.FTMttTableKey,
                         TMT.FTSpwRmk,
                         PDTL.FTPdtName,
                         BCHL.FTBchName,
                         WAHL.FTWahName
                
                        FROM TsysMasTmp TMT
                        LEFT JOIN TCNMPdt_L PDTL     ON TMT.FTPdtCode = PDTL.FTPdtCode  AND PDTL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMBranch_L BCHL  ON TMT.FTBchCode = BCHL.FTBchCode  AND BCHL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMWaHouse_L WAHL ON TMT.FTWahCode = WAHL.FTWahCode  AND TMT.FTBchCode = WAHL.FTBchCode  AND WAHL.FNLngID   =  $nLngID 
                        WHERE 1=1 
                        AND TMT.FTPdtCode   = '$tPdtCode'
                        AND TMT.FTBchCode   = '$tBchCode'
                        AND TMT.FTWahCode   = '$tWahCode'
                    ";

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

    // Functionality : ลบข้อมูลในตาราง TsysMasTmp
    // Parameters : function parameters
    // Creator : 23/01/2020 Saharat(GolF)
    // Return : Array
	// Return Type : array
    public function FSaMPDTStockConditionsDel($paData){

        $this->db->where_in('FTPdtCode', $paData['FTPdtCode']);
        $this->db->where_in('FTBchCode', $paData['FTBchCode']);
        $this->db->where_in('FTWahCode', $paData['FTWahCode']);
        $this->db->delete('TsysMasTmp');
 
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

    //Functionality : บันทึกข้อมูลงตาราง  TCNMPdtSpcWah
    //Parameters : function parameters
    //Creator : 24/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsAddEdit($ptPdtCode){
        try{
            $tPdtCode = $ptPdtCode;
            // delete TCNMPdtSpcWah
            $this->db->where('FTPdtCode', $tPdtCode);
            $this->db->delete('TCNMPdtSpcWah');

            //Add Master

            $tSQL  ="INSERT INTO TCNMPdtSpcWah (FTPdtCode, FTBchCode, FTWahCode, FCSpwQtyMin, FCSpwQtyMax, FTSpwRmk)
                    SELECT FTPdtCode, FTBchCode, FTWahCode, FCSpwQtyMin, FCSpwQtyMax, FTSpwRmk
                    FROM TsysMasTmp
                    WHERE FTPdtCode     = '$tPdtCode'
                    AND   FTMttTableKey = 'TCNMPdtSpcWah' 
                ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery){
                // ลบข้อมูลในตาราง Tmp
                $this->db->where('FTPdtCode', $tPdtCode);
                $this->db->delete('TsysMasTmp');

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
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : บันทึกข้อมูลงตาราง  TsysMasTmp
    //Parameters : function parameters
    //Creator : 24/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsGetDataList($ptData){
        try{

            $this->db->where_in('FTPdtCode', $ptData['FTPdtCode']);
            $this->db->delete('TsysMasTmp');
            $tPdtCode = $ptData['FTPdtCode'];
            //Add Master
            $tSQL  ="INSERT INTO  TsysMasTmp (
                    FTPdtCode, 
                    FTBchCode, 
                    FTWahCode, 
                    FCSpwQtyMin, 
                    FCSpwQtyMax,
                    FTSpwRmk,
                    FTMttTableKey,
                    FTMttSessionID
                    )
                    SELECT 
                    FTPdtCode,
                    FTBchCode, 
                    FTWahCode,
                    FCSpwQtyMin, 
                    FCSpwQtyMax, 
                    FTSpwRmk , 
                    'TCNMPdtSpcWah' AS TCNMPdtSpcWah,
                    '".$ptData['FTMttSessionID']."' AS FTMttSessionID
                    FROM TCNMPdtSpcWah
                    WHERE FTPdtCode     = '$tPdtCode'
                ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery){
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
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : ดึงข้อมูล StockConditions ไปแก้ไข 
    //Parameters : function parameters
    //Creator : 23/01/2020 saharat(GolF)
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTStockConditionsCheckBchWah($paData){
        $tPdtCode   = $paData['FTPdtCode'];
        $tBchCode   = $paData['FTBchCode'];
        $tWahCode   = $paData['FTWahCode'];
        $tSQL       = " SELECT 
                        TMT.FTPdtCode
                        FROM TsysMasTmp TMT
                        WHERE 1=1  
                        AND TMT.FTPdtCode   = '$tPdtCode'
                        AND TMT.FTBchCode   = '$tBchCode'
                        AND TMT.FTWahCode   = '$tWahCode'
                    ";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    //Functionality : เพิ่มสีแทนรูป
    //Parameters : function parameters
    //Creator : 19/03/2020 Saharat(Golf)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMPDTAddUpdateImgObj($paData){ 
        try{  

            $this->db->where_in('FTImgTable', $paData['tImgTable']);
            $this->db->where_in('FTImgRefID', $paData['tImgRefID']);
            $this->db->delete('TCNMImgPdt');
            if($this->db->affected_rows() > 0){
            if(file_exists('application/modules/'.$paData['tModuleName'].'/assets/systemimg/'.$paData['tImgFolder'])){
                $files	= glob('application/modules/'.$paData['tModuleName'].'/assets/systemimg/'.$paData['tImgFolder']."/".$paData['tImgRefID']."/*"); // get all file names
                foreach($files as $file){ // iterate files
                    if(is_file($file))
                        unlink($file); // delete file
                    }
                }
            }
            //Add Master
            $this->db->insert('TCNMImgPdt',array(
                'FTImgRefID'        => $paData['tImgRefID'],
                'FTImgTable'        => $paData['tImgTable'],
                'FTImgObj'          => $paData['tImgObj'],
                'FNImgSeq'          => 1,
                'FTImgKey'          => $paData['tImgKey'],
                'FDLastUpdOn'       => $paData['FDLastUpdOn'],
                'FDCreateOn'        => $paData['FDCreateOn'],
                'FTLastUpdBy'       => $paData['FTLastUpdBy'],
                'FTCreateBy'        => $paData['FTCreateBy'],
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
        return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    // Functionality : เคลียร์ข้อมูลในตาราง TsysMasTmp
    // Parameters : function parameters
    // Creator : 16/11/2022 [IcePun]
    // Return : Array
	// Return Type : array
    public function FSaMPDTZoneConditionsDelTmp($paData){

        $this->db->where_in('FTMttTableKey', $paData['FTMttTableKey']);
        $this->db->where_in('FTMttRefKey', $paData['FTMttRefKey']);
        $this->db->where_in('FTMttSessionID', $paData['FTMttSessionID']);
        $this->db->delete('TsysMasTmp');
 
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

    //Functionality : List Data  Zone Conditions 
    //Parameters : function parameters
    //Creator : 15/12/2022 [IcePun]
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTZoneConditionsList($paData){

        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tPdtCode       = $paData['FTPdtCode'];
        $tSessionID     = $paData['FTMttSessionID'];
        $nLngID         = $paData['FNLngID'];
        $tTableKey      = $paData['FTMttTableKey'];
        $tRefKey        = $paData['FTMttRefKey'];
        $nLngID         = $paData['FNLngID'];

        $tSQL   = "SELECT c.* FROM
                    ( SELECT  ROW_NUMBER() OVER(ORDER BY FTZneCode ASC) AS FNRowID,* FROM
                        (SELECT  DISTINCT
                                TMT.FTPdtCode,
                                TMT.FTZneCode,
                                TMT.FTPdtStaInOrEx,
                                ZNEL.FTZneName
                            FROM TsysMasTmp TMT
                            -- LEFT JOIN TCNMZone      ZNE         ON ZNESPC.FTZneCode     = ZNE.FTZneCode 
                            -- LEFT JOIN TCNMZoneObj   ZNEOBJ      ON ZNESPC.FTZneCode     = ZNEOBJ.FTZneCode 
                            LEFT JOIN TCNMZone_L    ZNEL        ON TMT.FTZneCode        = ZNEL.FTZneChain        AND ZNEL.FNLngID   =  $nLngID 
                            WHERE 1=1 
                            AND TMT.FTPdtCode       = '$tPdtCode'
                            AND TMT.FTMttSessionID  = '$tSessionID'
                            AND TMT.FTMttTableKey   = '$tTableKey' 
                            AND TMT.FTMttRefKey     = '$tRefKey' ";


        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        // echo $tSQL;
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMPDTZoneConditionsGetPageAll($nLngID);
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
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : All Page Of StockConditions
    //Parameters : function parameters
    //Creator :  23/01/2020 saharat(Golf)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMPDTZoneConditionsGetPageAll($paData){
        $nLngID   = $paData;
        $tSQL = "SELECT COUNT (TMT.FTMttRefKey) AS counts
                 FROM TsysMasTmp TMT
                    LEFT JOIN TCNMZone_L    ZNEL        ON TMT.FTZneCode        = ZNEL.FTZneCode        AND ZNEL.FNLngID   =  $nLngID 
                 WHERE 1=1 AND FTMttRefKey = 'TCNMPdtSpcZone'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    public function FSaMPDTCheckMasZoneTempDuplicate($paData){
        $FTMttTableKey  = $paData['FTMttTableKey'];
        $FTMttRefKey    = $paData['FTMttRefKey'];
        $FTPdtCode      = $paData['FTPdtCode'];
        $FTZneCode      = $paData['FTZneCode'];
        $FTMttSessionID = $paData['FTMttSessionID'];

        $tSQL           = "SELECT * FROM TsysMasTmp WHERE FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTPdtCode='$FTPdtCode' AND FTZneCode='$FTZneCode' AND FTMttSessionID='$FTMttSessionID'";
        $oQuery         = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = array(
                'rtCode'	=> '1',
                'rtDesc'	=> 'success'
            );
        }else{
            $aResult = array(
                'rtCode'	=> '800',
                'rtDesc'	=> 'data not found'
            );
        }
        return $aResult;
    }

    //Functionality : Update&insert TCNMPdtSpcZone
    //Parameters : function parameters
    //Creator : 15/12/2022 [IcePun]
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTZoneConditionsAddEditTemp($paData){
        try{    
            //Update Master
            $this->db->set('FTMttSessionID' , $paData['FTMttSessionID']);
            $this->db->set('FTPdtCode' , $paData['FTPdtCode']);
            $this->db->set('FTZneCode' , $paData['FTZneCode']);
            $this->db->set('FTPdtStaInOrEx' , $paData['FTPdtStaInOrEx']);
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);

            $this->db->where('FTPdtCode', $paData['FTPdtCode']);
            $this->db->where('FTZneCode', $paData['FTZneCode']);
            $this->db->where('FTMttRefKey', $paData['FTMttRefKey']);
            $this->db->update('TsysMasTmp');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Product Special Zone Success',
                );
            }else{
            //Add Master
              $this->db->insert('TsysMasTmp',array(
                    'FTMttTableKey'         => $paData['FTMttTableKey'],
                    'FTMttRefKey'           => $paData['FTMttRefKey'],
                    'FTMttSessionID'        => $paData['FTMttSessionID'],
                    'FTPdtCode'             => $paData['FTPdtCode'],
                    'FTZneCode'             => $paData['FTZneCode'],
                    'FTPdtStaInOrEx'        => $paData['FTPdtStaInOrEx'],

                    'FDLastUpdOn'           => $paData['FDLastUpdOn'],
                    'FDCreateOn'            => $paData['FDCreateOn'],
                    'FTLastUpdBy'           => $paData['FTLastUpdBy'],
                    'FTCreateBy'            => $paData['FTCreateBy'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Product Special Zone Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Product Special Zone.',
                    );
                }
            }
            // print_r($aStatus);
        return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : บันทึกข้อมูลงตาราง  TsysMasTmp
    //Parameters : function parameters
    //Creator : 16/11/2022 [IcePun]
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTZoneConditionsGetDataList($ptData){
        try{

            $this->db->where_in('FTPdtCode', $ptData['FTPdtCode']);
            $this->db->where_in('FTMttTableKey', $ptData['FTMttTableKey']);
            $this->db->where_in('FTMttRefKey', $ptData['FTMttRefKey']);
            $this->db->delete('TsysMasTmp');
            $tPdtCode = $ptData['FTPdtCode'];
            //Add Master
            $tSQL  ="INSERT INTO TsysMasTmp (
                    FTPdtCode, 
                    FTZneCode, 
                    FTPdtStaInOrEx, 
                    FTMttTableKey,
                    FTMttRefKey,
                    FTMttSessionID
                    )
                    SELECT 
                    FTPdtCode,
                    FTZneCode, 
                    FTPdtStaInOrEx,
                    'TCNMPdt' AS FTMttTableKey,
                    'TCNMPdtSpcZone' AS FTMttRefKey,
                    '".$ptData['FTMttSessionID']."' AS FTMttSessionID
                    FROM TCNMPdtSpcZone
                    WHERE FTPdtCode     = '$tPdtCode'
                ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery){
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
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

     //Functionality : บันทึกข้อมูลงตาราง  TCNMPdtSpcZone
    //Parameters : function parameters
    //Creator : 16/11/2022 [IcePun]
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSxMPDTAddUpdateZone($paPdtWhere,$paPackSizeWhere){
        $FTPdtCode        = $paPdtWhere['FTPdtCode'];
        $FTMttTableKey    = $paPackSizeWhere['FTMttTableKey'];
        $FTMttRefKey      = $paPackSizeWhere['FTMttRefKey'];
        $FTMttSessionID   = $paPackSizeWhere['FTMttSessionID'];
        try{
            // $tPdtCode = $ptPdtCode;
            // delete TCNMPdtSpcWah
            $this->db->where('FTPdtCode', $FTPdtCode);
            $this->db->delete('TCNMPdtSpcZone');

            //Add Master

            // $tSQL  ="INSERT INTO TCNMPdtSpcZone (FTPdtCode, FTZneCode, FTPdtStaInOrEx, FDLastUpdOn, FDCreateOn, FTLastUpdBy, FTCreateBy)
            $tSQL  ="INSERT INTO TCNMPdtSpcZone (FTPdtCode, 
                        FTZneCode, 
                        FTPdtStaInOrEx,
                        FDLastUpdOn,
                        FDCreateOn,
                        FTLastUpdBy,
                        FTCreateBy)
                    SELECT FTPdtCode, 
                        FTZneCode, 
                        FTPdtStaInOrEx,
                        FDLastUpdOn,
                        FDCreateOn,
                        FTLastUpdBy,
                        FTCreateBy
                    FROM TsysMasTmp
                    WHERE FTPdtCode='$FTPdtCode' AND FTMttTableKey='$FTMttTableKey' AND FTMttRefKey='$FTMttRefKey' AND FTMttSessionID='$FTMttSessionID'";

            $oQuery = $this->db->query($tSQL);
            if ($oQuery){
                // ลบข้อมูลในตาราง Tmp
                $this->db->where('FTPdtCode', $FTPdtCode);
                $this->db->where('FTMttRefKey', 'TCNMPdtSpcZone');
                $this->db->delete('TsysMasTmp');

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
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    // Functionality : ลบข้อมูลในตาราง TsysMasTmp
    // Parameters : function parameters
    // Creator : 16/11/2022 [IcePun]
    // Return : Array
	// Return Type : array
    public function FSaMPDTZoneConditionsDel($paData){

        $this->db->where_in('FTPdtCode', $paData['FTPdtCode']);
        $this->db->where_in('FTZneCode', $paData['FTZneCode']);
        $this->db->delete('TsysMasTmp');
 
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
    
    //Functionality : List Data  Zone Conditions 
    //Parameters : function parameters
    //Creator : 15/12/2022 [IcePun]
    //Last Modified : -
    //Return : Array
    //Return Type : Array
    public function FSaMPDTGetDataTableZoneDatail($paData){

        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tPdtCode       = $paData['FTPdtCode'];
        $nLngID         = $paData['FNLngID'];
        $tZoneCode      = $paData['FTZneCode'];

        $tSQL   = "SELECT c.* FROM
                    ( SELECT  ROW_NUMBER() OVER(ORDER BY FTZneRefCode ASC) AS FNRowID,* FROM
                        (SELECT DISTINCT ZNEOBJ.FNZneID, 
                                ZNEOBJ.FTZneRefCode, 
                                ZNEOBJ.FTZneChain, 
                                ZNEOBJ.FTAgnCode,
                                ZNEL.FTZneName, 
                                --BCHL.FTBchName
                                CASE WHEN ZNEOBJ.FTZneTable = 'TCNMAgency'     THEN AGNL.FTAgnName ELSE '' END AS FTAgnName,
                                CASE WHEN ZNEOBJ.FTZneTable = 'TCNMBranch'     THEN BCHL.FTBchName ELSE '' END AS FTBchName,
                                CASE WHEN ZNEOBJ.FTZneTable = 'TCNMMerchant'   THEN MERL.FTMerName ELSE '' END AS FTMerName,
                                CASE WHEN ZNEOBJ.FTZneTable = 'TCNMShop'       THEN SHPL.FTShpName ELSE '' END AS FTShpName
                        FROM TCNMZoneObj ZNEOBJ
                        LEFT JOIN TCNMZone_L     ZNEL        ON ZNEOBJ.FTZneChain        = ZNEL.FTZneChain            AND ZNEL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMAgency_L   AGNL        ON AGNL.FTAgnCode           = ZNEOBJ.FTZneRefCode        AND ZNEL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMBranch_L   BCHL        ON BCHL.FTBchCode           = ZNEOBJ.FTZneRefCode        AND ZNEL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMMerchant_L MERL        ON MERL.FTMerCode           = ZNEOBJ.FTZneRefCode        AND ZNEL.FNLngID   =  $nLngID 
                        LEFT JOIN TCNMShop_L     SHPL        ON SHPL.FTShpCode           = ZNEOBJ.FTZneRefCode        AND ZNEL.FNLngID   =  $nLngID 
                        WHERE 1=1 
                        --AND ZNEOBJ.FTZneTable  = 'TCNMBranch'
                        AND ZNEOBJ.FTZneChain  = '$tZoneCode'
                            ";


        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        // echo $tSQL;
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aResult = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            //No Data
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }
}