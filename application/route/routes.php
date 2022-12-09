<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

// $route ['DataDic'] = 'cDataDic/index';
// // $route['default_controller'] = 'authen/cLogin';
// $route ['default_controller'] = 'common/cHome';

// //Browse
// $route ['BrowseData'] = 'common/cBrowser/index';

// // GenCode
// $route ['generateCode']             = 'common/cCommon/FCNtCCMMGenCode';
// $route ['CheckInputGenCode']        = 'common/cCommon/FCNtCCMMCheckInputGenCode';
// $route ['GetPanalLangSystemHTML']   = 'common/cCommon/FCNtCCMMGetLangSystem';
// $route ['GetPanalLangListHTML']     = 'common/cCommon/FCNtCCMMChangeLangList';

// // language
// $route ['ChangeLang/(:any)/(:num)'] = 'cLanguage/index/$1/$2';
// $route ['ChangeLangEdit']           = 'cLanguage/FSxChangeLangEdit';
// $route ['ChangeBtnSaveAction']      = 'cLanguage/FSxChangeBtnSaveAction';

// //Image Temp.
// $route ['ImageCallMaster']  = 'common/cTempImg/FSaCallMasterImage';
// $route ['ImageCallTemp']    = 'common/cTempImg/FSaCallTempImage';
// $route ['ImageCallTempNEW']    = 'common/cTempImg/FSaCallTempImageNEW';
// $route ['ImageDeleteFileNEW']  = 'common/cTempImg/FSoImageDeleteNEW';
// $route ['ImageUplodeNEW']      = 'common/cTempImg/FSaImageUplodeNEW';

// $route ['ImageUplode']      = 'common/cTempImg/FSaImageUplode';
// $route ['ImageConvertCrop'] = 'common/cTempImg/FSoConvertSizeCrop';
// $route ['ImageDeleteFile']  = 'common/cTempImg/FSoImageDelete';


// // Authencation
// $route ['user']         = 'authen/cUser';
// $route ['login']        = 'authen/cLogin';
// $route ['logout']       = 'authen/cLogout';
// $route ['CheckSession'] = 'authen/cSession/FCNnCheckSession';
// $route ['checklogin']   = 'authen/cLogin/FSnCLOGChkLogin';

// // language
// $route ['ChangeLang/(:any)/(:num)'] = 'cLanguage/index/$1/$2';
// $route ['ChangeLangEdit'] = 'cLanguage/FSxChangeLangEdit';
// $route ['ChangeBtnSaveAction'] = 'cLanguage/FSxChangeBtnSaveAction';

// // Company
// $route ['company/(:any)/(:any)']        = 'pos5/company/cCompany/index/$1/$2';
// $route ['companyCheckUserLevel']        = 'pos5/company/cCompany/FSvCheckUserLevel';
// $route ['companyList']          		= 'pos5/company/cCompany/FSvCMPListPage';
// $route ['companyPageAdd']				= 'pos5/company/cCompany/FSvCMPAddPage';
// $route ['companyEventAdd']              = 'pos5/company/cCompany/FSoCMPAddEvent';
// $route ['companyEventEdit']				= 'pos5/company/cCompany/FSoCMPEditEvent';
// $route ['companyEventAddVat']           = 'pos5/company/cCompany/FSaCMPAddVat';
// $route ['companyEventCallAddress']      = 'pos5/company/cCompany/FSoCMPCallAddress';

// // User
// $route ['user/(:any)/(:any)']   = 'pos5/user/cUser/index/$1/$2';
// $route ['userList']             = 'pos5/user/cUser/FSvUSRListPage';
// $route ['userDataTable']        = 'pos5/user/cUser/FSvUSRDataList';
// $route ['userPageAdd']          = 'pos5/user/cUser/FSvUSRAddPage';
// $route ['userPageEdit']         = 'pos5/user/cUser/FSvUSREditPage';
// $route ['userEventAdd']         = 'pos5/user/cUser/FSoUSRAddEvent';
// $route ['userEventEdit']        = 'pos5/user/cUser/FSoUSREditEvent';
// $route ['userEventDelete']      = 'pos5/user/cUser/FSoUSRDeleteEvent';

// //Card
// $route ['card/(:any)/(:any)']    = 'pos5/card/cCard/index/$1/$2';
// $route ['cardList']              = 'pos5/card/cCard/FSvCCRDListPage';
// $route ['cardDataTable']         = 'pos5/card/cCard/FSvCCRDDataList';
// $route ['cardPageAdd']           = 'pos5/card/cCard/FSvCCRDAddPage';
// $route ['cardPageEdit']          = 'pos5/card/cCard/FSvCCRDEditPage';
// $route ['cardEventAdd']          = 'pos5/card/cCard/FSoCCRDAddEvent';
// $route ['cardEventEdit']         = 'pos5/card/cCard/FSoCCRDEditEvent';
// $route ['cardEventDelete']       = 'pos5/card/cCard/FSoCCRDDeleteEvent';
// $route ['checkStatusActive']     = "pos5/card/cCard/FSvCCRDChkStaAct";    // Add Check status Active

// // Card Shift
// $route ['cardShift/(:any)/(:any)'] = 'pos5/cardShift/cCardShift/index/$1/$2';
// $route ['cardShiftPanel'] = 'pos5/cardShift/cCardShift/FSvCardShiftPanelPage';
// $route ['cardShiftPanelEventInOut'] = 'pos5/cardShift/cCardShift/FSvCardShiftInOutEvent';
// $route ['cardShiftList'] = 'pos5/cardShift/cCardShift/FSvCardShiftListPage';
// $route ['cardShiftDataTable'] = 'pos5/cardShift/cCardShift/FSvCardShiftDataList';
// $route ['cardShiftPageAdd'] = 'pos5/cardShift/cCardShift/FSvCardShiftAddPage';
// $route ['cardShiftEventAdd'] = 'pos5/cardShift/cCardShift/FSaCardShiftAddEvent';
// $route ['cardShiftPageEdit'] = 'pos5/cardShift/cCardShift/FSvCardShiftEditPage';
// $route ['cardShiftEventEdit'] = 'pos5/cardShift/cCardShift/FSaCardShiftEditEvent';
// $route ['cardShiftDeleteMulti'] = 'pos5/cardShift/cCardShift/FSoCardShiftDeleteMulti';
// $route ['cardShiftDelete'] = 'pos5/cardShift/cCardShift/FSoCardShiftDelete';
// $route ['cardShiftUniqueValidate/(:any)'] = 'pos5/cardShift/cCardShift/FStCardShiftUniqueValidate/$1';

