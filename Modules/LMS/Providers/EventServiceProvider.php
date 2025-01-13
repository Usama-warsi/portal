<?php

namespace Modules\LMS\Providers;

use App\Events\CreatePaymentInvoice;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;
use Modules\AamarPay\Events\AamarPaymentStatus;
use Modules\ActivityLog\Listeners\CreatePaymentInvoiceLis;
use Modules\Benefit\Events\BenefitPaymentStatus;
use Modules\Cashfree\Events\CashfreePaymentStatus;
use Modules\Coingate\Events\CoingatePaymentStatus;
use Modules\Flutterwave\Events\FlutterwavePaymentStatus;
use Modules\Iyzipay\Events\IyzipayPaymentStatus;
use Modules\LMS\Listeners\InvoicepaymentLis;
use Modules\Mercado\Events\MercadoPaymentStatus;
use Modules\Mollie\Events\MolliePaymentStatus;
use Modules\PabblyConnect\Listeners\CompanyPaymentLis;
use Modules\Payfast\Events\PayfastPaymentStatus;
use Modules\Paypal\Events\PaypalPaymentStatus;
use Modules\Paystack\Events\PaystackPaymentStatus;
use Modules\PayTab\Events\PaytabPaymentStatus;
use Modules\Paytm\Events\PaytmPaymentStatus;
use Modules\PayTR\Events\PaytrPaymentStatus;
use Modules\Razorpay\Events\RazorpayPaymentStatus;
use Modules\Skrill\Events\SkrillPaymentStatus;
use Modules\SSPay\Events\SSpayPaymentStatus;
use Modules\Stripe\Events\StripePaymentStatus;
use Modules\Toyyibpay\Events\ToyyibpayPaymentStatus;
use Modules\YooKassa\Events\YooKassaPaymentStatus;

class EventServiceProvider extends Provider
{
    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    protected $listen = [
        CreatePaymentInvoice::class => [
            InvoicepaymentLis::class
        ],
        StripePaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        PaypalPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        PaytabPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        CoingatePaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        PaytmPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        MercadoPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        FlutterwavePaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        PayfastPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        MolliePaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        PaystackPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        RazorpayPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        SkrillPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        IyzipayPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        ToyyibpayPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        SSpayPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        YooKassaPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        PaytrPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        AamarPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        CashfreePaymentStatus::class => [
            InvoicepaymentLis::class
        ],
        BenefitPaymentStatus::class => [
            InvoicepaymentLis::class
        ],
    ];

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
}
