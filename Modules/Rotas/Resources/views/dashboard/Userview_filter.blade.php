
<table class="table align-items-center">
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Time') }}</th>
            <th>{{ __('Break') }}</th>
            <th>{{ __('Location') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($rotas) && count($rotas) != 0)
            @foreach ($rotas as $rota)
                <tr>
                    <th>
                        <div class="media align-items-center">
                            <div>
                                <div class="avatar-parent-child">
                                        <img src="{{ asset($rota->userprofile($rota->user_id)) }}"
                                        class="avatar rounded-circle" style="width: 40px;">
                                </div>
                            </div>
                            <div class="media-body ml-3">
                                <a href="#" class="text-dark" style="margin: 17px;">{{ !empty($rota->getrotauser) ? $rota->getrotauser->name : '-' }}</a>
                            </div>
                        </div>
                    </th>
                    <td> {{ $rota->rotas_date }} </td>
                    <td> {{ company_Time_formate($rota['start_time']) }} - {{ company_Time_formate($rota['end_time']) }} </td>
                    <td> {{ $rota->break_time . __('Min') }} </td>
                    <td> {{ !empty($rota->designation->name) ? $rota->designation->name : '-' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">
                    <div class="text-center">
                        <i class="fas fa-calendar-times text-primary fs-40"></i>
                        <h2>{{ __('Opps...') }}</h2>
                        <h6> {!! __('No rotas found.') !!} </h6>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>
