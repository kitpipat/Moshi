<?php

date_default_timezone_set('Asia/Bangkok');

// ** Center Route
$route['rptReport/(:any)/(:any)/(:any)']    = 'report/report/cReport/index/$1/$2/$3';
$route['rptReportMain']                     = 'report/report/cReport/FCNoCRPTViewPageMain';
$route['rptReportCondition']                = 'report/report/cReport/FCNoCRPTViewCondition';
$route['rptReportChkDataInTSysHisExport']   = 'report/report/cReport/FCNoCRPTChkDataInTSysHisExport';
$route['rptReportConfirmDownloadFile']      = 'report/report/cReport/FCNoCRPTConfirmDownloadFile';
$route['rptReportCancelDownloadFile']       = 'report/report/cReport/FCNoCRPTCancelDownloadFile';

// รายงานยอดขายตามการชำระเงิน
$route['rptRptSaleToPayment'] = 'report/reportsale/cRptSalePayment/index';
$route['rptRptSaleToPaymentClickPage']  = 'report/reportsale/cRptSalePayment/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleToPaymentCallExportFile'] = "report/reportsale/cRptSalePayment/FSvCCallRptExportFile";
/** =============================================================================== รายงานการขาย =============================================================================== */
// รายงานยอดขายตามบิล (Pos)
$route['rptRptSaleByBill'] = "report/reportsale/cRptSaleByBill/index";
$route['rptRptSaleByBillClickPage'] = "report/reportsale/cRptSaleByBill/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptSaleByBillCallExportFile'] = "report/reportsale/cRptSaleByBill/FSvCCallRptExportFile";

// รายงานยอดขายตามสินค้า
$route['rptRptSaleByProduct'] = "report/reportsale/cRptSaleByProduct/index";
$route['rptRptSaleByProductClickPage'] = "report/reportsale/cRptSaleByProduct/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptSaleByProductCallExportFile'] = "report/reportsale/cRptSaleByProduct/FSvCCallRptExportFile";

// รายงานภาษีขาย (POS)
$route['rptRptTaxSalePos'] = "report/reportsale/cRptTaxSalePos/index";
$route['rptRptTaxSalePosClickPage'] = "report/reportsale/cRptTaxSalePos/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSalePosCallExportFile'] = "report/reportsale/cRptTaxSalePos/FSvCCallRptExportFile";

// รายงานภาษีขายตามวันที่ (POS)
$route['rptRptTaxSalePosByDate'] = "report/reportsale/cRptTaxSalePosByDate/index";
$route['rptRptTaxSalePosByDateClickPage'] = "report/reportsale/cRptTaxSalePosByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSalePosByDateCallExportFile'] = "report/reportsale/cRptTaxSalePosByDate/FSvCCallRptExportFile";

//รายงานความเคลื่อนไหวสินค้า Pos+VD
$route['rtpMovePosVD'] = 'report/reportMovePosVD/cRptMovePosVD/index';
$route['rtpMovePosVDClickPage'] = 'report/reportMovePosVD/cRptMovePosVD/FSvCCallRptViewBeforePrintClickPage';
$route['rtpMovePosVDCallExportFile'] = "report/reportMovePosVD/cRptMovePosVD/FSvCCallRptExportFile";

/** ========================================= รายงานสินค้าคงคลัง (Pos) =============================================================================== */
$route['rptRptInventoryPos'] = 'report/reportInventoryPos/cRptInventoryPos/index';
$route['rptRptInventoryPosClickPage'] = 'report/reportInventoryPos/cRptInventoryPos/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptInventoryPosCallExportFile'] = 'report/reportInventoryPos/cRptInventoryPos/FSvCCallRptExportFile';
/** ===================================================================================================================================================================================== */

/** =============================================================================== รายงานตู้สินค้า (Vending) =============================================================================== */
/** ======================= รายงานการขาย ====================== */
//รายงานยอดขายตามการชำระเงินแบบสรุป  (Vending)
$route['rptSalePaymentSummary'] = 'report/reportsalepaymentsummary/cRptSalePaymentSummary/index';
$route['rptRptSalePaymentSummaryClickPage'] = 'report/reportsalepaymentsummary/cRptSalePaymentSummary/FSvCCallRptViewBeforePrintClickPage';
$route['rptSalePaymentSummaryCallExportFile'] = "report/reportsalepaymentsummary/cRptSalePaymentSummary/FSvCCallRptExportFile";

/** ===================== รายงานสินค้าคงคลัง ================================================ */
// รายงานการตรวจนับสต็อก (Vending)
$route['rptRptAdjStockVending'] = 'report/reportstkvd/cRptAdjStockVending/index';
$route['rptRptAdjStockVendingClickPage'] = 'report/reportstkvd/cRptAdjStockVending/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptAdjStockVendingCallExportFile'] = "report/reportstkvd/cRptAdjStockVending/FSvCCallRptExportFile";

/** ===================== รายงานสินค้าขายดี (Vending) ======================================== */
$route['rptRptBestSaleVending'] = 'report/reportbestsalevd/cRptBestSaleVending/index';
$route['rptRptBestSaleVendingClickPage'] = 'report/reportbestsalevd/cRptBestSaleVending/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptBestSaleVendingCallExportFile'] = "report/reportbestsalevd/cRptBestSaleVending/FSvCCallRptExportFile";

