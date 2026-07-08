<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Hanya log untuk user yang sudah login
        if (auth()->check()) {
            $method = $request->method();
            $route = $request->route();

            if ($route) {
                $routeName = $route->getName() ?? $route->uri();
                $module = $this->extractModule($routeName);
                $action = $this->mapAction($method);

                // Log aktivitas untuk POST, PUT, PATCH, DELETE
                if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
                    AuditLog::create([
                        'user_id' => auth()->id(),
                        'action' => $action,
                        'module' => $module,
                        'description' => "User melakukan {$action} pada {$module}",
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
            }
        }

        return $response;
    }

    /**
     * Extract module name from route
     */
    private function extractModule($routeName)
    {
        $parts = explode('.', $routeName);
        return ucfirst($parts[0] ?? 'unknown');
    }

    /**
     * Map HTTP method to action
     */
    private function mapAction($method)
    {
        $actions = [
            'POST' => 'create',
            'PUT' => 'update',
            'PATCH' => 'update',
            'DELETE' => 'delete',
        ];
        return $actions[$method] ?? 'view';
    }
}