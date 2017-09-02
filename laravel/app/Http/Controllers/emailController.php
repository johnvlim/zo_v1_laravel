<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;

class emailConstants{
	const datCustomerName = 'customer_name';
	const datCompanyName = 'company_name';
	const datCustomerEmail = 'customer_email';
	
	const emailSender = 'menugo.ziplogic@gmail.com';
	const emailZippies_1 = 'johnvictormlim@gmail.com';
	
	const emailPreSignupSubj = 'Pre-Signup Request';
	const emailPreSignupConfirmationSubj = 'Pre-Signup Request Acknowledgment';
	
	const emailSentSuccessMsg = 'EMAIL SENT SUCCESSFULLY';
	const emailSentCatchMsg = 'EXCEPTION ENCOUNTERED, UNABLE TO SEND EMAIL';
}

class emailController extends Controller
{
	//url-->>/pre-signup
	public function sendEmailFromPreSignup(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$customerName = $jsonData[0][emailConstants::datCustomerName];
		$companyName = $jsonData[0][emailConstants::datCompanyName];
		$customerEmail = $jsonData[0][emailConstants::datCustomerEmail];
		
		$data = array(
				'customerName' => $customerName, 
				'companyName' => $companyName, 
				'customerEmail' => $customerEmail
		);
		
		$emailResponse = new Response();
		$emailResponse->setStatusCode(
				400, 
				null
				);
		
		try{
			Mail::send(
					'email.preSignupAck', 
					[
							'customerName' => $customerName
					], 
					function($message) use ($data){
						$message->from(emailConstants::emailSender);
						$message->to($data['customerEmail']);
						$message->subject(emailConstants::emailPreSignupConfirmationSubj);
					}
			);
		} catch(\Exception $e){
			$emailResponse->setStatusCode(
					400, 
					emailSentCatchMsg
					);
			
			return $emailResponse;
		}
		
		try{
			Mail::send(
					'email.preSignup', 
					[
							'customerName' => $customerName, 
							'companyName' => $companyName, 
							'customerEmail' => $customerEmail
					], 
					function($message){
						$message->from(emailConstants::emailSender);
						$message->to(emailConstants::emailZippies_1);
						$message->subject(emailConstants::emailPreSignupSubj);
					}
			);
		} catch(\Exception $e){
			$emailResponse->setStatusCode(
					400, 
					emailSentCatchMsg
					);
			
			return $emailResponse;
		}
		
		return emailConstants::emailSentSuccessMsg;
	}
}