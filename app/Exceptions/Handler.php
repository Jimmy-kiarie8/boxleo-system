<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Throwable;
use Illuminate\Support\Str;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });


        $this->reportable(function (\League\OAuth2\Server\Exception\OAuthServerException $e) {
            if($e->getCode() == 9)
                return false;
        });

        // $this->renderable(function (TenantCouldNotBeIdentifiedOnDomainException $e, $request) {
        //     $error_message = ['message' => $e->getMessage()];
        //     $error = 'Tenant could not be identified on domain ';
        //     $contains = str_contains($e->getMessage(), $error);
        //     if ($contains) {
        //         $replaced = Str::replace($error, '', $error_message);
        //         $arr = explode('.', $replaced['message']);
        //         return response()->view('admin.errors.invalid-domain', ['domain' => $arr[0]], 500);
        //     }
        // });
    }
}
