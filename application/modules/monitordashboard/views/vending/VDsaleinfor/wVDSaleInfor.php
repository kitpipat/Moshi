<link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/monitordashboard/assets/css/globalcss/adaMDGeneral.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>application/modules/monitordashboard/assets/css/localcss/adaPossaleInfor.css">
<input type="hidden" id="ohdBaseURL" value="<?php echo base_url(); ?>">
<div class="container-fluid xCNPadding-20px">
    <input type="hidden" id="ohdBaseUrl" value="<?php echo base_url(); ?>">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <label class="xWTopicMenu">Dashboard</label>
        </div>
        <div class="col-xs-12 col-sm-6 text-right">
            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <div class="xCNDisplay-inline-block">
                        <label class="xCNMargin-bottom-0px">ข้อมูลวันที่</label>
                    </div>
                </div>
                <div class="col-xs-12 col-md-4 xCNMargin-bottom-20px">
                    <div class="xWTopicChoice-DateBox xCNWidth-100per">
                        <div class="input-group xCNWidth-100per">
                            <input type="text" id="oetDateFillter" class="form-control xCNDatePicker xCNInputMaskDate xCNInput-Text" readonly value="<?php echo date("Y-m-d"); ?>">
                            <!-- <span class="input-group-btn">
                                <button id="obtXthDocDate" type="button" class="btn xCNBtnDateTime">
                                    <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                </button>
                            </span> -->
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="xWTopicChoice-SelectBox">
                        <select class="selectpicker form-control" tabindex="-98" name="ocmWriteGraphCompare" id="ocmWriteGraphCompare">
                            <option>ข้อมูลการขาย</option>
                            <option>สินค้าคงคลัง</option>
                            <option>ตู้ขายสินค้า</option>
                            <option>สินค้าคงคลัง (ตู้ขายสินค้า)</option>
                            <option>ตู้ฝากของ</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row panel xCNMargin-top-30px xCNPadding-bottom-10">
        <div class="col-xs-12 col-md-8">
            <div class="row xCNClearMarginRow xWFrameDivChoice xCNPadding-20px">
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <div>
                        <label class="">เปรียบเทียบจาก</label>
                    </div>
                    <div class="xCNWidth-100per">
                        <select class="selectpicker form-control" tabindex="-98" id="ocmTypeWriteGraph" name="ocmTypeWriteGraph">
                            <option value="pdtGroup">กลุ่มสินค้า</option>
                            <option value="pdtType">ประเภทสินค้า</option>
                            <option value="usrBranch">สาขา</option>
                            <option value="usrShop">ร้านค้า</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <div>
                        <label>ค่าพ็อตกราฟ</label>
                    </div>
                    <div class="xCNWidth-100per">
                        <select class="selectpicker form-control" tabindex="-98" id="ocmTypeCalDisplayGraph" name="ocmTypeCalDisplayGraph">
                            <option value="gross">ยอดขาย</option>
                            <option value="bill">จำนวนบิล</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-5">
                    <div>
                        <label>เงื่อนไขข้อมูล</label>
                    </div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <input type="hidden" value="day" id="ohdConditionWritGraph" name="ohdConditionWritGraph">
                        <button type="button" class="btn btn-default xWBtnControlSearchActive" data-value="day" onclick="JSxSearchControlGraph(this);">วัน</button>
                        <button type="button" class="btn btn-default" data-value="week" onclick="JSxSearchControlGraph(this);">สัปดาห์</button>
                        <button type="button" class="btn btn-default" data-value="month" onclick="JSxSearchControlGraph(this);">เดือน</button>
                        <button type="button" class="btn btn-default" data-value="year" onclick="JSxSearchControlGraph(this);">ปี</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="row xCNClearMarginRow xCNMargin-top-30px">
                <div class="col-xs-12 col-sm-6 xWLeftBorderDiv-right">
                    <div>
                        <label>จำนวนบิลขายรวม</label>
                    </div>
                    <div class="text-right">
                        <label class="xCNFontSize-40px" id="olbCountSaleBill">0.00</label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 xWLeftBorderDiv-right">
                    <div>
                        <label>ยอดขายรวม</label>
                    </div>
                    <div class="text-right">
                        <label class="xCNFontSize-40px " id="olbSumSaleGross">0.00</label>
                    </div>
                </div>  
            </div>
            <div class="row xCNClearMarginRow xCNMargin-top-30px">
                <div class="col-xs-12 col-sm-6 xWLeftBorderDiv-right">
                    <div>
                        <label>จำนวนบิลคืนรวม</label>
                    </div>
                    <div class="text-right">
                        <label class="xCNFontSize-40px" id="olbCountReturnBill">0.00</label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 xWLeftBorderDiv-right">
                    <div>
                        <label>ยอดคืนรวม</label>
                    </div>
                    <div class="text-right">
                        <label class="xCNFontSize-40px" id="olbSumReturnGross">0.00</label>
                    </div>
                </div>  
            </div>
        </div>
        <div class="col-xs-12" id="odvShowGraph"></div>
        <div class="col-xs-12">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="10">สินค้าขายดีประจำวัน</th>
                    </tr>
                </thead>
                <tbody id="otbBestSalePdt">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>






























<script type="text/javascript" src="<?= base_url('application/modules/monitordashboard/assets/src/pos/jPosSaleInfor.js')?>"></script>