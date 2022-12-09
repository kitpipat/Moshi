<?php 

//Card
$route ['card/(:any)/(:any)']       = 'payment/card/cCard/index/$1/$2';
$route ['cardList']                 = 'payment/card/cCard/FSvCCRDListPage';
$route ['cardDataTable']            = 'payment/card/cCard/FSvCCRDDataList';
$route ['cardPageAdd']              = 'payment/card/cCard/FSvCCRDAddPage';
$route ['cardPageEdit']             = 'payment/card/cCard/FSvCCRDEditPage';
$route ['cardEventAdd']             = 'payment/card/cCard/FSoCCRDAddEvent';
$route ['cardEventEdit']            = 'payment/card/cCard/FSoCCRDEditEvent';
$route ['cardEventDelete']          = 'payment/card/cCard/FSoCCRDDeleteEvent';
$route ['checkStatusActive']        = "payment/card/cCard/FSvCCRDChkStaAct";

//CardType (ประเภทบัตร)
$route ['cardtype/(:any)/(:any)']   = 'payment/cardtype/cCardType/index/$1/$2';
$route ['cardtypeList']             = 'payment/cardtype/cCardType/FSvCCTYListPage';
$route ['cardtypeDataTable']        = 'payment/cardtype/cCardType/FSvCCTYDataList';
$route ['cardtypePageAdd']          = 'payment/cardtype/cCardType/FSvCCTYAddPage';
$route ['cardtypePageEdit']         = 'payment/cardtype/cCardType/FSvCCTYEditPage';
$route ['cardtypeEventAdd']         = 'payment/cardtype/cCardType/FSoCCTYAddEvent';
$route ['cardtypeEventEdit']        = 'payment/cardtype/cCardType/FSoCCTYEditEvent';
$route ['cardtypeEventDelete']      = 'payment/cardtype/cCardType/FSoCCTYDeleteEvent';

//Recive (ประเภทการชำระเงิน)
$route ['recive/(:any)/(:any)']      = 'payment/recive/cRecive/index/$1/$2';
$route ['reciveList']                = 'payment/recive/cRecive/FSvRCVListPage';
$route ['reciveDataTable']           = 'payment/recive/cRecive/FSvRCVDataList';
$route ['recivePageAdd']             = 'payment/recive/cRecive/FSvRCVAddPage';
$route ['reciveEventAdd']            = 'payment/recive/cRecive/FSaRCVAddEvent';
$route ['recivePageEdit']            = 'payment/recive/cRecive/FSvRCVEditPage';
$route ['reciveEventEdit']           = 'payment/recive/cRecive/FSaRCVEditEvent';
$route ['reciveEventDelete']         = 'payment/recive/cRecive/FSaRCVDeleteEvent';
$route ['recivespcGetRcvConfig']     = 'payment/recive/cRecive/FSaCRCVGetRcvConfig';

//BankNote (ธนบัตร)
$route ['banknote/(:any)/(:any)']   = 'payment/banknote/cBanknote/index/$1/$2';
$route ['banknoteList']             = 'payment/banknote/cBanknote/FSvCBNTListPage';
$route ['banknoteDataTable']        = 'payment/banknote/cBanknote/FSvCBNTDataList';
$route ['banknotePageAdd']          = 'payment/banknote/cBanknote/FSvCBNTAddPage';
$route ['banknotePageEdit']         = 'payment/banknote/cBanknote/FSvCBNTEditPage';
$route ['banknoteEventAdd']         = 'payment/banknote/cBanknote/FSoCBNTAddEvent';
$route ['banknoteEventEdit']        = 'payment/banknote/cBanknote/FSoCBNTEditEvent';
$route ['banknoteEventDelete']      = 'payment/banknote/cBanknote/FSoCBNTDeleteEvent';

//Rate สกุลเงิน
$route ['rate/(:any)/(:any)']       = 'payment/rate/cRate/index/$1/$2';
$route ['rateFormSearchList']       = 'payment/rate/cRate/FSxCRTEFormSearchList';
$route ['ratePageAdd']              = 'payment/rate/cRate/FSxCRTEAddPage';
$route ['rateDataTable']            = 'payment/rate/cRate/FSxCRTEDataTable';
$route ['ratePageEdit']             = 'payment/rate/cRate/FSvCRTEEditPage';
$route ['rateEventAdd']             = 'payment/rate/cRate/FSaCRTEAddEvent';
$route ['rateEventEdit']            = 'payment/rate/cRate/FSaCRTEEditEvent';
$route ['rateEventDelete']          = 'payment/rate/cRate/FSaCRTEDeleteEvent';

//CardLogin
$route ['cardlogin']                     = 'payment/cardlogin/cCardlogin/FSvCCardloginMainPage';
$route ['cardloginDataTable']            = 'payment/cardlogin/cCardlogin/FSvCCardLogDataList';
$route ['cardloginPageAdd']              = 'payment/cardlogin/cCardlogin/FSvCCardlogPageAdd';
$route ['cardloginEventAdd']             = 'payment/cardlogin/cCardlogin/FSaCCardlogAddEvent';
$route ['cardloginPageEdit']             = 'payment/cardlogin/cCardlogin/FSvCCardlogPageEdit';
$route ['cardloginEventEdit']            = 'payment/cardlogin/cCardlogin/FSaCCardlogEditEvent';
$route ['cardloginEventDelete']          = 'payment/cardlogin/cCardlogin/FSaCCardlogDeleteEvent';
$route ['cardloginEventDeleteMultiple']  = 'payment/cardlogin/cCardlogin/FSoCCardlogDelMultipleEvent';

// Create By Witsarut 27/11/2019
//ReciveSpc
$route ['recivespc/(:any)/(:any)']       = 'payment/recivespc/cReciveSpc/FSvCReciveSpcMainPage/$1/$2';
$route ['recivespcDataTable']            = 'payment/recivespc/cReciveSpc/FSvCReciveSpcDataList';
$route ['recivespcPageAdd']              = 'payment/recivespc/cReciveSpc/FSvCReciveSpcPageAdd';
$route ['recivespcEventAdd']             = 'payment/recivespc/cReciveSpc/FSaCReciveSpcAddEvent';
$route ['recivespcPageEdit']             = 'payment/recivespc/cReciveSpc/FSvCReciveSpcPageEdit';
$route ['recivespcEventEdit']            = 'payment/recivespc/cReciveSpc/FSaCReciveSpcEditEvent';
$route ['recivespcEventDelete']          = 'payment/recivespc/cReciveSpc/FSaCReciveSpcDeleteEvent';
$route ['recivespcEventDeleteMultiple']  = 'payment/recivespc/cReciveSpc/FSoCReciveSpcDelMultipleEvent';


