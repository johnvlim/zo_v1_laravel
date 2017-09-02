<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | is assigned the "api" middleware group. Enjoy building your API!
 |
 */

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:api');

//Routes::authenticateController
Route::post('login', 'authenticateController@authenticate');

//Routes::fileController
Route::get('companies/{CompanyName}/companyImage', [
		'uses' => 'fileController@getCompanyImage'
]);

Route::post('companies/{CompanyName}/companyImage', [
		'uses' => 'fileController@uploadCompanyImage'
]);

Route::delete('companies/{CompanyName}/companyImage', [
		'uses' => 'fileController@deleteCompanyImage'
]);

Route::get('companies/{CompanyName}/menus/{MenuName}/menuImage', [
		'uses' => 'fileController@getCompanyMenuImage'
]);

Route::post('companies/{CompanyName}/menus/{MenuName}/menuImage', [
		'uses' => 'fileController@uploadCompanyMenuImage'
]);

Route::delete('companies/{CompanyName}/menus/{MenuName}/menuImage', [
		'uses' => 'fileController@deleteCompanyMenuImage'
]);

Route::get('companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}/menuitemImage', [
		'uses' => 'fileController@getCompanyMenuMenuitemImage'
]);

Route::post('companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}/menuitemImage', [
		'uses' => 'fileController@uploadCompanyMenuMenuitemImage'
]);

Route::delete('companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}/menuitemImage', [
		'uses' => 'fileController@deleteCompanyMenuMenuitemImage'
]);

//Routes::customerController
Route::get('customers/companies/{CompanyName}', [
		'uses' => 'customerController@getCustomers_asAdministrator'
]);

Route::get('customers', [
		'uses' => 'customerController@getCustomers'
]);

Route::get('customers/{CustomerUsername}', [
		'uses' => 'customerController@getCustomer'
]);

Route::get('query/customers', [
		'uses' => 'customerController@getByQuery'
]);

Route::post('customers/validate', [
		'uses' => 'customerController@addCustomerValidate'
]);

Route::post('customers', [
		'uses' => 'customerController@addCustomer'
]);

Route::put('customers/{CustomerUsername}/validate', [
		'uses' => 'customerController@updateCustomerValidate'
]);

Route::put('customers/{CustomerUsername}', [
		'uses' => 'customerController@updateCustomer'
]);

Route::delete('customers/{CustomerUsername}', [
		'uses' => 'customerController@deleteCustomer'
]);

//Routes::companyController
Route::get('companies/customers/{CustomerUsername}', [
		'uses' => 'companyController@getCompanies_asAdministrator'
]);

Route::get('companies', [
		'uses' => 'companyController@getCompanies'
]);

Route::get('companies/{CompanyName}', [
		'uses' => 'companyController@getCompany'
]);

Route::get('query/companies', [
		'uses' => 'companyController@getByQuery'
]);

Route::post('companies/validate', [
		'uses' => 'companyController@addCompanyValidate'
]);

Route::post('companies', [
		'uses' => 'companyController@addCompany'
]);

Route::put('companies/{CompanyName}/validate', [
		'uses' => 'companyController@updateCompanyValidate'
]);

Route::put('companies/{CompanyName}', [
		'uses' => 'companyController@updateCompany'
]);

Route::delete('companies/{CompanyName}', [
		'uses' => 'companyController@deleteCompany'
]);

//Route::branchController
Route::get('companies/{CompanyName}/branches', [
		'uses' => 'branchController@getCompanyBranches'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}', [
		'uses' => 'branchController@getCompanyBranch'
]);

Route::get('query/branches', [
		'uses' => 'branchController@getByQuery'
]);

Route::post('companies/{CompanyName}/branches/validate', [
		'uses' => 'branchController@addBranchValidate'
]);

Route::post('companies/{CompanyName}/branches', [
		'uses' => 'branchController@addBranch'
]);

Route::put('companies/{CompanyName}/branches/{BranchName}/validate', [
		'uses' => 'branchController@updateBranchValidate'
]);

