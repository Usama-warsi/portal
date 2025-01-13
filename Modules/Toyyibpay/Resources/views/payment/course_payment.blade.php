<div class="payment-method">
    <div class="payment-title d-flex align-items-center justify-content-between">
        <h4>{{ __('Toyyibpay') }}</h4>
        <div class="payment-image extra-size d-flex align-items-center">
            <img src="{{ asset('Modules/LMS/Resources/assets/img/toyyibpay.png') }}" alt="Toyyibpay">
        </div>
    </div>
    <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Toyyibpay to finish complete your purchase.') }}
    </p>
    <form method="POST" action="{{ route('course.pay.with.toyyibpay', $store->slug) }}"
        class="payment-method-form">
        @csrf
        <input type="hidden" name="desc" value="{{ time() }}">
        <div class="form-group text-right">
            <button type="submit" class="btn">{{ __('Pay Now') }}</button>
        </div>
    </form>
</div>
