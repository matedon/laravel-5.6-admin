@extends('admin::index')

@section('content')
  <section class="content-header">
    <h1>
      <span data-element="title-part" data-level="main">
        {{ $header ? $header : trans('admin.title') }}
      </span>
      <small data-element="title-part" data-level="bracket">
        {{ $description ? $description : trans('admin.description') }}
      </small>
    </h1>

    <!-- breadcrumb start -->
    @if ($breadcrumb)
      <ol class="breadcrumb" style="margin-right: 30px;">
        <li><a href="{{ admin_url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        @foreach($breadcrumb as $item)
          @if($loop->last)
            <li class="active">
              @if (array_has($item, 'icon'))
                <i class="fa fa-{{ $item['icon'] }}"></i>
              @endif
              {{ $item['text'] }}
            </li>
          @else
            <li>
              <a href="{{ admin_url(array_get($item, 'url')) }}">
                @if (array_has($item, 'icon'))
                  <i class="fa fa-{{ $item['icon'] }}"></i>
                @endif
                {{ $item['text'] }}
              </a>
            </li>
          @endif
        @endforeach
      </ol>
  @endif
  <!-- breadcrumb end -->

  </section>

  <section class="content">

    @include('admin::partials.error')
    @include('admin::partials.success')
    @include('admin::partials.exception')
    @include('admin::partials.toastr')

    {!! $content !!}

  </section>
@endsection
