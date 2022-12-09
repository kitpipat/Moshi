<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

$route ['interfaceimport/(:any)/(:any)']  = 'Interface/Interfaceimport/cInterfaceimport/index/$1/$2';
$route ['interfaceimportAction']          = 'Interface/Interfaceimport/cInterfaceimport/FSxCINMCallRabitMQ';


//Interfacehistory ประวัตินำเข้า - นำออก
//create by nonpawich 5/3/2020
$route ['interfacehistory/(:any)/(:any)']  = 'interface/interfacehistory/cInterfaceHistory/index/$1/$2';
$route ['interfacehistorylist']            = 'interface/interfacehistory/cInterfaceHistory/FSxCIFHList';
$route ['interfaceihistorydatatable']      = 'interface/interfacehistory/cInterfaceHistory/FSaCIFHGetDataTable';


//InterfaceExport ส่งออก
//Create by Napat(Jame) 05/03/2020
$route ['interfaceexport/(:any)/(:any)']  = 'interface/interfaceexport/cInterfaceExport/index/$1/$2';
$route ['interfaceexportAction']          = 'Interface/interfaceexport/cInterfaceExport/FSxCIFXCallRabitMQ';


