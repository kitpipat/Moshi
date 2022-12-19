<?php

// Product
$route ['product/(:any)/(:any)']        = 'product/product/cProduct/index/$1/$2';
$route ['productMain']                  = 'product/product/cProduct/FSvCPDTCallPageMain';
$route ['productDataTable']             = 'product/product/cProduct/FSvCPDTCallPageDataTable';
$route ['productAdvTableShwColList']    = 'product/product/cProduct/FSoCPDTAdvTableShwColList';
$route ['productAdvTableShwColSave']    = 'product/product/cProduct/FSnCPDTAdvTableShwColSave';
$route ['productPageAdd']               = 'product/product/cProduct/FSoCPDTCallPageAdd';
$route ['productPageEdit']              = 'product/product/cProduct/FSoCPDTCallPageEdit';
$route ['productAddPackSizeUnit']       = 'product/product/cProduct/FSoCPDTPackSizeAdd';
$route ['productUpdatePackSizeUnit']    = 'product/product/cProduct/FSoCPDTPackSizeUpdate';
// $route ['productUpdatePdtUnitFact']     = 'product/product/cProduct/FSoCPDTPdtUnitFactUpdate';
$route ['productGetPackSizeUnit']       = 'product/product/cProduct/FSoCPDTPackSizeDataTable';
$route ['productDelPackSizeUnit']       = 'product/product/cProduct/FSoCPDTPackSizeDelete';
$route ['productGetEvnNotSale']         = 'product/product/cProduct/FSoCPDTEvnNotSaleDataTable';
$route ['productGetDataPdtSet']         = 'product/product/cProduct/FSoCPDTPdtSetDataTable';
$route ['productChkBarCodeDup']         = 'product/product/cProduct/FSoCPDTChkBarcodeDup';
$route ['productEventAdd']              = 'product/product/cProduct/FSoCPDTAddEvent';
$route ['productEventEdit']             = 'product/product/cProduct/FSoCPDTEditEvent';
$route ['productEventDelete']           = 'product/product/cProduct/FSoCPDTDeleteEvent';
$route ['productGetDataBarCode']        = 'product/product/cProduct/FSoCPDTBarCodeDataTable';
$route ['productUpdateBarCode']         = 'product/product/cProduct/FSoCPDTUpdateBarCode';
$route ['productDeleteBarCode']         = 'product/product/cProduct/FSoCPDTDeleteBarCode';

//Product Set
$route ['productSetDataTable']          = 'product/product/cProduct/FSaCPDTSETCallDataTable';
$route ['productSetCallPageAdd']        = 'product/product/cProduct/FSaCPDTSETCallPageAdd';
$route ['productSetEventAdd']           = 'product/product/cProduct/FSaCPDTSETEventAdd';
$route ['productSetCallPageEdit']       = 'product/product/cProduct/FSaCPDTSETCallPageEdit';
$route ['productSetEventDelete']        = 'product/product/cProduct/FSaCPDTSETEventDelete';
$route ['productSetUpdStaSetPri']       = 'product/product/cProduct/FSaCPDTSETUpdateStaSetPri';
$route ['productSetUpdStaSetShwDT']     = 'product/product/cProduct/FSaCPDTSETUpdateStaSetShwDT';

// Product Modal Price Route
$route ['productCallModalPriceList']    = 'product/phpprice/cPdtPrice/FSvCallPdtPriceList';
$route ['productPriceTablePRI4PDT']     = 'product/phpprice/cPdtPrice/FSvCallPdtTablePrice4PDT';
$route ['productPriceTablePRI4CST']     = 'product/phpprice/cPdtPrice/FSvCallPdtTablePrice4CST';
$route ['productPriceTablePRI4ZNE']     = 'product/phpprice/cPdtPrice/FSvCallPdtTablePrice4ZNE';
$route ['productPriceTablePRI4BCH']     = 'product/phpprice/cPdtPrice/FSvCallPdtTablePrice4BCH';
$route ['productPriceTablePRI4AGG']     = 'product/phpprice/cPdtPrice/FSvCallPdtTablePrice4AGG';