// //CardType (ประเภทบัตร)
// $route ['cardtype/(:any)/(:any)']       = 'pos5/cardtype/cCardType/index/$1/$2';
// $route ['cardtypeList']                 = 'pos5/cardtype/cCardType/FSvCCTYListPage';
// $route ['cardtypeDataTable']            = 'pos5/cardtype/cCardType/FSvCCTYDataList';
// $route ['cardtypePageAdd']              = 'pos5/cardtype/cCardType/FSvCCTYAddPage';
// $route ['cardtypePageEdit']             = 'pos5/cardtype/cCardType/FSvCCTYEditPage';
// $route ['cardtypeEventAdd']             = 'pos5/cardtype/cCardType/FSoCCTYAddEvent';
// $route ['cardtypeEventEdit']            = 'pos5/cardtype/cCardType/FSoCCTYEditEvent';
// $route ['cardtypeEventDelete']          = 'pos5/cardtype/cCardType/FSoCCTYDeleteEvent';

// //Card Import - Export (นำเข้า-ส่งออก ข้อมูลบัตร)
// $route ['cardmngdata/(:any)/(:any)']            = 'pos5/cardmngdata/cCardMngData/index/$1/$2';
// $route ['cardmngdataFromList']                  = 'pos5/cardmngdata/cCardMngData/FSvCCMDFromList';
// $route ['cardmngdataImpFileDataList']           = 'pos5/cardmngdata/cCardMngData/FSvCCMDImpFileDataList';
// $route ['cardmngdataExpFileDataList']           = 'pos5/cardmngdata/cCardMngData/FSvCCMDExpFileDataList';
// $route ['cardmngdataTopUpUpdateInlineOnTemp']   = 'pos5/cardmngdata/cCardMngData/FSxCTopUpUpdateInlineOnTemp';
// $route ['cardmngdataNewCardUpdateInlineOnTemp'] = 'pos5/cardmngdata/cCardMngData/FSxCNewCardUpdateInlineOnTemp';
// $route ['cardmngdataClearUpdateInlineOnTemp']   = 'pos5/cardmngdata/cCardMngData/FSxCClearUpdateInlineOnTemp';
// $route ['cardmngdataProcessImport']             = 'pos5/cardmngdata/cCardMngData/FSoCCMDProcessImport';
// $route ['cardmngdataProcessExport']             = 'pos5/cardmngdata/cCardMngData/FSoCCMDProcessExport';

// // Card Shift Out
// $route ['cardShiftOut/(:any)/(:any)']                   = 'pos5/cardShiftOut/cCardShiftOut/index/$1/$2';
// $route ['cardShiftOutList']                             = 'pos5/cardShiftOut/cCardShiftOut/FSvCardShiftOutListPage';
// $route ['cardShiftOutDataTable']                        = 'pos5/cardShiftOut/cCardShiftOut/FSvCardShiftOutDataList';
// $route ['cardShiftOutDataSourceTable']                  = 'pos5/cardShiftOut/cCardShiftOut/FSvCardShiftOutDataSourceList';
// $route ['cardShiftOutDataSourceTableByFile']            = 'pos5/cardShiftOut/cCardShiftOut/FSvCardShiftOutDataSourceListByFile';
// $route ['cardShiftOutPageAdd']                          = 'pos5/cardShiftOut/cCardShiftOut/FSvCardShiftOutAddPage';
// $route ['cardShiftOutEventAdd']                         = 'pos5/cardShiftOut/cCardShiftOut/FSaCardShiftOutAddEvent';
// $route ['cardShiftOutPageEdit']                         = 'pos5/cardShiftOut/cCardShiftOut/FSvCardShiftOutEditPage';
// $route ['cardShiftOutEventEdit']                        = 'pos5/cardShiftOut/cCardShiftOut/FSaCardShiftOutEditEvent';
// $route ['cardShiftOutEventUpdateApvDocAndCancelDoc']    = 'pos5/cardShiftOut/cCardShiftOut/FSaCardShiftOutUpdateApvDocAndCancelDocEvent';
// // $route ['cardShiftOutDeleteMulti']                   = 'pos5/cardShiftOut/cCardShiftOut/FSoCardShiftOutDeleteMulti';
// // $route ['cardShiftOutDelete']                        = 'pos5/cardShiftOut/cCardShiftOut/FSoCardShiftOutDelete';
// $route ['cardShiftOutUpdateInlineOnTemp']               = 'pos5/cardShiftOut/cCardShiftOut/FSxCardShiftOutUpdateInlineOnTemp';
// $route ['cardShiftOutInsertToTemp']                     = 'pos5/cardShiftOut/cCardShiftOut/FSxCardShiftOutInsertToTemp';
// $route ['cardShiftOutUniqueValidate/(:any)']            = 'pos5/cardShiftOut/cCardShiftOut/FStCardShiftOutUniqueValidate/$1';

