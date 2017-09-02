<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "customerCompanyBranchController.php";
include_once "pushController.php";

class companyConstants{
	const companiesTable = 'companies';
	
	const dbCompanyName = 'company_name';
	const dbCompanyDesc = 'company_desc';
	const dbCompanyCategory = 'company_category';
	const dbCompanyLogo = 'company_logo';
	const dbCompanyLastChangeTimestamp = 'company_last_change_timestamp';
	
	const reqCompanyName = 'CompanyName';
	const reqCompanyDesc = 'CompanyDesc';
	const reqCompanyCategory = 'CompanyCategory';
	const reqCompanyLogo = 'CompanyLogo';
	const reqCompanyLastChangeTimestamp = 'CompanyLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW COMPANY RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING COMPANY RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING COMPANY RECORD';
	
	const dbAddValidateSuccessMsg = 'DATA IS VALID FOR DB ADD OPERATION';
	const dbUpdateValidateSuccessMsg = 'DATA IS VALID FOR DB UPDATE OPERATION';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class companyController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCustomerCompanyBranchCompany($mySqlWhere){
		$customerCompanyBranchCompany = DB::table(companyConstants::companiesTable)
		->join(
				customerCompanyBranchConstants::customersCompaniesBranchesTable, 
				companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
				'=', 
				customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCompanyName
				)
				->where($mySqlWhere)
		->get();
		
		return $customerCompanyBranchCompany;
	}
	
	//URL-->>/companies/customers/{CustomerUsername}
	public function getCompanies_asAdministrator($CustomerUsername){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		
		$companiesResponse = new Response();
		try{
			$companies = $this->getJoinCustomerCompanyBranchCompany($mySqlWhere);
			if($companies->isEmpty()){	$companiesResponse->setStatusCode(
					200, 
					companyConstants::emptyResultSetErr
					);
			} else {	$companiesResponse->setContent(json_encode($companies));
			}
		} catch(\PDOException $e){	$companiesResponse->setStatusCode(
				400, 
				dbReadCatchMsg
				);
		}
		
		return $companiesResponse;
	}
	
	//URL-->>/companies/
	public function getCompanies(){
		$companiesResponse = new Response();
		try{
			$companies = DB::table(companyConstants::companiesTable)
			->get();
			if($companies->isEmpty()){	$companiesResponse->setStatusCode(
					200, 
					companyConstants::emptyResultSetErr
					);
			} else {	$companiesResponse->setContent(json_encode($companies));
			}
		} catch(\PDOException $e){
			$companiesResponse->setStatusCode(
					400, 
					customersConstants::dbReadCatchMsg
					);
		}
		
		return $companiesResponse;
	}
	
	//URL-->>/companies/{CompanyName}
	public function getCompany($CompanyName){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		
		$companiesResponse = new Response();
		try{	
			$company = DB::table(companyConstants::companiesTable)
			->where($mySqlWhere)
			->get();
			if($company->isEmpty()){	$companiesResponse->setStatusCode(
					200, 
					companyConstants::emptyResultSetErr
					);
			} else {	$companiesResponse->setContent(json_encode($company));
			}
		} catch(\PDOException $e){	$customersResponse->setStatusCode(
				400, 
				customersConstants::dbReadCatchMsg
				);
		}
		
		return $companiesResponse;
	}
	
