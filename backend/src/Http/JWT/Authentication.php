<?php

namespace App\Http\JWT;

use App\Entity\Account;
use App\Entity\Application;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Authentication
{
    public function authenticate(Request $request, Application $application)
    {
        if (!$request->headers->has('Authorization')) {
            return false;
        }

        $token = explode("Bearer ", $request->headers->get('Authorization'))[1] ?? null;

        if (!$token) {
            return false;
        }

        if (!$this->decode($token, $application)) {
            return false;
        }

        return true;
    }

    public function encode(Account $account)
    {
        return JWT::encode([
            "username" => $account->getEmail(),
            "id" => $account->getId(),
            'iat' => time()
        ], "jwt_secret_" . $account->getApplication()->getId(), 'HS256');
    }

    public function decode(string $jwt, Application $application)
    {
        try {
            return JWT::decode($jwt, new Key("jwt_secret_" . $application->getId(), 'HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}
