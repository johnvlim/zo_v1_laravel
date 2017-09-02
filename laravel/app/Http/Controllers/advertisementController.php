<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "companyController.php";

class advertisementConstants{
	const advertisementsTable = 'advertisements';
	
	const dbAdvertisementId = 'advertisement_id';
	const dbCompanyName = 'company_name';
	const dbAdvertisementTitle = 'advertisement_title';
	const dbAdvertisementContent = 'advertisement_content';
	const dbAdvertisementPrice = 'advertisement_price';
	const dbAdvertisementImage = 'advertisement_image';
	const dbAdvertisementUrl = 'advertisement_url';
	const dbAdvertisementLastChangeTimestamp = 'advertisement_last_change_timestamp';
	
	const reqAdvertisementId = 'AdvertisementId';
	const reqCompanyName = 'CompanyName';
	const reqAdvertisementTitle = 'AdvertisementTitle';
	const reqAdvertisementContent = 'AdvertisementContent';
	const reqAdvertisementPrice = 'AdvertisementPrice';
	const reqAdvertisementImage = 'AdvertisementImage';
	const reqAdvertisementUrl = 'AdvertisementUrl';
	const reqAdvertisementLastChangeTimestamp = 'AdvertisementLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW ADVERTISEMENT RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING ADVERTISEMENT RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING ADVERTISEMENT RECORD';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class advertisementController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyAdvertisement($mySqlWhere){
		$companyAdvertisement = DB::table(advertisementConstants::advertisementsTable)
		->join(
				companyConstants::companiesTable, 
				advertisementConstants::advertisementsTable . '.' . advertisementConstants::dbCompanyName, 
				'=', 
				companyConstants::companiesTable . '.' . companyConstants::dbCompanyName
				)
				->where($mySqlWhere)
		->get();
		
		return $companyAdvertisement;
	}
	
	//URL-->>/advertisements
	public function getAdvertisements(){
		$mySqlWhere = array();
		
		$advertisementsResponse = new Response();
		try{
			$advertisements = $this->getJoinCompanyAdvertisement($mySqlWhere);
			if($advertisements->isEmpty()){	$advertisementsResponse->setStatusCode(
					200, 
					advertisementConstants::emptyResultSetErr
					);
			} else {	$advertisementsResponse->setContent(json_encode($advertisements));
			}
		} catch(\PDOException $e){	$advertisementsResponse->setStatusCode(
				400, 
				advertisementConstants::dbReadCatchMsg
				);
		}
		
		return $advertisementsResponse;
	}
	
