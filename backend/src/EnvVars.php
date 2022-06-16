<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EnvVars extends AbstractController
{
    #[Route("/envvars")]
    public function __invoke()
    {

        echo "<pre>";
        var_dump("Public exists :", file_exists(__DIR__ . '/../config/jwt/public.pem'));
        var_dump("Public exists :", file_exists(__DIR__ . '/../config/jwt/public.pem'));
        print_r($_SERVER);
        die();
    }
}
