<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >
            @foreach($options as $k => $v)
                @if(in_array($v,$value))
                    <option value="{{$k}}" selected>{{$v}}</option>
                @else
                    <option value="{{$k}}">{{$v}}</option>
                @endif
            @endforeach
        </select>
        <input type="hidden" name="{{$name}}[]" />
        @include('admin::form.help-block')

    </div>
</div>
