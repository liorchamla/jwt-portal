<?php

namespace App\Factory;

use App\Entity\ProxyRoute;
use App\Repository\ProxyRouteRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<ProxyRoute>
 *
 * @method static ProxyRoute|Proxy createOne(array $attributes = [])
 * @method static ProxyRoute[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static ProxyRoute|Proxy find(object|array|mixed $criteria)
 * @method static ProxyRoute|Proxy findOrCreate(array $attributes)
 * @method static ProxyRoute|Proxy first(string $sortedField = 'id')
 * @method static ProxyRoute|Proxy last(string $sortedField = 'id')
 * @method static ProxyRoute|Proxy random(array $attributes = [])
 * @method static ProxyRoute|Proxy randomOrCreate(array $attributes = [])
 * @method static ProxyRoute[]|Proxy[] all()
 * @method static ProxyRoute[]|Proxy[] findBy(array $attributes)
 * @method static ProxyRoute[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ProxyRoute[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static ProxyRouteRepository|RepositoryProxy repository()
 * @method ProxyRoute|Proxy create(array|callable $attributes = [])
 */
final class ProxyRouteFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'pattern' => '/' . self::faker()->word() . '/' . self::faker()->word(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(ProxyRoute $proxyRoute): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProxyRoute::class;
    }
}
