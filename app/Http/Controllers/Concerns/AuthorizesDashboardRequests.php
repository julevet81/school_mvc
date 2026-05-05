<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait AuthorizesDashboardRequests
{
    protected function ensureAuthenticated(): void
    {
        if (Auth::guest()) {
            throw new HttpException(401);
        }
    }

    protected function ensurePermission(string $permission): void
    {
        $this->ensureAuthenticated();
    }
}