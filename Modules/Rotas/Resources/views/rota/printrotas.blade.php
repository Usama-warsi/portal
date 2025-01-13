
<form method="POST" action="{{ route('rotas.print') }}">
    @csrf
    <div class="modal-body">
     <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
             {{ Form::hidden('week', $week) }}
             {{ Form::hidden('create_by', $create_by) }}
             {{ Form::hidden('designation_id', $designation_id) }}

             {{ Form::label('', __('Select User'), ['class' => 'form-control-label mb-4 h6 d-block']) }}

             @if (!empty($user_array) && count($user_array) > 0)
                @foreach ($user_array as $key => $val)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input user_checkbox" id="{{ 'emp_'.$val['id'] }}" name="user[{{ $key }}]" type="checkbox" value="{{ $val['id'] }}" checked>
                        <label class="form-check-label" for="{{ 'emp_'.$val['id'] }}"> {{ $val['name'] }} </label>
                    </div>
                @endforeach
            @else
                <p>{{ __('No user found.') }}</p>
            @endif
         </div>
        </div>
    </div>

         <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary">{{ __('Print') }}</button>
        </div>
</form>
