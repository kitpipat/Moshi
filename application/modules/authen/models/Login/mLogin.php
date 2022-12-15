<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mLogin extends CI_Model
{

    public function FSaMLOGChkLogin($ptUsername, $ptPassword)
    {
        $tLang = $this->session->userdata("tLangID");
        $tSQL = "SELECT USR.* FROM (
                    SELECT FTUsrCode 
                    FROM  TCNMUsrLogin 
                    WHERE FTUsrLogin    = '$ptUsername'
                    AND   FTUsrLoginPwd = '$ptPassword'
                    AND   CONVERT(VARCHAR(10),FDUsrPwdStart,121) <= CONVERT(VARCHAR(10),GETDATE(),121) 
                    AND   CONVERT(VARCHAR(10),FDUsrPwdExpired,121) >= CONVERT(VARCHAR(10),GETDATE(),121) 
                    AND   FTUsrStaActive = 1
                ) AUT
                INNER JOIN (
                    SELECT TCNMUser.FTUsrCode, 
                        TCNMUser.FTDptCode, TCNMUsrDepart_L.FTDptName, 
                        TCNMUser.FTRolCode, TCNTUsrGroup.FTBchCode, 
                        TCNTUsrGroup.FTShpCode, TCNMBranch_L.FTBchName, TCNMImgPerson.FTImgObj ,
                        TCNMUser_L.FTUsrName, TCNMShop.FTMerCode, TCNMBranch.FTWahCode, TCNMWaHouse_L.FTWahName, TCNMMerchant_L.FTMerName, TCNMShop_L.FTShpName 
                    FROM TCNMUser 
                    LEFT JOIN TCNMUsrDepart_L ON TCNMUsrDepart_L.FTDptCode = TCNMUser.FTDptCode AND TCNMUsrDepart_L.FNLngID = $tLang 
                    LEFT JOIN TCNTUsrGroup ON TCNTUsrGroup.FTUsrCode = TCNMUser.FTUsrCode 
                    LEFT JOIN TCNMUser_L ON TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = $tLang 
                    LEFT JOIN TCNMShop ON TCNTUsrGroup.FTShpCode = TCNMShop.FTShpCode AND TCNTUsrGroup.FTBchCode = TCNMShop.FTBchCode 
                    LEFT JOIN TCNMShop_L ON TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode 
                    LEFT JOIN TCNMBranch_L ON TCNTUsrGroup.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = $tLang 
                    LEFT JOIN TCNMMerchant_L ON TCNMMerchant_L.FTMerCode = TCNMShop.FTMerCode AND TCNMMerchant_L.FNLngID = $tLang 
                    --LEFT JOIN TCNMWaHouse_L ON TCNMWaHouse_L.FTWahCode = TCNMShop.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMShop.FTBchCode AND TCNMWaHouse_L.FNLngID = 1 
                    LEFT JOIN TCNMImgPerson ON TCNMUser.FTUsrCode = TCNMImgPerson.FTImgRefID AND TCNMImgPerson.FTImgTable = 'TCNMUser'
                    LEFT JOIN TCNMBranch ON TCNTUsrGroup.FTBchCode = TCNMBranch.FTBchCode
                    LEFT JOIN TCNMWaHouse_L ON TCNMBranch.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMBranch.FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = $tLang
                ) USR ON AUT.FTUsrCode = USR.FTUsrCode
        ";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }

    public function FSaMLOGGetBch()
    {
        //Napat(Jame) 17/03/63
        //เพิ่มการ left join เพื่อไปเอาชื่อสาขา
        $tLang = $this->session->userdata("tLangID");
        $tSQL           = " SELECT TOP 1 
                                CMP.FTBchCode,
                                BCH_L.FTBchName,
                                WAH_L.FTWahCode,
	                            WAH_L.FTWahName
                            FROM TCNMComp CMP WITH(NOLOCK)
                            LEFT JOIN TCNMBranch BCH ON CMP.FTBchcode = BCH.FTBchCode
                            LEFT JOIN TCNMBranch_L BCH_L ON CMP.FTBchcode = BCH_L.FTBchCode AND FNLngID = $tLang
                            LEFT JOIN TCNMWaHouse_L WAH_L ON BCH.FTWahCode = WAH_L.FTWahCode AND CMP.FTBchCode = WAH_L.FTBchCode AND WAH_L.FNLngID = $tLang 
                          ";
        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }


    // Functionality: Get Data User Role Code
    // Parameters: String
    // Creator:  08/07/2020 Worakorn
    // Return: Data Array
    // Return Type: Array
    public function FSaMLOGListUsrRoleCode($ptUsrCode)
    {
        $tLang = $this->session->userdata("tLangID");

        $tSQL = " SELECT FTRolCode 
                  FROM TCNMUsrActRole 
                  WHERE FTUsrCode = '$ptUsrCode' ";

        $oQuery         = $this->db->query($tSQL);
        $oList          = $oQuery->result_array();
        return $oList;
    }

    // Last Update : Napat(Jame) 17/08/2020 ดึงสาขามาจาก Agn ทั้งหมด
    public function FSaMLOGGetDataUserLoginGroup($ptUsrCode){
        $tLang = $this->session->userdata("tLangID");
        $tSQL  = "  SELECT DISTINCT
                        USG.FTUsrCode       AS FTUsrCode,
                        USG.FTAgnCode       AS FTAgnCode,
                        AGNL.FTAgnName      AS FTAgnName,
                        BCH.FTBchCode       AS FTBchCode,
                        BCHL.FTBchName      AS FTBchName,
                        USG.FTShpCode       AS FTShpCode,
                        SHPL.FTShpName      AS FTShpName,
                        USG.FTMerCode       AS FTMerCode,
                        MERL.FTMerName      AS FTMerName,
                        WAHL.FTWahCode      AS FTWahCode,
                        WAHL.FTWahName      AS FTWahName,
                        CASE 
                            WHEN ISNULL(USG.FTAgnCode,'') != '' AND ISNULL(USG.FTBchCode,'')  = '' AND ISNULL(USG.FTMerCode,'')  = '' AND ISNULL(USG.FTShpCode,'')  = '' THEN 'AGN'
                            WHEN ISNULL(USG.FTBchCode,'') != '' AND ISNULL(USG.FTMerCode,'')  = '' AND ISNULL(USG.FTShpCode,'')  = '' THEN 'BCH' 
                            WHEN ISNULL(USG.FTBchCode,'') != '' AND ISNULL(USG.FTMerCode,'') != '' AND ISNULL(USG.FTShpCode,'')  = '' THEN 'MER'
                            WHEN ISNULL(USG.FTBchCode,'') != '' AND ISNULL(USG.FTMerCode,'') != '' AND ISNULL(USG.FTShpCode,'') != '' THEN 'SHP'
                            ELSE 'HQ'
                        END AS FTLoginLevel
                    FROM TCNTUsrGroup USG WITH(NOLOCK)
                    /* 
                        ถ้าเป็น User AD ให้ Where ด้วย FTAgnCode (จะดึงสาขาทั้งหมดภายใต้ AD)
                        ถ้าเป็น User AD Bch ให้ Where ด้วย FTBchCode (จะดึงเฉพาะสาขาที่กำหนดในหน้าจอผู้ใช้)
                        ถ้าเป็น User Bch ให้ Where ด้วย FTBchCode (จะดึงเฉพาะสาขาที่กำหนดในหน้าจอผู้ใช้)
                    */
                    LEFT JOIN TCNMBranch		BCH  WITH(NOLOCK) ON 
                        CASE 
                            WHEN ISNULL(USG.FTAgnCode,'') != '' AND ISNULL(USG.FTBchCode,'') != '' THEN USG.FTBchCode 
                            WHEN ISNULL(USG.FTAgnCode,'') != '' AND ISNULL(USG.FTBchCode,'')  = '' THEN USG.FTAgnCode 
                            ELSE USG.FTBchCode
                        END 
                            = 
                        CASE 
                            WHEN ISNULL(USG.FTAgnCode,'') != '' AND ISNULL(USG.FTBchCode,'') != '' THEN BCH.FTBchCode 
                            WHEN ISNULL(USG.FTAgnCode,'') != '' AND ISNULL(USG.FTBchCode,'')  = '' THEN BCH.FTAgnCode 
                            ELSE BCH.FTBchCode
                        END
                    LEFT JOIN TCNMBranch_L      BCHL WITH(NOLOCK) ON BCH.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $tLang
                    LEFT JOIN TCNMAgency_L      AGNL WITH(NOLOCK) ON USG.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = $tLang
                    LEFT JOIN TCNMShop_L        SHPL WITH(NOLOCK) ON USG.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID = $tLang
                    LEFT JOIN TCNMMerchant_L    MERL WITH(NOLOCK) ON USG.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $tLang
                    LEFT JOIN TCNMWaHouse_L     WAHL WITH(NOLOCK) ON BCH.FTWahCode = WAHL.FTWahCode AND BCH.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = $tLang
                    WHERE USG.FTUsrCode = '$ptUsrCode'
                    ORDER BY FTMerCode ASC, FTBchCode ASC, FTShpCode ASC
                    ";
        // $tSQL  = "   SELECT
        //                 USG.FTUsrCode       AS FTUsrCode,
        //                 USG.FTAgnCode       AS FTAgnCode,
        //                 AGNL.FTAgnName      AS FTAgnName,
        //                 USG.FTBchCode       AS FTBchCode,
        //                 BCHL.FTBchName      AS FTBchName,
        //                 USG.FTShpCode       AS FTShpCode,
        //                 SHPL.FTShpName      AS FTShpName,
        //                 USG.FTMerCode       AS FTMerCode,
        //                 MERL.FTMerName      AS FTMerName,
        //                 WAHL.FTWahCode      AS FTWahCode,
        //                 WAHL.FTWahName      AS FTWahName
        //             FROM TCNTUsrGroup USG WITH(NOLOCK)
        //             LEFT JOIN TCNMBranch        BCH  ON USG.FTBchCode = BCH.FTBchCode
        //             LEFT JOIN TCNMBranch_L      BCHL ON USG.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $tLang
        //             LEFT JOIN TCNMShop_L        SHPL ON USG.FTShpCode = SHPL.FTShpCode AND SHPL.FNLngID = $tLang
        //             LEFT JOIN TCNMMerchant_L    MERL ON USG.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $tLang
        //             LEFT JOIN TCNMAgency_L      AGNL ON USG.FTAgnCode = AGNL.FTAgnCode AND AGNL.FNLngID = $tLang
        //             LEFT JOIN TCNMWaHouse_L     WAHL ON BCH.FTWahCode = WAHL.FTWahCode AND USG.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = $tLang
        //             WHERE USG.FTUsrCode = '$ptUsrCode'
        //             ORDER BY FTMerCode ASC, FTBchCode ASC, FTShpCode ASC
        //          ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery->result_array();
    }
}

