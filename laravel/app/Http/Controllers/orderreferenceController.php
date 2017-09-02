<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "branchController.php";
include_once "companyController.php";
include_once "customerController.php";
include_once "pushController.php";
include_once "tableController.php";

class orderreferenceConstants{
	const this = 'Orderreference';
	
	const orderreferencesTable = 'orderreferences';
	
	const dbOrderreferenceId = 'orderreference_id';
	const dbOrderreferenceCode = 'orderreference_code';
	const dbCustomerUsername = 'customer_username';
	const dbTableId = 'table_id';
	const dbOrderreferenceStatus = 'orderreference_status';
	const dbOrderreferenceStatusChangeTimestamp = 'orderreference_status_change_timestamp';
	const dbOrderreferenceLastChangeTimestamp = 'orderreference_last_change_timestamp';
	
	const reqOrderreferenceId = 'OrderreferenceId';
	const reqOrderreferenceCode = 'OrderreferenceCode';
	const reqCustomerUsername = 'CustomerUsername';
	const reqTableId = 'TableId';
	const reqOrderreferenceStatus = 'OrderreferenceStatus';
	const reqOrderreferenceStatusChangeTimestamp = 'OrderreferenceStatusChangeTimestamp';
	const reqOrderreferenceLastChangeTimestamp = 'OrderreferenceLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW ORDER REFERENCE RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING ORDER REFERENCE RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING ORDER REFERENCE RECORD';
	
	const inconsistencyValidationErr1 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER IS NON-EXISTING';
	const inconsistencyValidationErr2 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER & ORDERREFERENCE_CODE IS NON-EXISTING';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class orderreferenceController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyBranchTableOrderreference($mySqlWhere){
		$companyBranchTableOrderreference = DB::table(orderreferenceConstants::orderreferencesTable)
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
		
