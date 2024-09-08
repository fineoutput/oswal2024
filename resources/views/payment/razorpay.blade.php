<form action="{{ route('checkout.verifypayment') }}" method="POST" id="razorpayform">

    {{-- @dd($data); --}}

    @csrf
    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ config('services.razorpay.key_id') }}"
        data-amount="{{ $data['amount'] * 100 }}" data-currency="INR" data-order_id="{{ $data['razor_order_id'] }}"
        data-buttontext="Pay with Razorpay" data-name="Your Company" data-description="Payment for your purchase"
        data-theme.color="#F37254"></script>
</form>