//Product Unit
$route ['pdtunit/(:any)/(:any)']    = 'product/pdtunit/cPdtUnit/index/$1/$2';
$route ['pdtunitList']              = 'product/pdtunit/cPdtUnit/FSvCPUNListPage';
$route ['pdtunitDataTable']         = 'product/pdtunit/cPdtUnit/FSvCPUNDataList';
$route ['pdtunitPageAdd']           = 'product/pdtunit/cPdtUnit/FSvCPUNAddPage';
$route ['pdtunitPageEdit']          = 'product/pdtunit/cPdtUnit/FSvCPUNEditPage';
$route ['pdtunitEventAdd']          = 'product/pdtunit/cPdtUnit/FSoCPUNAddEvent';
$route ['pdtunitEventEdit']         = 'product/pdtunit/cPdtUnit/FSoCPUNEditEvent';
$route ['pdtunitEventDelete']       = 'product/pdtunit/cPdtUnit/FSoCPUNDeleteEvent';

//Product Group
$route ['pdtgroup/(:any)/(:any)']    = 'product/pdtgroup/cPdtGroup/index/$1/$2';
$route ['pdtgroupList']              = 'product/pdtgroup/cPdtGroup/FSvCPGPListPage';
$route ['pdtgroupDataTable']         = 'product/pdtgroup/cPdtGroup/FSvCPGPDataList';
$route ['pdtgroupPageAdd']           = 'product/pdtgroup/cPdtGroup/FSvCPGPAddPage';
$route ['pdtgroupPageEdit']          = 'product/pdtgroup/cPdtGroup/FSvCPGPEditPage';
$route ['pdtgroupEventAdd']          = 'product/pdtgroup/cPdtGroup/FSoCPGPAddEvent';
$route ['pdtgroupEventEdit']         = 'product/pdtgroup/cPdtGroup/FSoCPGPEditEvent';
$route ['pdtgroupEventDelete']       = 'product/pdtgroup/cPdtGroup/FSoCPGPDeleteEvent';

//MerchantProduct Group
$route ['MerPdtGrp/(:any)/(:any)']   = 'product/merpdtgroup/cMerpdtgroup/index/$1/$2';
$route ['MerPdtGroupList']           = 'product/merpdtgroup/cMerpdtgroup/FSvCMGPListPage';
$route ['MerPdtGroupDataTable']      = 'product/merpdtgroup/cMerpdtgroup/FSvCMgpDataList';
$route ['MerPdtGroupPageAdd']        = 'product/merpdtgroup/cMerpdtgroup/FSvCMGPAddPage';
$route ['MerchantProductEventAdd']   = 'product/merpdtgroup/cMerpdtgroup/FSoCMGPAddEvent';
$route ['MerchantProductEventDelete']   = 'product/merpdtgroup/cMerpdtgroup/FSoCMgpDeleteEvent';
$route ['MerPdtGroupPageEdit']       = 'product/merpdtgroup/cMerpdtgroup/FSvCMGPEditPage';
$route ['MerchantProductEventEdit']  = 'product/merpdtgroup/cMerpdtgroup/FSoCMGPEditEvent';


//Product PriceGroup (กลุ่มราคา)
$route ['pdtpricegroup/(:any)/(:any)']  = 'product/pdtpricegroup/cPdtPriceGroup/index/$1/$2';
$route ['pdtpricegroupList']          = 'product/pdtpricegroup/cPdtPriceGroup/FSvCPPLListPage';
$route ['pdtpricegroupDataTable']     = 'product/pdtpricegroup/cPdtPriceGroup/FSvCPPLDataList';
$route ['pdtpricegroupPageAdd']       = 'product/pdtpricegroup/cPdtPriceGroup/FSvCPPLAddPage';
$route ['pdtpricegroupPageEdit']      = 'product/pdtpricegroup/cPdtPriceGroup/FSvCPPLEditPage';
$route ['pdtpricegroupEventAdd']      = 'product/pdtpricegroup/cPdtPriceGroup/FSoCPPLAddEvent';
$route ['pdtpricegroupEventEdit']     = 'product/pdtpricegroup/cPdtPriceGroup/FSoCPPLEditEvent';
$route ['pdtpricegroupEventDelete']   = 'product/pdtpricegroup/cPdtPriceGroup/FSoCPPLDeleteEvent';

