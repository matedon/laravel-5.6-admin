<?php

namespace Encore\Admin\Grid\Displayers;

class DateFormat extends AbstractDisplayer
{
    public function display($format = 'y-m-d<\b\r>H:i:s')
    {
        if (empty(trim($this->value))) {
            return '';
        }
        return date($format, strtotime($this->value));
    }
}
