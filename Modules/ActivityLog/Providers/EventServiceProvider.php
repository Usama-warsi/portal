<?php

namespace Modules\ActivityLog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\ActivityLog\Listeners\InvoicePaymentLis;
use Modules\Skrill\Events\SkrillPaymentStatus;
use Modules\SSPay\Events\SSpayPaymentStatus;
use Modules\Stripe\Events\StripePaymentStatus;
use Modules\Payfast\Events\PayfastPaymentStatus;
use Modules\Paypal\Events\PaypalPaymentStatus;
use Modules\Paystack\Events\PaystackPaymentStatus;
use Modules\PayTab\Events\PaytabPaymentStatus;
use Modules\Paytm\Events\PaytmPaymentStatus;
use Modules\PayTR\Events\PaytrPaymentStatus;
use Modules\Mercado\Events\MercadoPaymentStatus;
use Modules\Mollie\Events\MolliePaymentStatus;
use Modules\Benefit\Events\BenefitPaymentStatus;
use Modules\Cashfree\Events\CashfreePaymentStatus;
use Modules\Toyyibpay\Events\ToyyibpayPaymentStatus;
use Modules\Razorpay\Events\RazorpayPaymentStatus;
use Modules\Flutterwave\Events\FlutterwavePaymentStatus;
use Modules\Iyzipay\Events\IyzipayPaymentStatus;
use Modules\Coingate\Events\CoingatePaymentStatus;
use Modules\AamarPay\Events\AamarPaymentStatus;
use Modules\YooKassa\Events\YooKassaPaymentStatus;
use App\Events\BankTransferPaymentStatus;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            __DIR__ . '/../Listeners',
        ];
    }
    protected $listen = [

    // Invoice payment
        StripePaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        SSpayPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        PaytrPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        PaypalPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        CashfreePaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        ToyyibpayPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        RazorpayPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        PaystackPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        MolliePaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        MercadoPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        FlutterwavePaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        PaytmPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        PayfastPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        IyzipayPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        PaytabPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        CoingatePaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        SkrillPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        AamarPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        BenefitPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        YooKassaPaymentStatus::class => [
            InvoicePaymentLis::class
        ],
        BankTransferPaymentStatus::class => [
            InvoicePaymentLis::class
        ],

    ];
}
