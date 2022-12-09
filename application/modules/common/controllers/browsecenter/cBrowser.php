<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cBrowser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        $tIDCurrent         = '';
        $tTextSql           = '';
        $tFinalQuery        = '';
        $tOptionBrowseName  = $this->input->post('ptOptionsName');
        $aOptionBrowseData  = $this->input->post('paOptions');

        // $nCurentPage        = $this->input->post('pnCurentPage') ?? 1;
        // $tOldCallBackVal    = $this->input->post('ptCallVal') ?? '';
        // $tOldCallBackText   = $this->input->post('ptCallText') ?? '';
        // $tFilteInput        = $this->input->post('ptFilter') ?? '';
        // $tFilterGride       = $this->input->post('ptFilterGride') ?? '';
        // $tFilterNotIn       = $this->input->post('tNotIn') ?? '';

        $nCurentPage        = (!empty($this->input->post('pnCurentPage')))? $this->input->post('pnCurentPage') : 1;
        $tOldCallBackVal    = (!empty($this->input->post('ptCallVal')))? $this->input->post('ptCallVal') : '';
        $tOldCallBackText   = (!empty($this->input->post('ptCallText')))? $this->input->post('ptCallText') : '';
        $tFilteInput        = (!empty($this->input->post('ptFilter')))? $this->input->post('ptFilter') : '';
        $tFilterGride       = (!empty($this->input->post('ptFilterGride')))? $this->input->post('ptFilterGride') : '';
        $tFilterNotIn       = (!empty($this->input->post('tNotIn')))? $this->input->post('tNotIn') : '';

        // Check Data Browse 
        if($aOptionBrowseData != '' || $aOptionBrowseData != 'undefined'){
            // ============================== ตั้งค่า Parameter หลัก ==============================
            // $nStaBrowseLevel    = $aOptionBrowseData['BrowseLev'] ?? 0;
            $nStaBrowseLevel    = (!empty($this->input->post('BrowseLev')))? $this->input->post('BrowseLev') : 0;

            $tTitleLangPath     = $aOptionBrowseData['Title'][0];           // โฟลเดอร์ Patch ที่ดึงภาษา Title
            $tTitleLangKey      = $aOptionBrowseData['Title'][1];           // Key Name ภาษา Title
            $tTitleHeader       = language($tTitleLangPath,$tTitleLangKey); // ชื่อข้อมูลที่จะทำการ Browse
            $tMasterTable       = $aOptionBrowseData['Table']['Master'];    // Master Table ที่จะทำการ Browse
            $tMasterPK          = $aOptionBrowseData['Table']['PK'];        // Master PK
            $tMasterPKName      = (isset($aOptionBrowseData['Table']['PKName'])) ? $aOptionBrowseData['Table']['PKName'] : ''; // Master PK Name
            $tMasterFK          = (isset($aOptionBrowseData['Table']['FK'])) ? $aOptionBrowseData['Table']['FK'] : ''; // Master FK Check
            $tCallBackType      = $aOptionBrowseData['CallBack']['ReturnType']; // Status Call Back Single Data Or Multi Data
            $tTblPKCenter       = $tMasterTable.".".$tMasterPK;

            // Callback Option
            if (isset($aOptionBrowseData['CallBack']['Text'])):
                $tCallBackTextColumn = explode('.', $aOptionBrowseData['CallBack']['Text'][1]);
            else:
                $tCallBackTextColumn = '';
            endif;
            $tCallBackColumn    = $tCallBackTextColumn[1];

            // Check Page Row Render Gid
            if(isset($aOptionBrowseData['GrideView']['Perpage'])){
                $nPerPage   = $aOptionBrowseData['GrideView']['Perpage'];
            }else{
                $nPerPage   = 5;
            }
            $aDataRowLen    = FCNaHCallLenData($nPerPage, $nCurentPage);
        
            // Check Data เงื่อนไขการ Union
            if(isset($aOptionBrowseData['Union']) && !empty($aOptionBrowseData['Union'])){
                // Foreach Data Union
                $tUnionTextMain = "";
                $tUnionTextSub  = "";
                $aOptionUnion   = $aOptionBrowseData['Union'];
                foreach($aOptionUnion AS $nKey => $aDataUnion){
                    $tTypeUnion         = $aDataUnion['Type'];
                    $tMasterTblUnion    = $aDataUnion['Table']['Master'];
                    $tMasterPKUnion     = $aDataUnion['Table']['PK'];

                    // Set Union Column Select  Options
                    $tUnionSeleted      = "";
                    if(isset($aDataUnion['GrideView'])){
                        if(isset($aDataUnion['GrideView']['DataColumns'])){
                            $aSelectUnion   = $aDataUnion['GrideView']['DataColumns'];
                            if(is_array($aSelectUnion)){
                                $tUnionSeleted  = implode(',',$aSelectUnion);
                            }
                        }
                    }

                    // Set Union Column Join From Options
                    $tUnionJoinTable    = "";
                    if(isset($aDataUnion['Join']['Table'])){
                        for($nUnionJoin = 0; $nUnionJoin < count($aDataUnion['Join']['Table']); $nUnionJoin++) {
                            if(isset($aDataUnion['Join']['SpecialJoin'])){
                                $tUnionJoinTable    .= " ".$aDataUnion['Join']['SpecialJoin'][$nUnionJoin]." ".$aDataUnion['Join']['Table'][$nUnionJoin]." WITH(NOLOCK) ON ".$aDataUnion['Join']['On'][$nUnionJoin]." ";
                            }else{
                                $tUnionJoinTable    .= " LEFT JOIN ".$aDataUnion['Join']['Table'][$nUnionJoin]." WITH(NOLOCK) ON ".$aDataUnion['Join']['On'][$nUnionJoin]." ";
                            }
                        }
                    }

                    // Set Union Data Where From Option
                    $tUnionWhereCondtion    = "";
                    if(isset($aDataUnion['Where'])){
                        if($aDataUnion['Where']['Condition']){
                            for ($nUnionWhere = 0; $nUnionWhere < count($aDataUnion['Where']['Condition']); $nUnionWhere++) {
                                $tUnionWhereCondtion .= " " . $aDataUnion['Where']['Condition'][$nUnionWhere];
                            }
                        }
                    }
                    $tUnionTextSub  .= " ".$tTypeUnion;
                    $tUnionTextSub  .= " SELECT DISTINCT ";
                    $tUnionTextSub  .= $tUnionSeleted;
                    $tUnionTextSub  .= " FROM ".$tMasterTblUnion;
                    $tUnionTextSub  .= $tUnionJoinTable;
                    $tUnionTextSub  .= " WHERE 1=1 ";
                    $tUnionTextSub  .= $tUnionWhereCondtion;
                }

                // Set Column Select From Options
                $tColumnsSelect = "";
                if(isset($aOptionBrowseData['GrideView'])){
                    if(isset($aOptionBrowseData['GrideView']['DataColumns'])){
                        $aColumnsSelect = $aOptionBrowseData['GrideView']['DataColumns'];
                        if(is_array($aColumnsSelect)){
                            $tColumnsSelect = implode(',',$aColumnsSelect);
                        }
                    }
                }

                // Set Column JOIN From Options
                $tTextJoinBrowse    = "";
                if(isset($aOptionBrowseData['Join']['Table'])){
                    for($nJoin = 0; $nJoin < count($aOptionBrowseData['Join']['Table']); $nJoin++) {
                        if(isset($aOptionBrowseData['Join']['SpecialJoin'])){
                            $tTextJoinBrowse    .= " ".$aOptionBrowseData['Join']['SpecialJoin'][$nJoin]." ".$aOptionBrowseData['Join']['Table'][$nJoin]." WITH(NOLOCK) ON ".$aOptionBrowseData['Join']['On'][$nJoin]." ";
                        }else{
                            $tTextJoinBrowse    .= " LEFT JOIN " . $aOptionBrowseData['Join']['Table'][$nJoin] . " WITH(NOLOCK) ON " . $aOptionBrowseData['Join']['On'][$nJoin] . " ";
                        }
                    }
                }

                // Set Data Where From Option
                $tWhereCondtion = "";
                if(isset($aOptionBrowseData['Where'])){
                    if($aOptionBrowseData['Where']['Condition']){
                        for ($nWhere = 0; $nWhere < count($aOptionBrowseData['Where']['Condition']); $nWhere++) {
                            $tWhereCondtion .= " " . $aOptionBrowseData['Where']['Condition'][$nWhere];
                        }
                    }
                }

                $tUnionTextMain .= " SELECT DISTINCT ";
                $tUnionTextMain .= $tColumnsSelect;
                $tUnionTextMain .= " FROM ".$tMasterTable;
                $tUnionTextMain .= $tTextJoinBrowse;
                $tUnionTextMain .= " WHERE 1=1 ";
                $tUnionTextMain .= $tWhereCondtion;

                // Data Order By Option
                $tDataOrderBy   = "";
                if(isset($aOptionBrowseData['GrideView'])){
                    if(isset($aOptionBrowseData['GrideView']['OrderBy']) && !empty($aOptionBrowseData['GrideView']['OrderBy'])){
                        $tOrderBy           = implode(',',$aOptionBrowseData['GrideView']['OrderBy']);
                        $aExplodeOrderBy    = explode(',',$tOrderBy);
                        $aDataOrderBy       = [];
                        foreach($aExplodeOrderBy AS $nKeyOrderBy => $tValueOrderBy){
                            $aExplodeDosOrderBy = explode('.',$tValueOrderBy);
                            array_push($aDataOrderBy,"DATAUNION.".$aExplodeDosOrderBy[1]);
                        }
                        $tDataOrderBy   = implode(',',$aDataOrderBy);
                    }else{
                        $tDataOrderBy   = "DATAUNION.".$tMasterPK;
                    }
                }

                // Data Selet Final Text Select
                $tFinalColumnsSelect    = "";
                if(isset($aOptionBrowseData['GrideView'])){
                    if(isset($aOptionBrowseData['GrideView']['DataColumns']) && !empty($aOptionBrowseData['GrideView']['DataColumns'])){
                        $tColumnSlt         = implode(',',$aOptionBrowseData['GrideView']['DataColumns']);
                        $aExplodeColumnSlt  = explode(',',$tColumnSlt);
                        $aDataColumnslt     = [];
                        foreach($aExplodeColumnSlt AS $nKeyColumnSlt => $tValueColumnSlt){
                            $aExplodeDosColumnSlt   = explode('.',$tValueColumnSlt);
                            array_push($aDataColumnslt,"DATAUNION.".$aExplodeDosColumnSlt[1]);
                        }
                        $tFinalColumnsSelect    = implode(',',$aDataColumnslt);
                    }else{
                        $tFinalColumnsSelect    = "DATAUNION.*";
                    }
                }

                // Filter Not In Condtion
                $tTextFilterNotIn   = "";
                if(isset($aOptionBrowseData['NotIn'])){
                    if(isset($tFilterNotIn) && !empty($tFilterNotIn)){
                        if(!empty($aOptionBrowseData['NotIn']['Table']) && !empty($aOptionBrowseData['NotIn']['Key'])){
                            $tTableFilterNotIn  = "DATAALL".".".$aOptionBrowseData['NotIn']['Key'];
                            $tTextFilterNotIn   = " AND $tTableFilterNotIn NOT IN ('".$tFilterNotIn ."')";
                        }
                    }
                }

                // Filter Gird Search Table
                $tTextFilterGride   = "";
                if(isset($tFilterGride) && !empty($tFilterGride)){
                    if(isset($aOptionBrowseData['GrideView']['DataColumns']) && !empty($aOptionBrowseData['GrideView']['DataColumns'])){
                        $tColumn        = implode(',',$aOptionBrowseData['GrideView']['DataColumns']);
                        $aExplodeColumn = explode(',',$tColumn);
                        $aDataColumn    = [];
                        foreach($aExplodeColumn AS $nKeyColumn => $tValueColumn){
                            $aExplodeDosColumn  = explode('.',$tValueColumn);
                            if($nKeyColumn == 0){
                                $tTextFilterGride   .= " AND ( DATAUNION.".$aExplodeDosColumn['1']." COLLATE THAI_BIN LIKE '%$tFilterGride%' ";
                            }else{
                                $tTextFilterGride   .=  "  OR DATAUNION.".$aExplodeDosColumn['1']." COLLATE THAI_BIN LIKE '%$tFilterGride%' ";
                            }
                        }
                        $tTextFilterGride   .= " ) ";
                    }
                }

                // ================================ Set Text SQL ================================
                $tTextSql   .= " SELECT TOP 15000 DATAALL.* FROM ( ";
                $tTextSql   .= " SELECT DISTINCT";
                $tTextSql   .= " ROW_NUMBER() OVER(ORDER BY ".$tDataOrderBy.") AS FNRowID, ";
                $tTextSql   .= $tFinalColumnsSelect;
                $tTextSql   .= " FROM ( ";
                $tTextSql   .= $tUnionTextMain;
                $tTextSql   .= $tUnionTextSub;
                $tTextSql   .= " ) AS DATAUNION";
                $tTextSql   .= " WHERE 1=1";
                $tTextSql   .= $tTextFilterGride;
                $tTextSql   .= " ) AS DATAALL ";
                $tFinalQuery    .= $tTextSql;
                $tFinalQuery    .= "WHERE DATAALL.FNRowID > $aDataRowLen[0] AND DATAALL.FNRowID <= $aDataRowLen[1] ";
                $tFinalQuery    .= $tTextFilterNotIn;

            }else{
                // Order By Option
                $tOrderBy   = "";
                if(isset($aOptionBrowseData['GrideView']['OrderBy']) && !empty($aOptionBrowseData['GrideView']['OrderBy'])){
                    $tOrderBy   = implode(',',$aOptionBrowseData['GrideView']['OrderBy']);
                }else{
                    $tOrderBy   = "$tTblPKCenter ASC";
                }

                // Check Distinct Data
                if(isset($aOptionBrowseData['GrideView']['DistinctField'])){
                    $aDistinctField = $aOptionBrowseData['GrideView']['DistinctField'];
                }else{
                    $aDistinctField = '';
                }

                // Check Data Row Number
                if(isset($aDistinctField) && !empty($aDistinctField)){
                    $aDataColumnsDF     = $aOptionBrowseData['GrideView']['DataColumns'];
                    $nCountDataColumns  = count($aDataColumnsDF);
                    $tTextResultShow    = '';
                    for($nLoopDF = 0; $nLoopDF < $nCountDataColumns; $nLoopDF++){
                        $tTextShow  = Explode('.',$aDataColumnsDF[$nLoopDF]);
                        $tTextResultShow    .= 'ResultSubquery.'.$tTextShow[1] . ',';
                        // Remove
                        if($nLoopDF == $nCountDataColumns-1){
                            $tResultShow    = substr($tTextResultShow,0,-1);
                        }
                        //orderby
                        if($nLoopDF == $aDistinctField[0]){
                            $tOrderByDistinct   = $tTextShow[1];
                        }
                    }
                    $tTextRowNumber = " SELECT ROW_NUMBER() OVER(ORDER BY ResultSubquery.$tOrderByDistinct) AS FNRowID , $tResultShow FROM ( SELECT ";
                }else{
                    $tTextRowNumber = " SELECT ROW_NUMBER() OVER(ORDER BY $tOrderBy) AS FNRowID,";
                }

                // Select Column From Options
                if(isset($aOptionBrowseData['GrideView'])){
                    if(isset($aOptionBrowseData['GrideView']['DataColumns'])){
                        $aDataColumns   = $aOptionBrowseData['GrideView']['DataColumns'];
                        if(empty($aDistinctField)){
                            if(is_array($aDataColumns)){
                                $tTextColumns   = implode(',', $aDataColumns);
                            }
                        }else{
                            if(is_array($aDataColumns)){
                                $tTextColumns       = '';
                                $tTextLoopDistinct  = '';
                                $nCountColumns      = count($aDataColumns);
                                for($nLoopCl = 0; $nLoopCl < $nCountColumns; $nLoopCl++){
                                    if(isset($aDistinctField[$nLoopCl])){
                                        $tTextLoopDistinct  .= 'DISTINCT('.$aDataColumns[$nLoopCl].')' . ',';
                                    }else{
                                        $tTextLoopDistinct  .= $aDataColumns[$nLoopCl] . ',';
                                    }
                                    if($nLoopCl == $nCountColumns-1){
                                        $tTextColumns   = substr($tTextLoopDistinct,0,-1);
                                    }
                                }
                            }
                        }
                    }
                }

                // Join Table Options
                $tTextJoinBrowse    = '';
                if (isset($aOptionBrowseData['Join']['Table'])){
                    for($nLoopJoin = 0; $nLoopJoin < count($aOptionBrowseData['Join']['Table']); $nLoopJoin++){
                        if(isset($aOptionBrowseData['Join']['SpecialJoin'])){
                            $tTextJoinBrowse    .= " ".$aOptionBrowseData['Join']['SpecialJoin'][$nLoopJoin]." ".$aOptionBrowseData['Join']['Table'][$nLoopJoin]." WITH(NOLOCK) ON ".$aOptionBrowseData['Join']['On'][$nLoopJoin]." ";
                        }else{
                            $tTextJoinBrowse    .= " LEFT JOIN ".$aOptionBrowseData['Join']['Table'][$nLoopJoin]." WITH(NOLOCK) ON ".$aOptionBrowseData['Join']['On'][$nLoopJoin]." ";
                        }
                    }
                }

                // Where Table Options
                $tTextWhereBrowse   = '';
                if(!empty($aDistinctField)){
                    // Where Distinct Field
                    if(isset($aOptionBrowseData['Where'])){
                        if($aOptionBrowseData['Where']['Condition']){
                            for ($nLoopWhereDis = 0; $nLoopWhereDis < count($aOptionBrowseData['Where']['Condition']); $nLoopWhereDis++) {
                                $tTextWhereBrowse   .= " " . $aOptionBrowseData['Where']['Condition'][$nLoopWhereDis];
                            }
                        }
                    }
                    $tTextWhereBrowse   .= ' ) AS ResultSubquery '; 
                }else{
                    // Where Center 
                    if(isset($aOptionBrowseData['Where'])){
                        if ($aOptionBrowseData['Where']['Condition']){
                            for ($nLoopWhere = 0; $nLoopWhere < count($aOptionBrowseData['Where']['Condition']); $nLoopWhere++) {
                                $tTextWhereBrowse   .= " " . $aOptionBrowseData['Where']['Condition'][$nLoopWhere];
                            }
                        }
                    }
                }

                // Filter Data From Selector Browse
                $tTextFilterOption  = '';
                if(isset($aOptionBrowseData['Filter'])){
                    if(isset($tFilteInput) && !empty($tFilteInput)){
                        if (!empty($aOptionBrowseData['Filter']['Table']) && !empty($aOptionBrowseData['Filter']['Key'])){
                            $tTableFilter       = $aOptionBrowseData['Filter']['Table'].".".$aOptionBrowseData['Filter']['Key'];
                            $tTextFilterOption  = " AND $tTableFilter = '".$tFilteInput."'";
                        }
                    }
                }

                // Filter Data From Filter Search
                $tTextFilterGride   = '';
                if(isset($tFilterGride) && !empty($tFilterGride)){
                    $tTextFilterGride   .= " AND ( $tTblPKCenter COLLATE THAI_BIN LIKE '%$tFilterGride%' ";
                    for($nLoopFG=0;$nLoopFG<count($aOptionBrowseData['GrideView']['DataColumns']);$nLoopFG++){
                        $tFilterCol = $aOptionBrowseData['GrideView']['DataColumns'][$nLoopFG];
                        $tTextFilterGride   .=  "  OR $tFilterCol COLLATE THAI_BIN LIKE '%$tFilterGride%' ";
                    }
                    $tTextFilterGride   .= ")";
                }

                // Filter Not In Condtion
                $tTextFilterNotIn   = "";
                if(isset($aOptionBrowseData['NotIn'])){
                    if(isset($tFilterNotIn) && !empty($tFilterNotIn)){
                        if(!empty($aOptionBrowseData['NotIn']['Table']) && !empty($aOptionBrowseData['NotIn']['Key'])){
                            $tTableFilterNotIn  = "DATAALL".".".$aOptionBrowseData['NotIn']['Key'];
                            $tTextFilterNotIn   = " AND $tTableFilterNotIn NOT IN ('".$tFilterNotIn ."')";
                        }
                    }
                }

                // ================================ Set Text SQL ================================
                $tTextSql   .= " SELECT TOP 15000 DATAALL.* FROM ( ";
                $tTextSql   .= $tTextRowNumber;
                $tTextSql   .= $tTextColumns;
                $tTextSql   .= " FROM $tMasterTable";
                $tTextSql   .= $tTextJoinBrowse;
                $tTextSql   .= " WHERE 1=1 ";
                $tTextSql   .= $tTextWhereBrowse;
                $tTextSql   .= $tTextFilterOption;
                $tTextSql   .= $tTextFilterGride;
                $tTextSql   .= " ) DATAALL";

                $tFinalQuery    .= $tTextSql;
                $tFinalQuery    .= " WHERE 1=1 AND DATAALL.FNRowID > $aDataRowLen[0] AND DATAALL.FNRowID <= $aDataRowLen[1] ";
                $tFinalQuery    .= $tTextFilterNotIn;
            }

            // Check Data Table Heard Gird In Option
            $tHtmlTableHeard    = "";
            for($c = 0; $c < count($aOptionBrowseData['GrideView']['DataColumns']); $c++){
                if(isset($aOptionBrowseData['GrideView']['ColumnsSize'][$c])) {
                    $nColumnSizeHeard   = $aOptionBrowseData['GrideView']['ColumnsSize'][$c];
                } else {
                    $nColumnSizeHeard   = '';
                }

                // ที่อยู่ Lang Path หัวตาราง
                $tlangPathTableHeard    = $aOptionBrowseData['GrideView']['ColumnPathLang'];

                // Loop Check Key Table Lang Heard
                if(isset($aOptionBrowseData['GrideView']['ColumnKeyLang'][$c])){
                    $tlangKeyTableHeard = $aOptionBrowseData['GrideView']['ColumnKeyLang'][$c];
                }else{
                    $tlangKeyTableHeard = "N/A";
                }

                if(isset($aOptionBrowseData['GrideView']['DisabledColumns'])){
                    if($this->JCNtColDisabled($c,$aOptionBrowseData['GrideView']['DisabledColumns']) == false){
                        $tHtmlTableHeard    .= "<th class='xCNTextBold' style='text-align:center' width='$nColumnSizeHeard'>".language($tlangPathTableHeard,$tlangKeyTableHeard)."</th>";
                    }
                }else{
                    $tHtmlTableHeard        .= "<th class='xCNTextBold' style='text-align:center' width='$nColumnSizeHeard'>".language($tlangPathTableHeard,$tlangKeyTableHeard)."</th>";
                }
            }

            // Check Data Table Body
            $oQuery         = $this->db->query($tFinalQuery);
            $nTotalRecord   = ceil($this->FMnCBWSGetRecord($tTextSql));
            $tHtmlTableData = "";
            if($oQuery->num_rows() > 0){
                $aDataTable = $oQuery->result();
                $nIdx       = 0;
                $tRowActive = '';
                if($tMasterFK != '') {
                    $tMasterPK = $tMasterFK;
                }
                // Foreach Data
                foreach($aDataTable as $nKeyData => $aValueData){
                    if($tIDCurrent != $aValueData->$tMasterPK){
                        if(isset($aOptionBrowseData['CallBack']['StaSingItem'])){
                            if ($aOptionBrowseData['CallBack']['StaSingItem'] == '1') {
                                $tIDCurrent = $aValueData->$tMasterPK;
                            }
                        }
                        $tCallBackTextData = $aValueData->$tCallBackColumn;
                        // Check Call Back Type
                        if ($tCallBackType == 'S'){
                            // =========================== Condition Single ===========================
                            if ($aValueData->$tMasterPK === $tOldCallBackVal) {
                                //Option Sta Doc
                                if (isset($aOptionBrowseData['CallBack']['StaDoc'])) {
                                    $CallBackStaDoc = $aOptionBrowseData['CallBack']['StaDoc'];
                                    if($CallBackStaDoc == 1) {
                                        $tRowActive = "xCNHide";
                                    }else{
                                        $tRowActive = "active  1 ";
                                    }
                                }else{
                                    $tRowActive = "active   2 ";
                                }
                            }else{
                                $tRowActive = "";
                            }
                            $tHtmlTableData .= '<tr class="xCNTextDetail2 ' . $tRowActive . '" onclick="JCNxPushSelection(' . "'" . $aValueData->$tMasterPK . "',this" . ')" ondblclick="JCNxDoubleClickSelection(' . "'" . $aValueData->$tMasterPK . "',this,". "'" . $tOptionBrowseName . "'".')">';
                            // =========================== Condition Single ===========================
                        }elseif($tCallBackType == 'M'){
                            if(is_array($tOldCallBackVal)){
                                if(in_array($aValueData->$tMasterPK, $tOldCallBackVal)){
                                    //Option Sta Doc
                                    if (isset($aOptionBrowseData['CallBack']['StaDoc'])) {
                                        $CallBackStaDoc = $aOptionBrowseData['CallBack']['StaDoc'];
                                        if(($CallBackStaDoc == 1) || ($CallBackStaDoc == 2)) {
                                            if($CallBackStaDoc == 1){ // Hide for select
                                                $tRowActive = "xCNHide";
                                            }
                                            if ($CallBackStaDoc == 2) { // Show with unactive
                                                $tRowActive = "";
                                            }
                                        } else {
                                            $tRowActive = "active  3 ";
                                        }
                                    } else {
                                        $tRowActive = "active  4 ";
                                    }
                                } else {
                                    $tRowActive = "";
                                }
                            } else {
                                $tRowActive = "";
                            }

                            $tHtmlTableData .= '<tr class="xCNTextDetail2 ' . $tRowActive . ' " onclick="JCNxPushMultiSelection(' . "'" . $aValueData->$tMasterPK . "','" . $tCallBackTextData . "',this" . ')">';
                        }else{
                            $tHtmlTableData .= '<tr class="xCNTextDetail2" onclick="JCNxPushSelection(' . "'" . $aValueData->$tMasterPK . "',this" . ')" ondblclick="JCNxDoubleClickSelection(' . "'" . $aValueData->$tMasterPK . "',this,". "'" . $tOptionBrowseName . "'".')">';
                        }

                        if (isset($aOptionBrowseData['NextFunc']['ArgReturn'])):
                            $aArgRet = array();
                            for($g = 0; $g < count($aOptionBrowseData['NextFunc']['ArgReturn']); $g++) {
                                $tAgrCol        = $aOptionBrowseData['NextFunc']['ArgReturn'][$g];
                                $aArgRet[$g]    = $aValueData->$tAgrCol;
                            }
                            $tHtmlTableData .= "<input type='hidden' id='ohdCallBackArg" . $aValueData->$tMasterPK . "' value='" . json_encode($aArgRet) . "'" . ">";
                        endif;

                        $tHtmlTableData .= "<input type='hidden' id='ohdCallBackText" . $aValueData->$tMasterPK . "' value='" . $aValueData->$tCallBackColumn . "'" . ">";

                        for ($f = 0; $f < count($aOptionBrowseData['GrideView']['DataColumns']); $f++){

                            $aColumnVal = explode('.', $aOptionBrowseData['GrideView']['DataColumns'][$f]);
                            $tColumnVal = $aColumnVal[1];

                            if (isset($aOptionBrowseData['GrideView']['DataColumnsFormat'])){
                                if (isset($aOptionBrowseData['GrideView']['DataColumnsFormat'][$f])){
                                    if ($aOptionBrowseData['GrideView']['DataColumnsFormat'][$f] != ''):
                                        $aColumnFormat = explode(":", $aOptionBrowseData['GrideView']['DataColumnsFormat'][$f]);
                                        $tFomatType = $aColumnFormat[0];
                                        $tFomatVal = $aColumnFormat[1];
                                    else:
                                        $tFomatType = '';
                                        $tFomatVal = '';
                                    endif;
                                    // Switch Case Format Type
                                    switch ($tFomatType) {
                                        case '':
                                            $tDataDisPlay   = $aValueData->$tColumnVal;
                                            $tTextAlign     = "left!important";
                                            break;
                                        case 'Text':
                                            $tDataDisPlay   = $this->JCNtFormatText($tFomatVal,$aValueData->$tColumnVal);
                                            $tTextAlign     = "left!important";
                                            break;
                                        case 'Date':
                                            $tDataDisPlay   = $this->JCNtFormatDate($tFomatVal, $aValueData->$tColumnVal);
                                            $tTextAlign     = "left!important";
                                            break;
                                        case 'Currency':
                                            if (isset($aColumnFormat[2])):
                                                $tCurrencySign  = $aColumnFormat[2];
                                            else:
                                                $tCurrencySign  = '&#3647;';
                                            endif;
                                            $tDataDisPlay   = $this->JCNtFormatCurrency($tFomatVal, $aValueData->$tColumnVal, $tCurrencySign);
                                            $tTextAlign     = "right!important";
                                            break;

                                        case 'Number':
                                            $tDataDisPlay   = number_format($aValueData->$tColumnVal);
                                            $tTextAlign     = "right!important";
                                            break;
                                        default:
                                            $tDataDisPlay   = $aValueData->$tColumnVal;
                                            $tTextAlign     = "left!important";
                                            break;
                                    }
                                }
                            }else{
                                $tDataDisPlay   = $aValueData->$tColumnVal;
                                $tTextAlign     = "left!important";
                            }

                            if (isset($aOptionBrowseData['GrideView']['DisabledColumns'])) {
                                if ($this->JCNtColDisabled($f, $aOptionBrowseData['GrideView']['DisabledColumns']) == false) {
                                    $tHtmlTableData .= "<td style='text-align:$tTextAlign'>" . $this->JCNtColChkNull($tDataDisPlay) . "</td>";
                                }
                            } else {
                                $tHtmlTableData .= "<td style='text-align:$tTextAlign'>" . $this->JCNtColChkNull($tDataDisPlay) . "</td>";
                            }
                        }
                        $tHtmlTableData .= "</tr>";
                        $nIdx++;
                    }
                }
            }else{
                $nCountColData  =  count($aOptionBrowseData['GrideView']['DataColumns']);
                $nColspanData   =  $nCountColData;
                $tHtmlTableData .=  "<tr><td colspan='".$nColspanData."' style='text-align:center';>";
                $tHtmlTableData .=  language('common/main/main', 'tCMNNotFoundData');
                $tHtmlTableData .=  "</td></tr>";
            }

            // Text Input Call Back
            $tHtmlInputCallBack = '';
            if($tCallBackType == 'S') {
                $tHtmlInputCallBack .= '<input type="text" class="xCNHide" id="oetCallBackVal"  value="' . $tOldCallBackVal . '">';
                $tHtmlInputCallBack .= '<input type="text" class="xCNHide" id="oetCallBackText" value="' . $tOldCallBackText . '">';
            }

            $aDataConfigView    = [
                'nStaBrowseLevel'       => $nStaBrowseLevel,
                'nCurentPage'           => $nCurentPage,
                'nPerPage'              => $nPerPage,
                'nTotalRecord'          => $nTotalRecord,
                'tTitleHeader'          => $tTitleHeader,
                'tOptionName'           => $tOptionBrowseName,
                'tFilterSearch'         => $tFilterGride,
                'tOldCallBackVal'       => $tOldCallBackVal,
                'tOldCallBackText'      => $tOldCallBackText,
                'tHtmlTableHeard'       => $tHtmlTableHeard,
                'tHtmlTableData'        => $tHtmlTableData,
                'tHtmlInputCallBack'    => $tHtmlInputCallBack,
            ];
            $this->load->view('common/browsecenter/wBrowseCenter',$aDataConfigView);
        }
        if(isset($aOptionBrowseData['DebugSQL']) && $aOptionBrowseData['DebugSQL'] == true){
            echo $tFinalQuery;
        }
    }

    private function FMnCBWSGetRecord($ptQuery) {
        $oQuery = $this->db->query($ptQuery);
        return $oQuery->num_rows();
    }

    private function JCNtFormatText($paFomatSetVal, $ptOriData) {

        if ($paFomatSetVal != ''):
            return substr($ptOriData, 0, $paFomatSetVal);
        else:
            return $ptOriData;
        endif;
    }

    private function JCNtFormatDate($paFomatSetVal, $ptOriData) {

        if ($paFomatSetVal != ''):
            switch ($paFomatSetVal) {

                case 'YYYY-MM-DD':
                    return substr($ptOriData, 0, 10);
                    break;

                case 'DD-MM-YYYY':

                    $tNewDataFormat = substr($ptOriData, 0, 10);

                    $aNewDataFormat = explode("-", $tNewDataFormat);

                    return $aNewDataFormat[2] . "-" . $aNewDataFormat[1] . "-" . $aNewDataFormat[0];

                    break;

                case 'MM-DD-YYYY':

                    $tNewDataFormat = substr($ptOriData, 0, 10);

                    $aNewDataFormat = explode("-", $tNewDataFormat);

                    return $aNewDataFormat[1] . "-" . $aNewDataFormat[2] . "-" . $aNewDataFormat[0];
                    break;

                case 'YYYY/MM/DD':
                    $tNewDataFormat = substr($ptOriData, 0, 10);

                    $aNewDataFormat = explode("-", $tNewDataFormat);

                    return $aNewDataFormat[0] . "/" . $aNewDataFormat[1] . "/" . $aNewDataFormat[2];

                    break;

                case 'DD/MM/YYYY':
                    $tNewDataFormat = substr($ptOriData, 0, 10);

                    $aNewDataFormat = explode("-", $tNewDataFormat);

                    return $aNewDataFormat[2] . "/" . $aNewDataFormat[1] . "/" . $aNewDataFormat[0];

                    break;

                case 'MM/DD/YYYY':
                    $tNewDataFormat = substr($ptOriData, 0, 10);

                    $aNewDataFormat = explode("-", $tNewDataFormat);

                    return $aNewDataFormat[1] . "/" . $aNewDataFormat[2] . "/" . $aNewDataFormat[0];

                    break;

                default:
                    return substr($ptOriData, 0, 10);
                    break;
            }

        else:
            return $ptOriData;
        endif;
    }

    private function JCNtFormatCurrency($paFomatSetVal, $ptOriData, $ptCurrencySign) {

        if ($paFomatSetVal != ''):
            $cCurrency = number_format($ptOriData, $paFomatSetVal);
            return $ptCurrencySign . ' ' . $cCurrency;
        else:
            $cCurrency = number_format($ptOriData);
            return $ptCurrencySign . ' ' . $cCurrency;
        endif;
    }

    private function JCNtColDisabled($pnInx, $paDisable) {

        if (in_array($pnInx, $paDisable)) {
            return true;
        } else {
            return false;
        }
    }

    private function JCNtColChkNull($ptData) {

        if ($ptData != '' || $ptData != null) {
            return $ptData;
        } else {
            return 'N/A';
        }
    }

}


