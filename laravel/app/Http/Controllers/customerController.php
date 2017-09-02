<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

include_once "customerCompanyBranchController.php";

class customerConstants{
	const customersTable = 'customers';
	
	const dbCustomerUsername = 'customer_username';
	const dbCustomerPassword = 'customer_password';
	const dbCustomerNameFname = 'customer_name_fname';
	const dbCustomerNameMname = 'customer_name_mname';
	const dbCustomerNameLname = 'customer_name_lname';
	const dbCustomerRole = 'customer_role';
	const dbCustomerGender = 'customer_gender';
	const dbCustomerAddressHouseBuilding = 'customer_address_house_building';
	const dbCustomerAddressStreet = 'customer_address_street';
	const dbCustomerAddressDistrict = 'customer_address_district';
	const dbCustomerAddressCity = 'customer_address_city';
	const dbCustomerAddressPostalcode = 'customer_address_postalcode';
	const dbCustomerAddressCountry = 'customer_address_country';
	const dbCustomerMobile = 'customer_mobile';
	const dbCustomerEmail = 'customer_email';
	const dbCustomerBirthdayMonth = 'customer_birthday_month';
	const dbCustomerBirthdayDate = 'customer_birthday_date';
	const dbCustomerBirthdayYear = 'customer_birthday_year';
	const dbCustomerLastChangeTimestamp = 'customer_last_change_timestamp';
	const dbCustomerDeviceToken = 'customer_device_token';
	
	const reqCustomerUsername = 'CustomerUsername';
	const reqCustomerPassword = 'CustomerPassword';
	const reqCustomerNameFname = 'CustomerNameFname';
	const reqCustomerNameMname = 'CustomerNameMname';
	const reqCustomerNameLname = 'CustomerNameLname';
	const reqCustomerRole = 'CustomerRole';
	const reqCustomerGender = 'CustomerGender';
	const reqCustomerAddressHouseBuilding = 'CustomerAddressHouseBuilding';
	const reqCustomerAddressStreet = 'CustomerAddressStreet';
	const reqCustomerAddressDistrict = 'CustomerAddressDistrict';
	const reqCustomerAddressCity = 'CustomerAddressCity';
	const reqCustomerAddressPostalcode = 'CustomerAddressPostalcode';
	const reqCustomerAddressCountry = 'CustomerAddressCountry';
	const reqCustomerMobile = 'CustomerMobile';
	const reqCustomerEmail = 'CustomerEmail';
	const reqCustomerBirthdayMonth = 'CustomerBirthdayMonth';
	const reqCustomerBirthdayDate = 'CustomerBirthdayDate';
	const reqCustomerBirthdayYear = 'CustomerBirthdayYear';
	const reqCustomerLastChangeTimestamp = 'CustomerLastChangeTimestamp';
	const reqCustomerDeviceToken = 'CustomerDeviceToken';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW CUSTOMER RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING CUSTOMER RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING CUSTOMER RECORD';
	