//Product Promotion Group (กลุ่มโปรโมชั่น)
$route ['pdtpmggroup/(:any)/(:any)'] = 'product/pdtpromotiongroup/cPdtPmgGrp/index/$1/$2';
$route ['pdtpromotionList']          = 'product/pdtpromotiongroup/cPdtPmgGrp/FSvCPMGListPage';
$route ['pdtpromotionDataTable']     = 'product/pdtpromotiongroup/cPdtPmgGrp/FSvCPMGDataList';
$route ['pdtpromotionPageAdd']       = 'product/pdtpromotiongroup/cPdtPmgGrp/FSvCPMGAddPage';
$route ['pdtpromotionPageEdit']      = 'product/pdtpromotiongroup/cPdtPmgGrp/FSvCPMGEditPage';
$route ['pdtpromotionEventAdd']      = 'product/pdtpromotiongroup/cPdtPmgGrp/FSoCPMGAddEvent';
$route ['pdtpromotionEventEdit']     = 'product/pdtpromotiongroup/cPdtPmgGrp/FSoCPMGEditEvent';
$route ['pdtpromotionEventDelete']   = 'product/pdtpromotiongroup/cPdtPmgGrp/FSoCPMGDeleteEvent';

//Product Type
$route ['pdttype/(:any)/(:any)']    = 'product/pdttype/cPdtType/index/$1/$2';
$route ['pdttypeList']              = 'product/pdttype/cPdtType/FSvCPTYListPage';
$route ['pdttypeDataTable']         = 'product/pdttype/cPdtType/FSvCPTYDataList';
$route ['pdttypePageAdd']           = 'product/pdttype/cPdtType/FSvCPTYAddPage';
$route ['pdttypePageEdit']          = 'product/pdttype/cPdtType/FSvCPTYEditPage';
$route ['pdttypeEventAdd']          = 'product/pdttype/cPdtType/FSoCPTYAddEvent';
$route ['pdttypeEventEdit']         = 'product/pdttype/cPdtType/FSoCPTYEditEvent';
$route ['pdttypeEventDelete']       = 'product/pdttype/cPdtType/FSoCPTYDeleteEvent';

//Product Brand (ยี่ห้อ)
$route ['pdtbrand/(:any)/(:any)']    = 'product/pdtbrand/cPdtBrand/index/$1/$2';
$route ['pdtbrandList']              = 'product/pdtbrand/cPdtBrand/FSvCBNListPage';
$route ['pdtbrandDataTable']         = 'product/pdtbrand/cPdtBrand/FSvCBNDataList';
$route ['pdtbrandPageAdd']           = 'product/pdtbrand/cPdtBrand/FSvCBNAddPage';
$route ['pdtbrandPageEdit']          = 'product/pdtbrand/cPdtBrand/FSvCBNEditPage';
$route ['pdtbrandEventAdd']          = 'product/pdtbrand/cPdtBrand/FSoCBNAddEvent';
$route ['pdtbrandEventEdit']         = 'product/pdtbrand/cPdtBrand/FSoCBNEditEvent';
$route ['pdtbrandEventDelete']       = 'product/pdtbrand/cPdtBrand/FSoCBNDeleteEvent';

//Product Model (รุ่น)
$route ['pdtmodel/(:any)/(:any)']    = 'product/pdtmodel/cPdtModel/index/$1/$2';
$route ['pdtmodelList']              = 'product/pdtmodel/cPdtModel/FSvCPMOListPage';
$route ['pdtmodelDataTable']         = 'product/pdtmodel/cPdtModel/FSvCPMODataList';
$route ['pdtmodelPageAdd']           = 'product/pdtmodel/cPdtModel/FSvCPMOAddPage';
$route ['pdtmodelPageEdit']          = 'product/pdtmodel/cPdtModel/FSvCPMOEditPage';
$route ['pdtmodelEventAdd']          = 'product/pdtmodel/cPdtModel/FSoCPMOAddEvent';
$route ['pdtmodelEventEdit']         = 'product/pdtmodel/cPdtModel/FSoCPMOEditEvent';
$route ['pdtmodelEventDelete']       = 'product/pdtmodel/cPdtModel/FSoCPMODeleteEvent';

