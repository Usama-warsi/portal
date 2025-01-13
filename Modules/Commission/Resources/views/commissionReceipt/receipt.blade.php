<div class="modal-body">
    <div class="row">
        <div class="col-form-label">
            <div class="text-md-end mb-2">
                <a class="btn btn-sm btn-primary text-white" data-bs-toggle="tooltip" data-bs-toggle="bottom"
                    title="{{ __('Download') }}" onclick="saveAsPDF()"><span class="ti ti-download"></span></a>
            </div>

            <div class="invoice" id="printableArea">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-number">
                                <img src="{{ get_file(sidebar_logo()) }}" width="170px;">
                            </div>
                            <div class="invoice-title">
                            </div>
                            <hr>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    @php
                                        $agent = App\Models\User::where('id', $commissionReceipt->agent)
                                            ->pluck('name')
                                            ->first();
                                    @endphp
                                    <address>
                                        <strong>{{ __('Name') }} :</strong> {{ $agent }}<br>
                                        <strong>{{ __('Position') }} :</strong> {{ __('Employee') }}<br>
                                        <strong>{{ __('Receipt Date') }} :</strong>
                                        {{ company_date_formate($commissionReceipt->created_at) }}<br>
                                    </address>
                                </div>
                                <div class="col-md-6 text-end">
                                    <address>
                                        <strong>{{ !empty(company_setting('company_name')) ? company_setting('company_name') : '' }}
                                        </strong><br>
                                        {{ !empty(company_setting('company_address')) ? company_setting('company_address') : '' }}
                                        ,
                                        {{ !empty(company_setting('company_city')) ? company_setting('company_city') : '' }},<br>
                                        {{ !empty(company_setting('company_state')) ? company_setting('company_state') : '' }}<br>

                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table  table-md">
                                    <tbody>
                                        <tr class="font-bold">
                                            <th>{{ __('Id') }}</th>
                                            <th>{{ __('Item') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('quantity') }}</th>
                                            <th>{{ __('discount') }}</th>
                                            <th class="text-right">{{ __('Amount') }}</th>
                                        </tr>
                                        @php
                                            $amount = $subtotal = $totalDiscount =0;
                                        @endphp
                                        @foreach ($invoiceProducts as $key => $invoiceProduct)
                                            @php
                                                $amount += ($invoiceProduct->price * $invoiceProduct->quantity) - $invoiceProduct->discount;
                                                $subtotal += $invoiceProduct->price * $invoiceProduct->quantity;
                                                $totalDiscount += $invoiceProduct->discount;
                                            @endphp
                                            <tr>
                                                <td>{{ $key +1 }}</td>
                                                <td>{{ optional( Modules\ProductService\Entities\ProductService::find($invoiceProduct->product_id))->name  }}</td>
                                                <td>{{ $invoiceProduct->price }}</td>
                                                <td>{{ $invoiceProduct->quantity }}</td>
                                                <td>{{ $invoiceProduct->discount }}</td>
                                                <td> {{$amount}} </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                            <td class="text-right"> {{$subtotal}} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-right"><b>{{__('Discount')}}</b></td>
                                            <td class="text-right"> {{$totalDiscount}} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-right"><b>{{__('Total')}}</b></td>
                                            <td class="text-right"> {{$subtotal+$totalDiscount}} </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-right"><b>{{__('Commission')}}</b></td>
                                            <td class="text-right"> {{$commissionReceipt->amount}} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

            </div>
        </div>
    </div>
</div>
<script src="{{ asset('Modules/Commission/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>

<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: '{{ $commissionReceipt->id }}',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
