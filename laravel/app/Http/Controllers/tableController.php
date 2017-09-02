<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "branchController.php";
include_once "companyController.php";
include_once "pushController.php";

class tableConstants{
	const tablesTable = 'tables';
	
	const dbTableId = 'table_id';
	const dbBranchId = 'branch_id';
	const dbTableNumber = 'table_number';
	const dbTableCapacity = 'table_capacity';
	const dbTableStatus = 'table_status';
	const dbTableStatusChangeTimestamp = 'table_status_change_timestamp';
	const dbTableLastChangeTimestamp = 'table_last_change_timestamp';
	
	const reqTableId = 'TableId';
	const reqBranchId = 'BranchId';
	const reqTableNumber = 'TableNumber';
	const reqTableCapacity = 'TableCapacity';
	const reqTableStatus = 'TableStatus';
	const reqTableStatusChangeTimestamp = 'TableStatusChangeTimestamp';
	const reqTableLastChangeTimestamp = 'TableLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW TABLE RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING TABLE RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING TABLE RECORD';
	
	const inconsistencyValidationErr1 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME IS NON-EXISTING';
	const inconsistencyValidationErr2 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER IS NON-EXISTING';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class tableController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}

	public function getJoinCompanyBranchTable($mySqlWhere){
		$companyBranchTable = DB::table(tableConstants::tablesTable)
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
		
		return $companyBranchTable;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables
	public function getCompanyBranchTables(
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
		
		$tablesResponse = new Response();
		try{
			$companyBranchTables = $this->getJoinCompanyBranchTable($mySqlWhere);
			if($companyBranchTables->isEmpty()){	$tablesResponse->setStatusCode(
					200, 
					tableConstants::emptyResultSetErr
					);
			} else {	$tablesResponse->setContent(json_encode($companyBranchTables));
			}
		} catch(\PDOException $e){	$tablesResponse->setStatusCode(
				400, 
				tableConstants::dbReadCatchMsg
				);
		}
		
		return $tablesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber} 
	public function getCompanyBranchTable(
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
				[branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
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
		
		$tablesResponse = new Response();
		try{
			$companyBranchTable = $this->getJoinCompanyBranchTable($mySqlWhere);
			if($companyBranchTable->isEmpty()){	$tablesResponse->setStatusCode(
					200, 
					tableConstants::emptyResultSetErr
					);
			} else {	$tablesResponse->setContent(json_encode($companyBranchTable));
			}
		} catch(\PDOException $e){	$tablesResponse->setStatusCode(
				400, 
				tableConstants::dbReadCatchMsg
				);
		}
		
		return $tablesResponse;
	}
	
	//URL-->>/query/tables
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[tableConstants::reqTableId])){	array_push(
				$mySqlWhere, 
				[
						tableConstants::dbTableId, 
						'=', 
						$_GET[tableConstants::reqTableId]
				]
				);
		}
		if(isset($_GET[tableConstants::reqTableNumber])){	array_push(
				$mySqlWhere,
				[
						tableConstants::dbTableNumber,
						'=',
						$_GET[tableConstants::reqTableNumber]
				]
				);
		}
		if(isset($_GET[tableConstants::reqBranchId])){	array_push(
				$mySqlWhere, 
				[
						tableConstants::dbBranchId, 
						'=', 
						$_GET[tableConstants::reqBranchId]
				]
				);
		}
		if(isset($_GET[tableConstants::reqTableCapacity])){	array_push(
				$mySqlWhere, 
				[
						tableConstants::dbTableCapacity, 
						'=', 
						$_GET[tableConstants::reqTableCapacity]
				]
				);
		}
		if(isset($_GET[tableConstants::reqTableStatus])){	array_push(
				$mySqlWhere, 
				[
						tableConstants::dbTableStatus, 
						'LIKE', 
						'%' . $_GET[tableConstants::reqTableStatus] . '%'
				]
				);
		}
		if(isset($_GET[tableConstants::reqTableStatusChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						tableConstants::dbTableStatusChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[tableConstants::reqTableStatusChangeTimestamp] . '%'
				]
				);
		}
		if(isset($_GET[tableConstants::reqTableLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						tableConstants::dbTableLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[tableConstants::reqTableLastChangeTimestamp] . '%'
				]
				);
		}
		
		$tablesResponse = new Response();
		try{
			$tables = DB::table(tableConstants::tablesTable)
			->where($mySqlWhere)
			->get();
			if($tables->isEmpty()){	$tablesResponse->setStatusCode(
					200, 
					tableConstants::emptyResultSetErr
					);
			} else {	$tablesResponse->setContent(json_encode($tables));
			}
		} catch(\PDOException $e){	$tablesResponse->setStatusCode(
				400, 
				tableConstants::dbReadCatchMsg
				);
		}
		