	const dbAddValidateSuccessMsg = 'DATA IS VALID FOR DB ADD OPERATION';
	const dbUpdateValidateSuccessMsg = 'DATA IS VALID FOR DB UPDATE OPERATION';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class customerController extends Controller
{
	public function __construct(){	$this->middleware(
			'jwt.auth', 
			['except' => ['addCustomer']]
			);
	}
	
	public function getJoinCustomerCompanyBranchCustomer($mySqlWhere){
		$customerCompanyBranchCustomer = DB::table(customerConstants::customersTable)
		->join(
				customerCompanyBranchConstants::customersCompaniesBranchesTable, 
				customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
				'=', 
				customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCustomerUsername
				)
				->where($mySqlWhere)
		->get();
		
		return $customerCompanyBranchCustomer;
	}
	
	//URL-->>/customers/companies/{CompanyName}
	public function getCustomers_asAdministrator($CompanyName){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		
		$customersResponse = new Response();
		try{
			$customers = $this->getJoinCustomerCompanyBranchCustomer($mySqlWhere);
			if($customers->isEmpty()){	$customersResponse->setStatusCode(
					200, 
					customerConstants::emptyResultSetErr
					);
			} else {	$customersResponse->setContent(json_encode($customers));
			}
		} catch(\PDOException $e){	$customersResponse->setStatusCode(
				400, 
				dbReadCatchMsg
				);
		}
		
		return $customersResponse;
	}
	
	//URL-->>/customers/
	public function getCustomers(){
		$customersResponse = new Response();
		try{
			$customers = DB::table(customerConstants::customersTable)
			->get();
			if($customers->isEmpty()){	$customersResponse->setStatusCode(
					200, 
					customerConstants::emptyResultSetErr
					);
			} else {	$customersResponse->setContent(json_encode($customers));
			}
		} catch(\PDOException $e){	$customersResponse->setStatusCode(
				400, 
				customerConstants::dbReadCatchMsg
				);
		}
		
		return $customersResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}
	public function getCustomer($CustomerUsername){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		
		$customersResponse = new Response();
		try{
			$customer = DB::table(customerConstants::customersTable)
			->where($mySqlWhere)
			->get();
			if($customer->isEmpty()){	$customersResponse->setStatusCode(
					200, 
					customerConstants::emptyResultSetErr
					);
			} else {	$customersResponse->setContent(json_encode($customer));
			}
		} catch(\PDOException $e){	$customersResponse->setStatusCode(
				400, 
				customerConstants::dbReadCatchMsg
				);
		}
		
		return $customersResponse;
	}
	
	//URL-->>/query/customers
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[customerConstants::reqCustomerUsername])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerUsername, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerUsername] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerPassword])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerPassword, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerPassword] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerNameFname])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerNameFname, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerNameFname] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerNameMname])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerNameMname, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerNameMname] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerNameLname])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerNameLname, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerNameLname] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerRole])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerRole, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerRole] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerGender])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerGender, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerGender] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerAddressHouseBuilding])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerAddressHouseBuilding, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerAddressHouseBuilding] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerAddressStreet])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerAddressStreet, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerAddressStreet] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerAddressDistrict])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerAddressDistrict, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerAddressDistrict] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerAddressCity])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerAddressCity, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerAddressCity] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerAddressPostalcode])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerAddressPostalcode, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerAddressPostalcode] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerAddressCountry])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerAddressCountry, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerAddressCountry] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerMobile])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerMobile, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerMobile] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerEmail])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerEmail, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerEmail] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerBirthdayMonth])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerBirthdayMonth, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerBirthdayMonth] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerBirthdayDate])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerBirthdayDate, 
						'=', 
						$_GET[customerConstants::reqCustomerBirthdayDate]
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerBirthdayYear])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerBirthdayYear, 
						'=', 
						$_GET[customerConstants::reqCustomerBirthdayYear]
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[customerConstants::reqCustomerLastChangeTimestamp] . '%'
				]
				);
		}
		if(isset($_GET[customerConstants::reqCustomerDeviceToken])){	array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerDeviceToken, 
						'=', 
						$_GET[customerConstants::reqCustomerDeviceToken]
				]
				);
		}
		
		$customersResponse = new Response();
		try{
			$customers = DB::table(customerConstants::customersTable)
			->where($mySqlWhere)
			->get();
			if($customers->isEmpty()){	$customersResponse->setStatusCode(
					200, 
					customerConstants::emptyResultSetErr
					);
			} else {	$customersResponse->setContent(json_encode($customers));
			}
		} catch(\PDOException $e){
			$customersResponse->setStatusCode(
					400, 
					customerConstants::dbReadCatchMsg
					);
		}
		
		return $customersResponse;
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
							'*.' . customerConstants::dbCustomerUsername => 'unique:customers,customer_username|required|string|max:30', 
							'*.' . customerConstants::dbCustomerPassword => 'required|string|max:100', 
							'*.' . customerConstants::dbCustomerNameFname => 'required|string|max:30', 
							'*.' . customerConstants::dbCustomerNameMname => 'required|string|max:30', 
							'*.' . customerConstants::dbCustomerNameLname => 'required|string|max:30', 
							'*.' . customerConstants::dbCustomerRole => 'required|string|max:30', 
							'*.' . customerConstants::dbCustomerGender => 'string|max:10', 
							'*.' . customerConstants::dbCustomerAddressHouseBuilding => 'string|max:30', 
							'*.' . customerConstants::dbCustomerAddressStreet => 'string|max:30', 
							'*.' . customerConstants::dbCustomerAddressDistrict => 'string|max:30', 
							'*.' . customerConstants::dbCustomerAddressCity => 'string|max:30', 
							'*.' . customerConstants::dbCustomerAddressPostalcode => 'string|max:30', 
							'*.' . customerConstants::dbCustomerAddressCountry => 'string|max:30', 
							'*.' . customerConstants::dbCustomerMobile => 'string|max:30', 
							'*.' . customerConstants::dbCustomerEmail => 'string|email|max:30', 
							'*.' . customerConstants::dbCustomerBirthdayMonth => 'string|max:30', 
							'*.' . customerConstants::dbCustomerBirthdayDate => 'numeric', 
							'*.' . customerConstants::dbCustomerBirthdayYear => 'numeric', 
							'*.' . customerConstants::dbCustomerDeviceToken => 'string|max:1000'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . customerConstants::dbCustomerUsername => 'unique:customers,customer_username|sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerPassword => 'sometimes|string|max:100', 
							'*.' . customerConstants::dbCustomerNameFname => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerNameMname => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerNameLname => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerRole => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerGender => 'sometimes|string|max:10', 
							'*.' . customerConstants::dbCustomerAddressHouseBuilding => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerAddressStreet => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerAddressDistrict => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerAddressCity => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerAddressPostalcode => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerAddressCountry => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerMobile => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerEmail => 'sometimes|string|email|max:30', 
							'*.' . customerConstants::dbCustomerBirthdayMonth => 'sometimes|string|max:30', 
							'*.' . customerConstants::dbCustomerBirthdayDate => 'sometimes|numeric', 
							'*.' . customerConstants::dbCustomerBirthdayYear => 'sometimes|numeric', 
							'*.' . customerConstants::dbCustomerLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s', 
							'*.' . customerConstants::dbCustomerDeviceToken => 'sometimes|string|max:1000'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg .=  $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/customers/validate
	public function addCustomerValidate(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$customersResponse = new Response();
		$customersResponse->setStatusCode(
				400, 
				null
				);
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){	return customerConstants::dbAddValidateSuccessMsg;
		} else {
			$customersResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $customersResponse;
		}
	}
	
	//URL-->>/customers/
	public function addCustomer(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$customersResponse = new Response();
		$customersResponse->setStatusCode(
				400, 
				null
				);
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){
			for($i=0; $i<$jsonDataSize; $i++){
				try{
					$pwHashed = Hash::make($jsonData[$i][customerConstants::dbCustomerPassword]);
					$jsonData[$i][customerConstants::dbCustomerPassword] = $pwHashed;
					DB::table(customerConstants::customersTable)
					->insert($jsonData[$i]);
				} catch(\PDOException $e){
					$customersResponse->setStatusCode(
							400, 
							customerConstants::dbAddCatchMsg
							);
					
					return $customersResponse;
				}
			}
		} else  {
			$customersResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $customersResponse;
		}
		
		return customerConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/customers/{CustomerUsername}/validate
	public function updateCustomerValidate(
			Request $jsonRequest, 
			$CustomerUsername
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$customersResponse = new Response();
		$customersResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][customerConstants::dbCustomerLastChangeTimestamp])){
			try{	$jsonData[0][customerConstants::dbCustomerLastChangeTimestamp] = Carbon::parse($jsonData[0][customerConstants::dbCustomerLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$customersResponse->setStatusCode(
						400, 
						customerConstants::carbonParseErr
						);
				
				return $customersResponse;
			}
		}
		
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){	return customerConstants::dbUpdateValidateSuccessMsg;
		} else {
			$customersResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $customersResponse;
		}
	}
	
	//URL-->>/customers/{CustomerUsername}
	public function updateCustomer(
			Request $jsonRequest, 
			$CustomerUsername
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$customersResponse = new Response();
		$customersResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][customerConstants::dbCustomerLastChangeTimestamp])){
			try{	$jsonData[0][customerConstants::dbCustomerLastChangeTimestamp] = Carbon::parse($jsonData[0][customerConstants::dbCustomerLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$customersResponse->setStatusCode(
						400, 
						customerConstants::carbonParseErr
						);
				
				return $customersResponse;
			}
		}
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$customersResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $customersResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							customerConstants::dbCustomerUsername, 
							'=', 
							$CustomerUsername
					]
					);
			DB::table(customerConstants::customersTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
		} catch(\PDOException $e){
			$customersResponse->setStatusCode(
					400, 
					customerConstants::dbUpdateCatchMsg
					);
			
			return $customersResponse;
		}
		
		return customerConstants::dbUpdateSuccessMsg;
	}

	//URL-->>/customers/{CustomerUsername}
	public function deleteCustomer($CustomerUsername){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$customersResponse = new Response();
		$customersResponse->setStatusCode(
				400, 
				null
				);
		try{
			array_push(
					$mySqlWhere, 
					[
							customerConstants::dbCustomerUsername, 
							'=', 
							$CustomerUsername
					]
					);
			DB::table(customerConstants::customersTable)
			->where($mySqlWhere)
			->delete();
		} catch(\PDOException $e){
			$customersResponse->setStatusCode(
					400, 
					customerConstants::dbDeleteCatchMsg
					);
			
			return $customersResponse;
		}
		
		return customerConstants::dbDeleteSuccessMsg;
	}
}