// // Card Shift Return
// $route ['cardShiftReturn/(:any)/(:any)']                    = 'pos5/cardShiftReturn/cCardShiftReturn/index/$1/$2';
// $route ['cardShiftReturnList']                              = 'pos5/cardShiftReturn/cCardShiftReturn/FSvCardShiftReturnListPage';
// $route ['cardShiftReturnDataTable']                         = 'pos5/cardShiftReturn/cCardShiftReturn/FSvCardShiftReturnDataList';
// $route ['cardShiftReturnDataSourceTable']                   = 'pos5/cardShiftReturn/cCardShiftReturn/FSvCardShiftReturnDataSourceList';
// $route ['cardShiftReturnDataSourceTableByFile']             = 'pos5/cardShiftReturn/cCardShiftReturn/FSvCardShiftReturnDataSourceListByFile';
// $route ['cardShiftReturnPageAdd']                           = 'pos5/cardShiftReturn/cCardShiftReturn/FSvCardShiftReturnAddPage';
// $route ['cardShiftReturnEventAdd']                          = 'pos5/cardShiftReturn/cCardShiftReturn/FSaCardShiftReturnAddEvent';
// $route ['cardShiftReturnPageEdit']                          = 'pos5/cardShiftReturn/cCardShiftReturn/FSvCardShiftReturnEditPage';
// $route ['cardShiftReturnEventEdit']                         = 'pos5/cardShiftReturn/cCardShiftReturn/FSaCardShiftReturnEditEvent';
// $route ['cardShiftReturnEventUpdateApvDocAndCancelDoc']     = 'pos5/cardShiftReturn/cCardShiftReturn/FSaCardShiftReturnUpdateApvDocAndCancelDocEvent';
// $route ['cardShiftReturnGetCardOnHD']                       = 'pos5/cardShiftReturn/cCardShiftReturn/FSaCardShiftReturnGetCardOnHD';
// $route ['cardShiftReturnUniqueValidate/(:any)']             = 'pos5/cardShiftReturn/cCardShiftReturn/FStCardShiftReturnUniqueValidate/$1';
// $route ['cardShiftReturnUpdateInlineOnTemp']                = 'pos5/cardShiftReturn/cCardShiftReturn/FSxCardShiftReturnUpdateInlineOnTemp';
// $route ['cardShiftReturnInsertToTemp']                      = 'pos5/cardShiftReturn/cCardShiftReturn/FSxCardShiftReturnInsertToTemp';


// // Card Shift TopUp
// $route ['cardShiftTopUp/(:any)/(:any)']                 = 'pos5/cardShiftTopUp/cCardShiftTopUp/index/$1/$2';
// $route ['cardShiftTopUpList']                           = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSvCardShiftTopUpListPage';
// $route ['cardShiftTopUpDataTable']                      = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSvCardShiftTopUpDataList';
// $route ['cardShiftTopUpDataSourceTable']                = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSvCardShiftTopUpDataSourceList';
// $route ['cardShiftTopUpDataSourceTableByFile']          = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSvCardShiftTopUpDataSourceListByFile';
// $route ['cardShiftTopUpPageAdd']                        = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSvCardShiftTopUpAddPage';
// $route ['cardShiftTopUpEventAdd']                       = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSaCardShiftTopUpAddEvent';
// $route ['cardShiftTopUpPageEdit']                       = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSvCardShiftTopUpEditPage';
// $route ['cardShiftTopUpEventEdit']                      = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSaCardShiftTopUpEditEvent';
// $route ['cardShiftTopUpEventUpdateApvDocAndCancelDoc']  = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSaCardShiftTopUpUpdateApvDocAndCancelDocEvent';
// $route ['cardShiftTopUpUniqueValidate/(:any)']          = 'pos5/cardShiftTopUp/cCardShiftTopUp/FStCardShiftTopUpUniqueValidate/$1';
// $route ['cardShiftTopUpUpdateInlineOnTemp']             = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSxCardShiftTopUpUpdateInlineOnTemp';
// $route ['cardShiftTopUpInsertToTemp']                   = 'pos5/cardShiftTopUp/cCardShiftTopUp/FSxCardShiftTopUpInsertToTemp';

// // Card Shift Refund
// $route ['cardShiftRefund/(:any)/(:any)'] = 'pos5/cardShiftRefund/cCardShiftRefund/index/$1/$2';
// $route ['cardShiftRefundList'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSvCardShiftRefundListPage';
// $route ['cardShiftRefundDataTable'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSvCardShiftRefundDataList';
// $route ['cardShiftRefundDataSourceTable'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSvCardShiftRefundDataSourceList';
// $route ['cardShiftRefundDataSourceTableByFile'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSvCardShiftRefundDataSourceListByFile';
// $route ['cardShiftRefundPageAdd'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSvCardShiftRefundAddPage';
// $route ['cardShiftRefundEventAdd'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSaCardShiftRefundAddEvent';
// $route ['cardShiftRefundPageEdit'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSvCardShiftRefundEditPage';
// $route ['cardShiftRefundEventEdit'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSaCardShiftRefundEditEvent';
// $route ['cardShiftRefundEventUpdateApvDocAndCancelDoc'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSaCardShiftRefundUpdateApvDocAndCancelDocEvent';
// $route ['cardShiftRefundUpdateInlineOnTemp'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSxCardShiftRefundUpdateInlineOnTemp';
// $route ['cardShiftRefundInsertToTemp'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSxCardShiftRefundInsertToTemp';
// // $route ['cardShiftRefundDeleteMulti'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSoCardShiftRefundDeleteMulti';
// // $route ['cardShiftRefundDelete'] = 'pos5/cardShiftRefund/cCardShiftRefund/FSoCardShiftRefundDelete';
// $route ['cardShiftRefundUniqueValidate/(:any)'] = 'pos5/cardShiftRefund/cCardShiftRefund/FStCardShiftRefundUniqueValidate/$1';

// // Card Shift Status
// $route ['cardShiftStatus/(:any)/(:any)'] = 'pos5/cardShiftStatus/cCardShiftStatus/index/$1/$2';
// $route ['cardShiftStatusList'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSvCardShiftStatusListPage';
// $route ['cardShiftStatusDataTable'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSvCardShiftStatusDataList';
// $route ['cardShiftStatusDataSourceTable'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSvCardShiftStatusDataSourceList';
// $route ['cardShiftStatusDataSourceTableByFile'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSvCardShiftStatusDataSourceListByFile';
// $route ['cardShiftStatusPageAdd'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSvCardShiftStatusAddPage';
// $route ['cardShiftStatusEventAdd'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSaCardShiftStatusAddEvent';
// $route ['cardShiftStatusPageEdit'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSvCardShiftStatusEditPage';
// $route ['cardShiftStatusEventEdit'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSaCardShiftStatusEditEvent';
// $route ['cardShiftStatusEventUpdateApvDocAndCancelDoc'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSaCardShiftStatusUpdateApvDocAndCancelDocEvent';
// $route ['cardShiftStatusUpdateInlineOnTemp'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSxCardShiftStatusUpdateInlineOnTemp';
// $route ['cardShiftStatusInsertToTemp'] = 'pos5/cardShiftStatus/cCardShiftStatus/FSxCardShiftStatusInsertToTemp';
// $route ['cardShiftStatusUniqueValidate/(:any)'] = 'pos5/cardShiftStatus/cCardShiftStatus/FStCardShiftStatusUniqueValidate/$1';

