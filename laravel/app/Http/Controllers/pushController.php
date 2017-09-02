<?php

namespace App\Http\Controllers;

use DB;
use Ixudra\Curl\Facades\Curl;

include_once "branchController.php";
include_once "customerController.php";

class pushConstants{
	const headerAuthorization = 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiIzNThhYTU2MS0wNzdiLTRiMGQtOGU4ZC1mYWZkMmU5ZGE1ZDIifQ.qJwC_-b0oa0her48JWx4-xsgZHWTh7seeg6tP9lTj-8';
	const headerContentType = 'application/json';
	const pushAPI = 'https://api.ionic.io/push/notifications';
	const pushProfile = 'zipmenu';
	
	const dbCustomerDeviceToken = 'customer_device_token';
}

class pushController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	//URL-->>/send/update-company
	public function sendUpdateCompanyCommand($notificationMessage){
		$mySqlWhere = array();
		$tokens = array();
		
		$customers = DB::table(customerConstants::customersTable)
		->get();
		$customersSize = sizeof($customers);
		
		for($i=0; $i<$customersSize; $i++){
			$customer = json_decode(
					json_encode($customers[$i]), 
					true
					);
			
			if(!(null == $customer[pushConstants::dbCustomerDeviceToken])){
				array_push(
						$tokens, 
						$customer[pushConstants::dbCustomerDeviceToken]
						);
			}
		}
		
		$notification = $this->genNotificationObj($notificationMessage);
		$notification['android']['payload']['command'] = 'updateCompany';
		
		$pushNotification = array(
				'tokens' => $tokens, 
				'profile' => pushConstants::pushProfile, 
				'notification' => $notification
		);
		
		$response = Curl::to(pushConstants::pushAPI)
		->asJson()
		->withContentType(pushConstants::headerContentType)
		->withData($pushNotification)
		->withHeader('Authorization: ' . pushConstants::headerAuthorization)
		->post();
	}
	
	//URL-->>/send/update-orderreference-order
	public function sendUpdateOrderreferenceOrderTableCommand(
			$CompanyName, 
			$BranchName, 
			$type, 
			$notificationMessage, 
			$orderOrderreferenceTable
			){
		$typeOrder = 'Order';
		$typeOrderreference = 'Orderreference';
		$typeTable = 'Table';
		
		$mySqlWhere = array();
		$tokens = array();
		
		$branch = (new branchController())->getCompanyBranch(
				$CompanyName, 
				$BranchName
				)
				->content();
		$branch = json_decode(
				$branch, 
				true
				);
		
		array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbCompanyName, 
						'=', 
						$CompanyName
				]
				);
		array_push(
				$mySqlWhere, 
				[
						customerCompanyBranchConstants::customersCompaniesBranchesTable . '.' . customerCompanyBranchConstants::dbBranchId, 
						'=', 
						$branch[0][branchConstants::dbBranchId]
				]
				);
		
		$customers = (new customerController())->getJoinCustomerCompanyBranchCustomer($mySqlWhere);
		$customersSize = sizeof($customers);
		
		for($i=0; $i<$customersSize; $i++){
			$customer = json_decode(
					json_encode($customers[$i]), 
					true
					);
			
			if(!(null == $customer[pushConstants::dbCustomerDeviceToken])){
				array_push(
						$tokens, 
						$customer[pushConstants::dbCustomerDeviceToken]
						);
			}
		}
		
		$notification = $this->genNotificationObj($notificationMessage);
		if($typeOrderreference == $type){	$notification['android']['payload']['command'] = 'updateOrderreference';
		} else if($typeOrder == $type){	$notification['android']['payload']['command'] = 'updateOrder';
		} else if($typeTable == $type){	$notification['android']['payload']['command'] = 'updateTable';
		}
		$notification['android']['payload']['orderOrderreferenceTable'] = $orderOrderreferenceTable;
		
		$pushNotification = array(
				'tokens' => $tokens, 
				'profile' => pushConstants::pushProfile, 
				'notification' => $notification
		);
		
		$response = Curl::to(pushConstants::pushAPI)
		->asJson()
		->withContentType(pushConstants::headerContentType)
		->withData($pushNotification)
		->withHeader('Authorization: ' . pushConstants::headerAuthorization)
		->post();
	}
	
	private function genNotificationObj($notificationMessage){
		$notification = array();
		
		$notification['android'] = array();
		$notification['android']['title'] = 'ZipOrder Notification';
		$notification['android']['message'] = $notificationMessage;
		
		return $notification;
	}
}