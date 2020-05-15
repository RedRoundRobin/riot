<?php

namespace App\Providers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\ServiceProvider;

class BasicProvider extends ServiceProvider
{
    /**
     * @param RequestException $e
     * @return RedirectResponse|Redirector
     */
    protected function isExpired(RequestException $e)
    {
        if ($e->getCode() == 419 || $e->getCode() == 401 /* fai il controllo del token */) {
            session()->invalidate();
            session()->flush();
            return redirect(route('login'));
        } elseif ($e->getCode() != 409 || $e->getCode() == 0) {
            ($e->getCode()) ? abort($e->getCode()) : abort(409);
        }
    }

    protected function setHeaders()
    {
        return [
                'headers' => [
                    'Authorization' => 'Bearer ' . session()->get('token'),
                    'X-Forwarded-For' => request()->ip()
                ]
            ];
    }
}