// // Card Shift Change
// $route ['cardShiftChange/(:any)/(:any)'] = 'pos5/cardShiftChange/cCardShiftChange/index/$1/$2';
// $route ['cardShiftChangeList'] = 'pos5/cardShiftChange/cCardShiftChange/FSvCardShiftChangeListPage';
// $route ['cardShiftChangeDataTable'] = 'pos5/cardShiftChange/cCardShiftChange/FSvCardShiftChangeDataList';
// $route ['cardShiftChangeDataSourceTable'] = 'pos5/cardShiftChange/cCardShiftChange/FSvCardShiftChangeDataSourceList';
// $route ['cardShiftChangeDataSourceTableByFile'] = 'pos5/cardShiftChange/cCardShiftChange/FSvCardShiftChangeDataSourceListByFile';
// $route ['cardShiftChangePageAdd'] = 'pos5/cardShiftChange/cCardShiftChange/FSvCardShiftChangeAddPage';
// $route ['cardShiftChangeEventAdd'] = 'pos5/cardShiftChange/cCardShiftChange/FSaCardShiftChangeAddEvent';
// $route ['cardShiftChangePageEdit'] = 'pos5/cardShiftChange/cCardShiftChange/FSvCardShiftChangeEditPage';
// $route ['cardShiftChangeEventEdit'] = 'pos5/cardShiftChange/cCardShiftChange/FSaCardShiftChangeEditEvent';
// $route ['cardShiftChangeEventUpdateApvDocAndCancelDoc'] = 'pos5/cardShiftChange/cCardShiftChange/FSaCardShiftChangeUpdateApvDocAndCancelDocEvent';
// $route ['cardShiftChangeUpdateInlineOnTemp'] = 'pos5/cardShiftChange/cCardShiftChange/FSxCardShiftChangeUpdateInlineOnTemp';
// $route ['cardShiftChangeInsertToTemp'] = 'pos5/cardShiftChange/cCardShiftChange/FSxCardShiftChangeInsertToTemp';
// $route ['cardShiftChangeUniqueValidate/(:any)'] = 'pos5/cardShiftChange/cCardShiftChange/FStCardShiftChangeUniqueValidate/$1';
// $route ['cardShiftChangeCardUniqueValidate/(:any)'] = 'pos5/cardShiftChange/cCardShiftChange/FStCardShiftChangeCardUniqueValidate/$1';

// // Vat Rate
// $route['VatRate'] = 'pos5/vatrate/cVateRate/FCNaCVATList';

// $route['vatrate/(:any)/(:any)'] =   'pos5/vatrate/cVatrate/index/$1/$2';
// $route['vatrateList']           =   'pos5/vatrate/cVatrate/FSvVATListPage';
// $route['vatrateDataTable']      =   'pos5/vatrate/cVatrate/FSvVATDataList';
// $route['vatratePageAdd']        =   'pos5/vatrate/cVatrate/FSvVATAddPage';
// $route['vatratePageEdit']       =   'pos5/vatrate/cVatrate/FSvVATEditPage';
// $route['vatrateEventAdd']       =   'pos5/vatrate/cVatrate/FSoVATAddEvent';
// $route['vatrateEventEdit']      =   'pos5/vatrate/cVatrate/FSoVATEditEvent';
// $route['vatrateEventDelete']    =   'pos5/vatrate/cVatrate/FSoVATDeleteEvent';
// $route['vatrateChkDup']         =   'pos5/vatrate/cVatrate/FSoVATChackDup';

// $route['vatrateDeleteMulti']    =   'pos5/vatrate/cVatrate/FSoVATDeleteMultiVat';
// $route['vatrateDelete']    =   'pos5/vatrate/cVatrate/FSoVATDelete';
// $route['vatrateCreateOrUpdate'] =   'pos5/vatrate/cVatrate/FSxVATCreateOrUpdate';
// $route['vatrateUniqueValidate/(:any)'] =   'pos5/vatrate/cVatrate/FStVATUniqueValidate/$1';

// // Department
// $route ['department/(:any)/(:any)']    = 'pos5/department/cDepartment/index/$1/$2';
// $route ['departmentList']              = 'pos5/department/cDepartment/FSvCDPTListPage';
// $route ['departmentDataTable']         = 'pos5/department/cDepartment/FSvCDPTDataList';
// $route ['departmentPageAdd']           = 'pos5/department/cDepartment/FSvCDPTAddPage';
// $route ['departmentPageEdit']          = 'pos5/department/cDepartment/FSvCDPTEditPage';
// $route ['departmentEventAdd']          = 'pos5/department/cDepartment/FSoCDPTAddEvent';
// $route ['departmentEventEdit']         = 'pos5/department/cDepartment/FSoCDPTEditEvent';
// $route ['departmentEventDelete']       = 'pos5/department/cDepartment/FSoCDPTDeleteEvent';


// // Slip Message
// $route ['slipMessage/(:any)/(:any)'] = 'pos5/slipmessage/cSlipMessage/index/$1/$2';
// $route ['slipMessageList'] = 'pos5/slipmessage/cSlipMessage/FSvSMGListPage';
// $route ['slipMessageDataTable'] = 'pos5/slipmessage/cSlipMessage/FSvSMGDataList';
// $route ['slipMessagePageAdd'] = 'pos5/slipmessage/cSlipMessage/FSvSMGAddPage';
// $route ['slipMessageEventAdd'] = 'pos5/slipmessage/cSlipMessage/FSaSMGAddEvent';
// $route ['slipMessagePageEdit'] = 'pos5/slipmessage/cSlipMessage/FSvSMGEditPage';
// $route ['slipMessageEventEdit'] = 'pos5/slipmessage/cSlipMessage/FSaSMGEditEvent';
// $route ['slipMessageDeleteMulti'] = 'pos5/slipmessage/cSlipMessage/FSoSMGDeleteMulti';
// $route ['slipMessageDelete'] = 'pos5/slipmessage/cSlipMessage/FSoSMGDelete';
// $route ['slipMessageUniqueValidate/(:any)'] = 'pos5/slipmessage/cSlipMessage/FStSMGUniqueValidate/$1';

