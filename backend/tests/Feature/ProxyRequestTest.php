<?php

namespace App\Tests\Feature;

use App\Entity\Account;
use App\Factory\AccountFactory;
use App\Factory\ApplicationFactory;
use App\Factory\ProxyRouteFactory;
use App\Http\JWT\Authentication;
use App\ProxyRequest\ProxyHttpClient;
use App\Tests\WebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProxyRequestTest extends WebTestCase
{

    private function makeHttpClientMock()
    {
        /** @var MockObject */
        $httpClientMock = $this->createMock(ProxyHttpClient::class);
        $httpClientMock->method('makeApiRequest')->willReturn(new JsonResponse());
        static::getContainer()->set(ProxyHttpClient::class, $httpClientMock);

        return $httpClientMock;
    }


    /** @test */
    public function it_should_retrieve_remote_api_data_if_route_exists_and_is_not_protected()
    {
        // Mocking HttpClient to make sure no request really gets out
        $mock = $this->makeHttpClientMock();
        $mock->expects($this->once())
            ->method('makeApiRequest')
            ->with('https://mockapi.io/real/pattern/12')
            ->willReturn(new JsonResponse(['customers' => 12], 200));

        // Given we have an account
        $account = AccountFactory::createOne();

        $application = ApplicationFactory::createOne([
            'accounts' => [$account]
        ]);

        $route = ProxyRouteFactory::createOne([
            'application' => $application,
            'isProtected' => false,
            'clientPattern' => '/mock/pattern/{id}',
            'pattern' => 'https://mockapi.io/real/pattern/{id}'
        ]);

        // When we call our proxy
        $this->client->jsonRequest("GET", "/a/{$account->getApplication()->getId()}/u/mock/pattern/12");

        // Then the response should be successful
        static::assertResponseIsSuccessful();
    }

    /** @test */
    public function it_should_deny_access_for_a_protected_proxy_route()
    {
        $this->makeHttpClientMock();

        // Given we have an application
        $app = ApplicationFactory::createOne();

        // And it has a protected route :
        $route = ProxyRouteFactory::createOne([
            'application' => $app,
            'clientPattern' => '/mock/{id}',
            'pattern' => 'https://mockapi.io/real/{id}',
            'isProtected' => true
        ]);

        // And we have no account
        // When we try to reach proxy api
        $this->client->jsonRequest('GET', '/a/' . $app->getId() . '/u/mock/12');

        // Then we should have a 401 because we have not sent a JWT 
        static::assertResponseStatusCodeSame(401);
    }

    /** @test */
    public function it_should_deny_access_to_an_unknown_application()
    {
        $this->client->jsonRequest("GET", "/a/666/mock/pattern/12");

        static::assertResponseStatusCodeSame(404);
    }

    /** @test */
    public function it_should_retrieve_remote_api_data_if_route_is_protected_and_we_provide_jwt()
    {
        // Mocking HttpClient to make sure no request really gets out
        $mock = $this->makeHttpClientMock();
        $mock->expects($this->once())->method('makeApiRequest')->willReturn(new JsonResponse(['customers' => 12], 200));

        // Given we have an account
        /** @var Account */
        $account = AccountFactory::createOne()->object();

        $route = ProxyRouteFactory::createOne([
            'clientPattern' => '/mock/pattern/{id}',
            'pattern' => 'https://mockapi.io/real/{id}',
            'isProtected' => true,
            'application' => $account->getApplication()
        ]);

        // And we have the matching JWT
        $authentication = new Authentication;
        $jwt = $authentication->encode($account);

        // When we call our proxy
        $this->client->jsonRequest("GET", "/a/{$account->getApplication()->getId()}/u/mock/pattern/12", [], [
            'HTTP_AUTHORIZATION' => "Bearer $jwt",
        ]);

        // Then the response should be successful
        static::assertResponseIsSuccessful();
    }

    /** @test */
    public function it_should_send_a_request_with_the_same_http_method()
    {
        $mockHttpClient = $this->makeHttpClientMock();
        $mockHttpClient->expects($this->once())
            ->method('makeApiRequest')
            ->with('https://mockapi.io/real/pattern/12', 'PUT')
            ->willReturn(new JsonResponse(['customers' => 12], 200));

        // Given we have an application and a route with PUT method
        $account = AccountFactory::createOne();

        $application = ApplicationFactory::createOne([
            'accounts' => [$account]
        ]);

        $route = ProxyRouteFactory::createOne([
            'application' => $application,
            'isProtected' => false,
            'clientPattern' => '/mock/pattern/{id}',
            'pattern' => 'https://mockapi.io/real/pattern/{id}'
        ]);

        // When we call it through the proxy
        $this->client->jsonRequest("PUT", "/a/{$account->getApplication()->getId()}/u/mock/pattern/12");


        // Then the request should be sent with PUT method
        static::assertResponseIsSuccessful();
    }
}
