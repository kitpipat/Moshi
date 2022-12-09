<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRole extends CI_Model {

    // Functionality : list Role
    // Parameters : Function Parameter
    // Creator :  05/06/2019 Saharat(Golf)
    // Last Modified : 13/08/2019 Wasin(Yoshi)
    // Return : Data User List
    // Return Type : Array
    public function FSaMGetDataRoleList($paDataWhere){
        $aRowLen        = FCNaHCallLenData($paDataWhere['nRow'],$paDataWhere['nPage']);
        $nLngID         = $paDataWhere['FNLngID'];
        $tSearchList    = $paDataWhere['tSearchAll'];
        $tSQL           = " SELECT c.* FROM (
                                SELECT  ROW_NUMBER() OVER(ORDER BY FDCreateOn DESC) AS rtRowID,* FROM (
                                    SELECT DISTINCT
                                        USRR.FTRolCode,
                                        USRRL.FTRolName,
                                        USRRL.FTRolRmk,
                                        IMG.FTImgObj,
                                        USRR.FDCreateOn
                                    FROM [TCNMUsrRole] USRR
                                    LEFT JOIN [TCNMUsrRole_L] USRRL ON USRR.FTRolCode = USRRL.FTRolCode AND USRRL.FNLngID = $nLngID
                                    LEFT JOIN TCNMImgObj IMG ON IMG.FTImgRefID = USRR.FTRolCode AND IMG.FTImgTable = 'TCNMUsrRole'
                                    WHERE 1=1
        ";
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL   .= " AND (USRR.FTRolCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL   .= " OR USRRL.FTRolName COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }

        $tSQL   .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aListData  = $oQuery->result_array();
            $aFoundRow  = $this->FSaMGetDataRoleListAll($paDataWhere);
            $nFoundRow  = ($aFoundRow['rtCode'] == '1')? $aFoundRow['rtCountData'] : 0;
            $nPageAll   = ceil($nFoundRow/$paDataWhere['nRow']);
            $aResult = array(
                'raItems'       => $aListData,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage"     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        unset($oQuery);
        unset($aListData);
        unset($aFoundRow);
        unset($nFoundRow);
        unset($nPageAll);
        return $aResult;
    }

    // Functionality : Count Data All Role List
    // Parameters : Function Parameter
    // Creator :  05/06/2019 Saharat(Golf)
    // Last Modified : 13/08/2019 Wasin(Yoshi)
    // Return : data
    // Return Type : Array
    public function FSaMGetDataRoleListAll($paDataWhere){
        $nLngID         = $paDataWhere['FNLngID'];
        $tSearchList    = $paDataWhere['tSearchAll'];

        $tSQL           = " SELECT
                                COUNT (USRR.FTRolCode) AS counts
                            FROM [TCNMUsrRole]          USRR    WITH(NOLOCK)
                            LEFT JOIN [TCNMUsrRole_L]   USRRL   WITH(NOLOCK)    ON USRR.FTRolCode = USRRL.FTRolCode AND USRRL.FNLngID = $nLngID
                            WHERE 1 = 1
        ";
        if(isset($tSearchList) && !empty($tSearchList)){
            $tSQL   .= " AND (USRR.FTRolCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL   .= " OR USRRL.FTRolName COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0) {
            $aDetail        = $oQuery->row_array();
            $aDataReturn    =  array(
                'rtCountData'   => $aDetail['counts'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aDataReturn    =  array(
                'rtCode'        => '800',
                'rtDesc'        => 'Data Not Found',
            );
        }
        unset($oQuery);
        unset($aDetail);
        return $aDataReturn;
    }

    // Functionality : Function List Data Menu
    // Parameters : Function Parameter
    // Creator : 05/06/2019 Saharat(Golf)
    // Last Modified : 27/08/2019 Wasin(Yoshi)
    // Return : array
    // Return Type : array
    public function FSaMRoleMenuList($paData){
        $nLngID = $paData['FNLngID'];
        $tSQL   = " SELECT
                        MENU.FTGmnModCode,
                        MOD_L.FTGmnModName,
                        MOD.FNGmnModShwSeq,
                        MOD.FTGmnModCode    AS FTGmnCode,
                        MOD_L.FTGmnModName	AS FTGmnName,
                        '0'                 AS FNGmnShwSeq,
                        MENU.FTMnuCode,
                        MNU_L.FTMnuName,
                        MENU.FNMnuSeq,
                        MENU.FNMnuLevel,
                        ISNULL(MNUALB.FTAutStaRead,1)       AS FTAutStaRead,
                        ISNULL(MNUALB.FTAutStaAdd,1)        AS FTAutStaAdd,
                        ISNULL(MNUALB.FTAutStaDelete,1)     AS FTAutStaDelete,
                        ISNULL(MNUALB.FTAutStaEdit,1)       AS FTAutStaEdit,
                        ISNULL(MNUALB.FTAutStaCancel,1)     AS FTAutStaCancel,
                        ISNULL(MNUALB.FTAutStaAppv,1)       AS FTAutStaAppv,
                        ISNULL(MNUALB.FTAutStaPrint,1)      AS FTAutStaPrint,
                        ISNULL(MNUALB.FTAutStaPrintMore,1)  AS FTAutStaPrintMore
                    FROM TSysMenuList               MENU    WITH(NOLOCK)
                    LEFT JOIN TSysMenuList_L        MNU_L   WITH(NOLOCK) ON MENU.FTMnuCode      = MNU_L.FTMnuCode       AND MNU_L.FNLngID = $nLngID
                    LEFT JOIN TSysMenuGrpModule     MOD     WITH(NOLOCK) ON MENU.FTGmnModCode   = MOD.FTGmnModCode
                    LEFT JOIN TSysMenuGrpModule_L   MOD_L   WITH(NOLOCK) ON MOD.FTGmnModCode    = MOD_L.FTGmnModCode    AND MOD_L.FNLngID = $nLngID
                    LEFT JOIN TSysMenuAlbAct        MNUALB	WITH(NOLOCK) ON MENU.FTMnuCode      = MNUALB.FTMnuCode
                    WHERE 1=1
                    -- AND MENU.FTGmnModCode NOT IN ('RPT')
                    AND MENU.FNMnuLevel     = 0
                    AND MENU.FTMnuStaUse    = 1
                    AND MOD.FTGmnModStaUse	= 1
                    UNION
                    SELECT
                        MNU.FTGmnModCode,
                        MNU_L.FTGmnModName,
                        MNU.FNGmnModShwSeq,
                        MNG.FTGmnCode,
                        MNG_L.FTGmnName,
                        MNG.FNGmnShwSeq,
                        MNL.FTMnuCode,
                        MNL_L.FTMnuName,
                        MNL.FNMnuSeq,
                        MNL.FNMnuLevel,
                        ISNULL(MNUALB.FTAutStaRead,1)       AS FTAutStaRead,
                        ISNULL(MNUALB.FTAutStaAdd,1)        AS FTAutStaAdd,
                        ISNULL(MNUALB.FTAutStaDelete,1)     AS FTAutStaDelete,
                        ISNULL(MNUALB.FTAutStaEdit,1)       AS FTAutStaEdit,
                        ISNULL(MNUALB.FTAutStaCancel,1)     AS FTAutStaCancel,
                        ISNULL(MNUALB.FTAutStaAppv,1)       AS FTAutStaAppv,
                        ISNULL(MNUALB.FTAutStaPrint,1)      AS FTAutStaPrint,
                        ISNULL(MNUALB.FTAutStaPrintMore,1)  AS FTAutStaPrintMore
                    FROM TSysMenuGrpModule        MNU    WITH (NOLOCK)
                    LEFT JOIN TSysMenuGrpModule_L MNU_L  WITH (NOLOCK) ON MNU.FTGmnModCode = MNU_L.FTGmnModCode  AND MNU_L.FNLngID = $nLngID
                    LEFT JOIN TSysMenuGrp         MNG    WITH (NOLOCK) ON MNU.FTGmnModCode = MNG.FTGmnModCode
                    LEFT JOIN TSysMenuGrp_L       MNG_L  WITH (NOLOCK) ON MNG.FTGmnCode    = MNG_L.FTGmnCode     AND MNG_L.FNLngID = $nLngID
                    LEFT JOIN TSysMenuList        MNL    WITH (NOLOCK) ON MNU.FTGmnModCode = MNL.FTGmnModCode    AND MNG.FTGmnCode = MNL.FTGmnCode AND MNG.FTGmnCode = MNL.FTMnuParent
                    LEFT JOIN TSysMenuList_L      MNL_L  WITH (NOLOCK) ON MNL.FTMnuCode    = MNL_L.FTMnuCode     AND MNL_L.FNLngID = 1 
                    LEFT JOIN TSysMenuAlbAct      MNUALB WITH (NOLOCK) ON MNL.FTMnuCode    = MNUALB.FTMnuCode
                    WHERE 1=1
                    -- AND MNU.FTGmnModCode NOT IN ('RPT')
                    AND MNU.FTGmnModStaUse  = 1
                    AND MNG.FTGmnStaUse     = 1
                    AND MNL.FTMnuStaUse     = 1
                    ORDER BY FNGmnModShwSeq ASC,FNGmnShwSeq ASC,FNMnuSeq ASC,FNMnuLevel ASC
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0) {
            $oList = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $oList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($oList);
        return $aResult;
    }

    // Functionality : Function List Data Menu
    // Parameters : 
    // Creator : 24/07/2019 Saharat(Golf)
    // Last Modified : 27/08/2019 Wasin(Yoshi)
    // Return : array
    // Return Type : array
    public function FSaMRptListMenu($paData){
        $nLngID     = $paData['FNLngID'];
        $tSQL   =   "   SELECT 
                            RPM.FTGrpRptModCode,
                            RPM.FNGrpRptModShwSeq,
                            RPML.FNGrpRptModName,
                            RPG.FTGrpRptCode,
                            RPGL.FTGrpRptName,
                            RPT.FTRptCode,
                            RPTL.FTRptName                         
                        FROM TSysReportModule           RPM     WITH(NOLOCK)
                        LEFT JOIN TSysReportModule_L    RPML    WITH(NOLOCK)    ON RPM.FTGrpRptModCode  = RPML.FTGrpRptModCode AND RPML.FNLngID = $nLngID 
                        LEFT JOIN TSysReportGrp         RPG     WITH(NOLOCK)    ON RPM.FTGrpRptModCode  = RPG.FTGrpRptModCode
                        LEFT JOIN TSysReportGrp_L       RPGL    WITH(NOLOCK)    ON RPG.FTGrpRptCode     = RPGL.FTGrpRptCode    AND RPGL.FNLngID = $nLngID 
                        LEFT JOIN TSysReport            RPT     WITH(NOLOCK)    ON RPM.FTGrpRptModCode  = RPT.FTGrpRptModCode  AND RPG.FTGrpRptCode = RPT.FTGrpRptCode
                        LEFT JOIN TSysReport_L          RPTL    WITH(NOLOCK)    ON RPT.FTRptCode        = RPTL.FTRptCode       AND RPTL.FNLngID = $nLngID 
                        WHERE 1 = 1 
                        AND RPG.FTGrpRptStaUse    = 1
                        AND RPM.FTGrpRptModStaUse = 1
                        AND RPT.FTRptStaUse       = 1
                        ORDER BY RPM.FNGrpRptModShwSeq ASC , RPG.FNGrpRptShwSeq ASC 
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0) {
            $oList = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $oList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($oList);
        return $aResult;
    }
    
    // Functionality : Count Data Role
    // Parameters : Function Parameter In Controler
    // Creator : 24/07/2019 Saharat(Golf)
    // LastUpdate: 28/08/2019 Wasin(Yoshi)
    // Return : array result from db
    // Return Type : array
    public function FSnMCountDataRole(){
        $tSQL   = " SELECT
                        COUNT(FTRolCode) AS FNRolCountAll
                    FROM TCNMUsrRole WITH(NOLOCK)
        ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery->row_array()["FNRolCountAll"];
    }

    // Functionality : Add/Update Main Data User Role
    // Parameters : Function Parameter In Controler
    // Creator : 24/07/2019 Saharat(Golf)
    // LastUpdate: 28/08/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : None
    public function FSxMRoleAddUpdateUsrRole($paDataMaster){
        // Update Main User Role 
        $this->db->where('FTRolCode',$paDataMaster['FTRolCode']);
        $this->db->update('TCNMUsrRole',array(
            'FNRolLevel'    => $paDataMaster['FNRolLevel'],
            'FDLastUpdOn'   => $paDataMaster['FDLastUpdOn'],
            'FTLastUpdBy'   => $paDataMaster['FTLastUpdBy'],
        ));

        if($this->db->affected_rows() == 0){
            // Add Main User Role
            $this->db->insert('TCNMUsrRole',array(
                'FTRolCode'     => $paDataMaster['FTRolCode'],
                'FNRolLevel'    => $paDataMaster['FNRolLevel'],
                'FDCreateOn'    => $paDataMaster['FDCreateOn'],
                'FTCreateBy'    => $paDataMaster['FTCreateBy'],
                'FDLastUpdOn'   => $paDataMaster['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataMaster['FTLastUpdBy'],
            ));
        }
        return;
    }

    // Functionality : Add/Update Main Data User Role Lang
    // Parameters : Function Parameter In Controler
    // Creator : 24/07/2019 Saharat(Golf)
    // LastUpdate: 28/08/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : None
    public function FSxMRoleAddUpdateUsrRoleLang($paDataMaster){
        // Update Main User Role Lang
        $this->db->where('FNLngID',$paDataMaster['FNLngID']);
        $this->db->where('FTRolCode',$paDataMaster['FTRolCode']);
        $this->db->update('TCNMUsrRole_L',array(
            'FTRolName' => $paDataMaster['FTRolName'],
            'FTRolRmk'  => $paDataMaster['FTRolRmk'],
        ));
        if($this->db->affected_rows() == 0){
            // Add Main User Role Lang
            $this->db->insert('TCNMUsrRole_L',array(
                'FTRolCode' => $paDataMaster['FTRolCode'],
                'FNLngID'   => $paDataMaster['FNLngID'],
                'FTRolName' => $paDataMaster['FTRolName'],
                'FTRolRmk'  => $paDataMaster['FTRolRmk'],
            ));
        }
        return;
    }

    // Functionality : Add/Update Role User Menu
    // Parameters : Function Parameter In Controler
    // Creator : 24/07/2019 Saharat(Golf)
    // LastUpdate: 28/08/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : None
    public function FSxMRoleAddUpdateUsrMenu($paDataMaster,$paRoleMnuData){
        // Delete Data User Role Menu
        $this->db->where('FTRolCode',$paDataMaster['FTRolCode']);
        $this->db->delete('TCNTUsrMenu');

        // Check Data Role Menu Empty
        if(isset($paRoleMnuData) && !empty($paRoleMnuData)){
            // Loop Add/Update Data User Role Menu
            foreach($paRoleMnuData AS $nKey => $aValue){
                // Add Data User Role Menu
                $this->db->insert('TCNTUsrMenu',array(
                    'FTRolCode'         => $paDataMaster['FTRolCode'],
                    'FTGmnCode'         => $aValue['tGrpCode'],
                    'FTMnuParent'       => $aValue['tGrpCode'],
                    'FTMnuCode'         => $aValue['tMenuCode'],
                    'FTAutStaFull'      => 0,
                    'FTAutStaRead'      => $aValue['tMenuStaRead'],
                    'FTAutStaAdd'       => $aValue['tMenuStaAdd'],
                    'FTAutStaEdit'      => $aValue['tMenuStaEdit'],
                    'FTAutStaDelete'    => $aValue['tMenuStaDel'],
                    'FTAutStaCancel'    => $aValue['tMenuStaCancel'],
                    'FTAutStaAppv'      => $aValue['tMenuStaAppv'],
                    'FTAutStaPrint'     => $aValue['tMenuStaPrintMore'],
                    'FTAutStaPrintMore' => $aValue['tMenuStaPrintMore'],
                    'FTAutStaFavorite'  => 0,
                    'FDLastUpdOn'       => $paDataMaster['FDLastUpdOn'],
                    'FTLastUpdBy'       => $paDataMaster['FTLastUpdBy'],
                    'FDCreateOn'        => $paDataMaster['FDCreateOn'],
                    'FTCreateBy'        => $paDataMaster['FTCreateBy'],
                ));
            }
        }
        return;
    }

    // Functionality : Add/Update Role User Report Menu
    // Parameters : Function Parameter In Controler
    // Creator : 24/07/2019 Saharat(Golf)
    // LastUpdate: 28/08/2019 Wasin(Yoshi)
    // Return : -
    // Return Type : None
    public function FSxMRoleAddUpdateUsrRptMenu($paDataMaster,$paRoleRptMnuData){
        // Delete Data User Role Menu
        $this->db->where('FTRolCode',$paDataMaster['FTRolCode']);
        $this->db->delete('TCNTUsrFuncRpt');

        // Check Data Role Menu Report
        if(isset($paRoleRptMnuData) && !empty($paRoleRptMnuData)){
            // Loop Add/Update Data User Role Report Menu
            foreach($paRoleRptMnuData AS $nKey => $aValue){
                // Add Data User Role Report Menu
                $this->db->insert('TCNTUsrFuncRpt',array(
                    'FTRolCode'         => $paDataMaster['FTRolCode'],
                    'FTUfrType'         => 2,
                    'FTUfrGrpRef'       => $aValue['tRptGrpCode'],
                    'FTUfrRef'          => $aValue['tRptCode'],
                    'FTUfrStaAlw'       => $aValue['tRptStaAlw'],
                    'FTUfrStaFavorite'  => 0,
                    'FDCreateOn'        => $paDataMaster['FDCreateOn'],
                    'FTCreateBy'        => $paDataMaster['FTCreateBy'],
                ));
            }
        }
        return;
    }

    // Functionality : Delete Role
    // Parameters : function parameters
    // Creator :  05/06/2019 Saharat(Golf)
    // Last Modified : 28/08/2019 Wasin(Yoshi)
    // Return : Status Delete
    // Return Type : array
    public function FSaMRoleDeleteData($paData){
        $this->db->trans_begin();

        $this->db->where_in('FTRolCode', $paData['FTRolCode']);
        $this->db->delete('TCNMUsrRole');

        $this->db->where_in('FTRolCode', $paData['FTRolCode']);
        $this->db->delete('TCNMUsrRole_L');

        $this->db->where_in('FTRolCode', $paData['FTRolCode']);
        $this->db->delete('TCNTUsrMenu');

        $this->db->where_in('FTRolCode', $paData['FTRolCode']);
        $this->db->delete('TCNTUsrFuncRpt');

        // Del IMGobj
        $this->db->where_in('FTImgRefID', $paData['FTRolCode']);
        $this->db->delete('TCNMImgObj');


        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'Error Cannot Delete Data Role.',
            );
        }else{
            $this->db->trans_commit();
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'Delete Role And Menu Complete.'
            );
        }
        return $aStatus;
    }

    // Functionality : Get Data Master Role
    // Parameters : function parameters
    // Creator :  05/06/2019 Saharat(Golf)
    // Last Modified : 28/08/2019 Wasin(Yoshi)
    // Return : Array Data Role Master
    // Return Type : array
    public function FSaMRoleGetDataMaster($paDataWhere){
        $tRoleCode  = $paDataWhere['FTRolCode'];
        $nLngID     = $paDataWhere['FNLngID'];
        $tSQL       = " SELECT
                            ROL.FTRolCode,
                            ROL.FNRolLevel,
                            ROL_L.FTRolName,
                            ROL_L.FTRolRmk,
                            IMG.FTImgObj   AS rtRoleImgObj
                        FROM TCNMUsrRole        ROL     WITH(NOLOCK)
                        LEFT JOIN TCNMUsrRole_L ROL_L   WITH(NOLOCK) ON ROL.FTRolCode = ROL_L.FTRolCode AND ROL_L.FNLngID = $nLngID
                        LEFT JOIN TCNMImgObj IMG ON IMG.FTImgRefID = ROL.FTRolCode AND IMG.FTImgTable = 'TCNMUsrRole'
                        WHERE ROL.FTRolCode = '$tRoleCode'
        ";
        $oQuery = $this->db->query($tSQL);

        if($oQuery->num_rows() > 0) {
            $aDataReturn    = $oQuery->row_array();
        }else{
            $aDataReturn    = array();
        }
        return $aDataReturn;
    }

    // Functionality: Get Data Edit Menu Role
    // Parameters:  Function Parameter
    // Creator: 27/06/2019 Saharat(Golf)
    // Last Modified: -
    // Return: array
    // ReturnType: array
    public function FSaMGetDataRoleMenuEdit($paDataWhere){
        $nLngID     = $paDataWhere['FNLngID'];
        $tRoleCode  = $paDataWhere['FTRolCode'];
        $tSQL       = " SELECT
                            USERMENU.*,
                            ISNULL(MNUDATAAUT.FTAutStaRead,0)       AS FTAutStaRead,
                            ISNULL(MNUDATAAUT.FTAutStaAdd,0)        AS FTAutStaAdd,
                            ISNULL(MNUDATAAUT.FTAutStaDelete,0)     AS FTAutStaDelete,
                            ISNULL(MNUDATAAUT.FTAutStaEdit,0)       AS FTAutStaEdit,
                            ISNULL(MNUDATAAUT.FTAutStaAppv,0)       AS FTAutStaAppv,
                            ISNULL(MNUDATAAUT.FTAutStaCancel,0)     AS FTAutStaCancel,
                            ISNULL(MNUDATAAUT.FTAutStaPrint,0)      AS FTAutStaPrint,
                            ISNULL(MNUDATAAUT.FTAutStaPrintMore,0)  AS FTAutStaPrintMore
                        FROM (
                            SELECT
                                USRM.FTRolCode,
                                MOD.FTGmnModCode,
                                MOD.FNGmnModShwSeq,
                                MOD_L.FTGmnModName,
                                USRM.FTGmnCode,
                                0 AS FNGmnShwSeq,
                                MOD_L.FTGmnModName AS FTGmnName,
                                MNL.FTMnuCode,
                                MNL.FNMnuSeq,
                                MNL_L.FTMnuName,
                                MNL.FNMnuLevel,
                                ISNULL(USRM.FTAutStaFull,0)         AS FTUsrUseStaStaFull,
                                ISNULL(USRM.FTAutStaRead,0)         AS FTUsrUseStaStaRead,
                                ISNULL(USRM.FTAutStaAdd,0)          AS FTUsrUseStaStaAdd,
                                ISNULL(USRM.FTAutStaDelete,0)       AS FTUsrUseStaStaDelete,
                                ISNULL(USRM.FTAutStaEdit,0)         AS FTUsrUseStaStaEdit,
                                ISNULL(USRM.FTAutStaAppv,0)         AS FTUsrUseStaStaAppv,
                                ISNULL(USRM.FTAutStaCancel,0)       AS FTUsrUseStaStaCancel,
                                ISNULL(USRM.FTAutStaPrint,0)        AS FTUsrUseStaPrint,
                                ISNULL(USRM.FTAutStaPrintMore,0)    AS FTUsrUseStaPrintMore,
                                ISNULL(USRM.FTAutStaFavorite,0)     AS FTUsrUseStaFavorite
                            FROM TCNTUsrMenu                USRM	WITH(NOLOCK)
                            LEFT JOIN TSysMenuList          MNL		WITH(NOLOCK) ON USRM.FTGmnCode 		= MNL.FTGmnCode         AND USRM.FTMnuParent = MNL.FTMnuParent AND USRM.FTMnuCode = MNL.FTMnuCode
                            LEFT JOIN TSysMenuList_L        MNL_L	WITH(NOLOCK) ON MNL.FTMnuCode 		= MNL_L.FTMnuCode 		AND MNL_L.FNLngID = $nLngID
                            LEFT JOIN TSysMenuGrpModule     MOD		WITH(NOLOCK) ON MNL.FTGmnModCode	= MOD.FTGmnModCode
                            LEFT JOIN TSysMenuGrpModule_L   MOD_L	WITH(NOLOCK) ON MOD.FTGmnModCode	= MOD_L.FTGmnModCode	AND MOD_L.FNLngID = $nLngID
                            WHERE 1=1
                            AND USRM.FTRolCode      = '$tRoleCode'
                            AND MNL.FNMnuLevel      = 0
                            AND MNL.FTMnuStaUse     = 1
                            AND MOD.FTGmnModStaUse  = 1
                            UNION ALL
                            SELECT
                                USRM.FTRolCode,
                                MOD.FTGmnModCode,
                                MOD.FNGmnModShwSeq,
                                MOD_L.FTGmnModName,
                                MGP.FTGmnCode,
                                MGP.FNGmnShwSeq,
                                MGP_L.FTGmnName,
                                MNL.FTMnuCode,
                                MNL.FNMnuSeq,
                                MNL_L.FTMnuName,
                                MNL.FNMnuLevel,
                                ISNULL(USRM.FTAutStaFull,0)         AS FTUsrUseStaStaFull,
                                ISNULL(USRM.FTAutStaRead,0)         AS FTUsrUseStaStaRead,
                                ISNULL(USRM.FTAutStaAdd,0)          AS FTUsrUseStaStaAdd,
                                ISNULL(USRM.FTAutStaDelete,0)       AS FTUsrUseStaStaDelete,
                                ISNULL(USRM.FTAutStaEdit,0)         AS FTUsrUseStaStaEdit,
                                ISNULL(USRM.FTAutStaCancel,0)       AS FTUsrUseStaStaCancel,
                                ISNULL(USRM.FTAutStaAppv,0)         AS FTUsrUseStaStaAppv,
                                ISNULL(USRM.FTAutStaPrint,0)        AS FTUsrUseStaPrint,
                                ISNULL(USRM.FTAutStaPrintMore,0)    AS FTUsrUseStaPrintMore,
                                ISNULL(USRM.FTAutStaFavorite,0)     AS FTUsrUseStaFavorite
                            FROM TCNTUsrMenu USRM               WITH(NOLOCK)
                            LEFT JOIN TSysMenuList MNL          WITH(NOLOCK) ON USRM.FTGmnCode   = MNL.FTGmnCode         AND USRM.FTMnuParent = MNL.FTMnuParent AND USRM.FTMnuCode = MNL.FTMnuCode
                            LEFT JOIN TSysMenuList_L MNL_L      WITH(NOLOCK) ON MNL.FTMnuCode    = MNL_L.FTMnuCode       AND MNL_L.FNLngID = $nLngID
                            LEFT JOIN TSysMenuGrp MGP           WITH(NOLOCK) ON MNL.FTGmnCode    = MGP.FTGmnCode
                            LEFT JOIN TSysMenuGrp_L MGP_L       WITH(NOLOCK) ON MGP.FTGmnCode    = MGP_L.FTGmnCode       AND MGP_L.FNLngID = $nLngID
                            LEFT JOIN TSysMenuGrpModule MOD     WITH(NOLOCK) ON MGP.FTGmnModCode = MOD.FTGmnModCode
                            LEFT JOIN TSysMenuGrpModule_L MOD_L WITH(NOLOCK) ON MOD.FTGmnModCode = MOD_L.FTGmnModCode    AND MOD_L.FNLngID = $nLngID
                            WHERE 1=1 
                            AND USRM.FTRolCode      = '$tRoleCode'
                            AND MNL.FTMnuStaUse     = 1
                            AND MGP.FTGmnStaUse     = 1
                            AND MOD.FTGmnModStaUse  = 1
                        ) AS USERMENU
                        LEFT JOIN (
                            SELECT
                                MENUAUT.FTMnuCode,
                                MENUAUT.FTAutStaRead,
                                MENUAUT.FTAutStaAdd,
                                MENUAUT.FTAutStaDelete,
                                MENUAUT.FTAutStaEdit,
                                MENUAUT.FTAutStaCancel,
                                MENUAUT.FTAutStaAppv,
                                MENUAUT.FTAutStaPrint,
                                MENUAUT.FTAutStaPrintMore
                            FROM TSysMenuAlbAct MENUAUT 
                        ) AS MNUDATAAUT ON  USERMENU.FTMnuCode = MNUDATAAUT.FTMnuCode 
                        ORDER BY USERMENU.FNGmnModShwSeq ASC,USERMENU.FNGmnShwSeq ASC,USERMENU.FNMnuSeq ASC,USERMENU.FNMnuLevel ASC
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aList  = $oQuery->result_array();
            $aResult = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',   
            );
        }else{
            $aResult = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($nLngID);
        unset($tRoleCode);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        return $aResult;
    }

    // Functionality: Get Data Edit Menu Role Edit
    // Parameters:  Function Parameter
    // Creator: 27/06/2019 Saharat(Golf)
    // Last Modified: -
    // Return: array
    // ReturnType: array
    public function FSaMGetDataRoleMenuRptEdit($paDataWhere){
        $nLngID     = $paDataWhere['FNLngID'];
        $tRoleCode  = $paDataWhere['FTRolCode'];
        $tSQL       = " SELECT
                            RPM.FTGrpRptModCode,
                            UFR.FTUfrStaAlw,
                            RPM.FNGrpRptModShwSeq,
                            RPML.FNGrpRptModName,
                            RPG.FTGrpRptCode,
                            RPGL.FTGrpRptName,
                            RPT.FTRptCode,
                            RPTL.FTRptName
                        FROM TCNTUsrFuncRpt             UFR     WITH(NOLOCK)
                        LEFT JOIN TSysReport            RPT     WITH(NOLOCK)    ON UFR.FTUfrGrpRef      = RPT.FTGrpRptCode      AND  UFR.FTUfrRef   = RPT.FTRptCode
                        LEFT JOIN TSysReport_L          RPTL    WITH(NOLOCK)    ON RPT.FTRptCode        = RPTL.FTRptCode        AND  RPTL.FNLngID   = $nLngID 
                        LEFT JOIN TSysReportGrp         RPG     WITH(NOLOCK)    ON RPT.FTGrpRptCode     = RPG.FTGrpRptCode      AND  RPT.FTGrpRptModCode = RPG.FTGrpRptModCode
                        LEFT JOIN TSysReportGrp_L       RPGL    WITH(NOLOCK)    ON RPG.FTGrpRptCode     = RPGL.FTGrpRptCode     AND RPGL.FNLngID    = $nLngID 
                        LEFT JOIN TSysReportModule      RPM     WITH(NOLOCK)    ON RPG.FTGrpRptModCode  = RPM.FTGrpRptModCode
                        LEFT JOIN TSysReportModule_L    RPML    WITH(NOLOCK)    ON RPM.FTGrpRptModCode  = RPML.FTGrpRptModCode  AND RPML.FNLngID    = $nLngID 
                        WHERE 1=1
                        AND UFR.FTRolCode           = '$tRoleCode'
                        AND RPG.FTGrpRptStaUse      = 1
                        AND RPM.FTGrpRptModStaUse   = 1
                        AND RPT.FTRptStaUse         = 1
                        ORDER BY RPM.FNGrpRptModShwSeq ASC,RPG.FNGrpRptShwSeq ASC 
        ";
        $oQuery = $this->db->query($tSQL);    
        if($oQuery->num_rows() > 0) {
            $aList      = $oQuery->result_array();
            $aResult    = array(
                'raItems'   => $aList,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found'
            );
        }
        unset($nLngID);
        unset($tRoleCode);
        unset($tSQL);
        unset($oQuery);
        unset($aList);
        return $aResult;
    }















}




