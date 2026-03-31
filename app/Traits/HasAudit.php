<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

trait HasAudit
{
    public static function bootHasAudit()
    {
        static::created(function ($model) {
            self::logAudit('created', $model);
        });

        static::updated(function ($model) {
            self::logAudit('updated', $model, $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            self::logAudit('deleted', $model, $model->getOriginal());
        });
    }

    protected static function logAudit($type, $model, $oldValues = null, $newValues = null)
    {
        // Limpar atributos ocultos (senhas etc) do log
        $hidden = $model->getHidden();
        
        $old = $oldValues ? array_diff_key($oldValues, array_flip($hidden)) : null;
        $new = $newValues ? array_diff_key($newValues, array_flip($hidden)) : null;

        AuditLog::create([
            'user_id'    => auth()->id(),
            'event_type' => $type,
            'table_name' => $model->getTable(),
            'record_id'  => $model->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