/** ===================== รายงานยอดขายตามการชำระเงินแบบละเอียด (Vending) ===================== */
$route['rptRptSalePayDetailVending'] = 'report/reportsalerecivevd/cRptSaleReciveVD/index';
$route['rptRptSalePayDetailVendingClickPage'] = 'report/reportsalerecivevd/cRptSaleReciveVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSalePayDetailVendingCallExportFile'] = "report/reportsalerecivevd/cRptSaleReciveVD/FSvCCallRptExportFile";

/** ===================================================================================================================================================================================== */

/** =============================================================================== รายงานตู้สินค้า (Vending) =============================================================================== */

/** ===================================================================================================================================================================================== */

/** =============================================================================== รายงานตู้ฝากของ (Locker) =============================================================================== */

// =============================================================================== รายงานตู้ฝากของ ===============================================================================
// รายงานเปลี่ยนสถานะช่องฝากขาย
$route['rptRptChangeStaSale'] = 'report/reportlocker/cRptChangeStaSale/index';
$route['rptRptChangeStaSaleClickPage'] = 'report/reportlocker/cRptChangeStaSale/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptChangeStaSaleCallExportFile'] = "report/reportlocker/cRptChangeStaSale/FSvCCallRptExportFile";

// รายงานการเปิดตู้โดยผู้ดูแลระบบ
$route['rptRptOpenSysAdmin'] = 'report/reportlocker/cRptOpenSysAdmin/index';
$route['rptRptOpenSysAdminClickPage'] = 'report/reportlocker/cRptOpenSysAdmin/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptOpenSysAdminCallExportFile'] = "report/reportlocker/cRptOpenSysAdmin/FSvCCallRptExportFile";

// รายงานภาษีขาย (Locker)
$route['rptRptTaxSaleLocker'] = 'report/reportlocker/cRptTaxSaleLocker/index';
$route['rptRptTaxSaleLockerClickPage'] = 'report/reportlocker/cRptTaxSaleLocker/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptTaxSaleLockerCallExportFile'] = "report/reportlocker/cRptTaxSaleLocker/FSvCCallRptExportFile";

// รายงานยอดขายตามการชำระเงินแบบละเอียด (Locker)
$route['rptRptSaleByPaymentDetail'] = 'report/reportlocker/cRptSaleByPaymentDetail/index';
$route['rptRptSaleByPaymentDetailClickPage'] = 'report/reportlocker/cRptSaleByPaymentDetail/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleByPaymentDetailCallExportFile'] = "report/reportlocker/cRptSaleByPaymentDetail/FSvCCallRptExportFile";
// Create By Witsarut 6/12/2019
// รายงานการฝากตามขนาดช่อง
$route['rptDepositAccSlotSize'] = 'report/reportdepositaccslotsize/cRptDepositAccSlotSize/index';
$route['rptDepositAccSlotSizeClickPage'] = 'report/reportdepositaccslotsize/cRptDepositAccSlotSize/FSvCCallRptViewBeforePrintClickPage';
$route['rptDepositAccSlotSizeCallExportFile'] = "report/reportdepositaccslotsize/cRptDepositAccSlotSize/FSvCCallRptExportFile";

// รายงานยอดฝากตามบริษัทขนส่ง (Locker)
$route['rptRentAmountFollowCourier'] = 'report/reportlocker/cRptRentAmountFolloweCourier/index';
$route['rptRentAmountFollowCourierClickPage'] = 'report/reportlocker/cRptRentAmountFolloweCourier/FSvCCallRptViewBeforePrintClickPage';
$route['rptRentAmountFollowCourierCallExportFile'] = "report/reportlocker/cRptRentAmountFolloweCourier/FSvCCallRptExportFile";

// รายงานยอดฝากแบบละเอียด (Locker)
$route['rptRentAmountDetail'] = 'report/reportlocker/cRptRentAmountDetail/index';
$route['rptRentAmountDetailClickPage'] = 'report/reportlocker/cRptRentAmountDetail/FSvCCallRptViewBeforePrintClickPage';
$route['rptRentAmountDetailCallExportFile'] = "report/reportlocker/cRptRentAmountDetail/FSvCCallRptExportFile";

// Create By Witsarut 03122019
// รายงานการฝากตามช่วงเวลา (Locker)
$route['rptTimeDeposit'] = 'report/reportlocker/cRptTimeDeposit/index';
$route['rptTimeDepositClickPage'] = 'report/reportlocker/cRptTimeDeposit/FSvCCallRptViewBeforePrintClickPage';
$route['rptTimeDepositCallExportFile'] = "report/reportlocker/cRptTimeDeposit/FSvCCallRptExportFile";

// รายงานการฝากตามช่วงเวลา แบบละเอียด (Locker)
$route['rptRptLockerDropByDate'] = 'report/reportlocker/cRptDropByDate/index';
$route['rptRptLockerDropByDateClickPage'] = 'report/reportlocker/cRptDropByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptLockerDropByDateCallExportFile'] = "report/reportlocker/cRptDropByDate/FSvCCallRptExportFile";

// รายงานการรับตามช่วงเวลา แบบละเอียด (Locker)
$route['rptRptLockerPickByDate'] = 'report/reportlocker/cRptPickByDate/index';
$route['rptRptLockerPickByDateClickPage'] = 'report/reportlocker/cRptPickByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptLockerPickByDateCallExportFile'] = "report/reportlocker/cRptPickByDate/FSvCCallRptExportFile";

// รายงาน - การจองช่องฝากของ
$route['rptRptBookingLocker']               = 'report/reportlocker/cRptBookingLocker/index';
$route['rptRptBookingLockerClickPage']      = 'report/reportlocker/cRptBookingLocker/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptBookingLockerCallExportFile'] = "report/reportlocker/cRptBookingLocker/FSvCCallRptExportFile";

