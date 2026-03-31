<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * @param string $eventType
     * @param string $tableName
     * @param int|null $recordId
     * @param array|null $oldValues
     * @param array|null $newValues
     */
    public function log(string $eventType, string $tableName, ?int $recordId = null, ?array $oldValues = null, ?array $newValues = null): void
    {
        // Tratamento adicional caso precise mascarar campos explicitamente
        AuditLog::create([
            'user_id'    => auth()->id(),
            'event_type' => $eventType,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
