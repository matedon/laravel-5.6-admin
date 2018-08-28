/**
 * Formatted with following standard: https://standardjs.com/
 */

$.fn.editable.defaults.params = function (params) {
  params._token = LA.token
  params._editable = 1
  params._method = 'PUT'
  return params
}

$.fn.editable.defaults.error = function (data) {
  var msg = ''
  if (data.responseJSON.errors) {
    $.each(data.responseJSON.errors, function (k, v) {
      msg += v + "\n"
    })
  }
  return msg
}

toastr.options = {
  closeButton: true,
  progressBar: true,
  showMethod: 'slideDown',
  timeOut: 4000
}

$.pjax.defaults.timeout = 5000
$.pjax.defaults.maxCacheLength = 0
$(document).pjax('a:not(a[target="_blank"])', {
  container: '#pjax-container'
})

NProgress.configure({parent: '#pjax-container'})

$(document).on('pjax:timeout', function (event) {
  event.preventDefault()
})

$(document).on('submit', 'form[pjax-container]', function (event) {
  $.pjax.submit(event, '#pjax-container')
})

$(document).on("pjax:popstate", function () {

  $(document).one("pjax:end", function (event) {
    $(event.target).find("script[data-exec-on-popstate]").each(function () {
      $.globalEval(this.text || this.textContent || this.innerHTML || '')
    })
  })
})

var filterFormEmtpyCheck = function () {
  $('[data-block="filter-form"]').each(function () {
    var $form = $(this)
    var notEmpty = false
    $form.find(':input').each(function () {
      notEmpty = notEmpty || Boolean($(this).val())
    })
    if (notEmpty) {
      if ($form.hasClass('hide')) {
        $form.find('[data-element="filter-form__search"]').trigger('click')
      }
      $form.removeClass('hide')
    } else {
      $form.addClass('hide')
    }
  })
}

var pjaxTitleRewrite = function () {
  var $title = $('head').find('title')
  var titleRoot = $title.data('titleRoot')
  var $titlePart = $('[data-element="title-part"]')
  var titlePartMain = $titlePart.filter('[data-level="main"]').text().trim()
  var titlePartBracket = $titlePart.filter('[data-level="bracket"]').text().trim()
  titlePartBracket = titlePartBracket.length ? ' (' + titlePartBracket + ')' : ''
  var newTitle = titleRoot + ' / ' + titlePartMain + titlePartBracket
  $title.html(newTitle)
}
pjaxTitleRewrite()

var pjaxSendComplete = function (xhr, button) {
  if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
    var $submitBtn = $('form[pjax-container] :submit')
    if ($submitBtn) {
      $submitBtn.button(button)
    }
  }
  switch (button) {
    case 'loading':
      NProgress.start()
      break
    case 'reset':
      NProgress.done()
      filterFormEmtpyCheck()
      pjaxTitleRewrite()
      break
  }
}

$(document).on('pjax:send', function (xhr) {
  pjaxSendComplete(xhr, 'loading')
})

$(document).on('pjax:complete', function (xhr) {
  pjaxSendComplete(xhr, 'reset')
})

;(function ($) {
  $('.sidebar-menu li:not(.treeview) > a').on('click', function () {
    var $parent = $(this).parent().addClass('active')
    $parent.siblings('.treeview.active').find('> a').trigger('click')
    $parent.siblings().removeClass('active').find('li').removeClass('active')
  })

  $('[data-toggle="popover"]').popover()

  $.fn.admin = LA
  $.admin = LA

  $('body').on('click', '[data-event="entry-delete"]', function () {
    var options = $(this).data()
    swal({
        title: LA.trans.delete_confirm,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: LA.trans.confirm,
        closeOnConfirm: false,
        cancelButtonText: LA.trans.cancel
      },
      function () {
        $.ajax({
          method: 'post',
          url: options.urlDelete,
          data: {
            _method: 'delete',
            _token: LA.token,
          },
          success: function (data) {
            switch (options.callback) {
              case 'reload':
                $.pjax.reload('#pjax-container')
                break
              case 'list':
                $.pjax({container: '#pjax-container', url: data.urlList})
                break
            }

            if (typeof data === 'object') {
              if (data.status) {
                swal(data.message, '', 'success')
              } else {
                swal(data.message, '', 'error')
              }
            }
          }
        })
      })
  })

  $().ready(function () {
    filterFormEmtpyCheck()
  })

})(jQuery)