<?php

namespace App\Traits;

use App\Models\System\Audit; // Assuming an Audit model exists
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Provides automatic audit logging for Eloquent models.
 */
trait Auditable
{
    /**
     * The "boot" method of the trait.
     *
     * This method is called when a model using this trait is booted,
     * allowing us to register our model event listeners.
     */
    public static function bootAuditable()
    {
        // Listener for the "created" event
        static::created(function (Model $model) {
            static::recordAuditEvent($model, 'created');
        });

        // Listener for the "updated" event
        static::updated(function (Model $model) {
            static::recordAuditEvent($model, 'updated');
        });

        // Listener for the "deleted" event
        static::deleted(function (Model $model) {
            static::recordAuditEvent($model, 'deleted');
        });
    }

    /**
     * Records an audit event for the model.
     *
     * @param Model $model
     * @param string $eventType ('created', 'updated', 'deleted')
     */
    protected static function recordAuditEvent(Model $model, string $eventType)
    {
        try {
            Audit::create([
                'user_id' => Auth::id() ?? null, // The user who performed the action
                'event' => $eventType,
                'auditable_type' => get_class($model),
                'auditable_id' => $model->getKey(),
                'old_values' => $eventType === 'updated' ? json_encode($model->getOriginal()) : null,
                'new_values' => ($eventType === 'created' || $eventType === 'updated') ? json_encode($model->getAttributes()) : null,
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log an error if the audit record fails to be created, but don't crash the application.
            Log::error('Failed to record audit event.', [
                'error' => $e->getMessage(),
                'model' => get_class($model),
                'model_id' => $model->getKey(),
            ]);
        }
    }
}