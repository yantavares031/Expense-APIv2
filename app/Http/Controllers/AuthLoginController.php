<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class AuthLoginController extends Controller
{
    public function makelogin(Request $request)
    {
        $usuario = $request->input('login');
        $senha   = $request->input('password');

        if(!$usuario && !$senha)
            return self::Response('Não autorizado, por falta de parametros!', 401);

        $account = User::where('login', $usuario)->first();
        if(!$account || $account->password != $senha){
            return self::Response('Não autorizado, Usuário ou senha invalidos!', 401);
        }

        $token = self::GeratingToken($usuario, $account->id);
        return self::ResponseSuccess($token, 200);
    }
    
    public function checkToken(Request $request)
    {
        $privateKey = 'asd80a84sfa05sd1605c16s5165b1b1db1erfwef46';
        $tokenBearer = $request->bearerToken();
        
        if(!$tokenBearer || $tokenBearer == '')
            return self::ResponseError('Token de sessão não informado!', 401);

        try{
            $decode = JWT::decode($tokenBearer, new Key($privateKey, 'HS256'));

        }catch(ExpiredException $e){
            return self::ResponseError($e->getMessage(),401);
        }
        catch(SignatureInvalidException $e){
            return self::ResponseError($e->getMessage(),401);
        }

        return json_encode($decode);
    }

    protected function Response($message, $status){
        return response()->json(['message' => $message], $status);
    }
    
    protected function ResponseError($message, $status){
        return response()->json(['error' => $message], $status);
    }
    protected function ResponseSuccess($message, $status){
        return response()->json(['success' => $message], $status);
    }

    protected function GeratingToken($usuario, $user_id){
        $privateKey = 'asd80a84sfa05sd1605c16s5165b1b1db1erfwef46';
        $payload = [
            'exp'     => time() + 1000,
            'iat'     => time(),
            'login'   => $usuario,
            'user_id' => $user_id
        ];

        $encode = JWT::encode($payload, $privateKey, 'HS256');
        return $encode;

    }
}