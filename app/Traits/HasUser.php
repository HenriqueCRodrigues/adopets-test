<?php

namespace App\Traits;

trait HasUser
{
    public static function bootHasUser()
    {
        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = \Auth::id();
            }
        });
    }
}