<?php
declare(strict_types = 1);
/**
 * /src/Repository/LogLoginFailureRepository.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Repository;

use App\Entity\LogLoginFailure as Entity;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/** @noinspection PhpHierarchyChecksInspection */
/**
 * Class LogLoginFailureRepository
 *
 * @package App\Repository
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null       find(string $id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Entity|array|null findAdvanced(string $id, $hydrationMode = null)
 * @method Entity|null       findOneBy(array $criteria, ?array $orderBy = null)
 * @method Entity[]          findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Entity[]          findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null): array
 * @method Entity[]          findAll()
 *
 * @codingStandardsIgnoreEnd
 */
class LogLoginFailureRepository extends BaseRepository
{
    /**
     * @var string
     */
    protected static $entityName = Entity::class;

    /**
     * Method to clear specified user login failures.
     *
     * @param UserInterface|User $user
     *
     * @return int
     */
    public function clear(UserInterface $user): int
    {
        // Create query builder and define delete query
        $queryBuilder = $this
            ->createQueryBuilder('logLoginFailure')
            ->delete()
            ->where('logLoginFailure.user = :user')
            ->setParameter('user', $user);

        // Return deleted row count
        return (int)$queryBuilder->getQuery()->execute();
    }
}