//Product Color (สี)
$route ['pdtcolor/(:any)/(:any)']    = 'product/pdtcolor/cPdtColor/index/$1/$2';
$route ['pdtcolorList']              = 'product/pdtcolor/cPdtColor/FSvCCLRListPage';
$route ['pdtcolorDataTable']         = 'product/pdtcolor/cPdtColor/FSvCCLRDataList';
$route ['pdtcolorPageAdd']           = 'product/pdtcolor/cPdtColor/FSvCCLRAddPage';
$route ['pdtcolorPageEdit']          = 'product/pdtcolor/cPdtColor/FSvCCLREditPage';
$route ['pdtcolorEventAdd']          = 'product/pdtcolor/cPdtColor/FSoCCLRAddEvent';
$route ['pdtcolorEventEdit']         = 'product/pdtcolor/cPdtColor/FSoCCLREditEvent';
$route ['pdtcolorEventDelete']       = 'product/pdtcolor/cPdtColor/FSoCCLRDeleteEvent';

//Product Size (ขนาด)
$route ['pdtsize/(:any)/(:any)']    = 'product/pdtsize/cPdtSize/index/$1/$2';
$route ['pdtsizeList']              = 'product/pdtsize/cPdtSize/FSvCPSZListPage';
$route ['pdtsizeDataTable']         = 'product/pdtsize/cPdtSize/FSvCPSZDataList';
$route ['pdtsizePageAdd']           = 'product/pdtsize/cPdtSize/FSvCPSZAddPage';
$route ['pdtsizePageEdit']          = 'product/pdtsize/cPdtSize/FSvCPSZEditPage';
$route ['pdtsizeEventAdd']          = 'product/pdtsize/cPdtSize/FSoCPSZAddEvent';
$route ['pdtsizeEventEdit']         = 'product/pdtsize/cPdtSize/FSoCPSZEditEvent';
$route ['pdtsizeEventDelete']       = 'product/pdtsize/cPdtSize/FSoCPSZDeleteEvent';

//Product No Sale By Event
$route ['pdtnoslebyevn/(:any)/(:any)']    = 'product/pdtnoslebyevn/cPdtNoSleByEvn/index/$1/$2';
$route ['pdtnoslebyevnList']              = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSvCEVNListPage';
$route ['pdtnoslebyevnDataTable']         = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSvCEVNDataList';
$route ['pdtnoslebyevnPageAdd']           = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSvCEVNAddPage';
$route ['pdtnoslebyevnPageEdit']          = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSvCEVNEditPage';
$route ['pdtnoslebyevnEventAdd']          = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSoCEVNAddEvent';
$route ['pdtnoslebyevnEventEdit']         = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSoCEVNEditEvent';
$route ['pdtnoslebyevnEventDelete']       = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSoCEVNDeleteEvent';
$route ['pdtnoslebyCheckDuplicateDatetime']       = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FSoCEVNCheckDuplicateDateTime'; //09-04-2562 pap
$route ['pdtnosleCheckTimeDuplicate']       = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FStCEVNCheckTimeDaplicate';
$route ['pdtnosleCheckDateDuplicate']       = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FStCEVNCheckDateDaplicate';
$route ['pdtnosleCheckDateTimeDuplicate']       = 'product/pdtnoslebyevn/cPdtNoSleByEvn/FStCEVNCheckDateTimeDaplicate';

