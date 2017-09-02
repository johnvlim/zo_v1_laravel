<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "branchController.php";
include_once "companyController.php";
include_once "orderreferenceController.php";
include_once "pushController.php";
include_once "tableController.php";

class orderConstants{
	const this = 'Order';
	
	const ordersTable = 'orders';
	
	const dbOrderId = 'order_id';
	const dbMenuitemId = 'menuitem_id';
	const dbOrderreferenceCode = 'orderreference_code';
	const dbOrderStatus = 'order_status';
	const dbOrderStatusChangeTimestamp = 'order_status_change_timestamp';
	const dbOrderLastChangeTimestamp = 'order_last_change_timestamp';
	
	const reqOrderId = 'OrderId';
	const reqMenuitemId = 'MenuitemId';
	const reqOrderreferenceCode = 'OrderreferenceCode';
	const reqOrderStatus = 'OrderStatus';
	const reqOrderStatusChangeTimestamp = 'OrderStatusChangeTimestamp';
	const reqOrderLastChangeTimestamp = 'OrderLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW ORDER RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING ORDER RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING ORDER RECORD';
	
	const inconsistencyValidationErr1 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER & ORDERREFERENCE_CODE IS NON-EXISTING';
	const inconsistencyValidationErr2 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER & ORDERREFERENCE_CODE & ORDER IS NON-EXISTING';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class orderController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere){
		$companyBranchTableOrderreferenceOrders = DB::table(orderConstants::ordersTable)
		->join(
				orderreferenceConstants::orderreferencesTable, 
				orderConstants::ordersTable . '.' . orderConstants::dbOrderreferenceCode, 
				'=', 
				orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode
				)
				->join(
						tableConstants::tablesTable, 
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbTableId, 
						'=', 
						tableConstants::tablesTable . '.' . tableConstants::dbTableId
						)
						->join(
								branchConstants::branchesTable, 
								tableConstants::tablesTable . '.' . tableConstants::dbBranchId, 
								'=', 
								branchConstants::branchesTable . '.' . branchConstants::dbBranchId
								)
								->join(
										companyConstants::companiesTable, 
										branchConstants::branchesTable . '.' . branchConstants::dbCompanyName, 
										'=', 
										companyConstants::companiesTable . '.' . companyConstants::dbCompanyName
										)
										->where($mySqlWhere)
		->get();
		
