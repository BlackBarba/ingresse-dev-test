<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('create', function ($result) {
            if ($result) {
                $code = 201;
                $response = [
                    'message' => 'Created with success',
                    'data' => $result
                ];
            } else {
                $code = 500;
                $response = [
                    'message' => 'Error on save',
                ];
            }
            
            return Response::make($response, $code);
        });

        Response::macro('update', function ($result) {
            if ($result) {
                $code = 200;
                $response = [
                    'message' => 'Updated with success',
                    'data' => $result
                ];
            } else {
                $code = 500;
                $response = [
                    'message' => 'Error on save',
                ];
            }
            
            return Response::make($response, $code);
        });

        Response::macro('delete', function ($result) {
            if($result) {
                $code = 200;
                $response = [
                    'message' => 'Deleted with success',
                    'data' => $result
                ];
            } else {
                $code = 500;
                $response = [
                    'message' => 'Error on delete',
                ];
            }
            
            return Response::make($response, $code);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
