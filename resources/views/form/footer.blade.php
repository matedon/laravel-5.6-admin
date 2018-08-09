<div class="box-footer">

  {{ csrf_field() }}

  <div class="col-md-{{$width['label']}}">
  </div>

  <div class="col-md-{{$width['field']}}">

    @if(in_array('submit', $buttons))
      <div class="btn-group pull-right">
        <button class="btn btn-primary">{{ trans('admin.submit') }}</button>
      </div>

      <label class="pull-right" style="margin: 5px 10px 0 0;">
        <input type="checkbox" class="after-submit" name="after-save" value="1">
        &nbsp;
        {{ trans('admin.continue_editing') }}
      </label>

      @if(config('admin.actions.show'))
        <label class="pull-right" style="margin: 5px 10px 0 0;">
          <input type="checkbox" class="after-submit" name="after-save" value="2">
          &nbsp;
          {{ trans('admin.view') }}
        </label>
      @endif

    @endif

    @if(in_array('reset', $buttons))
      <div class="btn-group pull-left">
        <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
      </div>
    @endif
  </div>
</div>