<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BlockSuspiciousPaths
{
    private $suspicious = [
        '.env', 
        'wp-admin', 
        'wp-login.php', 
        'config.php', 
        'phpinfo.php', 
        '.git/config'
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        foreach ($this->suspicious as $bad) {
            if (str_contains(strtolower($path), $bad)) {
                // Registrar ban
                DB::table('ips_banidos')->insertOrIgnore([
                    'ip_address' => $request->ip(),
                    'reason' => 'Tentativa de acesso a path suspeito: ' . $bad,
                    'expires_at' => now()->addDays(7),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                abort(403, 'Acesso negado permanentemente.');
            }
        }

        // Checar se já tá banido
        $ban = DB::table('ips_banidos')->where('ip_address', $request->ip())->first();
        if ($ban && ($ban->expires_at === null || \Carbon\Carbon::parse($ban->expires_at)->isFuture())) {
            abort(403, 'Seu IP está bloqueado.');
        }

        return $next($request);
    }
}
