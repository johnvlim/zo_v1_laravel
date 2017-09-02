<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "companyController.php";
include_once "pushController.php";

class menuConstants{
	const menusTable = 'menus';
	
	const dbMenuId = 'menu_id';
	const dbCompanyName = 'company_name';
	const dbMenuName = 'menu_name';
	const dbMenuDesc = 'menu_desc';
	const dbMenuImage = 'menu_image';
	const dbMenuLastChangeTimestamp = 'menu_last_change_timestamp';
	
	const reqMenuId = 'MenuId';
	const reqCompanyName = 'CompanyName';
	const reqMenuName = 'MenuName';
	const reqMenuDesc = 'MenuDesc';
	const reqMenuImage = 'MenuImage';
	const reqMenuLastChangeTimestamp = 'MenuLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW MENU RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING MENU RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING MENU RECORD';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class menuController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyMenu($mySqlWhere){
		$companyMenu = DB::table(menuConstants::menusTable)
		->join(
				companyConstants::companiesTable, 
				menuConstants::menusTable . '.' . menuConstants::dbCompanyName, 
				'=', 
				companyConstants::companiesTable . '.' . companyConstants::dbCompanyName
				)
				->where($mySqlWhere)
		->get();
		
		return $companyMenu;
	}
	
	//URL-->>/companies/{CompanyName}/menus
	public function getCompanyMenus($CompanyName){
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		
		$menusResponse = new Response();
		try{
			$companyMenus = $this->getJoinCompanyMenu($mySqlWhere); 
			if($companyMenus->isEmpty()){	$menusResponse->setStatusCode(
					200, 
					menuConstants::emptyResultSetErr
					);
			} else {	$menusResponse->setContent(json_encode($companyMenus));
			}
		} catch(\PDOException $e){	$menusResponse->setStatusCode(
				400, 
				menuConstants::dbReadCatchMsg
				);
		}
		
		return $menusResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}
	public function getCompanyMenu(
			$CompanyName, 
			$MenuName
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
						menuConstants::menusTable . '.' . menuConstants::dbMenuName, 
						'=', 
						$MenuName
				]
				);
		
		$menusResponse = new Response();
		try{
			$companyMenu = $this->getJoinCompanyMenu($mySqlWhere);
			if($companyMenu->isEmpty()){	$menusResponse->setStatusCode(
					200, 
					menuConstants::emptyResultSetErr
					);
			} else {	$menusResponse->setContent(json_encode($companyMenu));
			}
		} catch(\PDOException $e){	$menusResponse->setStatusCode(
				400, 
				menuConstants::dbReadCatchMsg
				);
		}
		
		return $menusResponse;
	}
	
	//URL-->>/query/menus
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[menuConstants::reqMenuId])){	array_push(
				$mySqlWhere, 
				[
						menuConstants::dbMenuId, 
						'=', 
						$_GET[menuConstants::reqMenuId]
				]
				);
		}
		if(isset($_GET[menuConstants::reqMenuName])){	array_push(
				$mySqlWhere, 
				[
						menuConstants::dbMenuName, 
						'LIKE', 
						'%' . $_GET[menuConstants::reqMenuName] . '%'
				]
				);
		}
		if(isset($_GET[menuConstants::reqCompanyName])){	array_push(
				$mySqlWhere, 
				[
						menuConstants::dbCompanyName, 
						'LIKE', 
						'%' . $_GET[menuConstants::reqCompanyName] . '%'
				]
				);
		}
		if(isset($_GET[menuConstants::reqMenuDesc])){	array_push(
				$mySqlWhere, 
				[
						menuConstants::dbMenuDesc, 
						'LIKE', 
						'%' . $_GET[menuConstants::reqMenuDesc] . '%'
				]
				);
		}
		if(isset($_GET[menuConstants::reqMenuImage])){	array_push(
				$mySqlWhere, 
				[
						menuConstants::dbMenuImage, 
						'LIKE', 
						'%' . $_GET[menuConstants::reqMenuImage] . '%'
				]
				);
		}
		if(isset($_GET[menuConstants::reqMenuLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						menuConstants::dbMenuLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[menuConstants::reqMenuLastChangeTimestamp] . '%'
				]
				);
		}
		
		$menusResponse = new Response();
		try{
			$companyMenus = DB::table(menuConstants::menusTable)
			->where($mySqlWhere)
			->get();
			if($companyMenus->isEmpty()){	$menusResponse->setStatusCode(
					200, 
					menuConstants::emptyResultSetErr
					);
			} else {	$menusResponse->setContent(json_encode($companyMenus));
			}
		} catch(\PDOException $e){	$menusResponse->setStatusCode(
				400, 
				menuConstants::dbReadCatchMsg
				);
		}
		
		return $menusResponse;
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
							'*.' . menuConstants::dbMenuName => 'required|string|max:30', 
							'*.' . menuConstants::dbCompanyName => 'exists:companies,company_name|required|string|max:30', 
							'*.' . menuConstants::dbMenuDesc => 'required|string|max:500', 
							'*.' . menuConstants::dbMenuImage => 'required|string|max:1000'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . menuConstants::dbMenuName => 'sometimes|string|max:30', 
							'*.' . menuConstants::dbCompanyName => 'exists:companies,company_name|sometimes|string|max:30', 
							'*.' . menuConstants::dbMenuDesc => 'sometimes|string|max:500', 
							'*.' . menuConstants::dbMenuImage => 'sometimes|string|max:1000', 
							'*.' . menuConstants::dbMenuLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/menus
	public function addMenu(
			Request $jsonRequest, 
			$CompanyName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$menusResponse = new Response();
		$menusResponse->setStatusCode(
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
				if($jsonData[$i][menuConstants::dbCompanyName] == $CompanyName){
					try{
						DB::table(menuConstants::menusTable)
						->insert($jsonData[$i]);
						
						$notificationMessage = $CompanyName . ' has added menu ' . $jsonData[$i][menuConstants::dbMenuName];
						(new pushController())->sendUpdateCompanyCommand($notificationMessage);
					} catch(\PDOException $e){	$menusResponse->setStatusCode(
							400, 
							menuConstants::dbAddCatchMsg
							);
					
					return $menusResponse;
					}
				}
			}
		} else {
			$menusResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $menusResponse;
		}
		
		return menuConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}
	public function updateMenu(
			Request $jsonRequest, 
			$CompanyName, 
			$MenuName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$menusResponse = new Response();
		$menusResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][menuConstants::dbMenuLastChangeTimestamp])){
			try{	$jsonData[0][menuConstants::dbMenuLastChangeTimestamp] = Carbon::parse($jsonData[0][menuConstants::dbMenuLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$menusResponse->setStatusCode(
						400, 
						menuConstants::carbonParseErr
						);
				
				return $menusResponse;
			}
		}
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$menusResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $menusResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							menuConstants::dbCompanyName, 
							'=', 
							$CompanyName
					]
					);
			array_push(
					$mySqlWhere, 
					[
							menuConstants::dbMenuName, 
							'=', 
							$MenuName
					]
					);
			DB::table(menuConstants::menusTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $CompanyName . ' has updated menu ' . $MenuName;
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$menusResponse->setStatusCode(
					400, 
					menuConstants::dbUpdateCatchMsg
					);
			
			return $menusResponse;
		}
		
		return menuConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}
	public function deleteMenu(
			$CompanyName, 
			$MenuName
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$menusResponse = new Response();
		$menusResponse->setStatusCode(
				400, 
				null
				);
		try{
			array_push(
					$mySqlWhere, 
					[
							menuConstants::dbCompanyName, 
							'=', 
							$CompanyName
					]
					);
			array_push(
					$mySqlWhere, 
					[
							menuConstants::dbMenuName, 
							'=', 
							$MenuName
					]
					);
			DB::table(menuConstants::menusTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage = $CompanyName . ' has deleted menu ' . $MenuName;
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$menusResponse->setStatusCode(
					400, 
					menuConstants::dbDeleteCatchMsg
					);
			
			return $menusResponse;
		}
		
		return menuConstants::dbDeleteSuccessMsg;
	}
}