

                <table id="t01" style="margin: 20px 15px;">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            @foreach ($week_date_formate as $date)
                                <th>{{ $date }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($users_name))
                            @foreach ($users_name as $item)
                            <tr>
                                <td>{{ $item->first_name }}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[0], $item->id, $location) !!}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[1], $item->id, $location) !!}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[2], $item->id, $location) !!}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[3], $item->id, $location) !!}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[4], $item->id, $location) !!}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[5], $item->id, $location) !!}</td>
                                <td>{!! \App\Models\Rotas::getdaterotas($week_date[6], $item->id, $location) !!}</td>
                            </tr>
                            @endforeach
                        @else 
                            <tr> <td colspan="8"> <h2> <center> {{ __('No Data Found') }}  </center> </h2> </td> </tr>
                        @endif
                    </tbody>                                    
                </table>