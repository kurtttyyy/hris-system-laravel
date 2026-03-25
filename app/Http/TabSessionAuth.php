<?php

namespace App\Http;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TabSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        $tabSession = trim((string) ($request->input('tab_session') ?? $request->query('tab_session') ?? ''));
        if ($tabSession !== '') {
            $request->attributes->set('tab_session', $tabSession);

            $tabAuthMap = $request->session()->get('tab_auth_users', []);
            $userId = (int) ($tabAuthMap[$tabSession] ?? 0);

            if ($userId > 0) {
                $user = User::query()->find($userId);
                if ($user) {
                    Auth::setUser($user);
                } else {
                    unset($tabAuthMap[$tabSession]);
                    $request->session()->put('tab_auth_users', $tabAuthMap);
                }
            }
        }

        return $next($request);
    }
}
