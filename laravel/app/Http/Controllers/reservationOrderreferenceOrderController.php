<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Uuid;

include_once "orderController.php";
include_once "orderreferenceController.php";
include_once "reservationController.php";

class reservationOrderreferenceOrderConstants{
	const keyOrderreference = 'orderreference';
	const keyOrder = 'order';
	const keyReservation = 'reservation';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW RESERVATION ORDERREFERENCE ORDER RECORDS';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING ORDERREFERENCE RESERVATION ORDERREFERENCE ORDER RECORDS';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING ORDERREFERENCE RESERVATION ORDERREFERENCE ORDER RECORDS';
	
	const inconsistencyValidationErr1  = 'KEYS CUSTOMER_USERNAME DO NOT MATCH';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
}

class reservationOrderreferenceOrderController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	//URL-->>/reservations-orderreferences-orders/
	public function addReservationOrderreferenceOrder(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$reservationController = new reservationController();
		$orderreferenceController = new orderreferenceController();
		$orderController = new orderController();
		
		$reservationsOrderreferencesOrdersResponse = new Response();
		$reservationsOrderreferencesOrdersResponse->setStatusCode(
				400, 
				null
				);
		
		//assign orderreference_code
		for($i=0; $i<$jsonDataSize; $i++){
			$orderreferenceCode = Uuid::generate(1)
			->string;
			$reservationOrderreferenceOrderRunner = $jsonData[$i];
			if(array_key_exists(
					reservationOrderreferenceOrderConstants::keyOrderreference, 
					$reservationOrderreferenceOrderRunner
					)
					){
				$orderreference = $reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyOrderreference];
				$orderreference[orderreferenceConstants::dbOrderreferenceCode] = $orderreferenceCode;
				
				$reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyOrderreference] = $orderreference;
			}
			if(array_key_exists(
					reservationOrderreferenceOrderConstants::keyOrder, 
					$reservationOrderreferenceOrderRunner
					)
					){
				$order = $reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyOrder];
				$orderSize = sizeof($order);
				for($j=0; $j<$orderSize; $j++){
					$orderRunner = $order[$j];
					$orderRunner[orderConstants::dbOrderreferenceCode] = $orderreferenceCode;
					
					$order[$j] = $orderRunner;
				}
				
				$reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyOrder] = $order;
			}
			
			$jsonData[$i] = $reservationOrderreferenceOrderRunner;
		}
		
		//assign reservation_code
		for($i=0; $i<$jsonDataSize; $i++){
			$reservationCode = Uuid::generate(1)
			->string;
			$reservationOrderreferenceOrderRunner = $jsonData[$i];
			if(array_key_exists(
					reservationOrderreferenceOrderConstants::keyReservation, 
					$reservationOrderreferenceOrderRunner
					)
					){
				$reservation = $reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyReservation];
				$reservation[reservationConstants::dbReservationCode] = $reservationCode;
				$reservation[reservationConstants::dbOrderreferenceCode] = $orderreferenceCode;
				
				$reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyReservation] = $reservation;
			}
			
			$jsonData[$i] = $reservationOrderreferenceOrderRunner;
		}
		
		for($i=0; $i<$jsonDataSize; $i++){
			$reservationOrderreferenceOrderRunner = $jsonData[$i];
			
			DB::beginTransaction();
			try{
				//db_transaction: add_orderreference
				if(array_key_exists(
						reservationOrderreferenceOrderConstants::keyOrderreference, 
						$reservationOrderreferenceOrderRunner
						)
						){
					$orderreference = $reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyOrderreference];
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
						$reservationsOrderreferencesOrdersResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $reservationsOrderreferencesOrdersResponse;
					}
				}
				
				//db_transaction: add_order
				if(array_key_exists(
						reservationOrderreferenceOrderConstants::keyOrder, 
						$reservationOrderreferenceOrderRunner
						)
						){
					$order = $reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyOrder];
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
							$reservationsOrderreferencesOrdersResponse->setStatusCode(
									400, 
									$errorMsg
									);
							
							return $reservationsOrderreferencesOrdersResponse;
						}
					}
				}
				
				//db_transaction: add_reservation
				if(array_key_exists(
						'reservation', 
						$reservationOrderreferenceOrderRunner
						)
						){
					$reservation = $reservationOrderreferenceOrderRunner[reservationOrderreferenceOrderConstants::keyReservation];
					if($reservationController->isDataValid(
							[$reservation], 
							$errorMsg, 
							"ADD"
							)
							){
						if(!($reservation[reservationConstants::dbCustomerUsername] == $orderreference[orderreferenceConstants::dbCustomerUsername])){
							$reservationsOrderreferencesOrdersResponse->setStatusCode(
									400, 
									reservationOrderreferenceOrderConstants::inconsistencyValidationErr1
									);
							
							return $reservationsOrderreferencesOrdersResponse;
						}
						
						try{	DB::table(reservationConstants::reservationsTable)
						->insert($reservation);
						} catch(\PDOException $e){	throw $e;
						}
					} else {
						$reservationsOrderreferencesOrdersResponse->setStatusCode(
								400, 
								$errorMsg
								);
						
						return $reservationsOrderreferencesOrdersResponse;
					}
				}
				
				DB::commit();
			} catch(\PDOException $e){
				DB::rollback();
				$reservationsOrderreferencesOrdersResponse->setStatusCode(
						400, 
						reservationOrderreferenceOrderConstants::dbAddCatchMsg
						);
				
				return $reservationsOrderreferencesOrdersResponse;
			}
		}
		
		$reservationsOrderreferencesOrdersResponse->setStatusCode(
				200, 
				reservationOrderreferenceOrderConstants::dbAddSuccessMsg
				);
		$reservationOrderreferenceCode = array(
				'reservationCode' => $reservationCode, 
				'orderreferenceCode' => $orderreferenceCode
		);
		return $reservationOrderreferenceCode;
	}
}