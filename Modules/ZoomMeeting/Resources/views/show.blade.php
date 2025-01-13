<div class="modal-body">
    <table class="table table-centered">
        <tbody>
            <tr>
                <th class="border-top-0">{{ __('Title') }}</th>
                <td class="border-top-0"> {{ $zoom_meeting->title }}</td>
            </tr>
            <tr>
                <th>{{ _('Meeting Date/Time') }}</th>
                <td>{{ company_date_formate($zoom_meeting->start_date) }} / {{ company_Time_formate($zoom_meeting->start_date) }}</td>
            </tr>
            <tr>
                <th>{{ __('Duration') }}</th>
                <td>{{ $zoom_meeting->duration }} {{ __('minutes') }}</td>
            </tr>
            @if (!empty($zoom_meeting->password))
                <tr>
                    <th>{{ __('Password') }}</th>
                    <td>{{ $zoom_meeting->password }}</td>
                </tr>
            @endif
            <tr>
                <th>{{ __('Meeting Status') }}</th>
                @if ($zoom_meeting->status == 'waiting')
                    <td class="leave-badge">
                        <span class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __('Waiting') }}</span>
                    </td>
                @elseif ($zoom_meeting->status == 'start')
                    <td class="leave-badge">
                        <span class="badge fix_badges bg-success p-2 px-3 rounded">{{ __('Start') }}</span>
                    </td>
                @else
                    <td class="leave-badge">
                        <span class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __($zoom_meeting->status) }}</span>
                    </td>
                @endif
            </tr>
            <tr>
                <th>{{ __('Join URL') }}</th>
                <td class="zoom_modal_url_link">
                    <a href="{{ $zoom_meeting->join_url }}" target="_blank">
                        {{ $zoom_meeting->join_url }} </a>
                </td>
            </tr>
            @if (Auth::user()->id == $zoom_meeting->created_by)
                <tr>
                    <th>{{ __('Start URL') }}</th>
                    <td class="zoom_modal_url_link">
                        <a href="{{ $zoom_meeting->start_url }}" target="_blank">{{ $zoom_meeting->start_url }}</a>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
