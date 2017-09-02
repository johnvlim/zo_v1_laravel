<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

include_once "customerController.php";

class authenticateController extends Controller
{
	const ERR_INVALID_LOGIN_CREDENTIALS = '[Error]: Invalid Login Credentials';
	const STATUS_CODE_INVALID_LOGIN_CREDENTIALS = 401;
	
	const ERR_INVALID_CLAIM_EXCEPTION = '[Error] Invalid Claim Exception';
	const ERR_PAYLOAD_EXCEPTION = '[Error] Payload Exception';
	const ERR_TOKEN_BLACKLISTED_EXCEPTION = '[Error] Token Blacklisted Exception';
	const ERR_TOKEN_EXPIRED_EXCEPTION = '[Error] Token Expired Exception';
	const ERR_TOKEN_INVALID_EXCEPTION = '[Error] Token Invalid Exception';
	
	const SUCCESS_MESSAGE = 'User Successfully Logged In';
	const STATUS_CODE_SUCCESS = 200;
	
	//URL-->>/login
	public function authenticate(Request $request){
		$loginCredentials = $request->only(
				customerConstants::dbCustomerUsername, 
				customerConstants::dbCustomerPassword
				);
		
		try{	$jwtAuthToken = JWTAuth::attempt($loginCredentials);
		} catch(InvalidClaimException $err){
			$authResponse = new Response();
			$authResponse->setStatusCode(
					$err->getStatusCode(), 
					$this::ERR_INVALID_CLAIM_EXCEPTION
					);
			
			return $authResponse;
		} catch(PayloadException $err){
			$authResponse = new Response();
			$authResponse->setStatusCode(
					$err->getStatusCode(), 
					$this::ERR_PAYLOAD_EXCEPTION
					);
			
			return $authResponse;
		} catch(TokenBlacklistedException $err){
			$authResponse = new Response();
			$authResponse->setStatusCode(
					$err->getStatusCode(), 
					$this::ERR_TOKEN_BLACKLISTED_EXCEPTION
					);
			
			return $authResponse;
		} catch(TokenExpiredException $err){
			$authResponse = new Response();
			$authResponse->setStatusCode(
					$err->getStatusCode(), 
					$this::ERR_TOKEN_EXPIRED_EXCEPTION
					);
			
			return $authResponse;
		} catch(TokenInvalidException $err){
			$authResponse = new Response();
			$authResponse->setStatusCode(
					$err->getStatusCode(), 
					$this::ERR_TOKEN_INVALID_EXCEPTION
					);
			
			return $authResponse;
		}
		
		if(!$jwtAuthToken){
			$authResponse = new Response();
			$authResponse->setStatusCode(
					$this::STATUS_CODE_INVALID_LOGIN_CREDENTIALS, 
					$this::ERR_INVALID_LOGIN_CREDENTIALS
					);
			
			return $authResponse;
		}
		
		//get user details
		$mySqlWhere = array();
		array_push(
				$mySqlWhere, 
				[
						customerConstants::dbCustomerUsername, 
						'=', 
						$loginCredentials[customerConstants::dbCustomerUsername]
				]
				);
		
		$user = (array)DB::table(customerConstants::customersTable)
		->where($mySqlWhere)
		->first();
		
		$userDetails = array();
		$userDetails[customerConstants::dbCustomerUsername] = $user[customerConstants::dbCustomerUsername];
		$userDetails[customerConstants::dbCustomerNameFname] = $user[customerConstants::dbCustomerNameFname];
		$userDetails[customerConstants::dbCustomerNameMname] = $user[customerConstants::dbCustomerNameMname];
		$userDetails[customerConstants::dbCustomerNameLname] = $user[customerConstants::dbCustomerNameLname];
		$userDetails[customerConstants::dbCustomerRole] = $user[customerConstants::dbCustomerRole];
		
		return response()
		->json(
				[
						'Token' => $jwtAuthToken, 
						'Message' => $this::SUCCESS_MESSAGE, 
						'Status' => $this::STATUS_CODE_SUCCESS, 
						'User' => $userDetails
				]
				);
	}
}