<?php
    // Reason (เหตุผล)
    $route ['reason/(:any)/(:any)']     = 'other/reason/cReason/index/$1/$2';
    $route ['reasonList']               = 'other/reason/cReason/FSvRSNListPage';
    $route ['reasonDataTable']          = 'other/reason/cReason/FSvRSNDataList';
    $route ['reasonPageAdd']            = 'other/reason/cReason/FSvRSNAddPage';
    $route ['reasonEventAdd']           = 'other/reason/cReason/FSaRSNAddEvent';
    $route ['reasonPageEdit']           = 'other/reason/cReason/FSvRSNEditPage';
    $route ['reasonEventEdit']          = 'other/reason/cReason/FSaRSNEditEvent';
    $route ['reasonEventDelete']        = 'other/reason/cReason/FSaRSNDeleteEvent';	
    // $route ['reasonEventDelete']        = 'other/reason/cReason/FSaRSNDeleteEvent';	

?>