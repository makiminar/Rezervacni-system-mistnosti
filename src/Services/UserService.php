<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private RoomManagerService $roomManagerService;
    private GroupManagerService $groupManagerService;
    private RoomService $roomService;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param RoomManagerService $roomManagerService
     * @param GroupManagerService $groupManagerService
     * @param RoomService $roomService
     */
    public function __construct(UserRepository $userRepository,
                                EntityManagerInterface $entityManager,
                                RoomManagerService $roomManagerService,
                                GroupManagerService $groupManagerService,
                                RoomService $roomService)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->roomManagerService = $roomManagerService;
        $this->groupManagerService = $groupManagerService;
        $this->roomService = $roomService;
    }


    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @return User[]|array
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param User $user
     */
    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param array $queryParams
     * @return array
     */
    public function filter(array $queryParams): array
    {
        return $this->userRepository->filter(
            ParamsParser::getFilters($queryParams, 'filter_by'),
            ParamsParser::getFilters($queryParams, 'order_by'),
            ParamsParser::getFilters($queryParams, 'paginate')
        );
    }

    public function search(array $searchParams): Collection
    {
        return $this->userRepository->search($searchParams);
    }

    public function getManagedRoomsByRoomAdmin(User $user): Collection
    {
        $roomManager = $this->roomManagerService->find($user->getId());
        return $roomManager->getManagedRooms();
    }

    public function getManagedRoomsByGroupAdmin(User $user): Collection
    {
        $groupManager = $this->groupManagerService->find($user->getId());
        $groups = $groupManager->getGroups();
        return $this->roomService->findByGroups($groups);
    }
}