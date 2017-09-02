<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class blogConstants{
	const blogsTable = 'blogs';
	
	const dbBlogId = 'blog_id';
	const dbBlogTitle = 'blog_title';
	const dbBlogAuthor = 'blog_author';
	const dbBlogContent = 'blog_content';
	const dbBlogImage = 'blog_image';
	const dbBlogUrl = 'blog_url';
	const dbBlogLastChangeTimestamp = 'blog_last_change_timestamp';
	
	const reqBlogId = 'BlogId';
	const reqBlogTitle = 'BlogTitle';
	const reqBlogAuthor = 'BlogAuthor';
	const reqBlogContent = 'BlogContent';
	const reqBlogImage = 'BlogImage';
	const reqBlogUrl = 'BlogUrl';
	const reqBlogLastChangeTimestamp = 'BlogLastChangeTimestamp';
	
	const dbReadCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO READ RECORD';
	const dbAddCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO ADD RECORD';
	const dbUpdateCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO UPDATE RECORD';
	const dbDeleteCatchMsg = 'DB EXCEPTION ENCOUNTERED, UNABLE TO DELETE RECORD';
	
	const dbAddSuccessMsg = 'DB UPDATED W/NEW BLOG RECORD';
	const dbUpdateSuccessMsg = 'DB UPDATED EXISTING BLOG RECORD';
	const dbDeleteSuccessMsg = 'DB DELETED EXISTING BLOG RECORD';
	
	const emptyResultSetErr = 'DB SELECT RETURNED EMPTY RESULT SET';
	const carbonParseErr = 'UNPARSEABLE DATE';
}

class blogController extends Controller
{
	public function __construct(){	$this->middleware('jwt.auth');
	}
	
	//URL-->>/blogs
	public function getBlogs(){
		$blogsResponse = new Response();
		
		try{
			$blogs = DB::table(blogConstants::blogsTable)
			->get();
			if($blogs->isEmpty()){	$blogsResponse->setStatusCode(
					200, 
					blogConstants::emptyResultSetErr
					);
			} else {	$blogsResponse->setContent(json_encode($blogs));
			}
		} catch(\PDOException $e){	$blogsResponse->setStatusCode(
				400, 
				blogConstants::dbReadCatchMsg
				);
		}
		
		return $blogsResponse;
	}
	
	//URL-->>/blogs/{BlogId}
	public function getBlog($BlogId){
		$mySqlWhere = array();
		
		array_push(
				$mySqlWhere, 
				[
						blogConstants::blogsTable . '.' . blogConstants::dbBlogId, 
						'=', 
						$BlogId
				]
				);
		
		$blogsResponse = new Response();
		try{
			$blog = DB::table(blogConstants::blogsTable)
			->where($mySqlWhere)
			->get();
			if($blog->isEmpty()){	$blogsResponse->setStatusCode(
					200, 
					blogConstants::emptyResultSetErr
					);
			} else {	$blogsResponse->setContent(json_encode($blog));
			}
		} catch(\PDOException $e){	$blogsResponse->setStatusCode(
				400, 
				blogConstants::dbReadCatchMsg
				);
		}
		
		return $blogsResponse;
	}
	
