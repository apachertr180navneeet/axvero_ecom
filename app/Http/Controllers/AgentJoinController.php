<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentJoin;
use App\Exports\AgentJoinExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AgentJoinImport;
class AgentJoinController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        
        
        $existingAgent = \App\Models\AgentJoin::where('user_id', $user->id)
                            ->where('payment_status', 'success')
                            ->first();
        
        return view('frontend.join_as_agent', compact('user', 'existingAgent'));
    }
    public function initiatePayment(Request $request)
    {
        $user = auth()->user();
        $txnid = uniqid();

        // DB save
        $agent = AgentJoin::create([
            'user_id'                => auth()->id(),
            'amount'                 => 999,
            'name'                   => $user->name,
            'mobile'                 => $user->phone ?? '',
            'email'                  => $user->email,
            'referred_person_name'   => $request->referred_person_name,
            'referred_person_mobile' => $request->referred_person_mobile,
            'referred_person_email'  => $request->referred_person_email,
            'manager_name'           => $request->manager_name,
            'manager_company_email'  => $request->manager_company_email,
                   'sales_name'           => $request->sales_name,
            'sales_email'  => $request->sales_email,
            'payment_status'         => 'pending',
            'txnid'                  => $txnid,
        ]);

       $amount = number_format((float)999, 2, '.', '');
        $productinfo = 'Agent Registration';
        $firstname   = $user->name;
        $email       = $user->email;

        $key  = env('PAYU_MERCHANT_KEY');
         $salt = env('PAYU_SALT');

     $hashString = $key . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $firstname . '|' . $email . '|||||||||||' . $salt;

       $hash = strtolower(hash('sha512', $hashString));
        auth()->user()->update(['is_first_login' => 1]);
        return view('frontend.payment.payu_form', compact(
            'key', 'txnid', 'amount', 'productinfo', 'firstname', 'email', 'hash'
        ));
    }


public function paymentSuccess(Request $request)
{
    $txnid  = $request->txnid;
    $status = $request->status;

    $agent = AgentJoin::where('txnid', $txnid)->first();

    if ($agent && strtolower($status) == 'success') {
        $agent->update([
            'payment_status' => 'success',
            'payment_id'     => $request->mihpayid,
        ]);

        // Force login with remember = true
        auth()->loginUsingId($agent->user_id, true);
        auth()->user()->update(['is_first_login' => 1]);
    }

    return redirect()->route('agent.join')
        ->with('success', 'Payment successful! You are now registered as Agent.');
}

public function paymentFail(Request $request)
{
    $txnid = $request->txnid;
    $agent = AgentJoin::where('txnid', $txnid)->first();

    if ($agent) {
        $agent->update(['payment_status' => 'failed']);
        // Force login with remember = true
        auth()->loginUsingId($agent->user_id, true);
    }

    return redirect()->route('agent.join')
        ->with('error', 'Payment failed! Please try again.');
}

        public function skipAgentJoin()
{
    $user = auth()->user();
    $user->is_first_login = 1;
    $user->save();
    
    return redirect()->route('dashboard');
}

public function export(Request $request)
{
    return Excel::download(new AgentJoinExport($request->all()), 'agent-join-list.xlsx');
}

public function paymentWebhook(Request $request)
{
    \Log::info('PayU Webhook Hit:', $request->all());

    $txnid  = $request->txnid;
    $status = $request->status;

    $agent = AgentJoin::where('txnid', $txnid)->first();

    if ($agent && strtolower($status) == 'success') {
        $agent->update([
            'payment_status' => 'success',
            'payment_id'     => $request->mihpayid,
        ]);
    }

    return response()->json(['status' => 'ok'], 200);
}



public function import(Request $request)
{
    $request->validate([
        'import_file' => 'required|mimes:xlsx,xls,csv|max:2048'
    ]);

    try {
        Excel::import(new AgentJoinImport, $request->file('import_file'));

        return back()->with('success', 'Emails imported & status updated successfully!');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}


}