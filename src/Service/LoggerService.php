<?php

namespace EnesEkinci\EloquentSystemLogger\Service;

use EnesEkinci\EloquentSystemLogger\Models\System\Logger as LoggerModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LoggerService
{
    protected static function boot()
    {

        static::created(function ($model) {
            Log::info(["createdProcess", $model->getRawOriginal(), $model->getAttributes()]);

            $tableName = $model->getTable();
            $process =  "create";

            LoggerModel::create([
                'user_id' => Auth::id() ?? null,
                'request_ip' => request()->ip(),
                'model' => get_class($model),
                'table' => $tableName,
                'row' => $model->id,
                'process' => $process,
                'content' => json_encode(['original' => $model->getAttributes()]),
            ]);
        });

        static::updated(function ($model) {
            $tableName = $model->getTable();
            $process =  "update";

            LoggerModel::create([
                'user_id' => Auth::id(),
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

        static::deleted(function ($model) {
            $tableName = $model->getTable();
            $process =  "delete";

            LoggerModel::create([
                'user_id' => Auth::id(),
                'model' => get_class($model),
                'table' => $tableName,
                'row' => $model->id,
                'process' => $process,
                'content' => json_encode(['original' => $model->getAttributes()]),
            ]);
        });

        parent::boot();
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
