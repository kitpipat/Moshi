<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

// $route ['bank/(:any)/(:any)']                        = 'bank/bank/cBank/index/$1/$2';

// $route ['banklist/(:any)/(:any)']                     = 'bank/bank/cBank/FSxCBNKGetDataBank/$1/$2';
$route ['bankindex/(:any)/(:any)']                    = 'bank/bank/cBank/Bankindex/$1/$2';
$route ['banklist']                                  = 'bank/bank/cBank/FSvBnkList';
// $route ['BankGetdata']                               = 'bank/bank/cBank/FSxCBNKDataTable';
$route ['bankAddData']                               = 'bank/bank/cBank/FSaCAGNAddEvent';
$route ['bankdelevent']                              = 'bank/bank/cBank/FSaCBnkDelete';
$route ['bankEventUpdate']                                = 'bank/bank/cBank/FSaCBNKAddEditEventBank';
$route ['bankedit']                                = 'bank/bank/cBank/FSvCBnkEdit';
$route ['bankeventedit']                                = 'bank/bank/cBank/FSaCBNKEditEventBank';

$route ['bankdatatable2']                            = 'bank/bank/cBank/FSxCBnkGetDataTable';

// $route ['Bankindex']                               = 'bank/bank/cBank/Bankindex';