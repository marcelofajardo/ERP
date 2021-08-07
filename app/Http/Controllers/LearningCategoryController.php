<?php

namespace App\Http\Controllers;

use App\LearningModule;
use Auth;
use Illuminate\Http\Request;

class LearningCategoryController extends Controller
{
	public function __construct() {

	}

	public function index(){

		$data = LearningModule::latest()->get();
		return view('task-module.category.index',compact('data'));
	}

	public function create(){

		$data = [];
		$data['title'] = '';
		$data['modify'] = 0;

		return view('task-module.category.form',$data);
	}

	public function edit(LearningModule $learning_category){

		$data = $learning_category->toArray();
		$data['modify'] = 1;

		return view('task-module.category.form',$data);
	}

	public function store(Request $request){

		$this->validate($request,[
			'title' => 'required_without:subcategory'
		]);

		$is_approved = Auth::user()->hasRole('Admin') ? 1 : 0;

		if ($request->title != '') {
			LearningModule::create(['title' => $request->title, 'is_approved' => $is_approved,  'parent_id' => 0]);
		}

		if ($request->parent_id != '' && $request->subcategory != '') {
			LearningModule::create(['title' => $request->subcategory, 'parent_id' => $request->parent_id, 'is_approved' => $is_approved]);
		}


		return redirect()->back()->with('success','Module created successfully');
	}
	public function getSubModule(Request $request) {
		if($request->module_id){
			return LearningModule::where('is_approved', 1)->where('parent_id', $request->module_id)->get();
		}
	}
	public function changeStatus(Request $request) {
		$categories = LearningModule::all();
		foreach($categories as $cat) {
			$cat->update(['is_active' => 0]);
		}
		foreach($request->categoriesList as $category) {
			LearningModule::where('id',$category)->update(['is_active' => 1]);
		}
		return response()->json(['message' => 'Successful'],200);
	}

	public function update(Request $request,LearningModule $learning_category){

		$this->validate($request,[
			'title' => 'required'
		]);

		$learning_category->update($request->all());

		return redirect()->route('learning_category.index')->with('success','Category udpated successfully');
	}

	public function approve(Request $request, $id){
		$learning_category = LearningModule::find($id);
		$learning_category->is_approved = 1;
		$learning_category->save();

		if ($request->ajax()) {
			return response('success', 200);
		}

		return redirect()->route('learning_category.index')->with('success','Category approved successfully');
	}

	public function destroy(LearningModule $learning_category){
		$learning_category->delete();

		return redirect()->route('learning_category.index')->with('success','Category deleted successfully');
	}

	public static function getAllLearningModule(){

		$learning_category = LearningModule::all()->toArray();
		$learning_category_new = [];

		foreach($learning_category as $item)
			$learning_category_new[$item['id']] = $item['title'];

		return $learning_category_new;
	}

	public static function getCategoryNameById($id){

		$learning_category = self::getAllLearningModule();

		if(!empty($learning_category[$id]))
			return $learning_category[$id];

		return '';
	}
}
