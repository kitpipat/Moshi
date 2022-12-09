<?php
/*===== SYSTEM ===============================================================*/
define('BASE_TITLE', 'Moshi Moshi');
define('BASE_URL', 'https://dev.ada-soft.com/AdaMoshi/');
define('BASE_DATABASE', 'AdaMoshi');
define('DATABASE_IP', '147.50.143.126,33433');
define('SYS_BCH_CODE', '00001');
define('DATABASE_USERNAME', 'sa');
define('DATABASE_PASSWORD', 'GvFhk@61');
/*===== RABBIT RABBIT MQ ============================================================*/
define('HOST', '147.50.143.126'); // Server
define('USER', 'Admin');
define('PASS', 'Admin');
define('VHOST', 'AdaPos5.0MoshiDev_Doc');
define('EXCHANGE', '');
define('PORT', 5672);
/*===== REPORT RABBIT MQ =====================================================*/
define('MQ_REPORT_HOST','147.50.143.126');
define('MQ_REPORT_USER','Admin');
define('MQ_REPORT_PASS','Admin');
define('MQ_REPORT_VHOST','AdaPos5.0ReportMoshi');
define('MQ_REPORT_EXCHANGE', '');
define('MQ_REPORT_PORT', 5672);
/*===== LOCKER RABBIT MQ =====================================================*/
define('MQ_LOCKER_HOST','147.50.143.126');
define('MQ_LOCKER_USER','Admin');
define('MQ_LOCKER_PASS','Admin');
define('MQ_LOCKER_VHOST','AdaPos5.0MoshiDev_Master');
define('MQ_LOCKER_EXCHANGE', '');
define('MQ_LOCKER_PORT', 5672);

/*===== LOCKER Booking RABBIT MQ =====================================================*/
define('MQ_BOOKINGLK_HOST','147.50.143.126');
define('MQ_BOOKINGLK_USER','Admin');
define('MQ_BOOKINGLK_PASS','Admin');
define('MQ_BOOKINGLK_VHOST','AdaPos5.0MoshiDev_Master');
define('MQ_BOOKINGLK_EXCHANGE', '');
define('MQ_BOOKINGLK_PORT', 5672);

/*===== RABBIT MQ STATDOSE ============================================================*/
define('STATDOSE_HOST', '147.50.143.126'); // Server
define('STATDOSE_USER', 'Admin');
define('STATDOSE_PASS', 'Admin');
define('STATDOSE_VHOST', 'AdaPos5.0SaleStatDose');
define('STATDOSE_EXCHANGE', '');
define('STATDOSE_PORT', 5672);

/*=======Interface================*/
define('INTERFACE_HOST','147.50.143.126');
define('INTERFACE_USER','Admin');
define('INTERFACE_PASS','Admin');
define('INTERFACE_VHOST','AdaPos5.0MoshiDev_Doc');
define('INTERFACE_EXCHANGE', '');
define('INTERFACE_PORT', 5672);

//============== Member ==================//
define('MemberV5_HOST','147.50.143.126');
define('MemberV5_USER','Admin');
define('MemberV5_PASS','Admin');
define('MemberV5_VHOST','AdaPos5.0Member');
define('MemberV5_EXCHANGE', '');
define('MemberV5_PORT', 5672);


//============== Sale ==================//
define('MQ_Sale_HOST','147.50.143.126');
define('MQ_Sale_USER','Admin');
define('MQ_Sale_PASS','Admin');
define('MQ_Sale_VHOST','AdaPos5.0MoshiDev_Sale');
define('MQ_Sale_QUEUES','UPLOADSALE,UPLOADSALEPAY,UPLOADSALERT,UPLOADSALEVD,UPLOADSHIFT,UPLOADTAX,UPLOADVOID');
define('MQ_Sale_EXCHANGE', '');
define('MQ_Sale_PORT', 5672);


@$aLastVersion = file(FCPATH.'version_deploy.txt');
define('VERSION_DEPLOY',$aLastVersion[0]);

