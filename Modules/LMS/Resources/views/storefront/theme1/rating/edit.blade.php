{{Form::model($rating, array('route' => array('rating.update', $rating->id), 'method' => 'PUT')) }}
    <div class="input-wrapper">
            {{Form::label('name',__('Name')) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
    </div>
    <div class="input-wrapper">
            {{Form::label('title',__('Title')) }}
            {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Title'),'required'=>'required'))}}
    </div>
    <div class="input-wrapper">
        {{Form::label('title',__('Ratting')) }}
        <div id="rating_div">
            <div class="rate pl-0">
                <input type="radio" class="rating" id="star5" name="rate" value="5" {{($rating->ratting == '5')?'checked':''}}>
                <label for="star5" title="text"></label>
                <input type="radio" class="rating" id="star4" name="rate" value="4" {{($rating->ratting == '4')?'checked':''}}>
                <label for="star4" title="text"></label>
                <input type="radio" class="rating" id="star3" name="rate" value="3" {{($rating->ratting == '3')?'checked':''}}>
                <label for="star3" title="text"></label>
                <input type="radio" class="rating" id="star2" name="rate" value="2" {{($rating->ratting == '2')?'checked':''}}>
                <label for="star2" title="text"></label>
                <input type="radio" class="rating" id="star1" name="rate" value="1" {{($rating->ratting == '1')?'checked':''}}>
                <label for="star1" title="text"></label>
            </div>
        </div>
    </div>
    <div class="checkbox-custom">
        <input type="checkbox" id="enable_rating" name="rating_view" class="rating_view" {{($rating->rating_view == 'on')?'checked':''}}>
        <label for="enable_rating" id="enable_rating" class="rating_view">{{__('Enable Rating')}}</label>
    </div>
    <div class="input-wrapper">
        <div class="form-group">
            {{Form::label('description',__('Description')) }}
            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Description'),'required'=>'required'))}}
        </div>
    </div>
    <div class="form-footer">
        <button type="submit" class="btn btn-sm btn-primary rounded-pill mr-auto" id="saverating">{{ __('Save Changes') }}</button>
    </div>
{{Form::close()}}
