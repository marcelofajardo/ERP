<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMailingListTemplateCategoryRequest;
use App\MailinglistTemplate;
use App\MailinglistTemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use qoraiche\mailEclipse\mailEclipse;
use View;

class MailinglistTemplateCategoryController extends Controller
{

    public function store(StoreMailingListTemplateCategoryRequest $request)
    {

        $logged_user = $request->user();

        MailinglistTemplateCategory::create([
            'title' => $request->name,
            'user_id' => $logged_user->id
        ]);

        return response()->json([
            'status' => true
        ]);

    }

}

