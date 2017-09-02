<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "companyController.php";
include_once "pushController.php";

class branchConstants{
	const branchesTable = 'branches';
	
	const dbBranchId = 'branch_id';
	const dbBranchName = 'branch_name';
	const dbCompanyName = 'company_name';
	const dbBranchAddressHouseBuilding = 'branch_address_house_building';
	const dbBranchAddressStreet = 'branch_address_street';
	const dbBranchAddressDistrict = 'branch_address_district';
	const dbBranchAddressCity = 'branch_address_city';
	const dbBranchAddressPostalcode = 'branch_address_postalcode';
	const dbBranchAddressCountry = 'branch_address_country';
	const dbBranchHotline = 'branch_hotline';
	const dbBranchLastChangeTimestamp = 'branch_last_change_timestamp';
	
	const reqBranchId = 'BranchId';
	const reqBranchName = 'BranchName';
	const reqCompanyName = 'CompanyName';
	const reqBranchAddressHouseBuilding = 'BranchAddressHouseBuilding';
	const reqBranchAddressStreet = 'BranchAddressStreet';
	const reqBranchAddressDistrict = 'BranchAddressDistrict';
	const reqBranchAddressCity = 'BranchAddressCity';
	const reqBranchAddressPostalcode = 'BranchAddressPostalcode';
	const reqBranchAddressCountry = 'BranchAddressCountry';
	const reqBranchHotline = 'BranchHotline';
	const reqBranchLastChangeTimestamp = 'BranchLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW BRANCH RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING BRANCH RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING BRANCH RECORD';
	
	const dbAddValidateSuccessMsg = 'DATA IS VALID FOR DB ADD OPERATION';
	const dbUpdateValidateSuccessMsg = 'DATA IS VALID FOR DB UPDATE OPERATION';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class branchController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyBranch($mySqlWhere){
		$companyBranch = DB::table(branchConstants::branchesTable)
		->join(
				companyConstants::companiesTable, 
				branchConstants::branchesTable . '.' . branchConstants::dbCompanyName, 
				'=', 
				companyConstants::companiesTable . '.' . companyConstants::dbCompanyName
				)
				->where($mySqlWhere)
		->get();
		
		return $companyBranch;
	}
	
	//URL-->>/companies/{CompanyName}/branches
	public function getCompanyBranches($CompanyName){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		
		$branchesResponse = new Response();
		try{
			$companyBranches = $this->getJoinCompanyBranch($mySqlWhere);
			if($companyBranches->isEmpty()){	$branchesResponse->setStatusCode(
					200, 
					branchConstants::emptyResultSetErr
					);
			} else {	$branchesResponse->setContent(json_encode($companyBranches));
			}
		} catch(\PDOException $e){	$branchesResponse->setStatusCode(
				400, 
				branchConstants::dbReadCatchMsg
				);
		}
		
		return $branchesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}
	public function getCompanyBranch($CompanyName, $BranchName){
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
		
		$branchesResponse = new Response();
		try{
			$companyBranch = $this->getJoinCompanyBranch($mySqlWhere);
			if($companyBranch->isEmpty()){	$branchesResponse->setStatusCode(
					200, 
					branchConstants::emptyResultSetErr
					);
			} else {	$branchesResponse->setContent(json_encode($companyBranch));
			}
		} catch(\PDOException $e){	$branchesResponse->setStatusCode(
				400, 
				branchConstants::dbReadCatchMsg
				);
		}
		
		return $branchesResponse;
	}
	
