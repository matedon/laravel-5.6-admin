<a href="javascript:void(0);" class="btn btn-sm btn-danger"
   data-event="entry-delete" data-url-delete="{{ $urlDelete }}" data-callback="{{ $callback }}">
  <i class="fa fa-fw fa-lg fa-trash"></i>
  {{ $label ? trans('admin.delete') : '' }}
</a>