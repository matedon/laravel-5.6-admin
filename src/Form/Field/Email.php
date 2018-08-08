<?php

namespace Encore\Admin\Form\Field;

class Email extends Text
{
    protected $rules = 'email';

    protected $icon = 'fa-at';

    public function render()
    {
        $this->prepend('<i class="fa fa-fw fa-lg ' . $this->icon . '"></i>')
            ->defaultAttribute('type', 'email');

        return parent::render();
    }
}