// รายงาน - ยอดฝากแบบละเอียด
$route['rptLockerDetailDepositAmount'] = 'report/reportlocker/cRptDetailDepositAmount/index';
$route['rptLockerDetailDepositAmountClickPage'] = 'report/reportlocker/cRptDetailDepositAmount/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerDetailDepositAmountCallExportFile'] = "report/reportlocker/cRptDetailDepositAmount/FSvCCallRptExportFile";

// รายงาน - การชำระเงิน ตามบิล
$route['rptLockerPaymentByBill'] = 'report/reportlocker/cRptPaymentByBill/index';
$route['rptLockerPaymentByBillClickPage'] = 'report/reportlocker/cRptPaymentByBill/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerPaymentByBillCallExportFile'] = "report/reportlocker/cRptPaymentByBill/FSvCCallRptExportFile";

// รายงาน - การชำระเงิน (New Create By Wasin 09-12-2019)
$route['rptLockerPayment']                  = 'report/reportlocker/cRptLockerPayment/index';
$route['rptLockerPaymentClickPage']         = 'report/reportlocker/cRptLockerPayment/FSvCCallRptViewBeforePrintClickPage';
$route['rptLockerPaymentCallExportFile']    = "report/reportlocker/cRptLockerPayment/FSvCCallRptExportFile";

/** ============================================================================================================================================= */

/** ======================================== รายงานการโอนสินค้า (ตู้ Vending) ============================================================================ */
$route['rptRptProductTransfer'] = 'report/reportproducttransfer/cRptProductTransfer/index';
$route['rptRptProductTransferClickPage'] = 'report/reportproducttransfer/cRptProductTransfer/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptProductTransferCallExportFile'] = 'report/reportproducttransfer/cRptProductTransfer/FSvCCallRptExportFile';

/** ================================================================================================================================================== */

/** ======================================== รายงานยอดขายตามการชำระเงินแบบละเอียด (Pos) ================================================================= */
$route['rptRptSaleRecive'] = 'report/reportsale/cRptSaleRecive/index';
$route['rptRptSaleReciveClickPage'] = 'report/reportsale/cRptSaleRecive/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleReciveCallExportFile'] = 'report/reportsale/cRptSaleRecive/FSvCCallRptExportFile';
/** ================================================================================================================================================== */

/** ========================================= รายงานสินค้าคงคลัง (Vending) =============================================================================== */
$route['rptRptInventory'] = 'report/reportInventory/cRptInventory/index';
$route['rptRptInventoryClickPage'] = 'report/reportInventory/cRptInventory/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptInventoryCallExportFile'] = 'report/reportInventory/cRptInventory/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงานภาษีขายตามกลุ่มร้านค้า (Vending) ======================================================================== */
$route['rptRptSaleShopGroup'] = 'report/reportsaleshopgroup/cRptsaleshopgroup/index';
$route['rptRptSaleShopGroupClickPage'] = 'report/reportsaleshopgroup/cRptsaleshopgroup/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleShopGroupCallExportFile'] = 'report/reportsaleshopgroup/cRptsaleshopgroup/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงานยอดขายตามบิล (Vending) ======================================================================== */
$route['rptRptSalesbyBill'] = 'report/reportSalesbybill/cRptSalesbybill/index';
$route['rptRptSalesbyBillClickPage'] = 'report/reportSalesbybill/cRptSalesbybill/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSalesbyBillCallExportFile'] = 'report/reportSalesbybill/cRptSalesbybill/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงานยอดขายตามสินค้า (Vending) ======================================================================== */
$route['rptRptSaleByProductVD'] = 'report/reportSaleByProductVD/cRptSaleByProductVD/index';
$route['rptRptSaleByProductVDClickPage'] = 'report/reportSaleByProductVD/cRptSaleByProductVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSaleByProductVDCallExportFile'] = 'report/reportSaleByProductVD/cRptSaleByProductVD/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงานวิเคราะห์กำไรขาดทุนตามสินค้า (Vending) ======================================================================== */
$route['rptRptAnalysisProfitLossProductVending'] = 'report/reportAnalysisProfitLossProductVending/cRptAnalysisProfitLossProductVending/index';
$route['rptRptAnalysisProfitLossProductVendingClickPage'] = 'report/reportAnalysisProfitLossProductVending/cRptAnalysisProfitLossProductVending/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptAnalysisProfitLossProductVendingCallExportFile'] = 'report/reportAnalysisProfitLossProductVending/cRptAnalysisProfitLossProductVending/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงานวิเคราะห์กำไรขาดทุนตามสินค้า (Pos) ======================================================================== */
$route['rptRptAnalysisProfitLossProductPos'] = 'report/reportAnalysisProfitLossProductPos/cRptAnalysisProfitLossProductPos/index';
$route['rptRptAnalysisProfitLossProductPosClickPage'] = 'report/reportAnalysisProfitLossProductPos/cRptAnalysisProfitLossProductPos/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptAnalysisProfitLossProductPosCallExportFile'] = 'report/reportAnalysisProfitLossProductPos/cRptAnalysisProfitLossProductPos/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

// รายงานยอดขายตามการชำระเงิน Locker
$route['rptRptLocToPayment'] = 'report/reportlocker/cRptLocPayment/index';
$route['rptRptLocToPaymentCallExportFile'] = 'report/reportlocker/cRptLocPayment/FSvCCallRptExportFile';
// $route ['rptRptSaleShopByDateClickPage']    = 'report/reportsale/cRptSaleShopByDate/FSvCCallRptViewBeforePrintClickPage';

