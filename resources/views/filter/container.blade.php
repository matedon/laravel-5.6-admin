<div class="box-header with-border {{ $expand ? '' : 'hide' }}" id="{{ $filterID }}"
     data-block="filter-form" data-expand="{{ $expand ? 'true' : 'false' }}">
  <form action="{!! $action !!}" class="form-horizontal" pjax-container>
    <div class="box-body">
      <div class="fields-group">
        @foreach($filters as $filter)
          {!! $filter->render() !!}
        @endforeach
      </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="btn-group pull-left">
          <button class="btn btn-info submit" type="submit"
                  data-element="filter-form__search">
            <i class="fa fa-lg fa-fw fa-search"></i>
            {{ trans('admin.search') }}
          </button>
        </div>
        <div class="btn-group pull-left " style="margin-left: 10px;">
          <a href="{!! $action !!}" class="btn btn-default">
            <i class="fa fa-lg fa-fw fa-undo"></i>
            {{ trans('admin.reset') }}</a>
        </div>
      </div>
    </div>
  </form>
</div>