	//URL-->>/query/blogs
	public function getByQuery(){
		$mySqlWhere = array();
		
		if(isset($_GET[blogConstants::reqBlogId])){	array_push(
				$mySqlWhere, 
				[
						blogConstants::dbBlogId, 
						'=', 
						$_GET[blogConstants::reqBlogId]
				]
				);
		}
		if(isset($_GET[blogConstants::reqBlogTitle])){	array_push(
				$mySqlWhere, 
				[
						blogConstants::dbBlogTitle, 
						'LIKE', 
						'%' . $_GET[blogConstants::reqBlogTitle] . '%'
				]
				);
		}
		if(isset($_GET[blogConstants::reqBlogAuthor])){	array_push(
				$mySqlWhere, 
				[
						blogConstants::dbBlogAuthor, 
						'LIKE', 
						'%' . $_GET[blogConstants::reqBlogAuthor] . '%'
				]
				);
		}
		if(isset($_GET[blogConstants::reqBlogContent])){	array_push(
				$mySqlWhere, 
				[
						blogConstants::dbBlogContent, 
						'LIKE', 
						'%' . $_GET[blogConstants::reqBlogContent] . '%'
				]
				);
		}
		if(isset($_GET[blogConstants::reqBlogImage])){	array_push(
				$mySqlWhere, 
				[blogConstants::dbBlogImage, 
						'LIKE', 
						'%' . $_GET[blogConstants::reqBlogImage] . '%'
				]
				);
		}
		if(isset($_GET[blogConstants::reqBlogUrl])){	array_push(
				$mySqlWhere, 
				[
						blogConstants::dbBlogUrl, 
						'LIKE', 
						'%' . $_GET[blogConstants::reqBlogUrl] . '%'
				]
				);
		}
		if(isset($_GET[blogConstants::reqBlogLastChangeTimestamp])){	array_push(
				$mySqlWhere, 
				[
						blogConstants::dbBlogLastChangeTimestamp, 
						'LIKE', 
						'%' . $_GET[blogConstants::reqBlogLastChangeTimestamp] . '%'
				]
				);
		}
		
		$blogsResponse = new Response();
		try{
			$blogs = DB::table(blogConstants::blogsTable)
			->where($mySqlWhere)
			->get();
			if($blogs->isEmpty()){	$blogsResponse->setStatusCode(
					200, 
					blogConstants::emptyResultSetErr
					);
			} else {	$blogsResponse->setContent(json_encode($blogs));
			}
		} catch(\PDOException $e){	$blogsResponse->setStatusCode(
				400, 
				blogConstants::dbReadCatchMsg
				);
		}
		
		return $blogsResponse;
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
							'*.' . blogConstants::dbBlogTitle => 'required|string|max:100', 
							'*.' . blogConstants::dbBlogAuthor => 'required|string|max:50', 
							'*.' . blogConstants::dbBlogContent => 'required|string|max:1000', 
							'*.' . blogConstants::dbBlogImage => 'required|string|max:500', 
							'*.' . blogConstants::dbBlogUrl => 'required|string|max:500'
					]
					);
		} else if("UPDATE" == $dbOperation){
			$jsonValidation = Validator::make(
					$jsonData, 
					[
							'*.' . blogConstants::dbBlogTitle => 'sometimes|string|max:100', 
							'*.' . blogConstants::dbBlogAuthor => 'sometimes|string|max:50', 
							'*.' . blogConstants::dbBlogContent => 'sometimes|string|max:1000', 
							'*.' . blogConstants::dbBlogImage => 'sometimes|string|max:500', 
							'*.' . blogConstants::dbBlogUrl => 'sometimes|string|max:500', 
							'*.' . blogConstants::dbBlogLastChangeTimestamp => 'required|date_format:Y-m-d H:i:s'
					]
					);
		}
		
		if($jsonValidation->fails()){
			$errorMsg = $jsonValidation->messages();
			
			return false;
		} else {	return true;
		}
	}
	
	//URL-->>/blogs
	public function addBlog(Request $jsonRequest){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$errorMsg = '';
		
		$blogsResponse = new Response();
		$blogsResponse->setStatusCode(
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
				try{	DB::table(blogConstants::blogsTable)
				->insert($jsonData[$i]);
				} catch(\PDOException $e){
					$blogsResponse->setStatusCode(
							400, 
							blogConstants::dbAddCatchMsg
							);
					
					return $blogsResponse;
				}
			}
		} else {
			$blogsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $blogsResponse;
		}
		
		return blogConstants::dbAddSuccessMsg;
	}
	
	//URL-->>/blogs/{BlogId}
	public function updateBlog(
			Request $jsonRequest, 
			$BlogId
			){
		$jsonData = json_decode(
				$jsonRequest->getContent(), 
				true
				);
		$jsonDataSize = sizeof($jsonData);
		$mySqlWhere = array();
		$errorMsg = '';
		
		$blogsResponse = new Response();
		$blogsResponse->setStatusCode(
				400, 
				null
				);
		if(isset($jsonData[0][blogConstants::dbBlogLastChangeTimestamp])){
			try{	$jsonData[0][blogConstants::dbBlogLastChangeTimestamp] = Carbon::parse($jsonData[0][blogConstants::dbBlogLastChangeTimestamp])
			->format('Y-m-d H:i:s');
			} catch(\Exception $e){
				$blogsResponse->setStatusCode(
						400, 
						blogConstants::carbonParseErr
						);
				
				return blogsResponse;
			}
		}
		
		if(!$this->isDataValid(
				$jsonData, 
				$errorMsg, 
				"UPDATE"
				)
				){
			$blogsResponse->setStatusCode(
					400, 
					$errorMsg
					);
			
			return $blogsResponse;
		}
		
		try{
			array_push(
					$mySqlWhere, 
					[
							blogConstants::dbBlogId, 
							'=' , 
							$BlogId
					]
					);
			DB::table(blogConstants::blogsTable)
			->where($mySqlWhere)
			->update($jsonData[0]);
		} catch(\PDOException $e){
			$blogsResponse->setStatusCode(
					400, 
					blogConstants::dbUpdateCatchMsg
					);
			
			return $blogsResponse;
		}
		
		return blogConstants::dbUpdateSuccessMsg;
	}
	
	//URL-->>/blogs/{BlogId}
	public function deleteBlog($BlogId){
		$mySqlWhere = array();
		$errorMsg = '';
		
		$blogsResponse = new Response();
		$blogsResponse->setStatusCode(
				400, 
				null
				);
		try{
			array_push(
					$mySqlWhere, 
					[
							blogConstants::dbBlogId, 
							'=', 
							$BlogId
					]
					);
			DB::table(blogConstants::blogsTable)
			->where($mySqlWhere)
			->delete();
		} catch(\PDOException $e){
			$blogsResponse->setStatusCode(
					400, 
					blogConstants::dbDeleteCatchMsg
					);
			
			return $blogsResponse;
		}
		
		return blogConstants::dbDeleteSuccessMsg;
	}
}