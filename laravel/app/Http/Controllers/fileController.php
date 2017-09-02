<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;

class fileConstants{
	const fileGetSuccessMsg = 'FILE RETRIEVED SUCCESSFULLY';
	const fileUploadSuccessMsg = 'FILE UPLOADED SUCCESSFULLY';
	const fileDeleteSuccessMsg = 'FILE DELETED SUCCESSFULLY';
	
	const fileGetCatchMsg = 'EXCEPTION ENCOUNTERED, UNABLE TO RETRIEVE FILE';
	const fileUploadCatchMsg = 'EXCEPTION ENCOUNTERED, UNABLE TO UPLOAD FILE';
	const fileDeleteCatchMsg = 'EXCEPTION ENCOUNTERED, UNABLE TO DELETE FILE';
}

class fileController extends Controller
{
	public function __construct(){	$this->middleware(
			'jwt.auth', 
			['except' => [
					'getCompanyImage', 
					'getCompanyMenuImage', 
					'getCompanyMenuMenuitemImage'
			]
			]
			);
	}
	
	//URL-->>/companies/{CompanyName}/companyImage
	public function getCompanyImage($CompanyName){
		$imgDirectory = '/companies/' . $CompanyName . '/';
		$imgFileName = $imgDirectory . $CompanyName . '.jpg';
		$filesResponse = new Response();
		
		if(
				null == $CompanyName ||
				'undefined' == $CompanyName
				){	$filesResponse->setStatusCode(
						400, 
						fileConstants::fileGetCatchMsg
						);
				
				return $filesResponse;
		}
		
		try{
			$companyImage = Storage::disk('public')
			->get($imgFileName);
			$filesResponse->setContent($companyImage);
			$filesResponse->setStatusCode(
					200, 
					fileConstants::fileGetSuccessMsg
					);
		} catch (\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileGetCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/companyImage
	public function uploadCompanyImage(
			Request $formRequest, 
			$CompanyName
			){
		$imgDirectory = '/companies/' . $CompanyName . '/';
		$imgFile = $formRequest->file('imgFile');
		$imgFileName = $imgDirectory . $CompanyName . '.jpg';
		$imgStore = Image::make(File::get($imgFile))
		->resize(
				300, 
				300, 
				function($constraint){
					$constraint->aspectRatio();
					$constraint->upsize();
				}
		)
		->stream();
		$filesResponse = new Response();
		
		if(
				null == $CompanyName ||
				'undefined'== $CompanyName
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileUploadCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			Storage::disk('public')
			->put(
					$imgFileName, 
					$imgStore
					);
					$filesResponse->setStatusCode(
							200, 
							fileConstants::fileUploadSuccessMsg
							);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileUploadCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/companyImage
	public function deleteCompanyImage($CompanyName){
		$imgDirectory = '/companies/' . $CompanyName . '/';
		$imgFileName = $imgDirectory . $CompanyName .  '.jpg';
		$filesResponse = new Response();
		
		if(
				null == $CompanyName ||
				'undefined' == $CompanyName
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileDeleteCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			Storage::disk('public')
			->delete($imgFileName);
			$filesResponse->setStatusCode(
					200, 
					fileConstants::fileDeleteSuccessMsg
					);
		} catch (\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileDeleteCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuImage
	public function getCompanyMenuImage(
			$CompanyName, 
			$MenuName
			){
		$imgDirectory = '/companies/' . $CompanyName . '/menus/';
		$imgFileName = $imgDirectory . $CompanyName . '_' . $MenuName . '.jpg';
		$filesResponse = new Response();
		
		if(
				(
						null == $CompanyName ||
						'undefined' == $CompanyName
						) ||
				(
						null == $MenuName ||
						'undefined' == $MenuName
						)
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileGetCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			$companyMenuImage  = Storage::disk('public')
			->get($imgFileName);
			$filesResponse->setContent($companyMenuImage);
			$filesResponse->setStatusCode(
					200, 
					fileConstants::fileGetSuccessMsg
					);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileGetCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuImage
	public function uploadCompanyMenuImage(
			Request $formRequest, 
			$CompanyName, 
			$MenuName
			){
		$imgDirectory = '/companies/' . $CompanyName . '/menus/';
		$imgFile = $formRequest->file('imgFile');
		$imgFileName = $imgDirectory . $CompanyName . '_' . $MenuName .'.jpg';
		$imgStore = Image::make(File::get($imgFile))
		->resize(
				300, 
				300, 
				function($constraint){
					$constraint->aspectRatio();
					$constraint->upsize();
				}
		)
		->stream();
		$filesResponse = new Response();
		
		if(
				(
						null == $CompanyName ||
						'undefined' == $CompanyName
						) ||
				(
						null == $MenuName ||
						'undefined' == $MenuName
						)
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileUploadCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			Storage::disk('public')
			->put(
					$imgFileName, 
					$imgStore
					);
					$filesResponse->setStatusCode(
							200, 
							fileConstants::fileUploadSuccessMsg
							);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileUploadCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuImage
	public function deleteCompanyMenuImage(
			Request $formRequest, 
			$CompanyName, 
			$MenuName
			){
		$imgDirectory = '/companies/' . $CompanyName . '/menus/';
		$imgFileName = $imgDirectory . $CompanyName . '_' . $MenuName . '.jpg';
		$filesResponse = new Response();
		
		if(
				(
						null == $CompanyName ||
						'undefined' == $CompanyName
						) ||
				(
						null == $MenuName ||
						'undefined' == $MenuName
						)
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileDeleteCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			Storage::disk('public')
			->delete($imgFileName);
			$filesResponse->setStatusCode(
					200, 
					fileConstants::fileDeleteSuccessMsg
					);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileDeleteCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}/menuitemImage
	public function getCompanyMenuMenuitemImage(
			$CompanyName, 
			$MenuName, 
			$MenuitemCode
			){
		$imgDirectory = '/companies/' . $CompanyName . '/menus/' . $MenuName . '/menuitems/';
		$imgFileName = $imgDirectory . $CompanyName . '_' . $MenuName . '_' . $MenuitemCode . '.jpg';
		$filesResponse = new Response();
		
		if(
				(
						null == $CompanyName ||
						'undefined' == $CompanyName
						) ||
				(
						null == $MenuName ||
						'undefined' == $MenuName
						) ||
				(
						null == $MenuitemCode ||
						'undefined' == $MenuitemCode
						)
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileGetCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			$companyMenuMenuitemImage = Storage::disk('public')
			->get($imgFileName);
			$filesResponse->setContent($companyMenuMenuitemImage);
			$filesResponse->setStatusCode(
					200, 
					fileConstants::fileGetSuccessMsg
					);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileGetCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}/menuitemImage
	public function uploadCompanyMenuMenuitemImage(
			Request $formRequest, 
			$CompanyName, 
			$MenuName, 
			$MenuitemCode
			){
		$imgDirectory = '/companies/' . $CompanyName . '/menus/' . $MenuName . '/menuitems/';
		$imgFile = $formRequest->file('imgFile');
		$imgFileName = $imgDirectory . $CompanyName . '_' . $MenuName . '_' . $MenuitemCode . '.jpg';
		$imgStore = Image::make(File::get($imgFile))
		->resize(
				300, 
				300, 
				function($constraint){
					$constraint->aspectRatio();
					$constraint->upsize();
				}
		)
		->stream();
		$filesResponse = new Response();
		
		if(
				(
						null == $CompanyName ||
						'undefined' == $CompanyName
						) ||
				(
						null == $MenuName ||
						'undefined' == $MenuName
						) ||
				(
						null == $MenuitemCode ||
						'undefined' == $MenuitemCode
						)
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileUploadCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			Storage::disk('public')
			->put(
					$imgFileName, 
					$imgStore
					);
					$filesResponse->setStatusCode(
							200, 
							fileConstants::fileUploadSuccessMsg
							);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				400, 
				fileConstants::fileUploadCatchMsg
				);
		}
		
		return $filesResponse;
	}
	
	//URL-->>/companies/{CompanyName}/menus/{MenuName}/menuitems/{MenuitemCode}/menuitemImage
	public function deleteCompanyMenuMenuitemImage(
			$CompanyName, 
			$MenuName, 
			$MenuitemCode
			){
		$imgDirectory = '/companies/' . $CompanyName . '/menus/' . $MenuName . '/menuitems/';
		$imgFileName = $imgDirectory . $CompanyName . '_' . $MenuName . '_' .$MenuitemCode . '.jpg';
		$filesResponse = new Response();
		
		if(
				(
						null == $CompanyName ||
						'undefined' == $CompanyName
						) ||
				(
						null == $MenuName ||
						'undefined' == $MenuName
						) ||
				(
						null == $MenuitemCode ||
						'undefined' == $MenuitemCode
						)
				){
			$filesResponse->setStatusCode(
					400, 
					fileConstants::fileDeleteCatchMsg
					);
			
			return $filesResponse;
		}
		
		try{
			Storage::disk('public')
			->delete($imgFileName);
			$filesResponse->setStatusCode(
					200, 
					fileConstants::fileDeleteSuccessMsg
					);
		} catch(\Exception $e){	$filesResponse->setStatusCode(
				200, 
				fileConstants::fileDeleteCatchMsg
				);
		}
		
		return $filesResponse;
	}
}