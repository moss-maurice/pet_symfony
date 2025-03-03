<?php

namespace App\Repository;

use App\Dto\UserDto;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    protected $passwordHasher;
    protected $entityManager;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($registry, User::class);

        $this->passwordHasher = $passwordHasher;

        $this->entityManager = $this->getEntityManager();
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $password): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function createUser(string $name, string $email, string $phone, string $password): User
    {
        $user = new User();

        $user->setName($name);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function hasUser(int $id): bool
    {
        return $this->getUser($id) !== null;
    }

    public function hasUserBy(array $conditions): bool
    {
        return $this->getUserBy($conditions) !== null;
    }

    public function getUser(int $id): User|null
    {
        return $this->find($id);
    }

    public function getUserBy(array $conditions): User|null
    {
        return $this->findOneBy($conditions);
    }

    public function delete(User $user): bool
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return !$this->hasUser($user->getId());
    }

    public function grantUser(User $user, string $role): User
    {
        $user->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->getUser($user->getId());
    }
}
