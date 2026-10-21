<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/*
 * Legacy Laravel 5 array/string helpers, removed from the framework in Laravel 6.
 * The package and its host applications still call them, so they are kept here
 * as thin wrappers around Arr/Str (same behaviour as the laravel/helpers package).
 */
if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

if (!function_exists('array_set')) {
    function array_set(&$array, $key, $value)
    {
        return Arr::set($array, $key, $value);
    }
}

if (!function_exists('array_has')) {
    function array_has($array, $keys)
    {
        return Arr::has($array, $keys);
    }
}

if (!function_exists('array_forget')) {
    function array_forget(&$array, $keys)
    {
        Arr::forget($array, $keys);
    }
}

if (!function_exists('array_dot')) {
    function array_dot($array, $prepend = '')
    {
        return Arr::dot($array, $prepend);
    }
}

if (!function_exists('array_only')) {
    function array_only($array, $keys)
    {
        return Arr::only($array, $keys);
    }
}

if (!function_exists('array_except')) {
    function array_except($array, $keys)
    {
        return Arr::except($array, $keys);
    }
}

if (!function_exists('array_flatten')) {
    function array_flatten($array, $depth = INF)
    {
        return Arr::flatten($array, $depth);
    }
}

if (!function_exists('snake_case')) {
    function snake_case($value, $delimiter = '_')
    {
        return Str::snake($value, $delimiter);
    }
}

if (!function_exists('camel_case')) {
    function camel_case($value)
    {
        return Str::camel($value);
    }
}

if (!function_exists('studly_case')) {
    function studly_case($value)
    {
        return Str::studly($value);
    }
}

if (!function_exists('starts_with')) {
    function starts_with($haystack, $needles)
    {
        return Str::startsWith($haystack, $needles);
    }
}

if (!function_exists('ends_with')) {
    function ends_with($haystack, $needles)
    {
        return Str::endsWith($haystack, $needles);
    }
}

if (!function_exists('str_slug')) {
    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }
}

if (!function_exists('str_random')) {
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

if (!function_exists('str_limit')) {
    function str_limit($value, $limit = 100, $end = '...')
    {
        return Str::limit($value, $limit, $end);
    }
}

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function admin_url($path = '', $parameters = [], $secure = null)
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        $secure = $secure ?: config('admin.secure');

        return url(admin_base_path($path), $parameters, $secure);
    }
}

if (!function_exists('admin_base_path')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/'.trim(config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        return $prefix.'/'.trim($path, '/');
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     *
     * @return string
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }
}

if (!function_exists('admin_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function admin_asset($path)
    {
        return asset($path, config('admin.secure'));
    }
}

if (!function_exists('array_delete')) {

    /**
     * Delete from array by value.
     *
     * @param array $array
     * @param mixed $value
     */
    function array_delete(&$array, $value)
    {
        foreach ($array as $index => $item) {
            if ($value == $item) {
                unset($array[$index]);
            }
        }
    }
}

if (!function_exists('admin_translate')) {

    /**
     * Now you can add your own translate files for your project.
     * The "laravel-admin" will search for the translations in these sequence:
     * A.) admin.modelName.columnName
     * B.) admin.columnName
     * C.) Column name with spaces (dots and underscore replaced with spaces)
     * D.) Fallback
     * If you have translation A, that will be used, if not then B.
     * If there is no translation at all:
     * if exists the fallback D else the C will be the output.
     *
     * @param $modelPath
     * @param $column
     * @param null $fallback
     * @return string
     */
    function admin_translate($modelPath, $column, $fallback = null)
    {
        $nameList = explode('\\', $modelPath);
        /*
         * CamelCase model name converted to underscore name version.
         * ExampleString => example_strinig
         */
        $modelName = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', end($nameList))), '_');
        /*
         * ExampleString with banana => example_string_with_banana
         */
        $columnLower = ltrim(strtolower(preg_replace('/[A-Z ]([A-Z](?![a-z]))*/', '_$0', $column)), '_');
        /*
         * example_  string _with_banana => example_string_with_banana
         */
        $columnLower = preg_replace('!\s+!', '', $columnLower);
        /*
         * example__sring_____with_banana => example_string_with_banana
         */
        $columnLower = preg_replace('!_+!', '_', $columnLower);
        /*
         * The possible translate keys in priority order.
         */
        $transLateKeys = [
            'admin.' . $modelName . '.' . $columnLower,
            'admin.' . $columnLower,
            'validation.attributes.' . $columnLower,
        ];
        $label = null;
        foreach ($transLateKeys as $key) {
            if (Lang::has($key)) {
                $label = trans($key);
                break;
            }
        }
        if (!$label) {
            $label = str_replace(['.', '_'], ' ', $fallback ? $fallback : ucfirst($column));
        }
        return (string)$label;
    }
}
