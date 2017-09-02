<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "companyController.php";
include_once "menuController.php";
include_once "pushController.php";

class menuitemConstants{
	const menuitemsTable = 'menuitems';
	
	const dbMenuitemId = 'menuitem_id';
	const dbMenuitemCode = 'menuitem_code';
	const dbMenuId = 'menu_id';
	const dbMenuitemName = 'menuitem_name';
	const dbMenuitemDesc = 'menuitem_desc';
	const dbMenuitemPrice = 'menuitem_price';
	const dbMenuitemFeatured = 'menuitem_featured';
	const dbMenuitemImage = 'menuitem_image';
	const dbMenuitemLastChangeTimestamp = 'menuitem_last_change_timestamp';
	
	const reqMenuitemId = 'MenuitemId';
	const reqMenuId = 'MenuId';
	const reqMenuitemCode = 'MenuitemCode';
	const reqMenuitemName = 'MenuitemName';
	const reqMenuitemDesc = 'MenuitemDesc';
	const reqMenuitemPrice = 'MenuitemPrice';
	const reqMenuitemFeatured = 'MenuitemFeatured';
	const reqMenuitemImage = 'MenuitemImage';
	const reqMenuitemLastChangeTimestamp = 'MenuitemLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW MENUITEM RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING MENUITEM RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING MENUITEM RECORD';
	