// รายงาน - สินค้าขายดีตามจำนวน
$route['rptBestSell'] = 'report/rptbestsell/cRptBestSell/index';
$route['rptBestSellClickPage'] = 'report/rptbestsell/cRptBestSell/FSvCCallRptViewBeforePrintClickPage';
$route['rptBestSellCallExportFile'] = 'report/rptbestsell/cRptBestSell/FSvCCallRptExportFile';

// รายงาน - สินค้าขายดีตามมูลค่า
$route['rptBestSellByValue'] = 'report/rptbestsellbyvalue/cRptBestSellByValue/index';
$route['rptBestSellByValueClickPage'] = 'report/rptbestsellbyvalue/cRptBestSellByValue/FSvCCallRptViewBeforePrintClickPage';
$route['rptBestSellByValueCallExportFile'] = 'report/rptbestsellbyvalue/cRptBestSellByValue/FSvCCallRptExportFile';

/*===== Begin Card Report ==============================================================*/
// 1. รายงานข้อมูลการใช้บัตร 004001001 rptCrdUseCard1
$route['rptCrdUseCard1'] = 'report/reportcard/cRptUseCard1/index';
$route['rptCrdUseCard1ClickPage'] = 'report/reportcard/cRptUseCard1/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdUseCard1CallExportFile'] = 'report/reportcard/cRptUseCard1/FSvCCallRptExportFile';

// 2. รายงานตรวจสอบสถานะบัตร 004001002 rptCrdCheckStatusCard
$route['rptCrdCheckStatusCard'] = 'report/reportcard/cRptCheckStatusCard/index';
$route['rptCrdCheckStatusCardClickPage'] = 'report/reportcard/cRptCheckStatusCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCheckStatusCardCallExportFile'] = 'report/reportcard/cRptCheckStatusCard/FSvCCallRptExportFile';

// 3. รายงานโอนข้อมูลบัตร 004001003 rptCrdTransferCardInfo
$route['rptCrdTransferCardInfo'] = 'report/reportcard/cRptTransferCardInfo/index';
$route['rptCrdTransferCardInfoClickPage'] = 'report/reportcard/cRptTransferCardInfo/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdTransferCardInfoCallExportFile'] = 'report/reportcard/cRptTransferCardInfo/FSvCCallRptExportFile';

// 4. รายงานการปรับมูลค่าเงินสดในบัตร 004001004 rptCrdAdjustCashInCard
$route['rptCrdAdjustCashInCard'] = 'report/reportcard/cRptAdjustCashInCard/index';
$route['rptCrdAdjustCashInCardClickPage'] = 'report/reportcard/cRptAdjustCashInCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdAdjustCashInCardCallExportFile'] = 'report/reportcard/cRptAdjustCashInCard/FSvCCallRptExportFile';

// 5. รายงานการล้างมูลค่าบัตรเพื่อกลับมาใช้งานใหม่ 004001005 rptCrdClearCardValueForReuse
$route['rptCrdClearCardValueForReuse'] = 'report/reportcard/cRptClearCardValueForReuse/index';
$route['rptCrdClearCardValueForReuseClickPage'] = 'report/reportcard/cRptClearCardValueForReuse/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdClearCardValueForReuseCallExportFile'] = 'report/reportcard/cRptClearCardValueForReuse/FSvCCallRptExportFile';

// 6. รายงานการลบข้อมูลบัตรที่ไม่ใช้งาน 004001006 rptCrdCardNoActive
$route['rptCrdCardNoActive'] = 'report/reportcard/cRptCardNoActive/index';
$route['rptCrdCardNoActiveClickPage'] = 'report/reportcard/cRptCardNoActive/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardNoActiveCallExportFile'] = 'report/reportcard/cRptCardNoActive/FSvCCallRptExportFile';

// 7. รายงานจำนวนรอบการใช้บัตร 004001007 rptCrdCardTimesUsed
$route['rptCrdCardTimesUsed'] = 'report/reportcard/cRptCardTimesUsed/index';
$route['rptCrdCardTimesUsedClickPage'] = 'report/reportcard/cRptCardTimesUsed/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardTimesUsedCallExportFile'] = 'report/reportcard/cRptCardTimesUsed/FSvCCallRptExportFile';

// 8. รายงานบัตรคงเหลือ 004001008 rptCrdCardBalance
$route['rptCrdCardBalance'] = 'report/reportcard/cRptCardBalance/index';
$route['rptCrdCardBalanceClickPage'] = 'report/reportcard/cRptCardBalance/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardBalanceCallExportFile'] = 'report/reportcard/cRptCardBalance/FSvCCallRptExportFile';

// 9. รายงานยอดสะสมบัตรหมดอายุ 004001009 rptCrdCollectExpireCard
$route['rptCrdCollectExpireCard'] = 'report/reportcard/cRptCollectExpireCard/index';
$route['rptCrdCollectExpireCardClickPage'] = 'report/reportcard/cRptCollectExpireCard/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCollectExpireCardCallExportFile'] = 'report/reportcard/cRptCollectExpireCard/FSvCCallRptExportFile';

// 10. รายงานรายการต้นงวดบัตรและเงินสด 004001010 rptCrdPrinciple
$route['rptCrdCardPrinciple'] = 'report/reportcard/cRptCardPrinciple/index';
$route['rptCrdCardPrincipleClickPage'] = 'report/reportcard/cRptCardPrinciple/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardPrincipleCallExportFile'] = 'report/reportcard/cRptCardPrinciple/FSvCCallRptExportFile';

