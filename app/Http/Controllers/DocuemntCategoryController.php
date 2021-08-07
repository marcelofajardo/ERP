<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentCategory;
use Response;

class DocuemntCategoryController extends Controller
{
    public function addCategory(Request $request)
    {
     $category =  new DocumentCategory;

     $category->name = $request->name;
     
     $category->save();
     
     if($category->id != NULL){
     return Response::json(array(
      'success' => true,
      'message'   => 'Category Created Sucessfully'
      ));
     }
     else
	 {
     return Response::json(array(
      'success' => false,
      'message'   => 'Category Not Created'
      ));	
     }
      
     
    }
}
