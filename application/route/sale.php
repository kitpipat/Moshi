<?php
//พี่รัตน์บอกให้ใช้ route sale (8 ตุลาคม 2562)

//ออกใบกำกับภาษีเต็มรูป
$route['TaxinvoiceABB/(:any)/(:any)']          = 'sale/Taxinvoice/cTaxinvoice/index/$1/$2';
$route['TaxinvoiceABBList']                    = 'sale/Taxinvoice/cTaxinvoice/FSvCTAXListPage';
$route['TaxinvoiceABBTable']                   = 'sale/Taxinvoice/cTaxinvoice/FSvCTAXDataTable';

// พิมพ์เอกสาร EJ
$route['dcmReprintEJ/(:any)/(:any)']            = 'sale/reprintej/cReprintEJ/index/$1/$2';
$route['dcmReprintEJCallPageMainFormPrint']     = 'sale/reprintej/cReprintEJ/FSvCEJCallPageMainFormPrint';
$route['dcmReprintEJFilterDataABB']             = 'sale/reprintej/cReprintEJ/FSoCEJGetDataAbbInDB';
$route['dcmReprintEJCallPageRenderPrintABB']    = 'sale/reprintej/cReprintEJ/FSoCEJCallPageRenderPrintABB';

// จองช่องฝากของ
$route['salBookingLocker/(:any)/(:any)']       = 'sale/bookinglocker/cBookingLocker/index/$1/$2';
$route['salBookingLockerPageMain']             = 'sale/bookinglocker/cBookingLocker/FSvCBKLCallPageMain';
$route['salBookingLockerGetViewRack']          = 'sale/bookinglocker/cBookingLocker/FSvCBKLGetViewRack';
$route['salBookingLockerGetModalBooking']      = 'sale/bookinglocker/cBookingLocker/FSvCBKLGetViewBooking';
$route['salBookingLockerConfirmBookingLocker'] = 'sale/bookinglocker/cBookingLocker/FSoCBKLConfirmBookingLocker';
$route['salBookingLockerCancelBookingLocker']  = 'sale/bookinglocker/cBookingLocker/FSoCBKLCancelBookingLocker';
$route['salBookingLockerDeleteQueues']         = 'sale/bookinglocker/cBookingLocker/FSoCBKLDeleteQueue';

// Dash Board Sale
$route['dashboardsale/(:any)/(:any)']          = 'sale/dashboardsale/cDashBoardSale/index/$1/$2';
$route['dashboardsaleMainPage']                = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALMainPage';
$route['dashboardsaleCallModalFilter']         = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALCallModalFilter';
$route['dashboardsaleConfirmFilter']           = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALConfirmFilter';
$route['dashboardsaleBillAllAndTotalSale']     = 'sale/dashboardsale/cDashBoardSale/FSoCDSHSALViewBillAllAndTotalSale';
$route['dashboardsaleTotalSaleByRecive']       = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalSaleByRecive';
$route['dashboardsalePdtStockBarlance']        = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewPdtStockBarlance';
$route['dashboardsaleTopTenNewPdt']            = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTopTenNewPdt';
$route['dashboardsaleTotalSaleByPdtGrp']       = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalSaleByPdtGrp';
$route['dashboardsaleTotalSaleByPdtPty']       = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalSaleByPdtPty';
$route['dashboardsaleTopTenBestSeller']        = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTopTenBestSaller';
$route['dashboardsaleTotalByBranch']           = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTotalByBranch';
$route['dashboardsaleTopTenBestSellerByValue']        = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALViewTopTenBestSallerByValue';

// Dash Board Modal Config
$route['dashboardsaleCallModalConfigPage']     = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALCallModalConfigPage';
$route['dashboardsaleCallModalConfigPageSaveCookie']     = 'sale/dashboardsale/cDashBoardSale/FSvCDSHSALCallModalConfigPageSaveCookie';


// Dash Board Sale
$route['salemonitor/(:any)/(:any)']          = 'sale/salemonitor/cSaleMonitor/index/$1/$2';
$route['salemonitorMainPage']                = 'sale/salemonitor/cSaleMonitor/FSvCSMTSALMainPage';
$route['salemonitorCallModalFilter']         = 'sale/salemonitor/cSaleMonitor/FSvCSMTSALCallModalFilter';
$route['salemonitorConfirmFilter']           = 'sale/salemonitor/cSaleMonitor/FSvCSMTSALConfirmFilter';
$route['salemonitorCallSaleDataTable']       = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallSaleDataTable';
$route['salemonitorCallApiDataTable']        = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallApiDataTable';
$route['salemonitorCallMQRequestSaleData']   = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallMQRequestSaleData';
$route['salemonitorCallMQRequestApiData']    = 'sale/salemonitor/cSaleMonitor/FSvCSMTCallMQRequestApiData';
$route['salemonitorRequestAPIInOnLine']      = 'sale/salemonitor/cSaleMonitor/FSaCSMTRequestAPIIsOnLine';

// MQ Information
$route['dasMQICallMianPage']                 = 'sale/salemonitor/cMqInfomation/FSvMQICallMainPage';
$route['dasMQICallDataTable']                = 'sale/salemonitor/cMqInfomation/FSvMQICallDataTable';
$route['dasMQIEventReConsumer']              = 'sale/salemonitor/cMqInfomation/FSvMQIEventReConsumer';

// Sale Tools
$route['dasSTLCallMianPage']                 = 'sale/salemonitor/cSaleTools/FSvSTLCallMainPage';
$route['dasSTLCallDataTable']                = 'sale/salemonitor/cSaleTools/FSvSTLCallDataTable';
$route['dasSTLEventRepair']                  = 'sale/salemonitor/cSaleTools/FSvSTLEventRePair';


// Sale Import
$route['dasIMPCallMianPage']                 = 'sale/salemonitor/cSaleImportBill/FSvCIMPCallMianPage';
$route['dasIMPCallPageFrom']                 = 'sale/salemonitor/cSaleImportBill/FSvCIMPCallPageFrom';
$route['dasIMPCallDataTable']                = 'sale/salemonitor/cSaleImportBill/FSvCIMPCallDataTable';
$route['dasIMPUploadFile']                   = 'sale/salemonitor/cSaleImportBill/FSaCIMPUploadFile';
$route['dasIMPLoadDatatable']                = 'sale/salemonitor/cSaleImportBill/FSvCIMPLoadDatatable';
$route['dasIMPInsertBillData']               = 'sale/salemonitor/cSaleImportBill/FSaCIMPInsertBillData';
