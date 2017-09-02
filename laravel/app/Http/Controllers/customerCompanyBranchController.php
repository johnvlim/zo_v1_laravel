<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

include_once "branchController.php";
include_once "companyController.php";
include_once "customerController.php";

class customerCompanyBranchConstants{
	const customersCompaniesBranchesTable = 'customers_companies_branches';
	
	const keyCustomer = 'customer';
	const keyCompany = 'company';
	const keyBranch = 'branch';
	const keyCustomerCompanyBranch = 'customerCompanyBranch';
	
	const dbCustomerUsername = 'customer_username';
	const dbCompanyName = 'company_name';
	const dbBranchId = 'branch_id';
	const dbCustomerCompanyBranchLastChangeTimestamp = 'customerCompanyBranch_last_change_timestamp';
	
	const reqCustomerUsername = 'CustomerUsername';
	const reqCompanyName = 'CompanyName';
	const reqBranchId = 'BranchId';
	const reqCustomerCompanyBranchLastChangeTimestamp = 'CustomerCompanyBranchLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW CUSTOMER, COMPANY, BRANCH RECORDS';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING CUSTOMER, COMPANY, BRANCH RECORDS';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING CUSTOMER, COMPANY, BRANCH RECORDS';
	
	const inconsistencyValidationErr1 = 'KEYS COMPANY_NAME DO NOT MATCH';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
}

class customerCompanyBranchController extends Controller
{
	public function __construct(){	$this->middleware(
			'jwt.auth', 
			['except' => ['addCustomerCompanyBranch']]
			);
	}
	
	public function getJoinCustomerCompanyBranch($mySqlWhere){
		$customerCompanyBranch = DB::table(customerCompanyBranchConstants::customersCompaniesBranchesTable)
		->join(
				customerConstants::customersTable, 
				customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCustomerUsername, 
				'=', 
				customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername
				)
				->join(
						companyConstants::companiesTable, 
						customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCompanyName, 
						'=', 
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName
						)
						->join(
								branchConstants::branchesTable, 
								companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
								'=', 
								branchConstants::branchesTable . '.' . branchConstants::dbCompanyName
								)
								->where($mySqlWhere)
		->get();
		
		return $customerCompanyBranch;
	}
	
	//URL-->>/customers-companies-branches
	public function getCustomersCompaniesBranches(){
		$mySqlWhere = array();
		
		$customersCompaniesBranchesResponse = new Response();
		try{
			$customersCompaniesBranches = $this->getJoinCustomerCompanyBranch($mySqlWhere);
			if($customersCompaniesBranches->isEmpty()){	$customersCompaniesBranchesResponse->setStatusCode(
					200, 
					customerCompanyBranchConstants::emptyResultSetErr
					);
			} else {	$customersCompaniesBranchesResponse->setContent(json_encode($customersCompaniesBranches));
			}
		} catch(\PDOException $e){
			$customersCompaniesBranchesResponse->setStatusCode(
					400, 
					customerCompanyBranchConstants::dbReadCatchMsg
					);
		}
		
		return $customersCompaniesBranchesResponse;
	}
	
	//URL-->>/customers-companies-branches/{CustomerUsername}
	public function getCustomerCompanyBranch(
			$CustomerUsername
			){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		
		$customersCompaniesBranchesResponse = new Response();
		try{
			$customerCompanyBranch = $this->getJoinCustomerCompanyBranch($mySqlWhere);
			if($customerCompanyBranch->isEmpty()){	$customersCompaniesBranchesResponse->setStatusCode(
					200, 
					customerCompanyBranchConstants::emptyResultSetErr
					);
			} else {	$customersCompaniesBranchesResponse->setContent(json_encode($customerCompanyBranch));
			}
		} catch(\PDOException $e){
			$customersCompaniesBranchesResponse->setStatusCode(
					400, 
					customerCompanyBranchConstants::dbReadCatchMsg
					);
		}
		
		return $customersCompaniesBranchesResponse;
	}
	
