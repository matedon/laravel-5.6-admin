<div class="box">
    @if(isset($title))
    <div class="box-header with-border">
        <h3 class="box-title"> {{ $title }}</h3>
    </div>
    @endif

    <div class="box-header with-border">
        <div class="pull-right">
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>
        <span>
            {!! $grid->renderHeaderTools() !!}
        </span>
    </div>

    {!! $grid->renderFilter() !!}

    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <tr>
                @foreach($grid->columns() as $column)
                <th>{{$column->getLabel()}}{!! $column->sorter() !!}</th>
                @endforeach
            </tr>

            @foreach($grid->rows() as $row)
            <tr {!! $row->getRowAttributes() !!}>
                @foreach($grid->columnNames as $name)
                <td {!! $row->getColumnAttributes($name) !!}>
                    {!! $row->column($name) !!}
                </td>
                @endforeach
            </tr>
            @endforeach

            {!! $grid->renderFooter() !!}

        </table>
    </div>
    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>

<script>
  $('[data-event="row-delete"]').off('click').on('click', function () {
    var url = $(this).data('url');
    swal({
        title: "{{ trans('admin.delete_confirm') }}",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "{{ trans('admin.confirm') }}",
        closeOnConfirm: false,
        cancelButtonText: "{{ trans('admin.cancel') }}"
      },
      function () {
        $.ajax({
          method: 'post',
          url: url,
          data: {
            _method: 'delete',
            _token: LA.token,
          },
          success: function (data) {
            $.pjax.reload('#pjax-container');

            if (typeof data === 'object') {
              if (data.status) {
                swal(data.message, '', 'success');
              } else {
                swal(data.message, '', 'error');
              }
            }
          }
        });
      });
  });
</script>