// 11. รายงานข้อมูลบัตร 004001011 rptCrdCardDetail
$route['rptCrdCardDetail'] = 'report/reportcard/cRptCardDetail/index';
$route['rptCrdCardDetailClickPage'] = 'report/reportcard/cRptCardDetail/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCardDetailCallExportFile'] = 'report/reportcard/cRptCardDetail/FSvCCallRptExportFile';

// 12. รายงานตรวจสอบการเติมเงิน 004001012 rptCrdCheckPrepaid
$route['rptCrdCheckPrepaid'] = 'report/reportcard/cRptCheckPrepaid/index';
$route['rptCrdCheckPrepaidClickPage'] = 'report/reportcard/cRptCheckPrepaid/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCheckPrepaidCallExportFile'] = 'report/reportcard/cRptCheckPrepaid/FSvCCallRptExportFile';

// 13. รายงานตรวจสอบข้อมูลการใช้บัตร 004001013 rptCrdCheckCardUseInfo
$route['rptCrdCheckCardUseInfo'] = 'report/reportcard/cRptCheckCardUseInfo/index';
$route['rptCrdCheckCardUseInfoClickPage'] = 'report/reportcard/cRptCheckCardUseInfo/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdCheckCardUseInfoCallExportFile'] = 'report/reportcard/cRptCheckCardUseInfo/FSvCCallRptExportFile';

// 14. รายงานการเติมเงิน 004001014 rptCrdTopUp
$route['rptCrdTopUp'] = 'report/reportcard/cRptTopUp/index';
$route['rptCrdTopUpClickPage'] = 'report/reportcard/cRptTopUp/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdTopUpCallExportFile'] = 'report/reportcard/cRptTopUp/FSvCCallRptExportFile';

// 15. รายงานข้อมูลการใช้บัตร 004001015 (แบบละเอียด) rptCrdUseCard2
$route['rptCrdUseCard2'] = 'report/reportcard/cRptUseCard2/index';
$route['rptCrdUseCard2ClickPage'] = 'report/reportcard/cRptUseCard2/FSvCCallRptViewBeforePrintClickPage';
$route['rptCrdUseCard2CallExportFile'] = 'report/reportcard/cRptUseCard2/FSvCCallRptExportFile';
/*===== End Card Report ================================================================*/


/*===== Begin Analysis Report ==========================================================*/
// 1. รายงานยอดขายร้านค้า-ตามวันที่ 005001001 rptSaleShopByDate
$route['rptSaleShopByDate'] = 'report/reportanalysis/cRptSaleShopByDate/index';
$route['rptSaleShopByDateClickPage'] = 'report/reportanalysis/cRptSaleShopByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleShopByDateCallExportFile'] = 'report/reportanalysis/cRptSaleShopByDate/FSvCCallRptExportFile';

// 2. รายงานยอดขายร้านค้า-ตามร้านค้า 005001002 rptSaleShopByShop
$route['rptSaleShopByShop'] = 'report/reportanalysis/cRptSaleShopByShop/index';
$route['rptSaleShopByShopClickPage'] = 'report/reportanalysis/cRptSaleShopByShop/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleShopByShopCallExportFile'] = 'report/reportanalysis/cRptSaleShopByShop/FSvCCallRptExportFile';

// 3. รายงานการเคลื่อนไหวบัตร-แบบสรุป 005001003 rptCrdCardActiveSummary
// 4. รายงานการเคลื่อนไหวบัตร-แบบละเอียด 005001004 rptCrdCardActiveDetail
// 5. รายงานสรุปยอดเงินคงเหลือบัตรไม่ได้แลกคืน 005001005 rptCrdUnExchangeBalance
/*===== End Analysis Report ============================================================*/

/** ========================================= รายงาน - การฝากที่ยังไม่มารับ (Locker) ======================================================================== */
$route['rptRptDepositsNotPicked']               = 'report/reportlocker/cRptDepositsNotPicked/index';
$route['rptRptDepositsNotPickedClickPage']      = 'report/reportlocker/cRptDepositsNotPicked/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDepositsNotPickedCallExportFile'] = 'report/reportlocker/cRptDepositsNotPicked/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงาน - การรับตามช่วงเวลา (Locker) ======================================================================== */
$route['rptRptRecePtionByTime']               = 'report/reportlocker/cRptRecePtionByTime/index';
$route['rptRptRecePtionByTimeClickPage']      = 'report/reportlocker/cRptRecePtionByTime/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptRecePtionByTimeCallExportFile'] = 'report/reportlocker/cRptRecePtionByTime/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงาน - การรับ-ฝากแบบละเอียด (Locker) ======================================================================== */
$route['rptRptDetailReceiveDeposit']               = 'report/reportlocker/cRptDetailReceiveDeposit/index';
$route['rptRptDetailReceiveDepositClickPage']      = 'report/reportlocker/cRptDetailReceiveDeposit/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDetailReceiveDepositCallExportFile'] = 'report/reportlocker/cRptDetailReceiveDeposit/FSvCCallRptExportFile';
/** =================================================================================================================================================== */