// // Reason
// $route ['reason/(:any)/(:any)']     = 'pos5/reason/cReason/index/$1/$2';
// $route ['reasonList']               = 'pos5/reason/cReason/FSvRSNListPage';
// $route ['reasonDataTable']          = 'pos5/reason/cReason/FSvRSNDataList';
// $route ['reasonPageAdd']            = 'pos5/reason/cReason/FSvRSNAddPage';
// $route ['reasonEventAdd']           = 'pos5/reason/cReason/FSaRSNAddEvent';
// $route ['reasonPageEdit']           = 'pos5/reason/cReason/FSvRSNEditPage';
// $route ['reasonEventEdit']          = 'pos5/reason/cReason/FSaRSNEditEvent';
// $route ['reasonEventDelete']        = 'pos5/reason/cReason/FSaRSNDeleteEvent';

// // Branch
// $route ['branch/(:any)/(:any)']     = 'pos5/branch/cBranch/index/$1/$2';
// $route ['branchList']               = 'pos5/branch/cBranch/FSvCBCHListPage';
// $route ['branchDataTable']          = 'pos5/branch/cBranch/FSvCBCHDataList';
// $route ['branchPageAdd']            = 'pos5/branch/cBranch/FSvCBCHAddPage';
// $route ['branchEventAdd']           = 'pos5/branch/cBranch/FSaCBCHAddEvent';
// $route ['branchPageEdit']           = 'pos5/branch/cBranch/FSvCBCHEditPage';
// $route ['branchEventEdit']          = 'pos5/branch/cBranch/FSaCBCHEditEvent';
// $route ['branchEventDelete']        = 'pos5/branch/cBranch/FSaCBCHDeleteEvent';
// $route ['branchCheckUserLevel']     = 'pos5/branch/cBranch/FSvCBCHCheckUserLevel';
// $route ['branchEventDeleteFolder']  = 'pos5/branch/cBranch/FSaCBCHDeleteEventFolder';
// $route ['branchBrowseWareHouse']    = 'pos5/branch/cBranch/FSoCBCHCallWareHouse';

// //Area (ภูมิภาค)
// $route ['area/(:any)/(:any)']    = 'pos5/area/cArea/index/$1/$2';
// $route ['areaList']              = 'pos5/area/cArea/FSvCAREListPage';
// $route ['areaDataTable']         = 'pos5/area/cArea/FSvCAREDataList';
// $route ['areaPageAdd']           = 'pos5/area/cArea/FSvCAREAddPage';
// $route ['areaPageEdit']          = 'pos5/area/cArea/FSvCAREEditPage';
// $route ['areaEventAdd']          = 'pos5/area/cArea/FSoCAREAddEvent';
// $route ['areaEventEdit']         = 'pos5/area/cArea/FSoCAREEditEvent';
// $route ['areaEventDelete']       = 'pos5/area/cArea/FSoCAREDeleteEvent';

// //Province
// $route ['province/(:any)/(:any)']   = 'pos5/province/cProvince/index/$1/$2';
// $route ['provinceList']             = 'pos5/province/cProvince/FSvPVNListPage';
// $route ['provinceDataTable']        = 'pos5/province/cProvince/FSvPVNDataList';
// $route ['provincePageAdd']          = 'pos5/province/cProvince/FSvPVNAddPage';
// $route ['provinceEventAdd']         = 'pos5/province/cProvince/FSaPVNAddEvent';
// $route ['provincePageEdit']         = 'pos5/province/cProvince/FSvPVNEditPage';
// $route ['provinceEventEdit']        = 'pos5/province/cProvince/FSaPVNEditEvent';
// $route ['provinceEventDelete']      = 'pos5/province/cProvince/FSaPVNDeleteEvent';

// //District
// $route ['district/(:any)/(:any)']       = 'pos5/district/cDistrict/index/$1/$2';
// $route ['districtList']             	= 'pos5/district/cDistrict/FSvDSTListPage';
// $route ['districtDataTable']            = 'pos5/district/cDistrict/FSvDSTDataList';
// $route ['districtPageAdd']          	= 'pos5/district/cDistrict/FSvDSTAddPage';
// $route ['districtEventAdd']         	= 'pos5/district/cDistrict/FSaDSTAddEvent';
// $route ['districtPageEdit']         	= 'pos5/district/cDistrict/FSvDSTEditPage';
// $route ['districtEventEdit']        	= 'pos5/district/cDistrict/FSaDSTEditEvent';
// $route ['districtEventDelete']      	= 'pos5/district/cDistrict/FSaDSTDeleteEvent';
// $route ['districtGetPostCode']      	= 'pos5/district/cDistrict/FSnCDSTGetPostCode';
// $route ['districtBrowseProvince']   	= 'pos5/district/cDistrict/FSoDSTCallProvince';
// $route ['BrowsedistrictWhereProvince']  = 'pos5/district/cDistrict/FSoCPVNCallBrowseDistrictWhereProvince';

// // Sub District
// $route ['subdistrict/(:any)/(:any)']    = 'pos5/subdistrict/cSubdistrict/index/$1/$2';
// $route ['subdistrictList']              = 'pos5/subdistrict/cSubdistrict/FSvSDTListPage';
// $route ['subdistrictDataTable']         = 'pos5/subdistrict/cSubdistrict/FSvSDTDataList';
// $route ['subdistrictPageAdd']           = 'pos5/subdistrict/cSubdistrict/FSvSDTAddPage';
// $route ['subdistrictPageEdit']          = 'pos5/subdistrict/cSubdistrict/FSvSDTEditPage';
// $route ['subdistrictEventAdd']          = 'pos5/subdistrict/cSubdistrict/FSoSDTAddEvent';
// $route ['subdistrictEventEdit']         = 'pos5/subdistrict/cSubdistrict/FSoSDTEditEvent';
// $route ['subdistrictEventDelete']       = 'pos5/subdistrict/cSubdistrict/FSoSDTDeleteEvent';