	//URL-->>/query/customers-companies-branches
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[customerCompanyBranchConstants::reqCustomerUsername])){	array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::dbCustomerUsername, 
						'LIKE', 
						'%' . $_GET[customerCompanyBranchConstants::reqCustomerUsername] . '%'
				]
				);
		}
		if(isset($_GET[customerCompanyBranchConstants::reqCompanyName])){	array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::dbCompanyName, 
						'LIKE', 
						'%' . $_GET[customerCompanyBranchConstants::reqCompanyName] . '%'
				]
				);
		}
		if(isset($_GET[customerCompanyBranchConstants::reqBranchId])){		array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::dbBranchId, 
						'=', 
						$_GET[customerCompanyBranchConstants::reqBranchId]
				]
				);
		}
		if(isset($_GET[customerCompanyBranchConstants::reqCustomerCompanyBranchLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::dbCustomerCompanyBranchLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[customerCompanyBranchConstants::reqCustomerCompanyBranchLastChangeTimestamp] . '%'
				]
				);
		}
		
		$customersCompaniesBranchesResponse = new Response();
		try{
			$customersCompaniesBranches = DB::table(customerCompanyBranchConstants::customersCompaniesBranchesTable)
			->where($mySqlWhere)
			->get();
			if($customersCompaniesBranches->isEmpty()){	$customersCompaniesBranchesResponse->setStatusCode(
					200, 
					customerCompanyBranchConstants::emptyResultSetErr
					);
			} else {	$customersCompaniesBranchesResponse->setContent(json_encode($customersCompaniesBranches));
			}
		} catch(\PDOException $e){	$customersCompaniesBranchesResponse->setStatusCode(
				400, 
				customerCompanyBranchConstants::dbReadCatchMessage
				);
		}
		
		return $customersCompaniesBranchesResponse;
	}
	
	private function isDataValid(
			$jsonData, 
			&$errorMsg, 
			$dbOperation
			){
		if("ADD" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . customerCompanyBranchConstants::dbCustomerUsername => 'exists:customers,customer_username|required|string|max:30', 
							'*.' . customerCompanyBranchConstants::dbCompanyName => 'exists:companies,company_name|required|string|max:30', 
							'*.' . customerCompanyBranchConstants::dbBranchId => 'exists:branches,branch_id|required|int'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . customerCompanyBranchConstants::dbCustomerUsername => 'exists:customers,customer_username|sometimes|string|max:30', 
							'*.' . customerCompanyBranchConstants::dbCompanyName => 'exists:companies,company_name|sometimes|string|max:30', 
							'*.' . customerCompanyBranchConstants::dbBranchId => 'exists:branches,branch_id|required|int', 
							'*.' . customerCompanyBranchConstants::dbCustomerCompanyBranchLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/customers-companies-branches/
	public function addCustomerCompanyBranch(Request $jsonRequest){
		$customerCompanyBranch = [];
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$customerController = new customerController();
		$companyController = new companyController();
		$branchController = new branchController();
		
		$customerDbWrite = false;
		$companyDbWrite = false;
		$branchDbWrite = false;
		
		$customersCompaniesBranchesResponse = new Response();
		$customersCompaniesBranchesResponse->setStatusCode(
				400, 
				null
				);
		for($i=0; $i<$jsonDataSize; $i++){
			$customerCompanyBranchRunner = $jsonData[$i];
			
			DB::beginTransaction();
			try{
				//db_transaction: add_customer
				if(array_key_exists(
						customerCompanyBranchConstants::keyCustomer, 
						$customerCompanyBranchRunner
						)
						){
					$customer = $customerCompanyBranchRunner[customerCompanyBranchConstants::keyCustomer];
					if($customerController->isDataValid(
							[$customer], 
							$errorMsg, 
							"ADD"
							)
							){
						$pwHashed = Hash::make($customer[customerConstants::dbCustomerPassword]);
						$customer[customerConstants::dbCustomerPassword] = $pwHashed;
						
						try{	DB::table(customerConstants::customersTable)
						->insert($customer);
						} catch(\PDOException $e){	throw $e;
						}
						
						$customerCompanyBranch[customerConstants::dbCustomerUsername] = $customer[customerConstants::dbCustomerUsername];
						$customerDbWrite = true;
					} else {
						$customersCompaniesBranchesResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $customersCompaniesBranchesResponse;
					}
				}
				
				//db_transaction: add_company
				if(array_key_exists(
						customerCompanyBranchConstants::keyCompany, 
						$customerCompanyBranchRunner
						)
						){
					$company = $customerCompanyBranchRunner[customerCompanyBranchConstants::keyCompany];
					if($companyController->isDataValid(
							[$company], 
							$errorMsg, 
							"ADD"
							)
							){
						try{	DB::table(companyConstants::companiesTable)
						->insert($company);
						} catch(\PDOException $e){	throw $e;
						}
						
						$customerCompanyBranch[companyConstants::dbCompanyName] = $company[companyConstants::dbCompanyName];
						$companyDbWrite = true;
					} else {
						$customersCompaniesBranchesResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $customersCompaniesBranchesResponse;
					}
				}
				
				//db_transaction: add_branch
				if(array_key_exists(
						customerCompanyBranchConstants::keyBranch, 
						$customerCompanyBranchRunner
						)
						){
					$branch = $customerCompanyBranchRunner[customerCompanyBranchConstants::keyBranch];
					if($branchController->isDataValid(
							[$branch], 
							$errorMsg, 
							"ADD"
							)
							){
						if(!($branch[branchConstants::dbCompanyName] == $company[companyConstants::dbCompanyName])){
							$customersCompaniesBranchesResponse->setStatusCode(
									400, 
									customerCompanyBranchConstants::inconsistencyValidationErr1
									);
							
							return $customersCompaniesBranchesResponse;
						}
						
						try{	DB::table(branchConstants::branchesTable)
						->insert($branch);
						} catch(\PDOException $e){	throw $e;
						}
					} else {
						$customersCompaniesBranchesResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $customersCompaniesBranchesResponse;
					}
				}
				
				//db_transaction: add_customerCompanyBranch
				if(array_key_exists(
						customerCompanyBranchConstants::keyBranch, 
						$customerCompanyBranchRunner
						)
						){
					$companyName = $company[companyConstants::dbCompanyName];
					$branchName = $branch[branchConstants::dbBranchName];
					$branch = json_decode(
							$branchController->getCompanyBranch(
									$companyName, 
									$branchName
									)->original, 
							true)[0];
					
					$customerCompanyBranch[branchConstants::dbBranchId] = $branch[branchConstants::dbBranchId];
					$branchDbWrite = true;
				}
				
				if(
						$customerDbWrite && 
						$companyDbWrite && 
						$branchDbWrite
						){
					if($this->isDataValid(
							[$customerCompanyBranch], 
							$errorMsg, 
							"ADD"
							)
							){
						try{
							DB::table(customerCompanyBranchConstants::customersCompaniesBranchesTable)
							->insert($customerCompanyBranch);
						} catch(\PDOException $e){	throw $e;
						}
					} else {
						$customersCompaniesBranchesResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $customersCompaniesBranchesResponse;
					}
				} else {
					if(array_key_exists(
							customerCompanyBranchConstants::keyCustomerCompanyBranch, 
							$customerCompanyBranchRunner
							)
							){
						if($customerDbWrite){
							$customerCompanyBranch = $customerCompanyBranchRunner[customerCompanyBranchConstants::keyCustomerCompanyBranch];
							if($this->isDataValid(
									[$customerCompanyBranch], 
									$errorMsg, 
									"ADD"
									)
									){
								try{
									DB::table(customerCompanyBranchConstants::customersCompaniesBranchesTable)
									->insert($customerCompanyBranch);
								} catch(\PDOException $e){	throw $e;
								}
							}
						}
					}
				}
				
				DB::commit();
			} catch(\PDOException $e){
				DB::rollback();
				$customersCompaniesBranchesResponse->setStatusCode(
						400, 
						customerCompanyBranchConstants::dbAddCatchMsg
						);
				
				return $customersCompaniesBranchesResponse;
			}
		}
		
		return customerCompanyBranchConstants::dbAddSuccessMsg;
	}
}