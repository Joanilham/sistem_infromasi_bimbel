<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use App\Models\System\AuditLog;
use Illuminate\Support\Facades\Request;

class LogAuthenticationEvents
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin(Login $event)
    {
        $this->recordAuthEvent('Login Sistem', $event->user);
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event)
    {
        $this->recordAuthEvent('Logout Sistem', $event->user);
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            Login::class,
            [LogAuthenticationEvents::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [LogAuthenticationEvents::class, 'handleUserLogout']
        );
    }

    /**
     * Record the authentication event to the audit log via centralized method.
     */
    private function recordAuthEvent(string $action, $user)
    {
        if (!$user) return;

        AuditLog::record([
            'user_id'        => $user->id,
            'event'          => $action,
            'auditable_type' => get_class($user),
            'auditable_id'   => $user->id,
            'old_values'     => null,
            'new_values'     => null,
            'url'            => Request::fullUrl(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }
}

