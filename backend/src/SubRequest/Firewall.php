<?php

namespace App\SubRequest;

use App\SubRequest\Exception\AuthorizationFormatException;
use App\SubRequest\Exception\BadCredentialsException;
use App\SubRequest\Exception\TokenNotFoundException;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Request;

class Firewall
{
    public function authenticate(Request $request): bool
    {
        if ($request->headers->has('Authorization') === false) {
            throw new TokenNotFoundException("Header Authorization and JWT were not found");
        }

        $parts = explode('Bearer ', $request->headers->get('Authorization'));

        if (count($parts) !== 2) {
            throw new AuthorizationFormatException("Authorization header is not well formatted, it should be 'Bearer YOUR_TOKEN'");
        }

        $token = $parts[1];

        try {
            JWT::decode($token, new Key('example_key', 'HS256'));
            return true;
        } catch (Exception $e) {
            throw new BadCredentialsException("Bad credentials");
        }
    }
}
