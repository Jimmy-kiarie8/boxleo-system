<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact V5</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <link rel="stylesheet" href="{{ asset('css/online-form.css') }}">
    <meta name="robots" content="noindex, follow">
</head>

<body>
    <div style="margin-left: 28%">
        <img src="{{ asset('images/logo.png') }}" alt="" style="width: 200px">
    </div>
    <div class="container-contact100">
        <div class="wrap-contact100">
            <span class="contact100-form-title">
                Boxleo Courier
                <hr>
                <small style="font-size: 15px">Order Confirmation</small>
            </span>
            <form class="contact100-form validate-form" action="{{ route('online-form', $order_no) }}" method="POST">
                @csrf
                <div class="wrap-input100 validate-input bg1">
                    <span class="label-input100">Order No</span>
                    <input class="input100" type="text" placeholder="Order No." disabled value="{{ $sale->order_no }}">
                </div>
                <div class="wrap-input100 validate-input bg1 rs1-wrap-input100">
                    <span class="label-input100">Update Address</span>
                    <input class="input100" type="text" name="address" placeholder="Enter Your Address" value="{{ $sale->client->address }}">
                </div>
                <div class="wrap-input100 validate-input bg1 rs1-wrap-input100">
                    <span class="label-input100">Delivery Date</span>
                    <input class="input100" type="date" name="delivery_date" placeholder="Delivery date">
                </div>
                <div class="wrap-input100 validate-input bg1 rs1-wrap-input100">
                    <span class="label-input100">Request a callback</span>
                    <input class="form-check-input" type="checkbox" value="callback">
                </div>
                <div class="wrap-input100 validate-input bg0 rs1-alert-validate"
                    data-validate="Please Type Your Message">
                    <span class="label-input100">Notes</span>
                    <textarea class="input100" name="note" placeholder="Your message here..."></textarea>
                </div>
                <div class="container-contact100-form-btn">
                    <button class="contact100-form-btn" type="submit">
                        <span>
                            Submit
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
