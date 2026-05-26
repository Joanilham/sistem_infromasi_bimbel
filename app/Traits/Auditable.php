<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the trait to hook into Eloquent events.
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updating(function ($model) {
            $model->logAudit('updated');
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }

    /**
     * Record an audit log entry.
     *
     * @param string $event
     */
    public function logAudit($event)
    {
        $oldValues = [];
        $newValues = [];

        if ($event === 'updated') {
            $newValues = $this->getDirty();
            // Get original attributes for those that have changed
            foreach ($newValues as $key => $value) {
                if ($key !== 'updated_at') { // Skip updated_at
                    $oldValues[$key] = $this->getOriginal($key);
                }
            }
            // Remove updated_at from new_values to save space
            unset($newValues['updated_at']);
            
            // If no actual substantive changes, don't log
            if (empty($newValues)) {
                return;
            }
        } elseif ($event === 'created') {
            $newValues = $this->getAttributes();
            unset($newValues['updated_at']);
        } elseif ($event === 'deleted') {
            $oldValues = $this->getAttributes();
        }

        // Sanitasi: hapus field sensitif (password hash, token, dll)
        $oldValues = AuditLog::sanitize($oldValues);
        $newValues = AuditLog::sanitize($newValues);

        AuditLog::record([
            'user_id'        => Auth::id(),
            'event'          => $event,
            'auditable_type' => get_class($this),
            'auditable_id'   => $this->id ?? 0,
            'old_values'     => !empty($oldValues) ? $oldValues : null,
            'new_values'     => !empty($newValues) ? $newValues : null,
            'url'            => Request::fullUrl(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }
}
