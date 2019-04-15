<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class PaymentsController extends Controller
{
    public function pay(Request $request)
    {
    	$user = Auth::user();
    	if ($user->subscribed($request->plan)) {
    		$user->subscription($request->plan)->swap($request->plan_id);
    	} else {
    		$user->newSubscription($request->plan, $request->plan_id)->withCoupon($request->coupon)->create($request->stripeToken);
    		//$user->newSubscription($request->plan, $request->plan_id)->trialDays(14)->create($request->stripeToken);
    	}
    	return redirect('/home');
    }

    public function cancel(Request $request)
    {
    	$user = Auth::user();
    	$user->subscription($request->plan)->cancel(); //use cancelNow() instead of cancel() to remove from Stripe immediately
    	return redirect('/home');
    }

    public function invoice(Request $request)
    {
    	return $request->user()->downloadInvoice($request->invoice_id, [
			'vendor' => 'Cheap Movies Store',
			'product' => 'Cheap Movies Subscription',
    	]);
    }
}
