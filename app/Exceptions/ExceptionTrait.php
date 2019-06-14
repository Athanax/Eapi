<?php

namespace App\Exceptions;
use App\Exceptions\ExceptionTrait; 

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ExceptionTrait
{
    public function apiException($request,$e)
    {
        if ($this->isModel($e)) {
            return response()->json([
                'errors'=>'Product Model not found'
            ],Response::HTTP_NOT_FOUND);
        }
    

        if ($this->isHttp($e)) {
            return response()->json([
                'errors'=>'Incorrect Route'
            ],Response::HTTP_NOT_FOUND);
        }
         
        return parent::render($request, $e);

        
    }

    protected function isModel($e)
    {

        return $e instanceof ModelNotFoundException;
    }

    protected function isHttp($e)
    {
        return $e instanceof NotFoundHttpException;
    }

}