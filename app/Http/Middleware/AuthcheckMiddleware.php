<?php

namespace App\Http\Middleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Closure;

class AuthcheckMiddleware
{
    public function handle($request, Closure $next)
    {
        if(!self::checkToken($request))
            return self::ResponseError('Não autorizado!', 401);

        return $next($request);
    }
     
    private function checkToken($request) : bool
    {
        $privateKey = 'asd80a84sfa05sd1605c16s5165b1b1db1erfwef46';
        $tokenBearer = $request->bearerToken();
        $result = true;

        if(!$tokenBearer || $tokenBearer == '' || $tokenBearer == null)
            $result = false;

        if($tokenBearer)
            try{
                $decode = JWT::decode($tokenBearer, new Key($privateKey, 'HS256'));
                $request->attributes->set('userdata',$decode);
                $result = true;

            }catch(ExpiredException $e){
                $result = false;
            }
            catch(SignatureInvalidException $e){
                $result = false;
            }

        return $result;
    }
    protected function ResponseError($message, $status){
        return response()->json(['error' => $message], $status);
    }
}