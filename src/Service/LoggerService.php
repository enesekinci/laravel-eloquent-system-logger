<?php

namespace EnesEkinci\EloquentSystemLogger\Service;

use EnesEkinci\EloquentSystemLogger\Models\System\Logger as LoggerModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LoggerService
{
    protected static function boot()
    {
        parent::boot();
        
        $authId = Auth::id() ?? null;
        $requestIp = request()->ip();

        static::created(function ($model) use ($authId, $requestIp) {
            $tableName = $model->getTable();
            $process =  "create";

            LoggerModel::create([
                'user_id' => $authId,
                'request_ip' => $requestIp,
                'model' => get_class($model),
                'table' => $tableName,
                'row' => $model->id,
                'process' => $process,
                'content' => json_encode(['original' => $model->getAttributes()]),
            ]);
        });

        static::updated(function ($model) use ($authId, $requestIp) {
            $tableName = $model->getTable();
            $process =  "update";

            LoggerModel::create([
                'user_id' => $authId,
                'request_ip' => $requestIp,
                'model' => get_class($model),
                'table' => $tableName,
                'row' => $model->id,
                'process' => $process,
                'content' => json_encode([
                    'original' => $model->getRawOriginal(),
                    'last_content' => $model->getAttributes(),
                    'difference' => LoggerService::differenceOfContents($model),
                ]),
            ]);
        });

        static::deleted(function ($model) use ($authId, $requestIp) {
            $tableName = $model->getTable();
            $process =  "delete";

            LoggerModel::create([
                'user_id' => $authId,
                'request_ip' => $requestIp,
                'model' => get_class($model),
                'table' => $tableName,
                'row' => $model->id,
                'process' => $process,
                'content' => json_encode(['original' => $model->getAttributes()]),
            ]);
        });
    }

    public static function differenceOfContents($model)
    {
        $originalContent = $model->getRawOriginal();
        $newContent = $model->getAttributes();
        $differentContent =  array_diff($originalContent, $newContent);
        if (isset($differentContent['updated_at'])) unset($differentContent['updated_at']);
        return $differentContent;
    }
}
