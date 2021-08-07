<?php

namespace App\Http\Controllers;

use App\CaseCost;
use App\Events\CaseBilled;
use App\Events\CaseBillPaid;
use App\Helpers;
use App\Http\Requests\CreateCaseRequest;
use App\Lawyer;
use App\LegalCase;
use App\ReplyCategory;
use App\User;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    protected $data;

    public function __construct()
    {
      //  $this->middleware('permission:case-all');
        $this->data['statuses'] = ['Not Filed', 'Filed', 'Hearing', 'Differed', 'Settled', 'Closed'];
    }

    public function index(LegalCase $case, Request $request)
    {
        $this->data['cases'] = $case;
        $order_by = 'DESC';
        if ($request->orderby == '')
            $order_by = 'ASC';

        //TODO refactor search functionality...
        //use some searchable package..

        if ($request->has('term') && $request->get('term')) {
            $term = $request->get('term');
            $this->data['term'] = $term;
            $this->data['cases'] = $this->data['cases']->where(function ($query) use ($term) {
                $query->where('case_number', 'like', '%' . $term . '%')
                    ->orWhere('court_detail', 'like', '%' . $term . '%')
                    ->orWhere('resource', 'like', '%' . $term . '%')
                    ->orWhereHas('lawyer', function ($lawyer) use ($term) {
                        $lawyer->where(function ($lawyer_query) use ($term) {
                            $lawyer_query->where('name', 'like', '%' . $term . '%')
                                ->orWhere('email', 'like', '%' . $term . '%');
                        });
                    });
            });
        }

        if ($request->sortby == 'lawyer') {
            $this->data['cases'] = $this->data['cases']->join(
                'lawyers',
                'lawyers.id', '=', 'cases.lawyer_id'
            )->orderBy('lawyer.title', $order_by);
        }
        $this->data['orderby'] = $order_by;
        $this->data['lawyers'] = Lawyer::pluck('name', 'id');
        if ($request->has('with_archived') && $request->get('with_archived') == 'on') {
            $this->data['cases'] = LegalCase::onlyTrashed();
        }
        $this->data['cases'] = $this->data['cases']->with(['chat_message' => function ($chat_message) {
            $chat_message->select('id', 'message', 'case_id', 'status')->orderBy('id', 'desc');
        }, 'lawyer:id,name']);
        $this->data['cases'] = $this->data['cases']->paginate(50);
        return view('case.index', $this->data);
    }

    public function store(CreateCaseRequest $request)
    {
        $case = new LegalCase($request->all());
        $case->save();
        return redirect()->route('case.index')->withSuccess('You have successfully saved a case!');
    }

    /**
     * @param CreateCaseRequest $request
     * @param LegalCase $case
     * @return mixed
     */
    public function update(CreateCaseRequest $request, LegalCase $case)
    {
        $case->fill($request->all())->save();
        return redirect()->route('case.index')->withSuccess('You have successfully saved a case!');
    }

    public function show(LegalCase $case)
    {
        $this->data['case'] = $case;
        $this->data['lawyers'] = Lawyer::pluck('name', 'id');
        $this->data['reply_categories'] = ReplyCategory::all();
        $this->data['users_array'] = Helpers::getUserArray(User::all());

        return view('case.show', $this->data);
    }

    public function destroy(LegalCase $case)
    {
        $case->delete();
        return redirect()->route('case.index')->withSuccess('You have successfully deleted a case');
    }

    public function getCosts(LegalCase $case, Request $request)
    {
        $costs = $case->costs;
        return response($costs);
    }

    public function costStore(Request $request)
    {
        $this->validate($request, [
            'billed_date' => 'required',
            'amount' => 'required|numeric',
        ]);
        try {
            $payment = CaseCost::create($request->all());
            $case = LegalCase::find($request->case_id);
            if($case)
                event (new CaseBilled($case, $payment));
        } catch (\Exception $exception) {
            return response($exception->getMessage());
        }

        return response($payment);
    }


    public function costUpdate(CaseCost $case_cost, Request $request)
    {
        $this->validate($request, [
            'paid_date' => 'required',
            'amount_paid' => 'required|numeric',
        ]);
        try {
            $case_cost->paid_date = $request->get('paid_date');
            $case_cost->amount_paid = $request->get('amount_paid');
            $case_cost->save();
            event (new CaseBillPaid($case_cost->case, $case_cost));
        } catch (\Exception $exception) {
            return response($exception->getMessage());
        }
        return response($case_cost);
    }
}
