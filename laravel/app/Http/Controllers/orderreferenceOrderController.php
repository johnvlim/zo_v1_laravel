<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Uuid;

include_once "orderController.php";
include_once "orderreferenceController.php";

class orderreferenceOrderConstants{
	const keyOrderreference = 'orderreference';
	const keyOrder = 'order';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW ORDERREFERENCE ORDER RECORDS';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING ORDERREFERENCE ORDER RECORDS';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING ORDERREFERENCE ORDER RECORDS';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
}

class orderreferenceOrderController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	//URL-->>/orderreferences-orders/
	public function addOrderreferenceOrder(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$orderreferenceController = new orderreferenceController();
		$orderController = new orderController();
		
		$orderreferencesOrdersResponse = new Response();
		$orderreferencesOrdersResponse->setStatusCode(
				400, 
				null
				);
		
		//assign orderreference_code
		for($i=0; $i<$jsonDataSize; $i++){
			$orderreferenceCode = Uuid::generate(1)
			->string;
			$orderreferenceOrderRunner = $jsonData[$i];
			if(array_key_exists(
					orderreferenceOrderConstants::keyOrderreference, 
					$orderreferenceOrderRunner
					)
					){
				$orderreference = $orderreferenceOrderRunner[orderreferenceOrderConstants::keyOrderreference];
				$orderreference[orderreferenceConstants::dbOrderreferenceCode] = $orderreferenceCode;
				
				$orderreferenceOrderRunner[orderreferenceOrderConstants::keyOrderreference] = $orderreference;
			}
			if(array_key_exists(
					orderreferenceOrderConstants::keyOrder, 
					$orderreferenceOrderRunner
					)
					){
				$order = $orderreferenceOrderRunner[orderreferenceOrderConstants::keyOrder];
				$orderSize = sizeof($order);
				for($j=0; $j<$orderSize; $j++){
					$orderRunner = $order[$j];
					$orderRunner[orderConstants::dbOrderreferenceCode] = $orderreferenceCode;
					
					$order[$j] = $orderRunner;
				}
				
				$orderreferenceOrderRunner[orderreferenceOrderConstants::keyOrder] = $order;
			}
			
			$jsonData[$i] = $orderreferenceOrderRunner;
		}
		
		for($i=0; $i<$jsonDataSize; $i++){
			$orderreferenceOrderRunner = $jsonData[$i];
			
			DB::beginTransaction();
			try{
				//db_transaction: add_orderreference
				if(array_key_exists(
						orderreferenceOrderConstants::keyOrderreference, 
						$orderreferenceOrderRunner
						)
						){
					$orderreference = $orderreferenceOrderRunner[orderreferenceOrderConstants::keyOrderreference];
					if($orderreferenceController->isDataValid(
							[$orderreference], 
							$errorMsg, 
							"ADD"
							)
							){
						try{	DB::table(orderreferenceConstants::orderreferencesTable)
						->insert($orderreference);
						} catch(\PDOException $e){	throw $e;
						}
					} else {
						$orderreferencesOrdersResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $orderreferencesOrdersResponse;
					}
				}
				
				//db_transaction: add_order
				if(array_key_exists(
						orderreferenceOrderConstants::keyOrder, 
						$orderreferenceOrderRunner
						)
						){
					$order = $orderreferenceOrderRunner[orderreferenceOrderConstants::keyOrder];
					$orderSize = sizeof($order);
					for($j=0; $j<$orderSize; $j++){
						$orderRunner = $order[$j];
						if($orderController->isDataValid(
								[$orderRunner], 
								$errorMsg, 
								"ADD"
								)
								){
							try{	DB::table(orderConstants::ordersTable)
							->insert($orderRunner);
							} catch(\PDOException $e){	throw $e;
							}
						} else {
							$orderreferencesOrdersResponse->setStatusCode(
									400, 
									$errorMsg
									);
							
							return $orderreferencesOrdersResponse;
						}
					}
				}
				
				DB::commit();
			} catch(\PDOException $e){
				DB::rollback();
				$orderreferencesOrdersResponse->setStatusCode(
						400, 
						orderreferenceOrderConstants::dbAddCatchMsg
						);
				
				return $orderreferencesOrdersResponse;
			}
		}
		
		return orderreferenceOrderConstants::dbAddSuccessMsg;
	}
}