// //Zone
// $route ['zone/(:any)/(:any)']   = 'pos5/zone/cZone/index/$1/$2';
// $route ['zoneCheckUserLevel']   = 'pos5/zone/cZone/FSvCZNECheckUserLevel';
// $route ['zoneList']             = 'pos5/zone/cZone/FSvCZNEListPage';
// $route ['zoneDataTable']        = 'pos5/zone/cZone/FSvCZNEDataList';
// $route ['zonePageAdd']          = 'pos5/zone/cZone/FSvCZNEAddPage';
// $route ['zoneEventAdd']         = 'pos5/zone/cZone/FSaCZNEAddEvent';
// $route ['zonePageEdit']         = 'pos5/zone/cZone/FSvCZNEEditPage';
// $route ['zoneEventEdit']        = 'pos5/zone/cZone/FSaCZNEEditEvent';
// $route ['zoneEventDelete']      = 'pos5/zone/cZone/FSaCZNEDeleteEvent';

// //Rate
// $route ['rate/(:any)/(:any)']     = 'pos5/rate/cRate/index/$1/$2';
// $route ['rateFormSearchList']     = 'pos5/rate/cRate/FSxCRTEFormSearchList';
// $route ['ratePageAdd']            = 'pos5/rate/cRate/FSxCRTEAddPage';
// $route ['rateDataTable']          = 'pos5/rate/cRate/FSxCRTEDataTable';
// $route ['ratePageEdit']           = 'pos5/rate/cRate/FSvCRTEEditPage';
// $route ['rateEventAdd']           = 'pos5/rate/cRate/FSaCRTEAddEvent';
// $route ['rateEventEdit']          = 'pos5/rate/cRate/FSaCRTEEditEvent';
// $route ['rateEventDelete']        = 'pos5/rate/cRate/FSaCRTEDeleteEvent';

// //Sale Machine (เครื่องจุดขาย)
// $route ['salemachine/(:any)/(:any)'] = 'pos5/salemachine/cSaleMachine/index/$1/$2';
// $route ['salemachineList']           = 'pos5/salemachine/cSaleMachine/FSvCPOSListPage';
// $route ['salemachineDataTable']      = 'pos5/salemachine/cSaleMachine/FSvCPOSDataList';
// $route ['salemachinePageAdd']        = 'pos5/salemachine/cSaleMachine/FSvCPOSAddPage';
// $route ['salemachinePageEdit']       = 'pos5/salemachine/cSaleMachine/FSvCPOSEditPage';
// $route ['salemachineEventAdd']       = 'pos5/salemachine/cSaleMachine/FSoCPOSAddEvent';
// $route ['salemachineEventEdit']      = 'pos5/salemachine/cSaleMachine/FSoCPOSEditEvent';
// $route ['salemachineEventDelete']    = 'pos5/salemachine/cSaleMachine/FSoCPOSDeleteEvent';

// //Sale MachineDevice (เครื่องจุดขายอุปกรณ์)
// $route ['salemachinedevice/(:any)/(:any)']   = 'pos5/salemachinedevice/cSaleMachineDevice/index/$1/$2';
// $route ['salemachinedeviceList']             = 'pos5/salemachinedevice/cSaleMachineDevice/FSvCPHWListPage';
// $route ['salemachinedeviceDataTable']        = 'pos5/salemachinedevice/cSaleMachineDevice/FSvCPHWDataList';
// $route ['salemachinedevicePageAdd']          = 'pos5/salemachinedevice/cSaleMachineDevice/FSvCPHWAddPage';
// $route ['salemachinedevicePageEdit']         = 'pos5/salemachinedevice/cSaleMachineDevice/FSvCPHWEditPage';
// $route ['salemachinedeviceEventAdd']         = 'pos5/salemachinedevice/cSaleMachineDevice/FSoCPHWAddEvent';
// $route ['salemachinedeviceEventEdit']        = 'pos5/salemachinedevice/cSaleMachineDevice/FSoCPHWEditEvent';
// $route ['salemachinedeviceEventDelete']      = 'pos5/salemachinedevice/cSaleMachineDevice/FSoCPHWDeleteEvent';

// //Report
// $route['testreport']              = 'reportcard/cRptUseCard1/testreport';                  /**Report 1 */

// $route['RPTCRD/(:any)/(:any)']                      = 'reportcard/cReport/index/$1/$2';
// $route['RPTCRDExportExcelRptUseCard1']              = 'reportcard/cRptUseCard1/FSoCRPTCRDExportExcel';                  /**Report 1 */
// $route['RPTCRDExportExcelRptCheckStatusCard']       = 'reportcard/cRptCheckStatusCard/FSoCRPTCRDExportExcel';           /**Report 2 */
// $route['RPTCRDExportExcelRptTransferCardInfo']      = 'reportcard/cRptTransferCardInfo/FSoCRPTCRDExportExcel';          /**Report 3 */
// $route['RPTCRDExportExcelRptAdjustCashInCard']      = 'reportcard/cRptAdjustCashInCard/FSoCRPTCRDExportExcel';          /**Report 4 */
// $route['RPTCRDExportExcelRptClearCardValueForReuse']= 'reportcard/cRptClearCardValueForReuse/FSoCRPTCRDExportExcel';    /**Report 5 */
// $route['RPTCRDExportExcelRptCardNoActive']          = 'reportcard/cRptCardNoActive/FSoCRPTCRDExportExcel';              /**Report 6 */
// $route['RPTCRDExportExcelRptCardTimesUsed']         = 'reportcard/cRptCardTimesUsed/FSoCRPTCRDExportExcel';             /**Report 7 */
// $route['RPTCRDExportExcelRptCardBalance']           = 'reportcard/cRptCardBalance/FSoCRPTCRDExportExcel';               /**Report 8 */
// $route['RPTCRDExportExcelRptCollectExpireCard']     = 'reportcard/cRptCollectExpireCard/FSoCRPTCRDExportExcel';         /**Report 9 */
// $route['RPTCRDExportExcelRptCardPrinciple']         = 'reportcard/cRptCardPrinciple/FSoCRPTCRDExportExcel';             /**Report 10 */
// $route['RPTCRDExportExcelRptCardDetail']            = 'reportcard/cRptCardDetail/FSoCRPTCRDExportExcel';                /**Report 11 */
// $route['RPTCRDExportExcelRptCheckPrepaid']          = 'reportcard/cRptCheckPrepaid/FSoCRPTCRDExportExcel';              /**Report 12 */
// $route['RPTCRDExportExcelRptCheckCardUseInfo']      = 'reportcard/cRptCheckCardUseInfo/FSoCRPTCRDExportExcel';          /**Report 13 */
// $route['RPTCRDExportExcelRptTopUp']                 = 'reportcard/cRptTopUp/FSoCRPTCRDExportExcel';                     /**Report 14 */
// $route['RPTCRDExportExcelRptUseCard2']              = 'reportcard/cRptUseCard2/FSoCRPTCRDExportExcel';                  /**Report 15 */

