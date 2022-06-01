<?php

namespace App\Factory;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Account>
 *
 * @method static Account|Proxy createOne(array $attributes = [])
 * @method static Account[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Account|Proxy find(object|array|mixed $criteria)
 * @method static Account|Proxy findOrCreate(array $attributes)
 * @method static Account|Proxy first(string $sortedField = 'id')
 * @method static Account|Proxy last(string $sortedField = 'id')
 * @method static Account|Proxy random(array $attributes = [])
 * @method static Account|Proxy randomOrCreate(array $attributes = [])
 * @method static Account[]|Proxy[] all()
 * @method static Account[]|Proxy[] findBy(array $attributes)
 * @method static Account[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Account[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AccountRepository|RepositoryProxy repository()
 * @method Account|Proxy create(array|callable $attributes = [])
 */
final class AccountFactory extends ModelFactory
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
            'email' => self::faker()->email(),
            'password' => 'password',
            'application' => ApplicationFactory::createOne()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Account $account): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Account::class;
    }
}
