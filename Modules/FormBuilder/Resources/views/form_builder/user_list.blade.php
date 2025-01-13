 <form action="{{ route('user.list.store',$id) }}" method="POST">
       @csrf
<div class="modal-body">
    <div class="row">

            <div class="col-12 text-xs">
                 <table class="table table-striped mb-0" id="">
                                    <thead>
                                    <tr>
                                        
                                        <th>{{__('User Name')}} </th>
                                        <th>{{__('Permissions')}} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i = 1; @endphp
                                     @php $allowid = '';
                                            @endphp
                                          @foreach($users as $que)
                                   
                                              
                                            
                                            @if(!empty(in_array($que->id,$allow)))
                                                <tr>
                                        <td><h5>{{$que->name}}</h5></td>
                                     <td>
                                            <h5>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="user[{{$i}}][status]" id="userallow[{{$que->id}}]" checked >
                                            <input type="hidden" class="form-check-input align-middle custom_align_middle" name="user[{{$i}}][user_id]" value="{{$que->id}}">
                                            <input type="hidden" class="form-check-input align-middle custom_align_middle" name="user[{{$i++}}][form_id]" value="{{$id}}">
                                      </h5>  </td>
                                    </tr>           
                                    @else
                                    
                                               <tr>
                                        <td><h5>{{$que->name}}</h5></td>
                                     <td>
                                            <h5>
                                            <input type="checkbox" class="form-check-input align-middle custom_align_middle" name="user[{{$i}}][status]" id="userallow[{{$que->id}}]"  >
                                            <input type="hidden" class="form-check-input align-middle custom_align_middle" name="user[{{$i}}][user_id]" value="{{$que->id}}">
                                            <input type="hidden" class="form-check-input align-middle custom_align_middle" name="user[{{$i++}}][form_id]" value="{{$id}}">
                                      </h5>  </td>
                                    </tr>    
                                          @endif                            
                                
                                  
                                     
                                            @endforeach
                                        </tbody>
                </table>
                
                
                
            </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
 </form>