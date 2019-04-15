@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::user()->subscribed('cheap-movies') && Auth::user()->subscription('cheap-movies')->onGracePeriod())
                        <div class="alert alert-danger">
                            You have cancelled but still on prepaid time on your subscription.
                        </div>
                    @endif

                    <h4>Current Subscriptions</h4>

                    @if(Auth::user()->subscribed('cheap-movies'))
                        <p>You are subscribed <b>{{ (Auth::user()->subscription('cheap-movies')->stripe_plan=='plan_Et6TWyDZKDIyKK'?'Monthly':'Yearly') }}</b> to cheap movies </p>


                        <hr />
                        <h4>Invoices</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice Date</th>
                                    <th>Total</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Auth::user()->invoices() as $invoice)
                                    <tr>
                                        <td>{{ $invoice->date }}</td>
                                        <td>{{ $invoice->total() }}</td>
                                        <td>
                                            <a href="/user/invoice/{{ $invoice->id }}">Download</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr />
                        @if (!Auth::user()->subscription('cheap-movies')->onGracePeriod())
                            @if (Auth::user()->subscribedToPlan('plan_Et6TWyDZKDIyKK', 'cheap-movies'))
                                <a href="/pay/cheap-movies/plan_Et6UOJucgBgaZp" class="btn btn-small btn-primary">Upgrade to Annual $10</a>
                            @elseif (Auth::user()->subscribedToPlan('plan_Et6UOJucgBgaZp', 'cheap-movies'))
                                <a href="/pay/cheap-movies/plan_Et6TWyDZKDIyKK" class="btn btn-small btn-primary">Downgrade to Monthly $1</a>
                            @endif
                            <a href="/cancel/cheap-movies" class="btn btn-small btn-danger">Cancel Subscription</a>
                        @endif
                    @else
                        <p>No subscriptions yet...</p>
                        <form action="/pay/cheap-movies/plan_Et6TWyDZKDIyKK" method="POST" >
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-xs-4">
                                    <input name="coupon" class="form-control" id="ex1" type="text" placeholder="Coupon for Monthly" />
                                </div>
                                <div class="col-xs-8">
                                    <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="{{ env('STRIPE_KEY') }}"
                                        data-amount="100"
                                        data-name="Cheap Movies"
                                        data-description="Monthly Subscription"
                                        data-label="Subscribe Monthly $1"
                                        data-image="https://dumielauxepices.net/sites/default/files/popcorn-clipart-logo-729297-8395733.png"
                                        data-locale="auto">
                                    </script>
                                </div>
                            </div>
                        </form>
                        <form action="/pay/cheap-movies/plan_Et6UOJucgBgaZp" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <div class="col-xs-4">
                                    No Coupon Available
                                </div>
                                <div class="col-xs-8">
                                    <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="{{ env('STRIPE_KEY') }}"
                                        data-amount="1000"
                                        data-name="Cheap Movies"
                                        data-description="Yearly Subscription"
                                        data-label="Subscribe Yearly $10"
                                        data-image="https://dumielauxepices.net/sites/default/files/popcorn-clipart-logo-729297-8395733.png"
                                        data-locale="auto">
                                    </script>
                                </div>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
