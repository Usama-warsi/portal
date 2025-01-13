@if($type == 'list')
    @if($proposal->is_convert_retainer==0 && $proposal->is_convert==0)
        @can('retainer convert invoice')
            <div class="action-btn bg-success ms-2">
                {!! Form::open(['method' => 'get', 'route' => ['retainer.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                <a href="#"
                   class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                   data-bs-toggle="tooltip" title="{{__('Convert into Retainer')}}"
                   data-bs-original-title="{{ __('Convert to Retainer') }}"
                   aria-label="Delete"
                   data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                   data-confirm-yes="proposal-form-{{ $proposal->id }}">
                    <i class="ti ti-exchange text-white"></i>
                </a>
            </div>
        @endcan
    @elseif($proposal->is_convert_retainer ==1)
        @can('retainer convert invoice')
            <div class="action-btn bg-dark ms-2">
                <a href="{{ route('retainer.show',\Crypt::encrypt($proposal->converted_retainer_id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Retainer')}}" data-original-title="{{__('Already convert to Invoice')}}" data-original-title="{{__('Delete')}}">
                    <i class="ti ti-eye text-white"></i>
                </a>
            </div>
        @endcan
    @endif


@elseif ($type == 'view')

@if($proposal->is_convert_retainer==0 && $proposal->is_convert==0)
    @can('proposal convert invoice')
        <div class="action-btn ms-2">
            {!! Form::open(['method' => 'get', 'route' => ['retainer.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                <a href="#"
                    class="btn btn-sm bg-success align-items-center bs-pass-para show_confirm"
                    data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Convert to Retainer')}}"
                    aria-label="Delete" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="proposal-form-{{$proposal->id}}">
                <i class="ti ti-exchange text-white"></i>
                </a>
            {{Form::close()}}
        </div>
    @endcan
    @elseif($proposal->is_convert_retainer ==1)

    @can('retainer convert invoice')
        <div class="action-btn ms-2">
            <a href="{{ route('retainer.show',\Crypt::encrypt($proposal->converted_retainer_id)) }}" class="btn btn-sm bg-success align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Retainer')}}" >
                <i class="ti ti-eye text-white"></i>
            </a>
        </div>
    @endcan
    @endif


@elseif ($type == 'grid')

@if($proposal->is_convert_retainer==0 && $proposal->is_convert==0)

    @can('proposal convert invoice')
        {!! Form::open([
            'method' => 'get',
            'route' => ['retainer.convert', $proposal->id],
            'id' => 'proposal-form-' . $proposal->id,
        ]) !!}
        <a href="#!" class="show_confirm dropdown-item" data-confirm-yes="proposal-form-{{ $proposal->id }}">
            <i class="ti ti-exchange"></i>{{ __('Convert to Retainer') }}
        </a>
        {{ Form::close() }}
    @endcan
    @elseif($proposal->is_convert_retainer ==1)

    @can('retainer convert invoice')
        <a href="{{ route('retainer.show', \Crypt::encrypt($proposal->converted_retainer_id)) }}"
            class="dropdown-item">
            <i class="ti ti-eye"></i>{{ __('View Retainer') }}
        </a>
    @endcan
    @endif

@endif