// Create By Witsarut  18/12/2019
/** ===================== กลุ่มรายงาน พิเศษ ===================== */
$route['rptCRSaleTaxByWeekly']               = 'report/reportsalespecial/cRptCRSaleTaxByWeekly/index';
$route['rptCRSaleTaxByWeeklyClickPage']      = 'report/reportsalespecial/cRptCRSaleTaxByWeekly/FSvCCallRptViewBeforePrintClickPage';
$route['rptCRSaleTaxByWeeklyCallExportFile'] = 'report/reportsalespecial/cRptCRSaleTaxByWeekly/FSvCCallRptExportFile';


/** ======================================== รายงานยอดขาย (Pos Service) ================================================================= */
$route['rptRptCrSale'] = 'report/reportsalespecial/cRptCrSale/index';
$route['rptRptCrSaleClickPage'] = 'report/reportsalespecial/cRptCrSale/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleCallExportFile'] = 'report/reportsalespecial/cRptCrSale/FSvCCallRptExportFile';
/** ================================================================================================================================================== */

/** ========================================= รายงาน - ยอดขาย (POS+VD) ======================================================================== */
$route['rptRptSalePosVD']                        = 'report/reportsalespecial/cRptSalePosVD/index';
$route['rptRptSalePosVDClickPage']               = 'report/reportsalespecial/cRptSalePosVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSalePosVDCallExportFile']          = 'report/reportsalespecial/cRptSalePosVD/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงาน - รายงานยอดขายผลิตภัณฑ์ของวัน (POS Vending) ========================================================== */
$route['rptRptCrSaleProductByDay']                          = 'report/reportsalespecial/cRptSaleProductByDay/index';
$route['rptRptCrSaleProductByDayClickPage']                 = 'report/reportsalespecial/cRptSaleProductByDay/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleProductByDayCallExportFile']            = 'report/reportsalespecial/cRptSaleProductByDay/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ========================================= รายงาน - รายงานยอดขายผลิตภัณฑ์ของเดือน (POS Vending) ========================================================== */
$route['rptRptCrSaleProductByMonth']                        = 'report/reportsalespecial/cRptSaleProductByMonth/index';
$route['rptRptCrSaleProductByMonthClickPage']               = 'report/reportsalespecial/cRptSaleProductByMonth/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleProductByMonthCallExportFile']          = 'report/reportsalespecial/cRptSaleProductByMonth/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงานภาษีขาย (วัน) ==================================================================== */
$route['rptDailySalesTax']                  = 'report/reportsalespecial/cRptDailySalesTax/index';
$route['rptDailySalesTaxClickPage']        = 'report/reportsalespecial/cRptDailySalesTax/FSvCCallRptViewBeforePrintClickPage';
$route['rptDailySalesTaxCallExportFile']   = 'report/reportsalespecial/cRptDailySalesTax/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงานภาษีขาย (รายเดือน) ==================================================================== */
$route['rptRptSpecialSaleTaxByMonthly']                 = 'report/reportsalespecial/cRptSaleTaxByMonthly/index';
$route['rptRptSpecialSaleTaxByMonthlyClickPage']        = 'report/reportsalespecial/cRptSaleTaxByMonthly/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptSpecialSaleTaxByMonthlyCallExportFile']   = 'report/reportsalespecial/cRptSaleTaxByMonthly/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

// รายงานยอดขายผลิตภัณฑ์ของสัปดาห์ (POS Vending) 001003012 rptProductSaleOfWeek
$route['rptProductSaleOfWeek'] = "report/reportProductSaleOfWeek/cRptProductSaleOfWeek/index";
$route['rptProductSaleOfWeekClickPage'] = "report/reportProductSaleOfWeek/cRptProductSaleOfWeek/FSvCCallRptViewBeforePrintClickPage";
$route['rptProductSaleOfWeekCallExportFile'] = "report/reportProductSaleOfWeek/cRptProductSaleOfWeek/FSvCCallRptExportFile";

/** ======================================================= (CR) รายงานยอดขายรายวัน (POS Service) ==================================================================== */
$route['rptRptDailySalesPosSv']                         = 'report/reportsalespecial/cRptDailySalesPosSv/index';
$route['rptRptDailySalesPosSvClickPage']                = 'report/reportsalespecial/cRptDailySalesPosSv/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDailySalesPosSvCallExportFile']           = 'report/reportsalespecial/cRptDailySalesPosSv/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= (CR) รายงานยอดขายรายสัปดาห์ (POS Service) ==================================================================== */
$route['rptRptWeeklySale']                         = 'report/reportsalespecial/cRptWeeklySale/index';
$route['rptRptWeeklySaleClickPage']                = 'report/reportsalespecial/cRptWeeklySale/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptWeeklySaleCallExportFile']           = 'report/reportsalespecial/cRptWeeklySale/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================== รายงานยอดขายรายเดือน (Pos Service) ================================================================= */
$route['rptRptCrSaleMonth'] = 'report/reportsalespecial/cRptCrSaleMonth/index';
$route['rptRptCrSaleMonthClickPage'] = 'report/reportsalespecial/cRptCrSaleMonth/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptCrSaleMonthCallExportFile'] = 'report/reportsalespecial/cRptCrSaleMonth/FSvCCallRptExportFile';
/** ================================================================================================================================================== */

