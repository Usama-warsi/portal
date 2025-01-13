    <div class="table-responsive">
        <table class="table" style="width:auto">
            <tbody class=""> 
                <tr>
                    <th class="border-top-0">{{ __('Title') }}</th>
                    <td class="border-top-0"> {{ $meeting->title }}</td>
                </tr>
                <tr>
                    <th class="border-top-0">{{ __('Description') }}</th>
                    <td class="border-top-0"> {{ $meeting->description }}</td>
                </tr>
                <tr>
                    <th>{{ _('Meeting Date/Time') }}</th>
                    <td>{{ company_date_formate($meeting->start_date) }} / {{ company_Time_formate($meeting->start_date) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Duration') }}</th>
                    <td>{{ $meeting->duration }} {{ __('minutes') }}</td>
                </tr>
                <tr>
                    <th>{{ __('Meeting Status') }}</th>
                    <td class="leave-badge">
                        <span class="badge fix_badges bg-success p-2 px-3 rounded">{{ __($meeting->status) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Join URL') }}</th>
                    <td class="zoom_modal_url_link">
                        <a href="{{ $meeting->join_url }}" target="_blank">
                            {{ $meeting->join_url }} </a>
                    </td>
                </tr>
                @if (Auth::user()->id == $meeting->created_by)
                    <tr>
                        <th>{{ __('Start URL') }}</th>
                        <td class="zoom_modal_url_link">
                            <a href="{{ $meeting->start_url }}" target="_blank">{{ $meeting->start_url }}</a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
