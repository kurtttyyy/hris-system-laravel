<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResolvesTabSession
{
    protected function resolveTabSessionKey(Request $request): string
    {
        $raw = trim((string) (
            $request->input('tab_session')
            ?? $request->query('tab_session')
            ?? $request->attributes->get('tab_session')
            ?? ''
        ));

        if ($raw === '') {
            $scope = $this->resolveTabSessionScope($request);
            $raw = trim((string) $request->session()->get('last_tab_session_'.$scope, ''));
        }

        return $raw !== '' ? Str::limit($raw, 120, '') : '';
    }

    protected function resolveTabSessionScope(Request $request): string
    {
        $path = ltrim($request->path(), '/');
        $routeName = (string) optional($request->route())->getName();

        if (
            str_starts_with($path, 'system/')
            || str_starts_with($path, 'admin/')
            || str_starts_with($routeName, 'admin.')
        ) {
            return 'admin';
        }

        if (
            str_starts_with($path, 'employee/')
            || str_starts_with($routeName, 'employee.')
        ) {
            return 'employee';
        }

        return 'web';
    }
}
