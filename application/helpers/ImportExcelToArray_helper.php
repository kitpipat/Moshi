<?php

    date_default_timezone_set("Asia/Bangkok");
    require_once(APPPATH.'third_party/PHPExcel/Classes/PHPExcel.php');
    require_once(APPPATH.'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php');
    require_once(APPPATH.'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

    //ไปค้นหา excel เเล้วเอาข้อมูลมาทำ array
    function FCNaHImportExcelToArray($ptFileNamePath){
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);

        $aArrayReturn   = [];
        $inputFileName  = APPPATH.'modules/document/assets/couponsetup/'.$ptFileNamePath.'.xlsx';   
        if (!file_exists($inputFileName)) {
            $tTextError = "No Found file '".$ptFileNamePath.".xlsx'";
            array_push($aArrayReturn, $tTextError);
        }else {
            $objReader      = PHPExcel_IOFactory::createReader('Excel2007');
            $inputFileType  = 'Excel2007';
            $objReader      = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel    = $objReader->load($inputFileName);
            $nRow           = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();  

            // array สำหรับส่งค่ากลับไป
            $aArrayReturn   = [];

            //เริ่มที่สอง เพราะไม่นับ Header
            for($i=2; $i<=$nRow; $i++){
                //รหัสคูปอง
                $tIDCoupon      = trim($objPHPExcel->setActiveSheetIndex(0)->getCell('A'.$i)->getValue());
                if($tIDCoupon == '' ||  $tIDCoupon == null){  $tIDCoupon = 'no_coupon'; }else{  $tIDCoupon = $tIDCoupon; }

                //จำนวนครั้งที่ใช้ได้
                $nValueCoupon   = trim($objPHPExcel->setActiveSheetIndex(0)->getCell('B'.$i)->getValue());
                if( $nValueCoupon == '' ||  $nValueCoupon == null){  $nValueCoupon = 0; }else{  $nValueCoupon = $nValueCoupon; }

                //pack ลง array
                array_push($aArrayReturn, ['ID' => $tIDCoupon , 'VAL' => $nValueCoupon] );
            }
        }
        return $aArrayReturn;
    }

?>