// /** Export PDF Report Card */
// $route['RPTCRDChkDataRptUseCard1']                  = 'reportcard/cRptUseCard1/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptUseCard1']                = 'reportcard/cRptUseCard1/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCheckStatusCard']           = 'reportcard/cRptCheckStatusCard/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCheckStatusCard']         = 'reportcard/cRptCheckStatusCard/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptTransferCardInfo']          = 'reportcard/cRptTransferCardInfo/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptTransferCardInfo']        = 'reportcard/cRptTransferCardInfo/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptAdjustCashInCard']          = 'reportcard/cRptAdjustCashInCard/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptAdjustCashInCard']        = 'reportcard/cRptAdjustCashInCard/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptClearCardValueForReuse']    = 'reportcard/cRptClearCardValueForReuse/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptClearCardValueForReuse']  = 'reportcard/cRptClearCardValueForReuse/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCardNoActive']              = 'reportcard/cRptCardNoActive/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCardNoActive']            = 'reportcard/cRptCardNoActive/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCardTimesUsed']             = 'reportcard/cRptCardTimesUsed/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCardTimesUsed']           = 'reportcard/cRptCardTimesUsed/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCardBalance']               = 'reportcard/cRptCardBalance/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCardBalance']             = 'reportcard/cRptCardBalance/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCollectExpireCard']         = 'reportcard/cRptCollectExpireCard/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCollectExpireCard']       = 'reportcard/cRptCollectExpireCard/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCardPrinciple']             = 'reportcard/cRptCardPrinciple/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCardPrinciple']           = 'reportcard/cRptCardPrinciple/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCardDetail']                = 'reportcard/cRptCardDetail/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCardDetail']              = 'reportcard/cRptCardDetail/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCheckPrepaid']              = 'reportcard/cRptCheckPrepaid/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCheckPrepaid']            = 'reportcard/cRptCheckPrepaid/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptCheckCardUseInfo']          = 'reportcard/cRptCheckCardUseInfo/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptCheckCardUseInfo']        = 'reportcard/cRptCheckCardUseInfo/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptTopUp']                     = 'reportcard/cRptTopUp/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptTopUp']                   = 'reportcard/cRptTopUp/FSoCRPTCRDExportPDF';

// $route['RPTCRDChkDataRptUseCard2']                  = 'reportcard/cRptUseCard2/FSoChkDataExportPDF';
// $route['RPTCRDExportPDFRptUseCard2']                = 'reportcard/cRptUseCard2/FSoCRPTCRDExportPDF';


// //Warehouse
// $route ['warehouse/(:any)/(:any)']  = 'pos5/warehouse/cWarehouse/index/$1/$2';
// $route ['warehouseCheckUserLevel']  = 'pos5/warehouse/cWarehouse/FSvCWAHCheckUserLevel';
// $route ['warehouseList']            = 'pos5/warehouse/cWarehouse/FSvCWAHListPage';
// $route ['warehouseDataTable']       = 'pos5/warehouse/cWarehouse/FSvCWAHDataList';
// $route ['warehousePageAdd']         = 'pos5/warehouse/cWarehouse/FSvCWAHAddPage'; 
// $route ['warehouseEventAdd']        = 'pos5/warehouse/cWarehouse/FSaCWAHAddEvent';
// $route ['warehousePageEdit']        = 'pos5/warehouse/cWarehouse/FSvCWAHEditPage';
// $route ['warehouseEventEdit']       = 'pos5/warehouse/cWarehouse/FSaCWAHEditEvent';
// $route ['warehouseEventDelete']     = 'pos5/warehouse/cWarehouse/FSaCWAHDeleteEvent';
// $route ['xxx']                      = 'pos5/warehouse/cWarehouse/xxx';


// //Role
// $route['role/(:any)/(:any)']    = 'pos5/role/cRole/index/$1/$2';
// $route['roleList']              = 'pos5/role/cRole/FSvROLListPage';
// $route['roleDataTable']         = 'pos5/role/cRole/FSvROLDataList';
// $route['rolePageAdd']           = 'pos5/role/cRole/FSvROLAddPage';
// $route['rolePageEdit']          = 'pos5/role/cRole/FSvROLEditPage';
// $route['roleEventAdd']          = 'pos5/role/cRole/FSoROLAddEvent';
// $route['roleEventEdit']         = 'pos5/role/cRole/FSoROLEditEvent';
// $route['roleEventDelete']       = 'pos5/role/cRole/FSoROLDeleteEvent';