Route::put('companies/{CompanyName}/branches/{BranchName}', [
		'uses' => 'branchController@updateBranch'
]);

Route::delete('companies/{CompanyName}/branches/{BranchName}', [
		'uses' => 'branchController@deleteBranch'
]);

//Routes::customerCompanyBranchController
Route::get('customers-companies-branches', [
		'uses' => 'customerCompanyBranchController@getCustomersCompaniesBranches'
]);

Route::get('customers-companies-branches/{CustomerUsername}', [
		'uses' => 'customerCompanyBranchController@getCustomerCompanyBranch'
]);

Route::get('query/customers-companies-branches', [
		'uses' => 'customerCompanyBranchController@getByQuery'
]);

Route::post('customers-companies-branches', [
		'uses' => 'customerCompanyBranchController@addCustomerCompanyBranch'
]);

//Routes::menuController
Route::get('companies/{CompanyName}/menus', [
		'uses' => 'menuController@getCompanyMenus'
]);

Route::get('companies/{CompanyName}/menus/{MenuName}', [
		'uses' => 'menuController@getCompanyMenu'
]);

Route::get('query/menus', [
		'uses' => 'menuController@getByQuery'
]);

Route::post('companies/{CompanyName}/menus', [
		'uses' => 'menuController@addMenu'
]);

Route::put('companies/{CompanyId}/menus/{MenuName}', [
		'uses' => 'menuController@updateMenu'
]);

Route::delete('companies/{CompanyName}/menus/{MenuName}', [
		'uses' => 'menuController@deleteMenu'
]);

//Route::tableController
Route::get('companies/{CompanyName}/branches/{BranchName}/tables', [
		'uses' => 'tableController@getCompanyBranchTables'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}', [
		'uses' => 'tableController@getCompanyBranchTable'
]);

Route::get('query/tables', [
		'uses' => 'tableController@getByQuery'
]);

Route::post('companies/{CompanyName}/branches/{BranchName}/tables', [
		'uses' => 'tableController@addTable'
]);

Route::put('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}', [
		'uses' => 'tableController@updateTable'
]);

Route::delete('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}', [
		'uses' => 'tableController@deleteTable'
]);

//Routes::menuitemController
Route::get('companies/{CompanyName}/menus/{MenuName}/menuitems', [
		'uses' => 'menuitemController@getCompanyMenuMenuitems'
]);

Route::get('companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}', [
		'uses' => 'menuitemController@getCompanyMenuMenuitem'
]);

Route::get('query/menuitems', [
		'uses' => 'menuitemController@getByQuery'
]);

Route::post('companies/{CompanyName}/menus/{MenuName}/menuitems', [
		'uses' => 'menuitemController@addMenuitem'
]);

Route::put('companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}', [
		'uses' => 'menuitemController@updateMenuitem'
]);

Route::delete('companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}', [
		'uses' => 'menuitemController@deleteMenuitem'
]);

