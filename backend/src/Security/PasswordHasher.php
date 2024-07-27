<?php


namespace App\Security;

use  Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use function Symfony\Component\Translation\t;

class PasswordHasher implements PasswordHasherInterface
{

    public function hash(string $plainPassword, string $salt = null): string
    {
        return hash('sha512', hash('whirlpool', $plainPassword. $salt));
    }

    public function verify(string $hashedPassword, string $plainPassword, string $salt = null): bool
    {

        return $hashedPassword === $this->hash($plainPassword, $salt);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return false;
    }
}
