<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

include_once "branchController.php";
include_once "companyController.php";
include_once "customerController.php";
include_once "orderreferenceController.php";
include_once "tableController.php";

class reservationConstants{
	const reservationsTable = 'reservations';
	
	const dbReservationId = 'reservation_id';
	const dbReservationCode = 'reservation_code';
	const dbCustomerUsername = 'customer_username';
	const dbOrderreferenceCode = 'orderreference_code';
	const dbReservationDinersCount = 'reservation_diners_count';
	const dbReservationEta = 'reservation_eta';
	const dbReservationPaymentMode = 'reservation_payment_mode';
	const dbReservationServiceTime = 'reservation_service_time';
	const dbReservationStatus = 'reservation_status';
	const dbReservationStatusChangeTimestamp = 'reservation_status_change_timestamp';
	const dbReservationLastChangeTimestamp = 'reservation_last_change_timestamp';
	
	const reqReservationId = 'ReservationId';
	const reqReservationCode = 'ReservationCode';
	const reqCustomerUsername = 'CustomerUsername';
	const reqOrderreferenceCode = 'OrderreferenceCode';
	const reqReservationDinersCount = 'ReservationDinersCount';
	const reqReservationEta = 'ReservationEta';
	const reqReservationPaymentMode = 'ReservationPaymentMode';
	const reqReservationServiceTime = 'ReservationServiceTime';
	const reqReservationStatus = 'ReservationStatus';
	const reqReservationStatusChangeTimestamp = 'ReservationStatusChangeTimestamp';
	const reqReservationLastChangeTimestamp = 'ReservationLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW RESERVATION RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING RESERVATION RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING RESERVATION RECORD';
	
