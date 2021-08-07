<?php

namespace App\Http\Controllers;

use App\CaseReceivable;
use App\Events\CaseReceivableCreated;
use App\Helpers;
use App\LegalCase;
use Illuminate\Http\Request;

class CaseReceivableController extends Controller
{
    public function index(LegalCase $case)
    {
        $receivables = $case->receivables()->orderBy('receivable_date')->paginate(50);
        return view('case.receivables', [
            'receivables' => $receivables,
            'case' => $case,
            'currencies' => Helpers::currencies(),
        ]);
    }

    public function store(LegalCase $case, Request $request)
    {
        $this->validate($request, [
            'currency' => 'required|numeric',
            'receivable_date' => 'required|date',
            'receivable_amount' => 'required|numeric',
            'received_date' => 'sometimes|nullable|date',
            'received_amount' => 'sometimes|nullable|numeric',
        ]);
        try {
            $status = 0;
            if ($request->get('received_date') && $request->get('received_amount')) {
                $status = 1;
            }
            $case_receivable = $case->receivables()->create([
                'receivable_date' => $request->get('receivable_date'),
                'receivable_amount' => $request->get('receivable_amount'),
                'received_date' => $request->get('received_date'),
                'received_amount' => $request->get('received_amount'),
                'description' => $request->get('description'),
                'currency' => $request->get('currency'),
                'status' => $status,
            ]);
            event(new CaseReceivableCreated($case, $case_receivable, $status));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t store Case Receivable');
        }
        return redirect()->back()->withSuccess('You have successfully added a Case Receivable!');
    }

    public function update(LegalCase $case, CaseReceivable $case_receivable, Request $request)
    {
        $this->validate($request, [
            'currency' => 'required|numeric',
            'receivable_date' => 'required|date',
            'receivable_amount' => 'required|numeric',
            'received_date' => 'sometimes|nullable|date',
            'received_amount' => 'sometimes|nullable|numeric',
        ]);
        try {
            $receivable = $case->receivables()->where('id', $case_receivable->id)->first();
            $status = 0;
            if ($request->get('received_date') && $request->get('received_amount')) {
                $status = 1;
            }
            $receivable->fill([
                'receivable_date' => $request->get('receivable_date'),
                'receivable_amount' => $request->get('receivable_amount'),
                'received_date' => $request->get('received_date'),
                'received_amount' => $request->get('received_amount'),
                'description' => $request->get('description'),
                'currency' => $request->get('currency'),
                'status' => $status,
            ])->save();
            event(new CaseReceivableCreated($case, $case_receivable, $status));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t update Case Receivable');
        }
        return redirect()->back()->withSuccess('You have successfully updated Case Receivable!');
    }

    public function destroy(LegalCase $case, CaseReceivable $case_receivable)
    {
        $receivable = $case->receivables()->where('id', $case_receivable->id)->firstOrFail();
        try {
            $receivable->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors('Couldn\'t delete Case Receivable');
        }
        return redirect()->back()->withSuccess('You have successfully deleted Case Receivable!');
    }
}
