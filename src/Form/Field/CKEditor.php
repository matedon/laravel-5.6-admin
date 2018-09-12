<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class CKEditor extends Field
{
  public static $js = [
    '/vendor/laravel-admin/ckeditor/ckeditor.js',
    '/vendor/laravel-admin/ckeditor/adapters/jquery.js',
  ];

  public function render()
  {
    $editor_id = $this->id;

    $this->script = <<<EOT
      $('textarea#{$editor_id}').ckeditor({
        language: 'hu',
        toolbar: [
          [ 'Undo', 'Redo' ],
          [ 'Format', 'Styles' ],
          [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ],
          [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ],
          [ 'RemoveFormat', 'SpellChecker', 'Scayt' ],
          [ 'Link', 'Unlink', 'SpecialChar' ],
          [ 'SelectAll', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ],
          [ 'Preview', 'Maximize' ],
          [ 'Source' ]
        ]
      });
EOT;
    return parent::render();
  }
}
