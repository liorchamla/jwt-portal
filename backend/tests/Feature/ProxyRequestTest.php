<?php

namespace App\Test\Feature;

use App\Entity\Account;
use App\Factory\AccountFactory;
use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\Http\JWT\Authentication;
use App\ProxyRequest\ProxyHttpClient;
use App\SubRequest\HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Zenstruck\Foundry\Test\Factories;

class ProxyRequestTest extends WebTestCase
{
    use Factories;

    private function makeHttpClientMock()
    {
        /** @var MockObject */
        $httpClientMock = $this->createMock(ProxyHttpClient::class);
        $httpClientMock->method('makeApiRequest')->willReturn(new JsonResponse());
        static::getContainer()->set(ProxyHttpClient::class, $httpClientMock);

        return $httpClientMock;
    }


    /** @test */
    public function it_should_retrieve_remote_api_data()
    {
        $client = static::createClient();

        // Mocking HttpClient to make sure no request really gets out
        $mock = $this->makeHttpClientMock();
        $mock->expects($this->once())->method('makeApiRequest')->willReturn(new JsonResponse(['customers' => 12], 200));

        // Given we have an account
        $account = AccountFactory::createOne();

        // When we call our proxy
        $client->jsonRequest("GET", "/a/{$account->getApplication()->getId()}/u/mock/pattern/12");

        // Then the response should be successful
        static::assertResponseIsSuccessful();
    }

    /** @test */
    public function it_should_deny_access_for_a_protected_proxy_route()
    {
        $client = static::createClient();

        $mock = $this->makeHttpClientMock();

        // Given we have an application
        $app = ApplicationFactory::createOne();

        // And it has a protected route :
        $route = ProxyRouteFactory::createOne([
            'application' => $app
        ]);

        // And we have no account
        // When we try to reach proxy api
        $client->jsonRequest('GET', '/a/' . $app->getId() . '/u/' . $route->getClientPattern());

        // Then we should have a 401 because we have not sent a JWT 
        static::assertResponseStatusCodeSame(401);
    }

    /** @test */
    public function it_should_deny_access_to_an_unknown_application()
    {
        $client = static::createClient();

        $client->jsonRequest("GET", "/a/666/mock/pattern/12");

        static::assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function it_should_retrieve_remote_api_data_if_route_is_protected_and_we_provide_jwt()
    {
        $client = static::createClient();

        // Mocking HttpClient to make sure no request really gets out
        $mock = $this->makeHttpClientMock();
        $mock->expects($this->once())->method('makeApiRequest')->willReturn(new JsonResponse(['customers' => 12], 200));

        // Given we have an account
        /** @var Account */
        $account = AccountFactory::createOne()->object();

        // And we have the matching JWT
        $authentication = new Authentication;
        $jwt = $authentication->encode($account);

        // When we call our proxy
        $client->jsonRequest("GET", "/a/{$account->getApplication()->getId()}/u/mock/pattern/12", [], [
            'AUTHORIZATION' => "Bearer $jwt"
        ]);

        // Then the response should be successful
        static::assertResponseIsSuccessful();
    }
}