		return $tablesResponse;
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
							'*.' . tableConstants::dbTableNumber => 'required|numeric', 
							'*.' . tableConstants::dbBranchId => 'exists:branches,branch_id|numeric', 
							'*.' . tableConstants::dbTableCapacity => 'required|numeric', 
							'*.' . tableConstants::dbTableStatus => 'required|string|max:30', 
							'*.' . tableConstants::dbTableStatusChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . tableConstants::dbTableNumber => 'sometimes|numeric', 
							'*.' . tableConstants::dbBranchId => 'exists:branches,branch_id|sometimes|numeric', 
							'*.' . tableConstants::dbTableCapacity => 'sometimes|numeric', 
							'*.' . tableConstants::dbTableStatus => 'sometimes|string|max:30', 
							'*.' . tableConstants::dbTableStatusChangeTimestamp => 'sometimes|date_format:Y-m-d H:i:s', 
							'*.' . tableConstants::dbTableLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/
	public function addTable(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$tablesResponse = new Response();
		$tablesResponse->setStatusCode(
				400, 
				null
				);
		$companyBranch = json_decode(
				(new branchController())->getCompanyBranch(
						$CompanyName, 
						$BranchName
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranch) == 0){
			$tablesResponse->setStatusCode(
					400, 
					tableConstants::inconsistencyValidationErr1
					);
			
			return $tablesResponse;
		}
		
		$branchId = $companyBranch[0][branchConstants::dbBranchId];
		for($i=0; $i<$jsonDataSize; $i++){
			if(!(isset($jsonData[$i][tableConstants::dbBranchId]))){	$jsonData[$i][tableConstants::dbBranchId] = $branchId;
			}
		}
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(isset($jsonData[$i][tableConstants::dbTableStatusChangeTimestamp])){
				try{	$jsonData[$i][tableConstants::dbTableStatusChangeTimestamp] = Carbon::parse($jsonData[$i][tableConstants::dbTableStatusChangeTimestamp])
				->format('Y-m-d H:i:s');
				} catch(\Exception $e){
					$tablesResponse->setStatusCode(
							400, 
							tableConstants::carbonParseErr
							);
					
					return $tablesResponse;
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
				if($jsonData[$i][tableConstants::dbBranchId] == $branchId){
					try{
						DB::table(tableConstants::tablesTable)
						->insert($jsonData[$i]);
						
						$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has added table ' . $jsonData[$i][tableConstants::dbTableNumber];
						(new pushController())->sendUpdateCompanyCommand($notificationMessage);
					} catch(\PDOException $e){	$tablesResponse->setStatusCode(
							400, 
							tableConstants::dbAddCatchMsg
							);
					
					return $tablesResponse;
					}
				}
			}
		} else {
			$tablesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $tablesResponse;
		}
		
		return tableConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}
	public function updateTable(
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
		$mySqlWhere = array();
		$errorMsg = '';
		
		$tablesResponse = new Response();
		$tablesResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][tableConstants::dbTableStatusChangeTimestamp])){
			try{	$jsonData[0][tableConstants::dbTableStatusChangeTimestamp] = Carbon::parse($jsonData[0][tableConstants::dbTableStatusChangeTimestamp])
				->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$tablesResponse->setStatusCode(
						400, 
						tableConstants::carbonParseErr
						);
				
				return $tablesResponse;
			}
		}
		if(isset($jsonData[0][tableConstants::dbTableLastChangeTimestamp])){
			try{	$jsonData[0][tableConstants::dbTableLastChangeTimestamp] = Carbon::parse($jsonData[0][tableConstants::dbTableLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$tablesResponse->setStatusCode(
						400, 
						tableConstants::carbonParseErr
						);
				
				return $tablesResponse;
			}
		}
		
		$companyBranchTable = json_decode(
				$this->getCompanyBranchTable(
						$CompanyName, 
						$BranchName, 
						$TableNumber
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTable) == 0){
			$tablesResponse->setStatusCode(
					400, 
					tableConstants::inconsistencyValidationErr2
					);
			
			return $tablesResponse;
		}
		
		$tableId = $companyBranchTable[0][tableConstants::dbTableId];
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$tablesResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $tablesResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							tableConstants::dbTableId, 
							'=', 
							$tableId
					]
					);
			DB::table(tableConstants::tablesTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has updated table ' . $TableNumber;
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$tablesResponse->setStatusCode(
					400, 
					tableConstants::dbUpdateCatchMsg
					);
			
			return $tablesResponse;
		}
		
		return tableConstants::dbUpdateSuccessMsg;
	}

	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}
	public function deleteTable(
			$CompanyName, 
			$BranchName, 
			$TableNumber
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$tablesResponse = new Response();
		$tablesResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTable = json_decode(
				$this->getCompanyBranchTable(
						$CompanyName, 
						$BranchName, 
						$TableNumber
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTable) == 0){
			$tablesResponse->setStatusCode(
					400, 
					tableConstants::inconsistencyValidationErr2
					);
			
			return $tablesResponse;
		}
		$tableId = $companyBranchTable[0][tableConstants::dbTableId];
		
		try{
			array_push(
					$mySqlWhere, 
					[
							tableConstants::dbTableId, 
							'=', 
							$tableId
					]
					);
			DB::table(tableConstants::tablesTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage = $CompanyName . ' @ ' . $BranchName . ' has deleted table ' . $TableNumber;
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$tablesResponse->setStatusCode(
					400, 
					tableConstants::dbDeleteCatchMsg
					);
			
			return $tablesResponse;
		}
		
		return tableConstants::dbDeleteSuccessMsg;
	}
}