// // Shop
// $route ['shop/(:any)/(:any)']       = 'pos5/shop/cShop/index/$1/$2';
// $route ['shopList']                 = 'pos5/shop/cShop/FSvCSHPListPage';
// $route ['shopDataTable']            = 'pos5/shop/cShop/FSvCSHPDataList';
// $route ['shopListFromBch']          = 'pos5/shop/cShop/FSvCSHPListPageFromBch'; /*From Branch*/
// $route ['branchToShopDataTable']    = 'pos5/shop/cShop/FSvCSHPBranchToShopDataList'; /*From Branch*/
// $route ['shopPageAdd']              = 'pos5/shop/cShop/FSvCSHPAddPage';
// $route ['shopEventAdd']             = 'pos5/shop/cShop/FSaCSHPAddEvent';
// $route ['shopPageEdit']             = 'pos5/shop/cShop/FSvCSHPEditPage';
// $route ['shopEventEdit']            = 'pos5/shop/cShop/FSaCSHPEditEvent';
// $route ['shopEventDelete']          = 'pos5/shop/cShop/FSaCSHPDeleteEvent';
// $route ['shopGPEdit']               = 'pos5/shop/cShop/FSvCSHPGPEditPage';


// // Card Shift New Card
// $route ['cardShiftNewCard/(:any)/(:any)']                   = 'pos5/cardShiftNewCard/cCardShiftNewCard/index/$1/$2';
// $route ['cardShiftNewCardList']                             = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSvCardShiftNewCardListPage';
// $route ['cardShiftNewCardDataTable']                        = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSvCardShiftNewCardDataList';
// $route ['cardShiftNewCardDataSourceTable']                  = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSvCardShiftNewCardDataSourceList';
// $route ['cardShiftNewCardDataSourceTableByFile']            = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSvCardShiftNewCardDataSourceListByFile';
// $route ['cardShiftNewCardPageAdd']                          = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSvCardShiftNewCardAddPage';
// $route ['cardShiftNewCardEventAdd']                         = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSaCardShiftNewCardAddEvent';
// $route ['cardShiftNewCardPageEdit']                         = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSvCardShiftNewCardEditPage';
// $route ['cardShiftNewCardEventEdit']                        = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSaCardShiftNewCardEditEvent';
// $route ['cardShiftNewCardEventUpdateApvDocAndCancelDoc']    = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSaCardShiftNewCardUpdateApvDocAndCancelDocEvent';
// $route ['cardShiftNewCardUpdateInlineOnTemp'] = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSxCardShiftNewCardUpdateInlineOnTemp';
// $route ['cardShiftNewCardInsertToTemp'] = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSxCardShiftNewCardInsertToTemp';
// $route ['cardShiftNewCardUniqueValidate/(:any)']            = 'pos5/cardShiftNewCard/cCardShiftNewCard/FStCardShiftNewCardUniqueValidate/$1';
// $route ['cardShiftNewCardChkCardCodeDup']                   = 'pos5/cardShiftNewCard/cCardShiftNewCard/FSnCardShiftNewCardChkCardCodeDup';


// $route['MyProfile']             = 'pos5/Profile/cProfile/index';
// $route['MyProfilePageEdit']     = 'pos5/Profile/cProfile/FSvCMPFPageEdit';
// // $route['MyProfileEventEdit']    = 'pos5/Profile/cProfile/FSoCMPFEditEvent';
// $route['ChangePassword']        = 'pos5/Profile/cProfile/FSvCMPFChangePassword';



// /** Report Analysis (รายงานวิเคราะห์) */
// $route['RPTANS/(:any)/(:any)']  = 'reportanalysis/cReportAnalysis/index/$1/$2';
// // Process Excel
// $route['RPTANSExportExcelRptSaleShopByDate']    = 'reportanalysis/cRptSaleShopByDate/FSoCExportExcel';
// $route['RPTANSExportExcelRptSaleShopByShop']    = 'reportanalysis/cRptSaleShopByShop/FSoCExportExcel';
// $route['RPTANSExportExcelRptCardActiveSummary'] = 'reportanalysis/cRptCardActiveSummary/FSoCExportExcel';
// $route['RPTANSExportExcelRptCardActiveDetail']  = 'reportanalysis/cRptCardActiveDetail/FSoCExportExcel';
// $route['RPTANSExportExcelRptUnExchangeBalance'] = 'reportanalysis/cRptUnExchangeBalance/FSoCExportExcel';

// // Process PDF
// $route['RPTANSChkDataRptSaleShopByDate']        = 'reportanalysis/cRptSaleShopByDate/FSoCChkDataExportPDF';
// $route['RPTANSExportPDFRptSaleShopByDate']      = 'reportanalysis/cRptSaleShopByDate/FSoCExportRptPDF';
// $route['RPTANSChkDataRptSaleShopByShop']        = 'reportanalysis/cRptSaleShopByShop/FSoCChkDataExportPDF';
// $route['RPTANSExportPDFRptSaleShopByShop']      = 'reportanalysis/cRptSaleShopByShop/FSoCExportRptPDF';
// $route['RPTANSChkDataRptCardActiveSummary']     = 'reportanalysis/cRptCardActiveSummary/FSoCChkDataExportPDF';
// $route['RPTANSExportPDFRptCardActiveSummary']   = 'reportanalysis/cRptCardActiveSummary/FSoCExportRptPDF';
// $route['RPTANSChkDataRptCardActiveDetail']      = 'reportanalysis/cRptCardActiveDetail/FSoCChkDataExportPDF';
// $route['RPTANSExportPDFRptCardActiveDetail']    = 'reportanalysis/cRptCardActiveDetail/FSoCExportRptPDF';
// $route['RPTANSChkDataRptUnExchangeBalance']     = 'reportanalysis/cRptUnExchangeBalance/FSoCChkDataExportPDF';
// $route['RPTANSExportPDFRptUnExchangeBalance']   = 'reportanalysis/cRptUnExchangeBalance/FSoCExportRptPDF';

// // Call Table Temp NewCard
// $route['CallTableTemp']                         = 'pos5/cardmngdata/cCardMngData/FSaSelectDataTableRight';
// $route['CallDeleteTemp']                        = 'pos5/cardmngdata/cCardMngData/FSaDeleteDataTableRight';
// $route['CallClearTempByTable']                  = 'pos5/cardmngdata/cCardMngData/FSaClearTempByTable';
// $route['CallUpdateDocNoinTempByTable']          = 'pos5/cardmngdata/cCardMngData/FSaUpdateDocnoinTempByTable';