	const inconsistencyValidationErr1 = 'KEY-COMBINATION COMPANY_NAME & MENU_NAME IS NON-EXISTING';
	const inconsistencyValidationErr2 = 'KEY-COMBINATION COMPANY_NAME & MENU_NAME & MENUITEM_CODE IS NON-EXISTING';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class menuitemController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyMenuMenuitem($mySqlWhere){
		$companyMenuMenuitem = DB::table(menuitemConstants::menuitemsTable)
		->join(
				menuConstants::menusTable, 
				menuitemConstants::menuitemsTable . '.' . menuitemConstants::dbMenuId, 
				'=', 
				menuConstants::menusTable . '.' . menuConstants::dbMenuId
				)
				->join(
						companyConstants::companiesTable, 
						menuConstants::menusTable . '.' . menuConstants::dbCompanyName, 
						'=', 
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName
						)
						->where($mySqlWhere)
		->get();
		
		return $companyMenuMenuitem;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems
	public function getCompanyMenuMenuitems(
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
		
		$menuitemsResponse = new Response();
		try{
			$companyMenuMenuitems = $this->getJoinCompanyMenuMenuitem($mySqlWhere);
			if($companyMenuMenuitems->isEmpty()){	$menuitemsResponse->setStatusCode(
					200, 
					menuitemConstants::emptyResultSetErr
					);
			} else {	$menuitemsResponse->setContent(json_encode($companyMenuMenuitems));
			}
		} catch(\PDOException $e){	$menuitemsResponse->setStatusCode(
				400, 
				menuitemConstants::dbReadCatchMsg
				);
		}
		
		return $menuitemsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}
	public function getCompanyMenuMenuitem(
			$CompanyName, 
			$MenuName, 
			$MenuitemCode
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
		array_push(
				$mySqlWhere, 
				[
						menuitemConstants::menuitemsTable . '.' . menuitemConstants::dbMenuitemCode, 
						'=', 
						$MenuitemCode
				]
				);
		
		$menuitemsResponse = new Response();
		try{
			$companyMenuMenuitem = $this->getJoinCompanyMenuMenuitem($mySqlWhere);
			if($companyMenuMenuitem->isEmpty()){	$menuitemsResponse->setStatusCode(
					200, 
					menuitemConstants::emptyResultSetErr
					);
			} else {	$menuitemsResponse->setContent(json_encode($companyMenuMenuitem));
			}
		} catch(\PDOException $e){	$menuitemsResponse->setStatusCode(
				400, 
				menuitemConstants::dbReadCatchMsg
				);
		}
		
		return $menuitemsResponse;
	}
	
	//URL-->>/query/menuitems
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[menuitemConstants::reqMenuitemId])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemId, 
						'=', 
						$_GET[menuitemConstants::reqMenuitemId]
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemCode])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemCode, 
						'LIKE', 
						'%' . $_GET[menuitemConstants::reqMenuitemCode] . '%'
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuId])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuId, 
						'=', 
						$_GET[menuitemConstants::reqMenuId]
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemName])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemName, 
						'LIKE', 
						'%' . $_GET[menuitemConstants::reqMenuitemName] . '%'
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemDesc])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemDesc, 
						'LIKE', 
						'%' . $_GET[menuitemConstants::reqMenuitemDesc] . '%'
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemPrice])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemPrice, 
						'=',  
						$_GET[menuitemConstants::reqMenuitemPrice]
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemFeatured])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemFeatured, 
						'=',  
						$_GET[menuitemConstants::reqMenuitemFeatured]
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemImage])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemImage, 
						'LIKE', 
						'%' . $_GET[menuitemConstants::reqMenuitemImage] . '%'
				]
				);
		}
		if(isset($_GET[menuitemConstants::reqMenuitemLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						menuitemConstants::dbMenuitemLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[menuitemConstants::reqMenuitemLastChangeTimestamp] . '%'
				]
				);
		}
		
		$menuitemsResponse = new Response();
		try{
			$menuitems = DB::table(menuitemConstants::menuitemsTable)
			->where($mySqlWhere)
			->get();
			if($menuitems->isEmpty()){	$menuitemsResponse->setStatusCode(
					200, 
					menuitemConstants::emptyResultSetErr
					);
			} else {	$menuitemsResponse->setContent(json_encode($menuitems));
			}
		} catch(\PDOException $e){	$menuitemsResponse->setStatusCode(
				400, 
				menuitemConstants::dbReadCatchMsg
				);
		}
		
		return $menuitemsResponse;
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
							'*.' . menuitemConstants::dbMenuitemCode => 'required|string|max:10', 
							'*.' . menuitemConstants::dbMenuId => 'exists:menus,menu_id|numeric', 
							'*.' . menuitemConstants::dbMenuitemName => 'required|string|max:30', 
							'*.' . menuitemConstants::dbMenuitemDesc => 'required|string|max:500', 
							'*.' . menuitemConstants::dbMenuitemPrice => 'required|numeric', 
							'*.' . menuitemConstants::dbMenuitemFeatured => 'required|numeric', 
							'*.' . menuitemConstants::dbMenuitemImage => 'required|string|max:500'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . menuitemConstants::dbMenuitemCode => 'sometimes|string|max:10', 
							'*.' . menuitemConstants::dbMenuId => 'exists:menus,menu_id|sometimes|numeric', 
							'*.' . menuitemConstants::dbMenuitemName => 'sometimes|string|max:30', 
							'*.' . menuitemConstants::dbMenuitemDesc => 'sometimes|string|max:500', 
							'*.' . menuitemConstants::dbMenuitemPrice => 'sometimes|numeric', 
							'*.' . menuitemConstants::dbMenuitemFeatured => 'sometimes|numeric', 
							'*.' . menuitemConstants::dbMenuitemImage => 'sometimes|string|max:500', 
							'*.' . menuitemConstants::dbMenuitemLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/
	public function addMenuitem(
			Request $jsonRequest, 
			$CompanyName, 
			$MenuName
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$menuitemsResponse = new Response();
		$menuitemsResponse->setStatusCode(
				400, 
				null
				);
		$companyMenu = json_decode(
				(new menuController())->getCompanyMenu(
						$CompanyName, 
						$MenuName
						)
				->getContent(), 
				true
				);
		if(sizeof($companyMenu) == 0){
			$menuitemsResponse->setStatusCode(
					400, 
					menuitemConstants::inconsistencyValidationErr1
					);
			
			return $menuitemsResponse;
		}
		
		$menuId = $companyMenu[0][menuConstants::dbMenuId];
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(!(isset($jsonData[$i][menuitemConstants::dbMenuId]))){	$jsonData[$i][menuitemConstants::dbMenuId] = $menuId;
			}
		}
		
		if($this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"ADD"
				)
				){
			for($i=0; $i<$jsonDataSize; $i++){
				if($jsonData[$i][menuitemConstants::dbMenuId] == $menuId){
					try{
						DB::table(menuitemConstants::menuitemsTable)
						->insert($jsonData[$i]);
						
						$notificationMessage = $CompanyName . ' has added menuitem ' . $jsonData[$i][menuitemConstants::dbMenuitemName];
						(new pushController())->sendUpdateCompanyCommand($notificationMessage);
					} catch(\PDOException $e){
						$menuitemsResponse->setStatusCode(
								400, 
								menuitemConstants::dbAddCatchMsg
								);
						
						return $menuitemsResponse;
					}
				}
			}
		} else {
			$menuitemsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $menuitemsResponse;
		}
		
		return menuitemConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}
	public function updateMenuitem(
			Request $jsonRequest, 
			$CompanyName, 
			$MenuName, 
			$MenuitemCode
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$menuitemsResponse = new Response();
		$menuitemsResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][menuitemConstants::dbMenuitemLastChangeTimestamp])){
			try{	$jsonData[0][menuitemConstants::dbMenuitemLastChangeTimestamp] = Carbon::parse($jsonData[0][menuitemConstants::dbMenuitemLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception  $e){
				$menuitemsResponse->setStatusCode(
						400, 
						menuitemConstants::carbonParseErr
						);
				
				return $menuitemsResponse;
			}
		}
		
		$companyMenuMenuitem = json_decode(
				$this->getCompanyMenuMenuitem(
						$CompanyName, 
						$MenuName, 
						$MenuitemCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyMenuMenuitem) == 0){
			$menuitemsResponse->setStatusCode(
					400, 
					menuitemConstants::inconsistencyValidationErr2
					);
			
			return $menuitemsResponse;
		}
		
		$menuitemId = $companyMenuMenuitem[0][menuitemConstants::dbMenuitemId];
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$menuitemsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $menuitemsResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							menuitemConstants::dbMenuitemId, 
							'=', 
							$menuitemId
					]
					);
			DB::table(menuitemConstants::menuitemsTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
			
			$notificationMessage = $CompanyName . ' has udpated menuitem ' . $companyMenuMenuitem[0][menuitemConstants::dbMenuitemName];
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$menuitemsResponse->setStatusCode(
					400, 
					menuitemConstants::dbUpdateCatchMsg
					);
			
			return $menuitemsResponse;
		}
		
		return menuitemConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}
	public function deleteMenuitem(
			$CompanyName, 
			$MenuName, 
			$MenuitemCode
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$menuitemsResponse = new Response();
		$menuitemsResponse->setStatusCode(
				400, 
				null
				);
		$companyMenuMenuitem = json_decode(
				$this->getCompanyMenuMenuitem(
						$CompanyName, 
						$MenuName, 
						$MenuitemCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyMenuMenuitem) == 0){
			$menuitemsResponse->setStatusCode(
					400, 
					menuitemConstants::inconsistencyValidationErr2
					);
			
			return $menuitemsResponse;
		}
		
		$menuitemId = $companyMenuMenuitem[0][menuitemConstants::dbMenuitemId];
		
		try{
			array_push(
					$mySqlWhere, 
					[
							menuitemConstants::dbMenuitemId, 
							'=', 
							$menuitemId
					]
					);
			DB::table(menuitemConstants::menuitemsTable)
			->where($mySqlWhere)
			->delete();
			
			$notificationMessage = $CompanyName . ' has deleted menuitem ' . $companyMenuMenuitem[0][menuitemConstants::dbMenuitemName];
			(new pushController())->sendUpdateCompanyCommand($notificationMessage);
		} catch(\PDOException $e){
			$menuitemsResponse->setStatusCode(
					400, 
					menuitemConstants::dbDeleteCatchMsg
					);
			
			return $menuitemsResponse;
		}
		
		return menuitemConstants::dbDeleteSuccessMsg;
	}
}