/** ======================================================= (CR) รายงานยอดขายผลิตภัณฑ์ (POS Vending) (แบบรายละเอียดรายวัน) ==================================================================== */
$route['rptProductSalesPosVD']                         = 'report/reportsalespecial/cRptProductSalesPosVD/index';
$route['rptProductSalesPosVDClickPage']                = 'report/reportsalespecial/cRptProductSalesPosVD/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductSalesPosVDCallExportFile']           = 'report/reportsalespecial/cRptProductSalesPosVD/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงานการนำฝากแบบละเอียด สาขา ==================================================================== */
$route['rptBankDepositBch']                         = 'report/reportlocker/cRptBankDepositBch/index';
$route['rptBankDepositBchClickPage']                = 'report/reportlocker/cRptBankDepositBch/FSvCCallRptViewBeforePrintClickPage';
$route['rptBankDepositBchCallExportFile']           = 'report/reportlocker/cRptBankDepositBch/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงาน ยอดเงินขาด/เงินเกิน ของแคชเชียร์ (ประจำวัน) ==================================================================== */
$route['rptMnyShotOver']                         = 'report/reportsale/cRptMnyShotOver/index';
$route['rptMnyShotOverClickPage']                = 'report/reportsale/cRptMnyShotOver/FSvCCallRptViewBeforePrintClickPage';
$route['rptMnyShotOverCallExportFile']           = 'report/reportsale/cRptMnyShotOver/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงาน ยอดเงินขาด/เงินเกิน ของแคชเชียร์ ประจำวัน(ละเอียด) ==================================================================== */
$route['rptMnyShotOverDairy']                         = 'report/reportsale/cRptMnyShotOverDaily/index';
$route['rptMnyShotOverDairyClickPage']                = 'report/reportsale/cRptMnyShotOverDaily/FSvCCallRptViewBeforePrintClickPage';
$route['rptMnyShotOverDairyCallExportFile']           = 'report/reportsale/cRptMnyShotOverDaily/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงาน ยอดเงินขาด/เงินเกิน ของแคชเชียร์ รายเดือน(ละเอียด) ==================================================================== */
$route['rptMnyShotOverMonthly']                         = 'report/reportsale/cRptMnyShotOverMonthly/index';
$route['rptMnyShotOverMonthlyClickPage']                = 'report/reportsale/cRptMnyShotOverMonthly/FSvCCallRptViewBeforePrintClickPage';
$route['rptMnyShotOverMonthlyCallExportFile']           = 'report/reportsale/cRptMnyShotOverMonthly/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงานยอดขาย - ตามจุดขาย ==================================================================== */
$route['rptsaledailybypos']                         = 'report/rptsaledailybypos/cRptSaleDailyByPos/index';
$route['rptsaledailybyposClickPage']                = 'report/rptsaledailybypos/cRptSaleDailyByPos/FSvCCallRptViewBeforePrintClickPage';
$route['rptsaledailybyposCallExportFile']           = 'report/rptsaledailybypos/cRptSaleDailyByPos/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงานยอดขาย - ตามแคชเชียร์ ==================================================================== */
$route['rptSalesDailyByCashier']                         = 'report/reportsale/cRptSalesDailyByCashier/index';
$route['rptSalesDailyByCashierClickPage']                = 'report/reportsale/cRptSalesDailyByCashier/FSvCCallRptViewBeforePrintClickPage';
$route['rptSalesDailyByCashierCallExportFile']           = 'report/reportsale/cRptSalesDailyByCashier/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงาน - จำนวนขายประจำเดือน - ตามสินค้า ==================================================================== */
$route['rptSMP']                         = 'report/reportsale/cRptSalesMonthProduct/index';
$route['rptSMPClickPage']                = 'report/reportsale/cRptSalesMonthProduct/FSvCCallRptViewBeforePrintClickPage';
$route['rptSMPCallExportFile']           = 'report/reportsale/cRptSalesMonthProduct/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** ======================================================= รายงาน - การคืนสินค้าตามวันที่ ==================================================================== */
$route['rptRPD']                         = 'report/reportsale/cRptReturnProductByDate/index';
$route['rptRPDClickPage']                = 'report/reportsale/cRptReturnProductByDate/FSvCCallRptViewBeforePrintClickPage';
$route['rptRPDCallExportFile']           = 'report/reportsale/cRptReturnProductByDate/FSvCCallRptExportFile';
/** =================================================================================================================================================== */


// Report For AdaStatDose
// Create By Witsarut 12/02/2020

/** ======================================================= รายางานการเติมสินค้า ==================================================================== */
$route['rptProductRefill']                  = 'report/reportproductrefill/cRptProductRefill/index';
$route['rptProductRefillClickPage']         = 'report/reportproductrefill/cRptProductRefill/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductRefillCallExportFile']    = 'report/reportproductrefill/cRptProductRefill/FSvCCallRptExportFile';

/** ======================================== รายงานสินค้าคงคลังตามสินค้าตามตู้  ================================================================= */
$route['rptProductByCabinet']               = 'report/reportproductbycabinet/cRptPdtByCabinet/index';
$route['rptProductByCabinetClickPage']      = 'report/reportproductbycabinet/cRptPdtByCabinet/FSvCCallRptViewBeforePrintClickPage';
$route['rptProductByCabinetCallExportFile'] = 'report/reportproductbycabinet/cRptPdtByCabinet/FSvCCallRptExportFile';

/** ======================================== รายงานการสั่งขาย  ================================================================= */
$route['rptSaleOrder']                      = 'report/reportsale/cRptSaleOrder/index';
$route['rptSaleOrderClickPage']             = 'report/reportsale/cRptSaleOrder/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleOrderCallExportFile']        = 'report/reportsale/cRptSaleOrder/FSvCCallRptExportFile';

//report Create by nonpawich 17/2/2020

