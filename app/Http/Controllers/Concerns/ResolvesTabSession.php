<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait ResolvesTabSession
{
    protected function resolveTabSessionKey(Request $request): string
    {
        $raw = trim((string) ($request->input('tab_session') ?? $request->query('tab_session') ?? ''));

        return $raw !== '' ? Str::limit($raw, 120, '') : '';
    }
}
