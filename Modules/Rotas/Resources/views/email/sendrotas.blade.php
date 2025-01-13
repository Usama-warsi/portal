<html>
   <head>
      <style>
         table {
           font-family: arial, sans-serif;
           border-collapse: collapse;
           width: 100%;
         }

         td, th {
           text-align: left;
           padding: 8px;
         }

         tr:nth-child(even) {
           background-color: #dddddd;
         }
         </style>
   </head>
   <body>
      <div style="background-color:#f8f8f8">
         <h6> {{ __('Details of your rotas for this week.') }} </h6>
            <table>
               <thead>
                  <tr>
                     <td ></td>
                     <td >{{ __('Date') }}</td>
                     <td >{{ __('Time') }}</td>
                     <td >{{ __('Designation') }}</td>
                     <td >{{ __('Note') }}</td>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($rotas_data as $key => $data)
                  <tr>
                      <td> {{ ++$key }}</td>
                      <td> {{ $data['rotas_date'] }} </td>
                      <td> {{ $data['start_time'].' - '.$data['end_time'] }} </td>
                      <td> {{ (!empty($location_datas[$data['designation_id']])) ? $location_datas[$data['designation_id']] : '' }}</td>
                     <td>{{ $data['note'] }}</td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
      </div>
   </body>
</html>