		return $companyBranchTableOrderreferenceOrders;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orders
	public function getCompanyBranchOrders(
			$CompanyName, 
			$BranchName
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orders/{OrderId}
	public function getCompanyBranchOrder(
			$CompanyName, 
			$BranchName, 
			$OrderId
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderId, 
						'=', 
						$OrderId
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchOrder = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchOrder->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchOrder));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orders/status/{OrderStatus}
	public function getCompanyBranchOrdersOrderStatus(
			$CompanyName, 
			$BranchName, 
			$OrderStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderStatus, 
						'=', 
						$OrderStatus
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orders/status_not/{OrderStatus}
	public function getCompanyBranchOrdersNotOrderStatus(
			$CompanyName, 
			$BranchName, 
			$OrderStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderStatus, 
						'!=', 
						$OrderStatus
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders
	public function getCompanyBranchTableOrders(
			$CompanyName, 
			$BranchName, 
			$TableNumber
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrders));
			}
		} catch(\PDOException  $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders/{OrderId}
	public function getCompanyBranchTableOrder(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderId
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderId, 
						'=', 
						$OrderId
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrder = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrder->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrder));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders/status/{OrderStatus}
	public function getCompanyBranchTableOrdersOrderStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderStatus, 
						'=', 
						$OrderStatus
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orders/status_not/{OrderStatus}
	public function getCompanyBranchTableOrdersNotOrderStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderStatus, 
						'!=', 
						$OrderStatus
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrders));
			}
		} catch(\PDOException  $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders
	public function getCompanyBranchTableOrderreferenceOrders(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrderreferenceOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrderreferenceOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrderreferenceOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/{OrderId}
	public function getCompanyBranchTableOrderreferenceOrder(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$OrderId
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderId, 
						'=', 
						$OrderId
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrderreferenceOrder = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrderreferenceOrder->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrderreferenceOrder));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/status/{OrderStatus}
	public function getCompanyBranchTableOrderreferenceOrdersOrderStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$OrderStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderStatus, 
						'=', 
						$OrderStatus
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrderreferenceOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrderreferenceOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrderreferenceOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/status_not/{OrderStatus}
	public function getCompanyBranchTableOrderreferenceOrdersNotOrderStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$OrderStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
						'=', 
						$BranchName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderConstants::ordersTable . '.' . orderConstants::dbOrderStatus, 
						'!=', 
						$OrderStatus
				]
				);
		
		$ordersResponse = new Response();
		try{
			$companyBranchTableOrderreferenceOrders = $this->getJoinCompanyBranchTableOrderreferenceOrder($mySqlWhere);
			if($companyBranchTableOrderreferenceOrders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($companyBranchTableOrderreferenceOrders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	//URL-->>/query/orders
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[orderConstants::reqOrderId])){	array_push(
				$mySqlWhere, 
				[
						orderConstants::dbOrderId, 
						'=', 
						$_GET[orderConstants::reqOrderId]
				]
				);
		}
		if(isset($_GET[orderConstants::reqMenuitemId])){	array_push(
				$mySqlWhere, 
				[
						orderConstants::dbMenuitemId, 
						'=', 
						$_GET[orderConstants::reqMenuitemId]
				]
				);
		}
		if(isset($_GET[orderConstants::reqOrderreferenceCode])){	array_push(
				$mySqlWhere, 
				[
						orderConstants::dbOrderreferenceCode, 
						'LIKE', 
						'%' . $_GET[orderConstants::reqOrderreferenceCode] . '%'
				]
				);
		}
		if(isset($_GET[orderConstants::reqOrderStatus])){	array_push(
				$mySqlWhere, 
				[
						orderConstants::dbOrderStatus, 
						'LIKE', 
						'%' . $_GET[orderConstants::reqOrderStatus] . '%'
				]
				);
		}
		if(isset($_GET[orderConstants::reqOrderStatusChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						orderConstants::dbOrderStatusChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[orderConstants::reqOrderStatusChangeTimestamp] . '%'
				]
				);
		}
		if(isset($_GET[orderConstants::reqOrderLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						orderConstants::dbOrderLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[orderConstants::reqOrderLastChangeTimestamp] . '%'
				]
				);
		}
		
		$ordersResponse = new Response();
		try{
			$orders = DB::table(orderConstants::ordersTable)
			->where($mySqlWhere)
			->get();
			if($orders->isEmpty()){	$ordersResponse->setStatusCode(
					200, 
					orderConstants::emptyResultSetErr
					);
			} else {	$ordersResponse->setContent(json_encode($orders));
			}
		} catch(\PDOException $e){	$ordersResponse->setStatusCode(
				400, 
				orderConstants::dbReadCatchMsg
				);
		}
		
		return $ordersResponse;
	}
	
	public function isDataValid(
			$jsonData, 
			&$errorMsg, 
			$dbOperation
			){
		if("ADD" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . orderConstants::dbMenuitemId => 'exists:menuitems,menuitem_id|required|numeric', 
							'*.' . orderConstants::dbOrderreferenceCode => 'exists:orderreferences,orderreference_code|required|string|max:40', 
							'*.' . orderConstants::dbOrderStatus => 'required|string|max:30', 
							'*.' . orderConstants::dbOrderStatusChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . orderConstants::dbMenuitemId => 'exists:menuitems,menuitem_id|sometimes|numeric', 
							'*.' . orderConstants::dbOrderreferenceCode => 'exists:orderreferences,orderreference_code|sometimes|string|max:40', 
							'*.' . orderConstants::dbOrderStatus => 'sometimes|string|max:30', 
							'*.' . orderConstants::dbOrderStatusChangeTimestamp => 'sometimes|date_format:Y-m-d H:i:s', 
							'*.' . orderConstants::dbOrderLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders
	public function addOrder(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$ordersResponse = new Response();
		$ordersResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTableOrderreference = json_decode(
				(new orderreferenceController())->getCompanyBranchTableOrderreference(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreference) == 0){
			$ordersResponse->setStatusCode(
					400, 
					orderConstants::inconsistencyValidationErr1
					);
			
			return $ordersResponse;
		}
		
		$orderreferenceCode = $companyBranchTableOrderreference[0][orderConstants::dbOrderreferenceCode];
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(!(isset($jsonData[$i][orderConstants::dbOrderreferenceCode]))){	$jsonData[$i][orderConstants::dbOrderreferenceCode] = $orderreferenceCode;
			}
		}
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(isset($jsonData[$i][orderConstants::dbOrderStatusChangeTimestamp])){
				try{	$jsonData[$i][orderConstants::dbOrderStatusChangeTimestamp] = Carbon::parse($jsonData[$i][orderConstants::dbOrderStatusChangeTimestamp])
				->format('Y-m-d H:i:s');
				} catch(\Exception $e){
					$ordersResponse->setStatusCode(
							400, 
							orderreferenceConstants::carbonParseErr
							);
					
					return $ordersResponse;
				}
			}
		}
		
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){
			for($i=0; $i<$jsonDataSize; $i++){
				if($jsonData[$i][orderConstants::dbOrderreferenceCode] == $orderreferenceCode){
					try{
						DB::table(orderConstants::ordersTable)
						->insert($jsonData[$i]);
						$lastInsertId = DB::getPdo()
						->lastInsertId();
						
						$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has new order ' . $lastInsertId;
						(new pushController())->sendUpdateOrderreferenceOrderTableCommand(
								$CompanyName, 
								$BranchName, 
								orderConstants::this, 
								$notificationMessage, 
								$lastInsertId
								);
					} catch(\PDOException $e){
						$ordersResponse->setStatusCode(
								400, 
								orderConstants::dbAddCatchMsg
								);
						
						return $ordersResponse;
					}
				}
			}
		} else {
			$ordersResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $ordersResponse;
		}
		
		return orderConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/{OrderId}
	public function updateOrder(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$OrderId
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$ordersResponse = new Response();
		$ordersResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][orderConstants::dbOrderStatusChangeTimestamp])){
			try{	$jsonData[0][orderConstants::dbOrderStatusChangeTimestamp] = Carbon::parse($jsonData[0][orderConstants::dbOrderStatusChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$ordersResponse->setStatusCode(
						400, 
						orderConstants::carbonParseErr
						);
				
				return $ordersResponse;
			}
		}
		if(isset($jsonData[0][orderConstants::dbOrderLastChangeTimestamp])){
			try{	$jsonData[0][orderConstants::dbOrderLastChangeTimestamp] = Carbon::parse($jsonData[0][orderConstants::dbOrderLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$ordersResponse->setStatusCode(
						400, 
						orderConstants::carbonParseErr
						);
				
				return $ordersResponse;
			}
		}
		
		$companyBranchTableOrderreferenceOrder = json_decode(
				$this->getCompanyBranchTableOrderreferenceOrder(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode, 
						$OrderId
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreferenceOrder) == 0){
			$ordersResponse->setStatusCode(
					400, 
					orderConstants::inconsistencyValidationErr2
					);
			
			return $ordersResponse;
		}
		
		$orderId = $companyBranchTableOrderreferenceOrder[0][orderConstants::dbOrderId];
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$ordersResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $ordersResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							orderConstants::dbOrderId, 
							'=', 
							$orderId
					]
					);
			DB::table(orderConstants::ordersTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has updated order ' . $orderId;
			(new pushController())->sendUpdateOrderreferenceOrderTableCommand(
					$CompanyName, 
					$BranchName, 
					orderConstants::this, 
					$notificationMessage, 
					$orderId
					);
		} catch(\PDOException $e){
			$ordersResponse->setStatusCode(
					400, 
					orderConstants::dbUpdateCatchMsg
					);
			
			return $ordersResponse;
		}
		
		return orderConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/orders/{OrderId}
	public function deleteOrder(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$OrderId
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$ordersResponse = new Response();
		$ordersResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTableOrderreferenceOrder = json_decode(
				$this->getCompanyBranchTableOrderreferenceOrder(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode, 
						$OrderId
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreferenceOrder) == 0){
			$ordersResponse->setStatusCode(
					200, 
					orderConstants::inconsistencyValidationErr2
					);
			
			return $ordersResponse;
		}
		
		$orderId = $companyBranchTableOrderreferenceOrder[0][orderConstants::dbOrderId];
		
		try{
			array_push(
					$mySqlWhere, 
					[
							orderConstants::dbOrderId, 
							'=', 
							$orderId
					]
					);
			DB::table(orderConstants::ordersTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has deleted order ' . $orderId;
			(new pushController())->sendUpdateOrderreferenceOrderTableCommand(
					$CompanyName, 
					$BranchName, 
					orderConstants::this, 
					$notificationMessage, 
					$orderId
					);
		} catch(\PDOException $e){
			$ordersResponse->setStatusCode(
					400, 
					orderConstants::dbDeleteCatchMsg
					);
			
			return $ordersResponse;
		}
		
		return orderConstants::dbDeleteSuccessMsg;
	}
}