	//URL-->>/query/companies
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[companyConstants::reqCompanyName])){	array_push(
				$mySqlWhere, 
				[
						companyConstants::dbCompanyName, 
						'LIKE', 
						'%' . $_GET[companyConstants::reqCompanyName] . '%'
				]
				);
		}
		if(isset($_GET[companyConstants::reqCompanyDesc])){	array_push(
				$mySqlWhere, 
				[
						companyConstants::dbCompanyDesc, 
						'LIKE', 
						'%' . $_GET[companyConstants::reqCompanyDesc] . '%'
				]
				);
		}
		if(isset($_GET[companyConstants::reqCompanyCategory])){	array_push(
				$mySqlWhere, 
				[
						companyConstants::dbCompanyCategory, 
						'LIKE', 
						'%' . $_GET[companyConstants::reqCompanyCategory] . '%'
				]
				);
		}
		if(isset($_GET[companyConstants::reqCompanyLogo])){	array_push(
				$mySqlWhere, 
				[
						companyConstants::dbCompanyLogo, 
						'LIKE', 
						'%' . $_GET[companyConstants::reqCompanyLogo] . '%'
				]
				);
		}
		if(isset($_GET[companyConstants::reqCompanyLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						companyConstants::dbCompanyLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[companyConstants::reqCompanyLastChangeTimestamp] . '%'
				]
				);
		}
			
		$companiesResponse = new Response();
		try{
			$companies = DB::table(companyConstants::companiesTable)
			->where($mySqlWhere)
			->get();
			if($companies->isEmpty()){	$companiesResponse->setStatusCode(
					200, 
					companyConstants::emptyResultSetErr
					);
			} else {	$companiesResponse->setContent(json_encode($companies));
			}
		} catch(\PDOException $e){	$customersResponse->setStatusCode(
				400, 
				customersConstants::dbReadCatchMsg
				);
		}
		
		return $companiesResponse;
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
							'*.' . companyConstants::dbCompanyName => 'unique:companies,company_name|required|string|max:30', 
							'*.' . companyConstants::dbCompanyDesc => 'required|string|max:500', 
							'*.' . companyConstants::dbCompanyCategory => 'required|string|max:30', 
							'*.' . companyConstants::dbCompanyLogo => 'required|string|max:500'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . companyConstants::dbCompanyName => 'unique:companies,company_name|sometimes|string|max:30', 
							'*.' . companyConstants::dbCompanyDesc => 'sometimes|string|max:500', 
							'*.' . companyConstants::dbCompanyCategory => 'sometimes|string|max:30', 
							'*.' . companyConstants::dbCompanyLogo => 'sometimes|string|max:500', 
							'*.' . companyConstants::dbCompanyLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg .=  $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/validate
	public function addCompanyValidate(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$companiesResponse = new Response();
		$companiesResponse->setStatusCode(
				400, 
				null
				);
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){	return companyConstants::dbAddValidateSuccessMsg;
		} else {
			$companiesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $companiesResponse;
		}
	}
	
	//URL-->>/companies/
	public function addCompany(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$companiesResponse = new Response();
		$companiesResponse->setStatusCode(
				400, 
				null);
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){
			for($i=0; $i<$jsonDataSize; $i++){
				try{
					DB::table(companyConstants::companiesTable)
					->insert($jsonData[$i]);
					
					$notificationMessage = $jsonData[$i][companyConstants::dbCompanyName] . ' record registered';
					(new pushController())->sendUpdateCompanyCommand($notificationMessage);
				} catch(\PDOException $e){
					$companiesResponse->setStatusCode(
							400, 
							companyConstants::dbAddCatchMsg
							);
					
					return $companiesResponse;
				}
			}
		} else {
			$companiesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $companiesResponse;
		}
		
		return companyConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/validate
	public function updateCompanyValidate(
			Request $jsonRequest, 
			$CompanyName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$companiesResponse = new Response();
		$companiesResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][companyConstants::dbCompanyLastChangeTimestamp])){
			try{	$jsonData[0][companyConstants::dbCompanyLastChangeTimestamp] = Carbon::parse($jsonData[0][companyConstants::dbCompanyLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$companiesResponse->setStatusCode(
						400, 
						companyConstants::carbonParseErr
						);
				
				return $companiesResponse;
			}
		}
		
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){	return companyConstants::dbUpdateValidateSuccessMsg;
		} else {
			$companiesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $companiesResponse;
		}
	}
	
	//URL-->>/companies/{CompanyName}
	public function updateCompany(
			Request $jsonRequest, 
			$CompanyName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$companiesResponse = new Response();
		$companiesResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][companyConstants::dbCompanyLastChangeTimestamp])){
			try{	$jsonData[0][companyConstants::dbCompanyLastChangeTimestamp] = Carbon::parse($jsonData[0][companyConstants::dbCompanyLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$companiesResponse->setStatusCode(
						400, 
						companyConstants::carbonParseErr
						);
				
				return $companiesResponse;
			}
		}
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$companiesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $companiesResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							companyConstants::dbCompanyName, 
							'=', 
							$CompanyName
					]
					);
			DB::table(companyConstants::companiesTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $jsonData[0][companyConstants::dbCompanyName] . ' record updated';
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$companiesResponse->setStatusCode(
					400, 
					companyConstants::dbUpdateCatchMsg
					);
			
			return $companiesResponse;
		}
		
		return companyConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}
	public function deleteCompany($CompanyName){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$companiesResponse = new Response();
		$companiesResponse->setStatusCode(
				400, 
				null
				);
		try{
			array_push(
					$mySqlWhere, 
					[
							companyConstants::dbCompanyName, 
							'=', 
							$CompanyName
					]
					);
			DB::table(companyConstants::companiesTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage =  $CompanyName . ' record deleted';
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$companiesResponse->setStatusCode(
					400, 
					companyConstants::dbDeleteCatchMsg
					);
			
			return $companiesResponse;
		}
		
		return companyConstants::dbDeleteSuccessMsg;
	}
}