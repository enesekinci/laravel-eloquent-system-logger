<?php

namespace EnesEkinci\EloquentSystemLogger\Models\System;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    protected $table = 'system_loggers';
    protected $fillable = [
        'user_id',
        'request_ip',
        'model',
        'table',
        'row',
        'email',
        'process',
        'content',
    ];
}
