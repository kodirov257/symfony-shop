<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Services\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public const REFERENCE_ADMIN = 'user_admin';
    public const REFERENCE_USER = 'user_user';

    private PasswordHasher $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('password');

        $admin = $this->createAdmin('admin', 'admin@example.com', $hash);

        $user = $this->createAdmin('user', 'user@example.com', $hash);

        // $product = new Product();
        // $manager->persist($product);

        $manager->persist($admin);
        $this->setReference(self::REFERENCE_ADMIN, $admin);

        $manager->persist($user);
        $this->setReference(self::REFERENCE_USER, $user);

        $manager->flush();
    }

    private function createAdmin(string $username, string $email, string $password): User
    {
        $user = $this->createUser($email, $username, $password);
        $user->changeRole(User::ROLE_ADMIN);
        return $user;
    }

    private function createUser(string $username, string $email, string $password): User
    {
        return User::create($email, $username, $password);
    }
}