		return $companyBranchTableOrderreference;
	}
	
	public function getJoinCustomerCompanyBranchTableOrderreferences($mySqlWhere){
		$customerCompanyBranchTableOrderreferences = DB::table(orderreferenceConstants::orderreferencesTable)
		->join(
				customerConstants::customersTable, 
				orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbCustomerUsername, 
				'=', 
				customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername
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
		
		return $customerCompanyBranchTableOrderreferences;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orderreferences
	public function getCompanyBranchOrderreferences(
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
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchOrderreferences = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orderreferences/{OrderreferenceCode}
	public function getCompanyBranchOrderreference(
			$CompanyName, 
			$BranchName, 
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
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchOrderreference = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchOrderreference->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchOrderreference));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orderreferences/status/{OrderreferenceStatus}
	public function getCompanyBranchOrderreferencesOrderreferenceStatus(
			$CompanyName, 
			$BranchName, 
			$OrderreferenceStatus
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
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceStatus, 
						'=', 
						$OrderreferenceStatus
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchOrderreferences = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/orderreferences/status_not/{OrderreferenceStatus}
	public function getCompanyBranchOrderreferencesNotOrderreferenceStatus(
			$CompanyName, 
			$BranchName, 
			$OrderreferenceStatus
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
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceStatus, 
						'!=', 
						$OrderreferenceStatus
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchOrderreferences = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				200, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences
	public function getCompanyBranchTableOrderreferences(
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
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchTableOrderreferences = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchTableOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchTableOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}
	public function getCompanyBranchTableOrderreference(
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
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchTableOrderreference = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchTableOrderreference->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchTableOrderreference));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/status/{OrderreferenceStatus}
	public function getCompanyBranchTableOrderreferencesOrderreferenceStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceStatus
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
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceStatus, 
						'=', 
						$OrderreferenceStatus
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchTableOrderreferences = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchTableOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchTableOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/status_not/{OrderreferenceStatus}
	public function getCompanyBranchTableOrderreferencesNotOrderreferenceStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceStatus
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
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceStatus, 
						'!=', 
						$OrderreferenceStatus
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$companyBranchTableOrderreferences = $this->getJoinCompanyBranchTableOrderreference($mySqlWhere);
			if($companyBranchTableOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($companyBranchTableOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/orderreferences
	public function getCustomerOrderreferences($CustomerUsername){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$customerOrderreferences = $this->getJoinCustomerCompanyBranchTableOrderreferences($mySqlWhere);
			if($customerOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($customerOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/orderreferences/{OrderreferenceCode}
	public function getCustomerOrderreference(
			$CustomerUsername, 
			$OrderreferenceCode
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
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
		
		$orderreferencesResponse = new Response();
		try{
			$customerOrderreference = $this->getJoinCustomerCompanyBranchTableOrderreferences($mySqlWhere);
			if($customerOrderreference->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($customerOrderreference));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/orderreferences/status/{OrderreferenceStatus}
	public function getCustomerOrderreferencesOrderreferenceStatus(
			$CustomerUsername, 
			$OrderreferenceStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceStatus, 
						'=', 
						$OrderreferenceStatus
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$customerOrderreferences = $this->getJoinCustomerCompanyBranchTableOrderreferences($mySqlWhere);
			if($customerOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($customerOrderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/orderreferences/status_not/{OrderreferenceStatus}
	public function getCustomerOrderreferencesNotOrderreferenceStatus(
			$CustomerUsername, 
			$OrderreferenceStatus
			){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceStatus, 
						'!=', 
						$OrderreferenceStatus
				]
				);
		
		$orderreferencesResponse = new Response();
		try{
			$customerOrderreferences = $this->getJoinCustomerCompanyBranchTableOrderreferences($mySqlWhere);
			if($customerOrderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($customerOrderreferences));
			}
		} catch(\PDOException  $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
	}
	
	//URL-->>/query/orderreferences
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[orderreferenceConstants::reqOrderreferenceId])){	array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::dbOrderreferenceId, 
						'=', 
						$_GET[orderreferenceConstants::reqOrderreferenceId]
				]
				);
		}
		if(isset($_GET[orderreferenceConstants::reqOrderreferenceCode])){	array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::dbOrderreferenceCode, 
						'LIKE', 
						'%' . $_GET[orderreferenceConstants::reqOrderreferenceCode] . '%'
				]
				);
		}
		if(isset($_GET[orderreferenceConstants::reqCustomerUsername])){	array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::dbCustomerUsername, 
						'LIKE', 
						'%' . $_GET[orderreferenceConstants::reqCustomerUsername] . '%'
				]
				);
		}
		if(isset($_GET[orderreferenceConstants::reqTableId])){	array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::dbTableId, 
						'=', 
						$_GET[orderreferenceConstants::reqTableId]
				]
				);
		}
		if(isset($_GET[orderreferenceConstants::reqOrderreferenceStatus])){	array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::dbOrderreferenceStatus, 
						'LIKE', 
						'%' . $_GET[orderreferenceConstants::reqOrderreferenceStatus] . '%'
				]
				);
		}
		if(isset($_GET[orderreferenceConstants::reqOrderreferenceStatusChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[orderreferenceConstants::reqOrderreferenceStatusChangeTimestamp] . '%'
				]
				);
		}
		if(isset($_GET[orderreferenceConstants::reqOrderreferenceLastChangeTimestamp])){
			array_push(
					$mySqlWhere, 
					[
							orderreferenceConstants::dbOrderreferenceLastChangeTimestamp, 
							'LIKE', 
							'%' . $_GET[orderreferenceConstants::reqOrderreferenceLastChangeTimestamp] . '%'
					]
					);
		}
		
		$orderreferencesResponse = new Response();
		try{
			$orderreferences = DB::table(orderreferenceConstants::orderreferencesTable)
			->where($mySqlWhere)
			->get();
			if($orderreferences->isEmpty()){	$orderreferencesResponse->setStatusCode(
					200, 
					orderreferenceConstants::emptyResultSetErr
					);
			} else {	$orderreferencesResponse->setContent(json_encode($orderreferences));
			}
		} catch(\PDOException $e){	$orderreferencesResponse->setStatusCode(
				400, 
				orderreferenceConstants::dbReadCatchMsg
				);
		}
		
		return $orderreferencesResponse;
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
							'*.' . orderreferenceConstants::dbOrderreferenceCode => 'unique:orderreferences,orderreference_code|required|string|max:40', 
							'*.' . orderreferenceConstants::dbCustomerUsername => 'exists:customers,customer_username|required|string|max:30', 
							'*.' . orderreferenceConstants::dbTableId => 'exists:tables,table_id|required|numeric', 
							'*.' . orderreferenceConstants::dbOrderreferenceStatus => 'required|string|max:30', 
							'*.' . orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . orderreferenceConstants::dbOrderreferenceCode => 'unique:orderreferences,orderreference_code|sometimes|string|max:40', 
							'*.' . orderreferenceConstants::dbCustomerUsername => 'exists:customers,customer_username|sometimes|string|max:30', 
							'*.' . orderreferenceConstants::dbTableId => 'exists:tables,table_id|sometimes|numeric', 
							'*.' . orderreferenceConstants::dbOrderreferenceStatus => 'sometimes|string|max:30', 
							'*.' . orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp => 'sometimes|date_format:Y-m-d H:i:s', 
							'*.' . orderreferenceConstants::dbOrderreferenceLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences
	public function addOrderreference(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName, 
			$TableNumber
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$orderreferencesResponse = new Response();
		$orderreferencesResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTable = json_decode(
				(new tableController())->getCompanyBranchTable(
						$CompanyName, 
						$BranchName, 
						$TableNumber
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTable) == 0){
			$orderreferencesResponse->setStatusCode(
					400, 
					orderreferenceConstants::inconsistencyValidationErr1
					);
			
			return $orderreferencesResponse;
		}
		
		$tableId = $companyBranchTable[0][tableConstants::dbTableId];
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(!(isset($jsonData[$i][orderreferenceConstants::dbTableId]))){	$jsonData[$i][orderreferenceConstants::dbTableId] = $tableId;
			}
		}
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(isset($jsonData[$i][orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp])){
				try{	$jsonData[$i][orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp] = Carbon::parse($jsonData[$i][orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp])
				->format('Y-m-d H:i:s');
				} catch(\Exception $e){
					$orderreferencesResponse->setStatusCode(
							400, 
							orderreferenceConstants::carbonParseErr
							);
					
					return $orderreferencesResponse;
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
				if($jsonData[$i][orderreferenceConstants::dbTableId] == $tableId){
					try{
						DB::table(orderreferenceConstants::orderreferencesTable)
						->insert($jsonData[$i]);
						
						$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has new orderreference ' . $jsonData[$i][orderreferenceConstants::dbOrderreferenceCode];
						(new pushController())->sendUpdateOrderreferenceOrderTableCommand(
								$CompanyName, 
								$BranchName, 
								orderreferenceConstants::this, 
								$notificationMessage, 
								$jsonData[$i][orderreferenceConstants::dbOrderreferenceCode]
								);
					} catch(\PDOException $e){
						$orderreferencesResponse->setStatusCode(
								400, 
								orderreferenceConstants::dbAddCatchMsg
								);
						
						return $orderreferencesResponse;
					}
				}
			}
		} else {
			$orderreferencesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $orderreferencesResponse;
		}
		
		return orderreferenceConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}
	public function updateOrderreference(
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
		$mySqlWhere = array();
		$errorMsg = '';
		
		$orderreferencesResponse = new Response();
		$orderreferencesResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp])){
			try{	$jsonData[0][orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp] = Carbon::parse($jsonData[0][orderreferenceConstants::dbOrderreferenceStatusChangeTimestamp])
			->format('Y-m-d H:i:s');
			}  catch(\Exception $e){
				$orderreferencesResponse->setStatusCode(
						400, 
						orderreferenceConstants::carbonParseErr
						);
				
				return $orderreferencesResponse;
			}
		}
		if(isset($jsonData[0][orderreferenceConstants::dbOrderreferenceLastChangeTimestamp])){
			try{	$jsonData[0][orderreferenceConstants::dbOrderreferenceLastChangeTimestamp] = Carbon::parse($jsonData[0][orderreferenceConstants::dbOrderreferenceLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$orderreferencesResponse->setStatusCode(
						400, 
						orderreferencsConstants::carbonParseErr
						);
				
				return $orderreferencesResponse;
			}
		}
		
		$companyBranchTableOrderreference = json_decode(
				$this->getCompanyBranchTableOrderreference(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreference) == 0){
			$orderreferencesResponse->setStatusCode(
					400, 
					orderreferenceConstants::inconsistencyValidationErr2
					);
			
			return $orderreferencesResponse;
		}
		
		$orderreferenceId = $companyBranchTableOrderreference[0][orderreferenceConstants::dbOrderreferenceId];
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$orderreferencesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $orderreferencesResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							orderreferenceConstants::dbOrderreferenceId, 
							'=', 
							$orderreferenceId
					]
					);
			DB::table(orderreferenceConstants::orderreferencesTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has updated orderreference ' . $OrderreferenceCode;
			(new pushController())->sendUpdateOrderreferenceOrderTableCommand(
					$CompanyName, 
					$BranchName, 
					orderreferenceConstants::this, 
					$notificationMessage, 
					$OrderreferenceCode
					);
		} catch(\PDOException $e){
			$orderreferencesResponse->setStatusCode(
					400, 
					orderreferenceConstants::dbUpdateCatchMsg
					);
			
			return $orderreferencesResponse;
		}
		
		return orderreferenceConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}
	public function deleteOrderreference(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$orderreferencesResponse = new Response();
		$orderreferencesResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTableOrderreference = json_decode(
				$this->getCompanyBranchTableOrderreference(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreference) == 0){
			$orderreferencesResponse->setStatusCode(
					400, 
					orderreferenceConstants::inconsistencyValidationErr2
					);
			
			return $orderreferencesResponse;
		}
		
		$orderreferenceId = $companyBranchTableOrderreference[0][orderreferenceConstants::dbOrderreferenceId];
		
		try{
			array_push(
					$mySqlWhere, 
					[
							orderreferenceConstants::dbOrderreferenceId, 
							'=', 
							$orderreferenceId
					]
					);
			DB::table(orderreferenceConstants::orderreferencesTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has deleted orderreference ' . $OrderreferenceCode;
			(new pushController())->sendUpdateOrderreferenceOrderTableCommand(
					$CompanyName, 
					$BranchName, 
					orderreferenceConstants::this, 
					$notificationMessage, 
					$OrderreferenceCode
					);
		} catch(\PDOException $e){
			$orderreferencesResponse->setStatusCode(
					400, 
					orderreferenceConstants::dbDeleteCatchMsg
					);
			
			return $orderreferencesResponse;
		}
		
		return orderreferenceConstants::dbDeleteSuccessMsg;
	}
}