//Routes; reservationController
Route::get('companies/{CompanyName}/branches/{BranchName}/reservations', [
		'uses' => 'reservationController@getCompanyBranchReservations'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/reservations/{ReservationCode}', [
		'uses' => 'reservationController@getCompanyBranchReservation'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/reservations/status/{ReservationStatus}', [
		'uses' => 'reservationController@getCompanyBranchReservationsReservationStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/reservations/status_not/{ReservationStatus}', [
		'uses' => 'reservationController@getCompanyBranchReservationsNotReservationStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations', [
		'uses' => 'reservationController@getCompanyBranchTableReservations'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations/{ReservationCode}', [
		'uses' => 'reservationController@getCompanyBranchTableReservation'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations/status/{ReservationStatus}', [
		'uses' => 'reservationController@getCompanyBranchTableReservationsReservationStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations/status_not/{ReservationStatus}', [
		'uses' => 'reservationController@getCompanyBranchTableReservationsNotReservationStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations', [
		'uses' => 'reservationController@getCompanyBranchTableOrderreferenceReservations'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/{ReservationCode}', [
		'uses' => 'reservationController@getCompanyBranchTableOrderreferenceReservation'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/status/{ReservationStatus}', [
		'uses' => 'reservationController@getCompanyBranchTableOrderreferenceReservationsReservationStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/status_not/{ReservationStatus}', [
		'uses' => 'reservationController@getCompanyBranchTableOrderreferenceReservationsNotReservationStatus'
]);

Route::get('customers/{CustomerUsername}/reservations', [
	'uses' => 'reservationController@getCustomerReservations'
]);

Route::get('customers/{CustomerUsername}/reservations/{ReservationCode}', [
		'uses' => 'reservationController@getCustomerReservation'
]);

Route::get('customers/{CustomerUsername}/reservations/status/{ReservationStatus}', [
		'uses' => 'reservationController@getCustomerReservationsReservationStatus'
]);

Route::get('customers/{CustomerUsername}/reservations/status_not/{ReservationStatus}', [
		'uses' => 'reservationController@getCustomerReservationsNotReservationStatus'
]);

Route::get('query/reservations', [
		'uses' => 'reservationController@getByQuery'
]);

Route::post('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations', [
		'uses' => 'reservationController@addReservation'
]);

Route::put('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/{ReservationCode}', [
		'uses' => 'reservationController@updateReservation'
]);

Route::delete('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/{ReservationCode}', [
		'uses' => 'reservationController@deleteReservation'
]);

//Routes; orderreferenceController
Route::get('companies/{CompanyName}/branches/{BranchName}/orderreferences', [
		'uses' => 'orderreferenceController@getCompanyBranchOrderreferences'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/orderreferences/{OrderreferenceCode}', [
		'uses' => 'orderreferenceController@getCompanyBranchOrderreference'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/orderreferences/status/{OrderreferenceStatus}', [
		'uses' => 'orderreferenceController@getCompanyBranchOrderreferencesOrderreferenceStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/orderreferences/status_not/{OrderreferenceStatus}', [
		'uses' => 'orderreferenceController@getCompanyBranchOrderreferencesNotOrderreferenceStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences', [
		'uses' => 'orderreferenceController@getCompanyBranchTableOrderreferences'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}', [
		'uses' => 'orderreferenceController@getCompanyBranchTableOrderreference'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/status/{OrderreferenceStatus}', [
		'uses' => 'orderreferenceController@getCompanyBranchTableOrderreferencesOrderreferenceStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/status_not/{OrderreferenceStatus}', [
		'uses' => 'orderreferenceController@getCompanyBranchTableOrderreferencesNotOrderreferenceStatus'
]);

Route::get('customers/{CustomerUsername}/orderreferences', [
		'uses' => 'orderreferenceController@getCustomerOrderreferences'
]);

Route::get('customers/{CustomerUsername}/orderreferences/{OrderreferenceCode}', [
		'uses' => 'orderreferenceController@getCustomerOrderreference'
]);

Route::get('customers/{CustomerUsername}/orderreferences/status/{OrderreferenceStatus}', [
		'uses' => 'orderreferenceController@getCustomerOrderreferencesOrderreferenceStatus'
]);

Route::get('customers/{CustomerUsername}/orderreferences/status_not/{OrderreferenceStatus}', [
		'uses' => 'orderreferenceController@getCustomerOrderreferencesNotOrderreferenceStatus'
]);

Route::get('query/orderreferences', [
		'uses' => 'orderreferenceController@getByQuery'
]);

Route::post('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences', [
		'uses' => 'orderreferenceController@addOrderreference'
]);

Route::put('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}', [
		'uses' => 'orderreferenceController@updateOrderreference'
]);

Route::delete('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}', [
		'uses' => 'orderreferenceController@deleteOrderreference'
]);

//Routes::orderController
Route::get('companies/{CompanyName}/branches/{BranchName}/orders', [
		'uses' => 'orderController@getCompanyBranchOrders'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/orders/{OrderId}', [
		'uses' => 'orderController@getCompanyBranchOrder'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/orders/status/{OrderStatus}', [
		'uses' => 'orderController@getCompanyBranchOrdersOrderStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/orders/status_not/{OrderStatus}', [
		'uses' => 'orderController@getCompanyBranchOrdersNotOrderStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders', [
		'uses' => 'orderController@getCompanyBranchTableOrders'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders/{OrderId}', [
		'uses' => 'orderController@getCompanyBranchTableOrder'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders/status/{OrderStatus}', [
		'uses' => 'orderController@getCompanyBranchTableOrdersOrderStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders/status_not/{OrderStatus}', [
		'uses' => 'orderController@getCompanyBranchTableOrdersNotOrderStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders', [
		'uses' => 'orderController@getCompanyBranchTableOrderreferenceOrders'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/{OrderId}', [
		'uses' => 'orderController@getCompanyBranchTableOrderreferenceOrder'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/status/{OrderStatus}', [
		'uses' => 'orderController@getCompanyBranchTableOrderreferenceOrdersOrderStatus'
]);

Route::get('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/status_not/{OrderStatus}', [
		'uses' => 'orderController@getCompanyBranchTableOrderreferenceOrdersNotOrderStatus'
]);

Route::get('query/orders', [
		'uses' => 'orderController@getByQuery'
]);

Route::post('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders', [
		'uses' => 'orderController@addOrder'
]);

Route::put('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/{OrderId}', [
		'uses' => 'orderController@updateOrder'
]);

Route::delete('companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/{OrderId}', [
		'uses' => 'orderController@deleteOrder'
]);

//Routes::advertisementController
Route::get('advertisements', [
		'uses' => 'advertisementController@getAdvertisements'
]);

Route::get('advertisements/{AdvertisementId}', [
		'uses' => 'advertisementController@getAdvertisement'
]);

Route::get('companies/{CompanyName}/advertisements', [
		'uses' => 'advertisementController@getCompanyAdvertisements'
]);

Route::get('companies/{CompanyName}/advertisements/{AdvertisementId}', [
		'uses' => 'advertisementController@getCompanyAdvertisement'
]);

Route::get('query/advertisements', [
		'uses' => 'advertisementController@getByQuery'
]);

Route::post('advertisements', [
		'uses' => 'advertisementController@addAdvertisement'
]);

Route::put('advertisements/{AdvertisementId}', [
		'uses' => 'advertisementController@updateAdvertisement'
]);

Route::delete('advertisements/{AdvertisementId}', [
		'uses' => 'advertisementController@deleteAdvertisement'
]);

//Routes::blogController
Route::get('blogs', [
		'uses' => 'blogController@getBlogs'
]);

Route::get('blogs/{BlogId}', [
		'uses' => 'blogController@getBlog'
]);

Route::get('query/blogs', [
		'uses' => 'blogController@getByQuery'
]);

Route::post('blogs', [
		'uses' => 'blogController@addBlog'
]);

Route::put('blogs/{BlogId}', [
		'uses' => 'blogController@updateBlog'
]);

Route::delete('blogs/{BlogId}', [
		'uses' => 'blogController@deleteBlog'
]);

//Routes:: orderreferenceOrderController
Route::post('orderreferences-orders', [
		'uses' => 'orderreferenceOrderController@addOrderreferenceOrder'
]);

//Routes:: reservationOrderreferenceOrderController
Route::post('reservations-orderreferences-orders', [
		'uses' => 'reservationOrderreferenceOrderController@addReservationOrderreferenceOrder'
]);

//Routes:: emailController
Route::post('pre-signup', [
		'uses' => 'emailController@sendEmailFromPreSignup'
]);

// Routes:: pushController
Route::post('send/update-company', [
		'uses' => 'pushController@sendUpdateCompanyCommand'
]);

Route::post('send/update-orderreference-order', [
		'uses' => 'pushController@sendUpdateOrderreferenceOrderCommand'
]);