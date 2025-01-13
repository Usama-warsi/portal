@extends('layouts.main')

@section('page-title')
    {{__('Manage Forms')}}
@endsection

@push('scripts')
    <script>
        $(document).on("click",".cp_link",function() {
            var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
        
         function CP_LINK(el) {
                var value = $(el).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
               toastrs('success', '{{__('Copy on Clipboard Success')}}', 'success')
            };
    </script>
 
@endpush

@section('page-action')
    <div class="row align-items-center m-1">
        <div class="col-auto pe-0">
            <a class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Form')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Form')}}" data-url="{{route('form_builder.create')}}"><i class="ti ti-plus text-white"></i></a>
        </div>
    </div>

@endsection

@section('page-breadcrumb')
   {{__('Form Builder')}}
@endsection


@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th width="50%">{{__('Name')}}</th>
                                    <th width="25%">{{__('Response')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($forms as $form)
                               
                                @for($i=0;$i < sizeof($users);$i++)
                                 @if($users[$i]['form_id'] == $form->code && \Auth::user()->type!='company' )
                                <tr>
                                        <td>{{ $form->name }}</td>
                                        <td>{{ $form->response->count() }}</td>
                                        <td class="Action">
                                            @if(Auth::user()->isAbleTo('formbuilder create'))
                                             <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('user.list',$form->code) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Allow user')}}" data-title="{{__('Allow User To ')}}{{ $form->name }}">
                                                    <i class="ti ti-user text-white"></i>
                                                </a>
                                            </div>
                                                 <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('form_builder.show',$form->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit/View Form field')}}" class="btn btn-icon btn-sm" data-toggle="tooltip" data-original-title="{{__('Edit/View Form field')}}"><i class="ti ti-table text-white"></i></a>
                                            </div>
                                             @endif
                                             <div class="action-btn bg-primary ms-2">
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Click to copy link')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link" data-link="{{route('form.view',$form->code)}}" data-toggle="tooltip" data-original-title="{{__('Click to copy link')}}"><i class="ti ti-file text-white"></i></a>
                                             </div>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " onclick="CP_LINK(this)" data-link="
                                               <script>
                                                     document.addEventListener('DOMContentLoaded', function () {
                                                         var forms = document.querySelectorAll('form');
                                                         forms.forEach(function (form) {
                                                       form.addEventListener('submit', function (event) {
                                                         event.preventDefault();
                                                           jqury(form);
                                                              });
                                                             });
                                                                    });
                                                           function jqury(el) {
                                                              const loaderDiv = document.createElement('div');
                                                                  loaderDiv.setAttribute('id', 'form-submitting-error');
                                                                      loaderDiv.setAttribute('style', `
                                                                         position:fixed;
                                                                         width:100%;
                                                                         height:100%;
                                                                         background:#ffffffc7;
                                                                         top:0;
                                                                         left:0;
                                                                         z-index:1000;
                                                                         display:flex;
                                                                         flex-direction:column;
                                                                         justify-content:center;
                                                                         align-items:center;
                                                                     `);
                                                                 loaderDiv.innerHTML = `
                                                            <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 200 200'>
                                                                <linearGradient id='a11'>
                                                                    <stop offset='0' stop-color='#000000' stop-opacity='0'></stop>
                                                                    <stop offset='1' stop-color='#000000'></stop>
                                                                </linearGradient>
                                                                <circle 
                                                                    fill='none' 
                                                                    stroke='url(#a11)' 
                                                                    stroke-width='15' 
                                                                    stroke-linecap='round' 
                                                                    stroke-dasharray='0 44 0 44 0 44 0 44 0 360' 
                                                                    cx='100' 
                                                                    cy='100' 
                                                                    r='70' 
                                                                    transform-origin='center'>
                                                                    <animateTransform 
                                                                        type='rotate' 
                                                                        attributeName='transform' 
                                                                        calcMode='discrete' 
                                                                        dur='2' 
                                                                        values='360;324;288;252;216;180;144;108;72;36' 
                                                                        repeatCount='indefinite'>
                                                                    </animateTransform>
                                                                </circle>
                                                            </svg>
                                                            <h5>SUBMITTING FORM</h5>
                                                        `;
       
                                                        document.body.appendChild(loaderDiv);
                                                        var url =
                                                            '{{ route('form.store',$form->code) }}';
                                                        var formData = new FormData(el);
                                                        formData.append('additional', 'true');
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.open('POST', url, true);
                                                        xhr.onload = function () {
                                                            if (xhr.status >= 200 && xhr.status < 300) {
                                                                console.log('Request successful. Response: ', xhr.responseText);
                                                                const loader = document.getElementById('form-submitting-error');
                                                                if (loader) {
                                                                    loader.remove();
                                                                }
                                                                el.submit();
                                                            } else {
                                                                console.error('Request failed with status ' + xhr.status);
                                                                const loader = document.getElementById('form-submitting-error');
                                                                if (loader) {
                                                                    loader.remove();
                                                                }
                                                                el.submit();
                                                            }
                                                        };
                                                        xhr.send(formData);
                                                 }
                                                </script>
                                                " data-bs-toggle="tooltip" title="{{__('Click to copy Javascript Code')}}"><i class="ti ti-code text-white"></i></a>
                                            </div>
                                       
                                           
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('form.response',$form->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('View Response')}}" class="btn btn-icon btn-sm" data-toggle="tooltip" data-original-title="{{__('View Response')}}"><i class="ti ti-eye text-white"></i></a>
                                            </div>
                                             @if(Auth::user()->isAbleTo('formbuilder create'))
                                            <div class="action-btn bg-success ms-2">
                                                <a class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Convert To')}}"  data-url="{{ route('form.field.bind',$form->id) }}" data-ajax-popup="true" data-title="{{__('Convert To')}}" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Convert To')}}"><i class="ti ti-exchange text-white"></i></a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Form')}}"  data-url="{{ URL::to('form_builder/'.$form->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Form')}}" class="btn btn-icon btn-sm" ><i class="ti ti-pencil text-white"></i></a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['form_builder.destroy', $form->id]]) !!}
                                                    <a href="#!" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete Form')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm">
                                                       <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                {!! Form::close() !!}
                                            </div>
                                            @endif
                                        </td>
                                </tr>
                                    @endif
                                
                                @endfor
                                
                                
                                  @if(\Auth::user()->type=='company' )
                                  
                                    <tr>
                                        <td>{{ $form->name }}</td>
                                        <td>{{ $form->response->count() }}</td>
                                        <td class="Action">
                                            @if(Auth::user()->isAbleTo('formbuilder create'))
                                             <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('user.list',$form->code) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Allow user')}}" data-title="{{__('Allow User To ')}}{{ $form->name }}">
                                                    <i class="ti ti-user text-white"></i>
                                                </a>
                                            </div>
                                             <div class="action-btn bg-primary ms-2">
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Click to copy link')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link" data-link="{{route('form.view',$form->code)}}" data-toggle="tooltip" data-original-title="{{__('Click to copy link')}}"><i class="ti ti-file text-white"></i></a>
                                             </div>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " onclick="CP_LINK(this)" data-link="
                                               <script>
                                                     document.addEventListener('DOMContentLoaded', function () {
                                                         var forms = document.querySelectorAll('form');
                                                         forms.forEach(function (form) {
                                                       form.addEventListener('submit', function (event) {
                                                         event.preventDefault();
                                                           jqury(form);
                                                              });
                                                             });
                                                                    });
                                                           function jqury(el) {
                                                              const loaderDiv = document.createElement('div');
                                                                  loaderDiv.setAttribute('id', 'form-submitting-error');
                                                                      loaderDiv.setAttribute('style', `
                                                                         position:fixed;
                                                                         width:100%;
                                                                         height:100%;
                                                                         background:#ffffffc7;
                                                                         top:0;
                                                                         left:0;
                                                                         z-index:1000;
                                                                         display:flex;
                                                                         flex-direction:column;
                                                                         justify-content:center;
                                                                         align-items:center;
                                                                     `);
                                                                 loaderDiv.innerHTML = `
                                                            <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 200 200'>
                                                                <linearGradient id='a11'>
                                                                    <stop offset='0' stop-color='#000000' stop-opacity='0'></stop>
                                                                    <stop offset='1' stop-color='#000000'></stop>
                                                                </linearGradient>
                                                                <circle 
                                                                    fill='none' 
                                                                    stroke='url(#a11)' 
                                                                    stroke-width='15' 
                                                                    stroke-linecap='round' 
                                                                    stroke-dasharray='0 44 0 44 0 44 0 44 0 360' 
                                                                    cx='100' 
                                                                    cy='100' 
                                                                    r='70' 
                                                                    transform-origin='center'>
                                                                    <animateTransform 
                                                                        type='rotate' 
                                                                        attributeName='transform' 
                                                                        calcMode='discrete' 
                                                                        dur='2' 
                                                                        values='360;324;288;252;216;180;144;108;72;36' 
                                                                        repeatCount='indefinite'>
                                                                    </animateTransform>
                                                                </circle>
                                                            </svg>
                                                            <h5>SUBMITTING FORM</h5>
                                                        `;
       
                                                        document.body.appendChild(loaderDiv);
                                                        var url =
                                                            '{{ route('form.store',$form->code) }}';
                                                        var formData = new FormData(el);
                                                        formData.append('additional', 'true');
                                                        var xhr = new XMLHttpRequest();
                                                        xhr.open('POST', url, true);
                                                        xhr.onload = function () {
                                                            if (xhr.status >= 200 && xhr.status < 300) {
                                                                console.log('Request successful. Response: ', xhr.responseText);
                                                                const loader = document.getElementById('form-submitting-error');
                                                                if (loader) {
                                                                    loader.remove();
                                                                }
                                                                el.submit();
                                                            } else {
                                                                console.error('Request failed with status ' + xhr.status);
                                                                const loader = document.getElementById('form-submitting-error');
                                                                if (loader) {
                                                                    loader.remove();
                                                                }
                                                                el.submit();
                                                            }
                                                        };
                                                        xhr.send(formData);
                                                 }
                                                </script>
                                                " data-bs-toggle="tooltip" title="{{__('Click to copy  javascript Code')}}"><i class="ti ti-code text-white"></i></a>
                                            </div>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('form_builder.show',$form->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit/View Form field')}}" class="btn btn-icon btn-sm" data-toggle="tooltip" data-original-title="{{__('Edit/View Form field')}}"><i class="ti ti-table text-white"></i></a>
                                            </div>
                                            @endif
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{route('form.response',$form->id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('View Response')}}" class="btn btn-icon btn-sm" data-toggle="tooltip" data-original-title="{{__('View Response')}}"><i class="ti ti-eye text-white"></i></a>
                                            </div>

                                            <div class="action-btn bg-success ms-2">
                                                <a class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Convert To')}}"  data-url="{{ route('form.field.bind',$form->id) }}" data-ajax-popup="true" data-title="{{__('Convert To')}}" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Convert To')}}"><i class="ti ti-exchange text-white"></i></a>
                                            </div>
                                             @if(Auth::user()->isAbleTo('formbuilder create'))
                                            <div class="action-btn bg-info ms-2">
                                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Form')}}"  data-url="{{ URL::to('form_builder/'.$form->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Form')}}" class="btn btn-icon btn-sm" ><i class="ti ti-pencil text-white"></i></a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['form_builder.destroy', $form->id]]) !!}
                                                    <a href="#!" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete Form')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm">
                                                       <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                {!! Form::close() !!}
                                            </div>
                                            
                                            @endif
                                        </td>
                                </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