	//URL-->>/advertisements/{AdvertisementId}
	public function getAdvertisement($AdvertisementId){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						advertisementConstants::advertisementsTable . '.' . advertisementConstants::dbAdvertisementId, 
						'=', 
						$AdvertisementId
				]
				);
		
		$advertisementsResponse = new Response();
		try{
			$advertisement = $this->getJoinCompanyAdvertisement($mySqlWhere);
			if($advertisement->isEmpty()){	$advertisementsResponse->setStatusCode(
					200, 
					advertisementConstants::emptyResultSetErr
					);
			} else {	$advertisementsResponse->setContent(json_encode($advertisement));
			}
		} catch(\PDOException $e){	$advertisementsResponse->setStatusCode(
				400, 
				advertisementConstants::dbReadCatchMsg
				);
		}
		
		return $advertisementsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/advertisements
	public function getCompanyAdvertisements($CompanyName){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						companyConstants::companiesTable . '.' . companyConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		
		$advertisementsResponse = new Response();
		try{
			$companyAdvertisements = $this->getJoinCompanyAdvertisement($mySqlWhere);
			if($companyAdvertisements->isEmpty()){	$advertisementsResponse->setStatusCode(
					200, 
					advertisementConstants::emptyResultSetErr
					);
			} else {	$advertisementsResponse->setContent(json_encode($companyAdvertisements));
			}
		} catch(\PDOException $e){	$advertisementsResponse->setStatusCode(
				400, 
				advertisementConstants::dbReadCatchMsg
				);
		}
		
		return $advertisementsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/advertisements/{AdvertisementId}
	public function getCompanyAdvertisement(
			$CompanyName, 
			$AdvertisementId
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
						advertisementConstants::advertisementsTable . '.' . advertisementConstants::dbAdvertisementId, 
						'=', 
						$AdvertisementId
				]
				);
		
		$advertisementsResponse = new Response();
		try{
			$companyAdvertisement = $this->getJoinCompanyAdvertisement($mySqlWhere);
			if($companyAdvertisement->isEmpty()){	$advertisementsResponse->setStatusCode(
					200, 
					advertisementConstants::emptyResultSetErr
					);
			} else {	$advertisementsResponse->setContent(json_encode($companyAdvertisement));
			}
		} catch(\PDOException $e){	$advertisementsResponse->setStatusCode(
				400, 
				advertisementConstants::dbReadCatchMsg
				);
		}
		
		return $advertisementsResponse;
	}
	
	//URL-->>/query/advertisements
	public function getByQuery(){
		$mySqlWhere = array();
		if(isset($_GET[advertisementConstants::reqAdvertisementId])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementId, 
						'=', 
						$_GET[advertisementConstants::reqAdvertisementId]
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqCompanyName])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbCompanyName, 
						'LIKE', 
						'%'. $_GET[advertisementConstants::reqCompanyName] . '%'
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqAdvertisementTitle])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementTitle, 
						'LIKE', 
						'%' . $_GET[advertisementConstants::reqAdvertisementTitle] . '%'
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqAdvertisementContent])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementContent, 
						'LIKE', 
						'%' . $_GET[advertisementConstants::reqAdvertisementContent] . '%'
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqAdvertisementPrice])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementPrice, 
						'=', 
						$_GET[advertisementConstants::reqAdvertisementPrice]
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqAdvertisementImage])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementImage, 
						'LIKE', 
						'%' . $_GET[advertisementConstants::reqAdvertisementImage] . '%'
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqAdvertisementUrl])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementUrl, 
						'LIKE', 
						'%' . $_GET[advertisementConstants::reqAdvertisementUrl] . '%'
				]
				);
		}
		if(isset($_GET[advertisementConstants::reqAdvertisementLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						advertisementConstants::dbAdvertisementLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[advertisementConstants::reqAdvertisementLastChangeTimestamp] . '%'
				]
				);
		}
		
		$advertisementsResponse = new Response();
		try{
			$advertisements = DB::table(advertisementConstants::advertisementsTable)
			->where($mySqlWhere)
			->get();
			if($advertisements->isEmpty()){	$advertisementsResponse->setStatusCode(
					200, 
					advertisementConstants::emptyResultSetErr
					);
			} else {	$advertisementsResponse->setContent(json_encode($advertisements));
			}
		} catch(\PDOException $e){	$advertisementsResponse->setStatusCode(
				400, 
				advertisementConstants::dbReadCatchMsg
				);
		}
		
		return $advertisementsResponse;
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
							'*.' . advertisementConstants::dbCompanyName => 'exists:companies,company_name|required|string|max:30', 
							'*.' . advertisementConstants::dbAdvertisementTitle => 'required|string|max:100', 
							'*.' . advertisementConstants::dbAdvertisementContent => 'required|string|max:1000', 
							'*.' . advertisementConstants::dbAdvertisementPrice => 'required|numeric', 
							'*.' . advertisementConstants::dbAdvertisementImage => 'required|string|max:500', 
							'*.' . advertisementConstants::dbAdvertisementUrl => 'required|string|max:500'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . advertisementConstants::dbCompanyName => 'exists:companies,company_name|sometimes|string|max:30', 
							'*.' . advertisementConstants::dbAdvertisementTitle => 'sometimes|string|max:100', 
							'*.' . advertisementConstants::dbAdvertisementContent => 'sometimes|string|max:1000', 
							'*.' . advertisementConstants::dbAdvertisementPrice => 'sometimes|numeric', 
							'*.' . advertisementConstants::dbAdvertisementImage => 'sometimes|string|max:500', 
							'*.' . advertisementConstants::dbAdvertisementUrl => 'sometimes|string|max:500', 
							'*.' . advertisementConstants::dbAdvertisementLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/advertisements
	public function addAdvertisement(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$advertisementsResponse = new Response();
		$advertisementsResponse->setStatusCode(
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
				try{		DB::table(advertisementConstants::advertisementsTable)
				->insert($jsonData[$i]);
				} catch(\PDOException $e){
					$advertisementsResponse->setStatusCode(
							400, 
							advertisementConstants::dbAddCatchMsg
							);
					
					return $advertisementsResponse;
				}
			}
		} else {
			$advertisementsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $advertisementsResponse;
		}
		
		return advertisementConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/advertisements/{AdvertisementId}
	public function updateAdvertisement(
			Request $jsonRequest, 
			$AdvertisementId
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$advertisementsResponse = new Response();
		$advertisementsResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][advertisementConstants::dbAdvertisementLastChangeTimestamp])){
			try{	$jsonData[0][advertisementConstants::dbAdvertisementLastChangeTimestamp] = Carbon::parse($jsonData[0][advertisementConstants::dbAdvertisementLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$advertisementsResponse->setStatusCode(
						400, 
						advertisementConstants::carbonParseErr
						);
				
				return $advertisementsResponse;
			}
		}
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$advertisementsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $advertisementsResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							advertisementConstants::dbAdvertisementId, 
							'=', 
							$AdvertisementId
					]
					);
			DB::table(advertisementConstants::advertisementsTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
		} catch(\PDOException $e){
			$advertisementsResponse->setStatusCode(
					400, 
					advertisementConstants::dbUpdateCatchMsg
					);
			
			return $advertisementsResponse;
		}
		
		return advertisementConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/advertisements/{AdvertisementId}
	public function deleteAdvertisement($AdvertisementId){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$advertisementsResponse = new Response();
		$advertisementsResponse->setStatusCode(
				400, 
				null
				);
		try{
			array_push(
					$mySqlWhere , 
					[
							advertisementConstants::dbAdvertisementId, 
							'=', 
							$AdvertisementId
					]
					);
			DB::table(advertisementConstants::advertisementsTable)
			->where($mySqlWhere)
			->delete();
		} catch(\PDOException $e){
			$advertisementsResponse->setStatusCode(
					400, 
					advertisementConstants::dbDeleteCatchMsg
					);
			
			return $advertisementsResponse;
		}
		
		return advertisementConstants::dbDeleteSuccessMsg;
	}
}