<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

  <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

  <div class="{{$viewClass['field']}}">
    <div
      class="input-group"
      data-block="field-select"
      data-options-field-select='{!! $dataSet !!}'>

      @include('admin::form.error')

      @if ($prepend)
        <span class="input-group-addon">{!! $prepend !!}</span>
      @endif

      <select class="form-control {{$class}}" style="width: 100%;" name="{{$name}}" {!! $attributes !!} >
        @if($groups)
          @foreach($groups as $group)
            <optgroup label="{{ $group['label'] }}">
              @foreach($group['options'] as $select => $option)
                <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
              @endforeach
            </optgroup>
          @endforeach
        @else
          <option value=""></option>
          @foreach($options as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
          @endforeach
        @endif
      </select>

      @if ($append)
        <span class="input-group-addon clearfix">{!! $append !!}</span>
      @endif

      @include('admin::form.help-block')

    </div>
  </div>
</div>
