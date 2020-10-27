<?php

namespace Encore\Admin\Grid\Displayers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

class Image extends AbstractDisplayer
{
    public function display(
        $server = '',
        $maxWidth = 200,
        $maxHeight = 200,
        $width = null,
        $height = null,
        $class = 'img img-thumbnail'
    ) {
        if ($this->value instanceof Arrayable) {
            $this->value = $this->value->toArray();
        }

        return collect((array)$this->value)->filter()->map(function ($path) use (
            $server,
            $maxWidth,
            $maxHeight,
            $width,
            $height,
            $class
        ) {
            if (url()->isValidUrl($path)) {
                $src = $path;
            } elseif ($server) {
                $src = $server . $path;
            } else {
                $src = Storage::disk(config('admin.upload.disk'))->url($path);
            }
            $styleArgs = [];
            if (!is_null($maxWidth)) {
                $styleArgs['max-width'] = $maxWidth . 'px';
            }
            if (!is_null($maxHeight)) {
                $styleArgs['max-height'] = $maxHeight . 'px';
            }
            if (!is_null($width)) {
                $styleArgs['width'] = $width . 'px';
            }
            if (!is_null($height)) {
                $styleArgs['height'] = $height . 'px';
            }
            $styles = [];
            foreach ($styleArgs as $key => $val) {
                $styles [] = "$key:$val";
            }
            if (!empty($styles)) {
                $style = 'style="' . implode($styles, '; ') . '"';
            }

            return "<img src='$src' $style class='{$class}' />";
        })->implode('&nbsp;');
    }
}