	const inconsistencyValidationErr1 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER & ORDERREFERENCE_CODE IS NON-EXISTING';
	const inconsistencyValidationErr2 = 'KEY-COMBINATION COMPANY_NAME & BRANCH_NAME & TABLE_NUMBER & ORDERREFERENCE_CODE & RESERVATION_CODE IS NON-EXISTING';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class reservationController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	public function getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere){
		$companyBranchTableOrderreferenceReservation = DB::table(reservationConstants::reservationsTable)
		->join(
				orderreferenceConstants::orderreferencesTable, 
				reservationConstants::reservationsTable . '.' . reservationConstants::dbOrderreferenceCode, 
				'=', 
				orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode
				)
				->join(
						tableConstants::tablesTable, 
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbTableId, 
						'=', 
						tableConstants::tablesTable . '.' . tableConstants::dbTableId
						)
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
		
		return $companyBranchTableOrderreferenceReservation;
	}
	
	public function getJoinCustomerCompanyBranchTableOrderreferenceReservation($mySqlWhere){
		$customerCompanyBranchTableOrderreferenceReservation = DB::table(reservationConstants::reservationsTable)
		->join(
				customerConstants::customersTable, 
				reservationConstants::reservationsTable . '.' . reservationConstants::dbCustomerUsername, 
				'=', 
				customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername
				)
				->join(
						orderreferenceConstants::orderreferencesTable, 
						reservationConstants::reservationsTable . '.' . reservationConstants::dbOrderreferenceCode, 
						'=', 
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode
						)
						->join(
								tableConstants::tablesTable, 
								orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbTableId, 
								'=', 
								tableConstants::tablesTable . '.' . tableConstants::dbTableId
								)
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
		
		return $customerCompanyBranchTableOrderreferenceReservation;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/reservations
	public function getCompanyBranchReservations(
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
		
		$reservationsResponse = new  Response();
		try{
			$companyBranchReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchReservations));
			}
		} catch(\PDOException  $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/reservations/{ReservationCode}
	public function getCompanyBranchReservation(
			$CompanyName, 
			$BranchName, 
			$ReservationCode
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
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationCode, 
						'=', 
						$ReservationCode
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchReservation = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchReservation->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchReservation));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/reservations/status/{ReservationStatus}
	public function getCompanyBranchReservationsReservationStatus(
			$CompanyName, 
			$BranchName, 
			$ReservationStatus
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
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/reservations/status_not/{ReservationStatus}
	public function getCompanyBranchReservationsNotReservationStatus(
			$CompanyName, 
			$BranchName, 
			$ReservationStatus
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
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'!=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations
	public function getCompanyBranchTableReservations(
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
				[
						branchConstants::branchesTable . '.' . branchConstants::dbBranchName, 
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
		
		$reservationsResponse = new Response();
		try{
			$companyBranchTableReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchTableReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchTableReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations/{ReservationCode}
	public function getCompanyBranchTableReservation(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$ReservationCode
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationCode, 
						'=', 
						$ReservationCode
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchTableReservation = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchTableReservation->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchTableReservation));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations/status/{ReservationStatus}
	public function getCompanyBranchTableReservationsReservationStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$ReservationStatus
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchTableReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchTableReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchTableReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/reservations/status_not/{ReservationStatus}
	public function getCompanyBranchTableReservationsNotReservationStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$ReservationStatus
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'!=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchTableReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchTableReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchTableReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations
	public function getCompanyBranchTableOrderreferenceReservations(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$companyBranchTableOrderreferenceReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($companyBranchTableOrderreferenceReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($companyBranchTableOrderreferenceReservations));
			}
		} catch(\PDOException  $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/{ReservationCode}
	public function getCompanyBranchTableOrderreferenceReservation(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$ReservationCode
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationCode, 
						'=', 
						$ReservationCode
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$customerCompanyBranchTableOrderreferenceReservation = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerCompanyBranchTableOrderreferenceReservation->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerCompanyBranchTableOrderreferenceReservation));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/status/{ReservationStatus}
	public function getCompanyBranchTableOrderreferenceReservationsReservationStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$ReservationStatus
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$customerCompanyBranchTableOrderreferenceReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerCompanyBranchTableOrderreferenceReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerCompanyBranchTableOrderreferenceReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/status_not/{ReservationStatus}
	public function getCompanyBranchTableOrderreferenceReservationsNotReservationStatus(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$ReservationStatus
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
		array_push(
				$mySqlWhere, 
				[
						tableConstants::tablesTable . '.' . tableConstants::dbTableNumber, 
						'=', 
						$TableNumber
				]
				);
		array_push(
				$mySqlWhere, 
				[
						orderreferenceConstants::orderreferencesTable . '.' . orderreferenceConstants::dbOrderreferenceCode, 
						'=', 
						$OrderreferenceCode
				]
				);
		array_push(
				$mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'!=', 
						$ReservationStatus
				]
				);
				
		$reservationsResponse = new Response();
		try{
			$customerCompanyBranchTableOrderreferenceReservations = $this->getJoinCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerCompanyBranchTableOrderreferenceReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerCompanyBranchTableOrderreferenceReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/reservations
	public function  getCustomerReservations($CustomerUsername){
		$mySqlWhere = array();
		array_push($mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
				
		$reservationsResponse = new Response();
		try{
			$customerReservations = $this->getJoinCustomerCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/reservations/{ReservationCode}
	public function getCustomerReservation(
			$CustomerUsername, 
			$ReservationCode
			){
		$mySqlWhere = array();
		array_push($mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		array_push($mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationCode, 
						'=', 
						$ReservationCode
				]
				);
				
		$reservationsResponse = new Response();
		try{
			$customerReservation = $this->getJoinCustomerCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerReservation->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerReservation));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/reservations/status/{ReservationStatus}
	public function getCustomerReservationsReservationStatus(
			$CustomerUsername, 
			$ReservationStatus
			){
		$mySqlWhere = array();
		array_push($mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		array_push($mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$customerReservations = $this->getJoinCustomerCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/customers/{CustomerUsername}/reservations/status_not/{ReservationStatus}
	public function getCustomerReservationsNotReservationStatus(
			$CustomerUsername, 
			$ReservationStatus
			){
		$mySqlWhere = array();
		array_push($mySqlWhere, 
				[
						customerConstants::customersTable . '.' . customerConstants::dbCustomerUsername, 
						'=', 
						$CustomerUsername
				]
				);
		array_push($mySqlWhere, 
				[
						reservationConstants::reservationsTable . '.' . reservationConstants::dbReservationStatus, 
						'!=', 
						$ReservationStatus
				]
				);
		
		$reservationsResponse = new Response();
		try{
			$customerReservations = $this->getJoinCustomerCompanyBranchTableOrderreferenceReservation($mySqlWhere);
			if($customerReservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($customerReservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
	}
	
	//URL-->>/query/reservations
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[reservationConstants::reqReservationId])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationId, 
						'=', 
						$_GET[reservationConstants::reqReservationId]
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationCode])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationCode, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationCode] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqCustomerUsername])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbCustomerUsername, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqCustomerUsername] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqOrderreferenceCode])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbOrderreferenceCode, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqOrderreferenceCode] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationDinersCount])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationDinersCount, 
						'=', 
						$_GET[reservationConstants::reqReservationDinersCount]
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationEta])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationEta, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationEta] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationPaymentMode])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationPaymentMode, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationPaymentMode] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationServiceTime])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationServiceTime, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationServiceTime] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationStatus])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationStatus, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationStatus] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationStatusChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationStatusChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationStatusChangeTimestamp] . '%'
				]
				);
		}
		if(isset($_GET[reservationConstants::reqReservationLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						reservationConstants::dbReservationLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[reservationConstants::reqReservationLastChangeTimestamp] . '%'
				]
				);
		}
		
		$reservationsResponse = new Response();
		try{
			$reservations = DB::table(reservationConstants::reservationsTable)
			->where($mySqlWhere)
			->get();
			if($reservations->isEmpty()){	$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::emptyResultSetErr
					);
			} else {	$reservationsResponse->setContent(json_encode($reservations));
			}
		} catch(\PDOException $e){	$reservationsResponse->setStatusCode(
				400, 
				reservationConstants::dbReadCatchMsg
				);
		}
		
		return $reservationsResponse;
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
							'*.' . reservationConstants::dbReservationCode => 'unique:reservations,reservation_code|required|string|max:40', 
							'*.' . reservationConstants::dbCustomerUsername => 'exists:customers,customer_username|required|string|max:30', 
							'*.' . reservationConstants::dbOrderreferenceCode => 'exists:orderreferences,orderreference_code|required|string|max:40', 
							'*.' . reservationConstants::dbReservationDinersCount => 'required|numeric', 
							'*.' . reservationConstants::dbReservationEta => 'required|date_format:Y-m-d H:i:s', 
							'*.' . reservationConstants::dbReservationPaymentMode => 'required|string|max:30', 
							'*.' . reservationConstants::dbReservationServiceTime => 'required|date_format:Y-m-d H:i:s', 
							'*.' . reservationConstants::dbReservationStatus => 'required|string|max:30', 
							'*.' . reservationConstants::dbReservationStatusChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . reservationConstants::dbReservationCode => 'unique:reservations,reservation_code|sometimes|string|max:40', 
							'*.' . reservationConstants::dbCustomerUsername => 'exists:customers,customer_username|sometimes|string|max:30', 
							'*.' . reservationConstants::dbOrderreferenceCode => 'exists:orderreferences,orderreference_code|sometimes|string|max:40', 
							'*.' . reservationConstants::dbReservationDinersCount => 'sometimes|numeric', 
							'*.' . reservationConstants::dbReservationEta => 'sometimes|date_format:Y-m-d H:i:s', 
							'*.' . reservationConstants::dbReservationPaymentMode => 'sometimes|string|max:30', 
							'*.' . reservationConstants::dbReservationServiceTime => 'sometimes|date_format:Y-m-d H:i:s', 
							'*.' . reservationConstants::dbReservationStatus => 'sometimes|string|max:30', 
							'*.' . reservationConstants::dbReservationStatusChangeTimestamp => 'sometimes|date_format:Y-m-d H:i:s', 
							'*.' . reservationConstants::dbReservationLastChangeTimestamp => 'sometimes|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations
	public function addReservation(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$reservationsResponse = new Response();
		$reservationsResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTableOrderreference = json_decode(
				(new orderreferenceController())->getCompanyBranchTableOrderreference(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreference) == 0){
			$reservationsResponse->setStatusCode(
					400, 
					reservationConstants::inconsistencyValidationErr1
					);
			
			return $reservationsResponse;
		}
		
		$orderreferenceCode = $companyBranchTableOrderreference[0][reservationConstants::dbOrderreferenceCode];
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(!(isset($jsonData[$i][reservationConstants::dbOrderreferenceCode]))){	$jsonData[$i][reservationConstants::dbOrderreferenceCode] = $orderreferenceCode;
			}
		}
		
		for($i=0; $i<$jsonDataSize; $i++){
			if(isset($jsonData[$i][reservationConstants::dbReservationEta])){
				try{
					$jsonData[$i][reservationConstants::dbReservationEta] = Carbon::parse($jsonData[$i][reservationConstants::dbReservationEta])
					->format('Y-m-d H:i:s');
				} catch(\Exception $e){
					$reservationsResponse->setStatusCode(
							400, 
							reservationConstants::carbonParseErr
							);
					
					return $reservationsResponse;
				}
			}
			if(isset($jsonData[$i][reservationConstants::dbReservationServiceTime])){
				try{
					$jsonData[$i][reservationConstants::dbReservationServiceTime] = Carbon::parse($jsonData[$i][reservationConstants::dbReservationServiceTime])
					->format('Y-m-d H:i:s');
				} catch(\Exception $e){
					$reservationsResponse->setStatusCode(
							400, 
							reservationConstants::carbonParseErr
							);
					
					return $reservationsResponse;
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
				if($jsonData[$i][reservationConstants::dbOrderreferenceCode] == $orderreferenceCode){
					try{
						DB::table(reservationConstants::reservationsTable)
						->insert($jsonData[$i]);
					} catch(\PDOException $e){
						$reservationsResponse->setStatusCode(
								400, 
								reservationConstants::dbAddCatchMsg
								);
						
						return reservationsResponse;
					}
				}
			}
		} else {	$reservationsResponse->setStatusCode(
				400, 
				$errorMsg
				);
			
			return $reservationsResponse;
		}
		
		return reservationConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/{ReservationCode}
	public function updateReservation(
			Request $jsonRequest, 
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$Orderreference, 
			$ReservationCode
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$reservationsResponse = new Response();
		$reservationsResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][reservationConstants::dbReservationEta])){
			try{	$jsonData[0][reservationConstants::dbReservationEta] = Carbon::parse($jsonData[0][reservationConstants::dbReservationEta])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$reservationsResponse->setStatusCode(
						400, 
						reservationConstants::carbonParseErr
						);
				
				return $reservationsResponse;
			}
		}
		if(isset($jsonData[0][reservationConstants::dbReservationServiceTime])){
			try{
				$jsonData[0][reservationConstants::dbReservationServiceTime] = Carbon::parse($jsonData[0][reservationConstants::dbReservationServiceTime])
				->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$reservationsResponse->setStatusCode(
						400, 
						reservationConstants::carbonParseErr
						);
				
				return $reservationsResponse;
			}
		}
		if(isset($jsonData[0][reservationConstants::dbReservationLastChangeTimestamp])){
			try{	$jsonData[0][reservationConstants::dbReservationLastChangeTimestamp] = Carbon::parse($jsonData[0][reservationConstants::dbReservationLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$reservationsResponse->setStatusCode(
						400, 
						reservationConstants::carbonParseErr
						);
				
				return $reservationsResponse;
			}
		}
		
		$companyBranchTableOrderreferenceReservation = json_decode(
				$this->getCompanyBranchTableOrderreferenceReservation(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$Orderreference, 
						$ReservationCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreferenceReservation) == 0){
			$reservationsResponse->setStatusCode(
					400, 
					reservationConstants::inconsistencyValidationErr2
					);
			
			return $reservationsResponse;
		}
		
		$reservationId = $companyBranchTableOrderreferenceReservation[0][reservationConstants::dbReservationId];
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$reservationsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $reservationsResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							reservationConstants::dbReservationId, 
							'=', 
							$reservationId
					]
					);
			DB::table(reservationConstants::reservationsTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
		} catch(\PDOException $e){
			$reservationsResponse->setStatusCode(
					400, 
					reservationConstants::dbUpdateCatchMsg
					);
			
			return $reservationsResponse;
		}
		
		return reservationConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/companies/{CompanyName}/branches/{BranchName}/tables/{TableNumber}/orderreferences/{OrderreferenceCode}/reservations/{ReservationCode}
	public function deleteReservation(
			$CompanyName, 
			$BranchName, 
			$TableNumber, 
			$OrderreferenceCode, 
			$ReservationCode
			){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$reservationsResponse = new Response();
		$reservationsResponse->setStatusCode(
				400, 
				null
				);
		$companyBranchTableOrderreferenceReservation = json_decode(
				$this->getCompanyBranchTableOrderreferenceReservation(
						$CompanyName, 
						$BranchName, 
						$TableNumber, 
						$OrderreferenceCode, 
						$ReservationCode
						)
				->getContent(), 
				true
				);
		if(sizeof($companyBranchTableOrderreferenceReservation) == 0){
			$reservationsResponse->setStatusCode(
					200, 
					reservationConstants::inconsistencyValidationErr2
					);
			
			return $reservationsResponse;
		}
		
		$reservationId = $companyBranchTableOrderreferenceReservation[0][reservationConstants::dbReservationId];
		
		try{
			array_push(
					$mySqlWhere, 
					[
							reservationConstants::dbReservationId, 
							'=', 
							$reservationId
					]
					);
			DB::table(reservationConstants::reservationsTable)
			->where($mySqlWhere)
			->delete();
		} catch(\PDOException $e){
			$reservationsResponse->setStatusCode(
					400, 
					reservationConstants::dbDeleteCatchMsg
					);
			
			return $reservationsResponse;
		}
		
		return reservationConstants::dbDeleteSuccessMsg;
	}
}