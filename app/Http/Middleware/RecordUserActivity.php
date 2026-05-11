<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RecordUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->record($request, $response->getStatusCode());

        return $response;
    }

    private function record(Request $request, int $statusCode): void
    {
        if ($statusCode >= 400 || !Schema::hasTable('activity_logs')) {
            return;
        }

        if ($request->isMethod('GET')) {
            return;
        }

        $user = $request->user();
        if (!$user) {
            return;
        }

        $role = strtolower(trim((string) ($user->role ?? '')));
        if (!in_array($role, ['admin', 'administrator', 'employee'], true)) {
            return;
        }

        $routeName = (string) optional($request->route())->getName();
        if ($routeName === '' || !$this->shouldRecordRoute($routeName)) {
            return;
        }

        $name = trim(implode(' ', array_filter([
            trim((string) ($user->first_name ?? '')),
            trim((string) ($user->middle_name ?? '')),
            trim((string) ($user->last_name ?? '')),
        ])));

        ActivityLog::query()->create([
            'user_id' => $user->id,
            'user_name' => $name !== '' ? $name : (string) ($user->email ?? 'Unknown user'),
            'user_email' => $user->email,
            'user_role' => $this->activityRoleLabel($user),
            'method' => $request->method(),
            'route_name' => $routeName,
            'path' => '/'.ltrim($request->path(), '/'),
            'action' => $this->actionLabel($request, $routeName),
            'description' => $this->description($request, $routeName),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);
    }

    private function shouldRecordRoute(string $routeName): bool
    {
        if (in_array($routeName, [
            'admin.activityLogs.note',
            'admin.adminNotifications.summary',
            'employee.employeeNotifications.summary',
        ], true)) {
            return false;
        }

        return Str::startsWith($routeName, ['admin.', 'employee.']);
    }

    private function activityRoleLabel(object $user): string
    {
        $role = trim((string) ($user->role ?? ''));
        $departmentHeadStatus = strtolower(trim((string) ($user->department_head ?? '')));

        if (strtolower($role) === 'employee' && $departmentHeadStatus === 'approved') {
            return 'Department Head';
        }

        return $role !== '' ? $role : 'User';
    }

    private function actionLabel(Request $request, string $routeName): string
    {
        $verb = match ($request->method()) {
            'GET' => 'Viewed',
            'POST' => 'Submitted',
            'PUT', 'PATCH' => 'Updated',
            'DELETE' => 'Deleted',
            default => ucfirst(strtolower($request->method())),
        };

        return $verb.' '.$this->routeLabel($routeName);
    }

    private function description(Request $request, string $routeName): string
    {
        return $this->actionLabel($request, $routeName).' from '.$request->ip().'.';
    }

    private function routeLabel(string $routeName): string
    {
        $label = Str::of($routeName)
            ->replace(['admin.', 'employee.'], '')
            ->replace(['.', '_', '-'], ' ')
            ->headline()
            ->toString();

        return $label !== '' ? $label : 'System Page';
    }
}