//Product Location
$route ['pdtlocation/(:any)/(:any)']    = 'product/pdtlocation/cPdtLocation/index/$1/$2';
$route ['pdtlocationList']              = 'product/pdtlocation/cPdtLocation/FSvCLOCListPage';
$route ['pdtlocationDataTable']         = 'product/pdtlocation/cPdtLocation/FSvCLOCDataList';
$route ['pdtlocationPageAdd']           = 'product/pdtlocation/cPdtLocation/FSvCLOCAddPage';
$route ['pdtlocationPageEdit']          = 'product/pdtlocation/cPdtLocation/FSvCLOCEditPage';
$route ['pdtlocationPageManage']        = 'product/pdtlocation/cPdtLocation/FSvCLOCManagePage';
$route ['pdtlocationEventAdd']          = 'product/pdtlocation/cPdtLocation/FSoCLOCAddEvent';
$route ['pdtlocationEventEdit']         = 'product/pdtlocation/cPdtLocation/FSoCLOCEditEvent';
$route ['pdtlocationEventDelete']       = 'product/pdtlocation/cPdtLocation/FSoCLOCDeleteEvent';
$route ['pdtlocationProductGroup']      = 'product/pdtlocation/cPdtLocation/FSvCLOCGetDataPdtGrp';
$route ['pdtlocationProductType']       = 'product/pdtlocation/cPdtLocation/FSvCLOCGetDataPdtTyp';
$route ['pdtlocationEventManageEdit']   = 'product/pdtlocation/cPdtLocation/FSoCLOCManageEditEvent';
$route ['pdtlocationEventManageAdd']    = 'product/pdtlocation/cPdtLocation/FSoCLOCManageAddEvent';
$route ['pdtlocationLocSeqDataTable']   = 'product/pdtlocation/cPdtLocation/FSvCLOCSeqDataList';
$route ['pdtlocationSeqEventDelete']    = 'product/pdtlocation/cPdtLocation/FSoCLOCSeqDeleteEvent';

// Product Touch Group (กลุ่มสินค้าด่วน)
$route ['pdtTouchGroup/(:any)/(:any)']  = 'product/pdttouchgroup/cPdtTouchGroup/index/$1/$2';
$route ['pdtTouchGroupPageMain']        = 'product/pdttouchgroup/cPdtTouchGroup/FSvCTCGCallPageMain';
$route ['pdtTouchGroupPageDataTable']   = 'product/pdttouchgroup/cPdtTouchGroup/FSvCTCGCallPageDataTable';
$route ['pdtTouchGroupPageAdd']         = 'product/pdttouchgroup/cPdtTouchGroup/FSvCTCGCallPageAdd';
$route ['pdtTouchGroupPageEdit']        = 'product/pdttouchgroup/cPdtTouchGroup/FSvCTCGCallPageEdit';
$route ['pdtTouchGroupEventAdd']        = 'product/pdttouchgroup/cPdtTouchGroup/FSoCTCGEventAdd';
$route ['pdtTouchGroupEventEdit']       = 'product/pdttouchgroup/cPdtTouchGroup/FSoCTCGEventEdit';
$route ['pdtTouchGroupEventDelete']     = 'product/pdttouchgroup/cPdtTouchGroup/FSoCTCGEventDelete';


// Drug Tab ยา
$route ['pdtDrugPageAdd/(:any)/(:any)'] = 'product/pdtdrug/cPdtDrug/FSvCDrugPageAdd/$1/$2';
$route ['pdtDrugEventAdd']              = 'product/pdtdrug/cPdtDrug/FSaCDrugAddEvent';

//กำหนดเงื่อนไขการควบคุมสต๊อก
$route ['pdtEventPageStockConditionsList']  = 'product/product/cProduct/FSvCPDTCallPageStockConditions';
$route ['pdtEventPageStockConditionsEdit']  = 'product/product/cProduct/FSvCPDTCStockConditionsGetDataById';
$route ['pdtEventAddStockConditions']       = 'product/product/cProduct/FSaCPDTStockConditionsEventAdd';
$route ['pdtEventEditStockConditions']      = 'product/product/cProduct/FSaCPDTStockConditionsEventEdit';
$route ['pdtEventDeleteStockConditions']    = 'product/product/cProduct/FSaCPDTStockConditionsDeleteEvent';

//กำหนดเงื่อนไขโซน
$route ['pdtEventPageZoneConditionsList']   = 'product/product/cProduct/FSvCPDTCallPageZoneConditions';
$route ['pdtEventAddZoneConditions']        = 'product/product/cProduct/FSaCPDTZoneConditionsEventAdd';
$route ['pdtEventDeleteZoneConditions']     = 'product/product/cProduct/FSaCPDTZoneConditionsDeleteEvent';
$route ['pdtViewZoneDetailConditions']      = 'product/product/cProduct/FSoCPDTZoneDetailDataTable';