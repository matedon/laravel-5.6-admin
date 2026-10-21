<?php

namespace Encore\Admin\Traits;

use DateTimeInterface;

/**
 * Keep the Laravel 5 "Y-m-d H:i:s" date serialization.
 *
 * Since Laravel 7 toArray()/toJson() serialize dates as ISO-8601
 * (2019-12-02T20:01:00.283041Z). Grid rows and edit forms are filled from
 * toArray(), so the old format is needed for datetime fields and displayers.
 */
trait DefaultDatetimeFormat
{
    /**
     * @param DateTimeInterface $date
     *
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }
}