	//URL-->>/query/branches
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[branchConstants::reqBranchId])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchId, 
						'=', 
						$_GET[branchConstants::reqBranchId]
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchName])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchName, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchName] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqCompanyName])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbCompanyName, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqCompanyName] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchAddressHouseBuilding])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchAddressHouseBuilding, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchAddressHouseBuilding] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchAddressStreet])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchAddressStreet, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchAddressStreet] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchAddressDistrict])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchAddressDistrict, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchAddressDistrict] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchAddressCity])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchAddressCity, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchAddressCity] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchAddressPostalcode])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchAddressPostalcode, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchAddressPostalcode] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchAddressCountry])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchAddressCountry, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchAddressCountry] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchHotline])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchHotline, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchHotline] . '%'
				]
				);
		}
		if(isset($_GET[branchConstants::reqBranchLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						branchConstants::dbBranchLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[branchConstants::reqBranchLastChangeTimestamp] . '%'
				]
				);
		}
		
		$branchesResponse = new Response();
		try{
			$companyBranches  = DB::table(branchConstants::branchesTable)
			->where($mySqlWhere)
			->get();
			if($companyBranches->isEmpty()){	$branchesResponse->setStatusCode(
					200, 
					branchConstants::emptyResultSetErr
					);
			} else {	$branchesResponse->setContent(json_encode($companyBranches));
			}
		} catch(\PDOException $e){	$branchesResponse->setStatusCode(
				400, 
				branchConstants::dbReadCatchMsg
				);
		}
		
		return $branchesResponse;
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
							'*.' . branchConstants::dbBranchName => 'required|string|max:30', 
							'*.' . branchConstants::dbCompanyName => 'exists:companies,company_name|required|string|max:30', 
							'*.' . branchConstants::dbBranchAddressHouseBuilding => 'required|string|max:30', 
							'*.' . branchConstants::dbBranchAddressStreet => 'required|string|max:30', 
							'*.' . branchConstants::dbBranchAddressDistrict => 'required|string|max:30', 
							'*.' . branchConstants::dbBranchAddressCity => 'required|string|max:30', 
							'*.' . branchConstants::dbBranchAddressPostalcode => 'required|string|max:30', 
							'*.' . branchConstants::dbBranchAddressCountry => 'required|string|max:30', 
							'*.' . branchConstants::dbBranchHotline => 'required|string|max:10'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . branchConstants::dbBranchName => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbCompanyName => 'exists:companies,company_name|sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchAddressHouseBuilding => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchAddressStreet => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchAddressDistrict => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchAddressCity => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchAddressPostalcode => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchAddressCountry => 'sometimes|string|max:30', 
							'*.' . branchConstants::dbBranchHotline => 'sometimes|string|max:10', 
							'*.' . branchConstants::dbBranchLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg .=  $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches/validate
	public function addBranchValidate(
			Request $jsonRequest, 
			$CompanyName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$branchesResponse = new Response();
		$branchesResponse->setStatusCode(
				400, 
				null
				);
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){	return branchConstants::dbAddValidateSuccessMsg;
		} else {
			$branchesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $branchesResponse;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches
	public function addBranch(
			Request $jsonRequest, 
			$CompanyName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$branchesResponse = new Response();
		$branchesResponse->setStatusCode(
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
				if($jsonData[$i][companyConstants::dbCompanyName] == $CompanyName){
					try{
						DB::table(branchConstants::branchesTable)
						->insert($jsonData[$i]);
						
						$notificationMessage = $CompanyName . ' has added branch ' . $jsonData[$i][branchConstants::dbBranchName];
						(new pushController())->sendUpdateCompanyCommand($notificationMessage);
					} catch(\PDOException $e){
						$branchesResponse->setStatusCode(
								400, 
								branchConstants::dbAddCatchMsg
								);
						
						return $branchesResponse;
					}
				}
			}
		} else {
			$branchesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $branchesResponse;
		}
		
		return branchConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/validate
	public function updateBranchValidate(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$branchesResponse = new Response();
		$branchesResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][branchConstants::dbBranchLastChangeTimestamp])){
			try{	$jsonData[0][branchConstants::dbBranchLastChangeTimestamp] = Carbon::parse($jsonData[0][branchConstants::dbBranchLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$branchesResponse->setStatusCode(
						400, 
						branchConstants::carbonParseErr
						);
				
				return $branchesResponse;
			}
		}
		
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){	return branchConstants::dbUpdateValidateSuccessMsg;
		} else {
			$branchesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $branchesResponse;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}
	public function updateBranch(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$branchesResponse = new Response();
		$branchesResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][branchConstants::dbBranchLastChangeTimestamp])){
			try{	$jsonData[0][branchConstants::dbBranchLastChangeTimestamp] = Carbon::parse($jsonData[0][branchConstants::dbBranchLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$branchesResponse->setStatusCode(
						400, 
						branchConstants::carbonParseErr
						);
				
				return $branchesResponse;
			}
		}
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$branchesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $branchesResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							branchConstants::dbCompanyName, 
							'=', 
							$CompanyName
					]
					);
			array_push(
					$mySqlWhere, 
					[
							branchConstants::dbBranchName, 
							'=', 
							$BranchName
					]
					);
			DB::table(branchConstants::branchesTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $CompanyName . ' has updated branch ' . $BranchName;
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$branchesResponse->setStatusCode(
					400, 
					branchConstants::dbUpdateCatchMsg
					);
			
			return $branchesResponse;
		}
		
		return branchConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}
	public function deleteBranch(
			$CompanyName, 
			$BranchName
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$branchesResponse = new Response();
		$branchesResponse->setStatusCode(
				400, 
				null
				);
		try{
			array_push(
					$mySqlWhere, 
					[
							branchConstants::dbCompanyName, 
							'=', 
							$CompanyName
					]
					);
			array_push(
					$mySqlWhere, 
					[
							branchConstants::dbBranchName, 
							'=', 
							$BranchName
					]
					);
			DB::table(branchConstants::branchesTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage = $CompanyName . ' has deleted branch ' . $BranchName;
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$branchesResponse->setStatusCode(
					400, 
					branchConstants::dbDeleteCatchMsg
					);
			
			return $branchesResponse;
		}
		
		return branchConstants::dbDeleteSuccessMsg;
	}
}