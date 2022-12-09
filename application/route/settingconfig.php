<?php

// ตั้งค่าระบบ
$route ['SettingConfig/(:any)/(:any)']      = 'settingconfig/settingconfig/cSettingconfig/index/$1/$2';
$route ['SettingConfigGetList']             = 'settingconfig/settingconfig/cSettingconfig/FSvSETGetPageList';

//Content ในตั้งค่าระบบ
$route ['SettingConfigLoadViewSearch']      = 'settingconfig/settingconfig/cSettingconfig/FSvSETGetPageListSearch';
$route ['SettingConfigLoadTable']           = 'settingconfig/settingconfig/cSettingconfig/FSvSETSettingGetTable';
$route ['SettingConfigSave']                = 'settingconfig/settingconfig/cSettingconfig/FSxSETSettingEventSave';
$route ['SettingConfigUseDefaultValue']     = 'settingconfig/settingconfig/cSettingconfig/FSxSETSettingUseDefaultValue';

//Content รหัสอัตโนมัติ
$route ['SettingAutonumberLoadViewSearch']  = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberGetPageListSearch';
$route ['SettingAutonumberLoadTable']       = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberSettingGetTable';
$route ['SettingAutonumberLoadPageEdit']    = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberPageEdit';
$route ['SettingAutonumberSave']            = 'settingconfig/settingconfig/cSettingconfig/FSvSETAutonumberEventSave';

//////////////////////////////////////// Menu //////////////////////////////////////////////////////////////////////////////
//ตั้งค่าเมนู
$route['settingmenu/(:any)/(:any)']          = 'settingconfig/settingmenu/cSettingmenu/index/$1/$2';
$route['SettingMenuGetPage']                 = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUGetPageSettingmenu';

//Module
$route['SettingMenuAddEditModule']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUAddEditModule';

$route['CallModalModulEdit']                 = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallModalEditModule';
$route['SettingMenuDelModule']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUDelModule';

//MenuGrp
$route['SettingMenuAddEditMenuGrp']              = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUAddEditMenuGrp';
$route['CallModalMenuGrpEdit']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallModalEditMenuGrp';
$route['SettingMenuDelMenuGrp']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUDelMenuGrp';


//MenuList
$route['SettingMenuAddEditMenuList']              = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUAddEditMenuList';
$route['CallModalMenuListEdit']               = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallModalEditMenuList';
$route['SettingMenuDelMenuList']              = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUDelMenuList';

//StaUse
$route['UpdateStaUse']                        = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUUpdateStaUse';


$route['CallMaxValueSequence']                = 'settingconfig/settingmenu/cSettingmenu/FSxCSMUCallMaxSequence';

//////////////////////////////////////// Report ////////////////////////////////////////////////////////////////////////////

$route['SettingReportGetPage']                = 'settingconfig/settingmenu/cSettingreport/FSxCSRTGetPageSettingreport';

$route['CallMaxValueSequenceAndRptCode']      = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallMaxSequence';

//Module Rpt
$route['SettingReportAddUpdateModule']       = 'settingconfig/settingmenu/cSettingreport/FSxCSRTReportAddUpdateModule';
$route['SettingReportCallEditModuleRpt']     = 'settingconfig/settingmenu/cSettingreport/FSxCSRTReportCallMoalEditModulRpt';
$route['SettingReportDelModule']             = 'settingconfig/settingmenu/cSettingreport/FSxCSRTDelModuleReport';


//ReportGrp
$route['SettingReportAddEditRptGrp']           = 'settingconfig/settingmenu/cSettingreport/FSxCSRTAddEditRptGrp';
$route['CallModalReportGrpEdit']               = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallModalEditRptGrp';
$route['SettingReportDelRptGrp']               = 'settingconfig/settingmenu/cSettingreport/FSxCSRTDelReportGrp';

//ReportMenu
$route['SettingReportAddEditRptMenu']           = 'settingconfig/settingmenu/cSettingreport/FSxCSRTReportAddUpdateMenu';
$route['CallModalReportMenuEdit']               = 'settingconfig/settingmenu/cSettingreport/FSxCSRTCallModalEditRptMenu';
$route['SettingReportDelMenu']                  = 'settingconfig/settingmenu/cSettingreport/FSxCSRTDelMenuReport';


// กำหนดเงื่อนไขช่วงการตรวจสอบ
// Create By Witsarut 07-10-2020
$route ['settingconperiod/(:any)/(:any)']       = 'settingconfig/settingconperiod/cSettingconperiod/index/$1/$2';
$route ['settingconperiodList']                 = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMListPage';
$route ['settingconperiodDataTable']            = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMDataList';
$route ['settingconperiodPageAdd']              = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMAddPage';
$route ['settingconperiodDataCheckRolCode']     = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMChkRole';
$route ['settingconperiodPageEdit']             = 'settingconfig/settingconperiod/cSettingconperiod/FSvCLIMEditPage';
$route ['settingconperiodEventDelete']          = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMDeleteEvent';
$route ['settingconperiodEventDeleteMultiple']  = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMDeleteMultiEvent';
$route ['settingconperiodEventAdd']             = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMAddEvent';
$route ['settingconperiodEventEdit']            = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMEditEvent';
// $route ['settingconperiodDataCheckStaAlwSeq']   = 'settingconfig/settingconperiod/cSettingconperiod/FSaCLIMChkStaAlwSeq';




