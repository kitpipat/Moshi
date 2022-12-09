<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

// Authencation
$route ['login']                        = 'authen/login/cLogin';
$route ['Checklogin']                   = 'authen/login/cLogin/FSnCLOGChkLogin';
$route ['logout']                       = 'authen/logout/cLogout';
$route ['CheckSession']                 = 'authen/session/cSession/FCNnCheckSession';

// Department
$route ['department/(:any)/(:any)']     = 'authen/department/cDepartment/index/$1/$2';
$route ['departmentList']               = 'authen/department/cDepartment/FSvCDPTListPage';
$route ['departmentDataTable']          = 'authen/department/cDepartment/FSvCDPTDataList';
$route ['departmentPageAdd']            = 'authen/department/cDepartment/FSvCDPTAddPage';
$route ['departmentPageEdit']           = 'authen/department/cDepartment/FSvCDPTEditPage';
$route ['departmentEventAdd']           = 'authen/department/cDepartment/FSoCDPTAddEvent';
$route ['departmentEventEdit']          = 'authen/department/cDepartment/FSoCDPTEditEvent';
$route ['departmentEventDelete']        = 'authen/department/cDepartment/FSoCDPTDeleteEvent';

// User
$route ['user/(:any)/(:any)']           = 'authen/user/cUser/index/$1/$2';
$route ['userList']                     = 'authen/user/cUser/FSvUSRListPage';
$route ['userDataTable']                = 'authen/user/cUser/FSvUSRDataList';
$route ['userPageAdd']                  = 'authen/user/cUser/FSvUSRAddPage';
$route ['userPageEdit']                 = 'authen/user/cUser/FSvUSREditPage';
$route ['userEventAdd']                 = 'authen/user/cUser/FSoUSRAddEvent';
$route ['userEventEdit']                = 'authen/user/cUser/FSoUSREditEvent';
$route ['userEventDelete']              = 'authen/user/cUser/FSoUSRDeleteEvent';

//Role
$route['role/(:any)/(:any)']            = 'authen/role/cRole/index/$1/$2';
$route['roleList']                      = 'authen/role/cRole/FStCCallPageRoleList';
$route['roleDataTable']                 = 'authen/role/cRole/FSoCCallPageRoleDataTable';
$route['rolePageAdd']                   = 'authen/role/cRole/FSoCCallPageRoleAdd';
$route['rolePageEdit']                  = 'authen/role/cRole/FSoCCallPageRoleEdit';
$route['roleEventAdd']                  = 'authen/role/cRole/FSoRoleAddEvent';
$route['roleEventEdit']                 = 'authen/role/cRole/FSoRoleEditEvent';
$route['roleEventDelete']               = 'authen/role/cRole/FSoRoleDeleteEvent';

//UserLogin
$route ['userlogin']                     = 'authen/userlogin/cUserlogin/FSvCUserloginMainPage';
$route ['userloginDataTable']            = 'authen/userlogin/cUserlogin/FSvCUserLogDataList';
$route ['userloginPageAdd']              = 'authen/userlogin/cUserlogin/FSvCUserlogPageAdd';
$route ['userloginEventAdd']             = 'authen/userlogin/cUserlogin/FSaCUserlogAddEvent';
$route ['userloginPageEdit']             = 'authen/userlogin/cUserlogin/FSvCUserlogPageEdit';
$route ['userloginEventEdit']            = 'authen/userlogin/cUserlogin/FSaCUserlogEditEvent';
$route ['userloginEventDelete']          = 'authen/userlogin/cUserlogin/FSaCUserlogDeleteEvent';
$route ['userloginEventDeleteMultiple']  = 'authen/userlogin/cUserlogin/FSoCUserlogDelMultipleEvent';
$route ['userloginEventChangePassword']  = 'authen/userlogin/cUserlogin/FSoCUserlogChangePasswordEvent';