/** ======================================== รายงานสินค้าไม่ผ่านอนุมัติ  ================================================================= */
$route['rptSaleSoNotPass']                      = 'report/reportsalesonotpass/cRptSaleSoNotPass/index';
$route['rptSaleSoNotPassClickPage']             = 'report/reportsalesonotpass/cRptSaleSoNotPass/FSvCCallRptViewBeforePrintClickPage';
$route['rptSaleSoNotPassCallExportFile']        = 'report/reportsalesonotpass/cRptSaleSoNotPass/FSvCCallRptExportFile';
/** =================================================================================================================================================== */

/** =================================================================================================================================================== */
// Create By Witsarut 29/04/2020
// รายงานยอดขายสิ้นวัน
$route['rptRptDayEndSales']               = 'report/reportsale/cRptDayEndSales/index';
$route['rptRptDayEndSalesClickPage']      = 'report/reportsale/cRptDayEndSales/FSvCCallRptViewBeforePrintClickPage';
$route['rptRptDayEndSalesCallExportFile'] = "report/reportsale/cRptDayEndSales/FSvCCallRptExportFile";
/** =================================================================================================================================================== */

// Create By Witsarut 29/04/2020
// รายงาน- ภาษีขาย (เต็มรูป)
$route['rptRptTaxSaleFull']                 = "report/reportsale/cRptTaxSaleFull/index";
$route['rptRptTaxSaleFullClickPage']        = "report/reportsale/cRptTaxSaleFull/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSaleFullCallExportFile']   = "report/reportsale/cRptTaxSaleFull/FSvCCallRptExportFile";

// Create By Saharat 04/05/2020
// รายงาน- ภาษีขายตามวันที่ (เต็มรูป)
$route['rptRptTaxSalePosByDateFull']                 = "report/reportsale/cRptTaxSalePosByDateFull/index";
$route['rptRptTaxSalePosByDateFullClickPage']        = "report/reportsale/cRptTaxSalePosByDateFull/FSvCCallRptViewBeforePrintClickPage";
$route['rptRptTaxSalePosByDateFullCallExportFile']   = "report/reportsale/cRptTaxSalePosByDateFull/FSvCCallRptExportFile";

// รายงาน ยอดขายตามแคชเชียร์ - ตามเครื่องจุดขาย
$route['rptSaleByCashierAndPos'] = "report/reportsale/cRptSaleByCashierAndPos/index";
$route['rptSaleByCashierAndPosClickPage'] = "report/reportsale/cRptSaleByCashierAndPos/FSvCCallRptViewBeforePrintClickPage";
$route['rptSaleByCashierAndPosCallExportFile'] = "report/reportsale/cRptSaleByCashierAndPos/FSvCCallRptExportFile";

// Create By Nattakit 09/07/2020
// รายงาน - ยกเลิกบิลตามวันที่
$route['rptCancelBillByDate'] = "report/reportsale/cRptCancelBillByDate/index";
$route['rptCancelBillByDateClickPage'] = "report/reportsale/cRptCancelBillByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptCancelBillByDateCallExportFile'] = "report/reportsale/cRptCancelBillByDate/FSvCCallRptExportFile";

// Create By Nattakit 09/07/2020
// รายงาน - ยกเลิกรายการตามวันที่
$route['rptCancelPdtDetailByDate'] = "report/reportsale/cRptCancelPdtDetailByDate/index";
$route['rptCancelPdtDetailByDateClickPage'] = "report/reportsale/cRptCancelPdtDetailByDate/FSvCCallRptViewBeforePrintClickPage";
$route['rptCancelPdtDetailByDateCallExportFile'] = "report/reportsale/cRptCancelPdtDetailByDate/FSvCCallRptExportFile";


//Create By Witsarut 21/07/2020
//รายงาน - ยอดขายตามสมาชิก
$route['rptSaleMember']                 = "report/reportsale/cRptSaleMember/index";
$route['rptSaleMemberClickPage']        = "report/reportsale/cRptSaleMember/FSvCCallRptViewBeforePrintClickPage";
$route['rptSaleMemberCallExportFile']   = "report/reportsale/cRptSaleMember/FSvCCallRptExportFile";


//Create By Witsarut 21/07/2020
//รายงาน - แต้มแบบสรุป (Point By Customer)
$route['rptPointByCst']                 = "report/reportsale/cRptPointByCst/index";
$route['rptPointByCstClickPage']        = "report/reportsale/cRptPointByCst/FSvCCallRptViewBeforePrintClickPage";
$route['rptPointByCstCallExportFile']   = "report/reportsale/cRptPointByCst/FSvCCallRptExportFile";

//Create By Nattakit 17/09/2020
/** ======================================================= รายงานการยกเลิกใบนำฝาก ==================================================================== */
$route['rptBankDepositCanCelBch']                         = 'report/reportlocker/cRptBankDepositCanCelBch/index';
$route['rptBankDepositCanCelBchClickPage']                = 'report/reportlocker/cRptBankDepositCanCelBch/FSvCCallRptViewBeforePrintClickPage';
$route['rptBankDepositCanCelBchCallExportFile']           = 'report/reportlocker/cRptBankDepositCanCelBch/FSvCCallRptExportFile';


//Create By Nattakit 17/09/2020
/** ======================================================= รายงาน - ยอดขายตามสาขา ==================================================================== */
$route['rptSalByBranch']                         = 'report/reportsale/cRptSalByBranch/index';
$route['rptSalByBranchClickPage']                = 'report/reportsale/cRptSalByBranch/FSvCCallRptViewBeforePrintClickPage';
$route['rptSalByBranchCallExportFile']           = 'report/reportsale/cRptSalByBranch/FSvCCallRptExportFile';
