<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAllUsers(array $filters = []): LengthAwarePaginator;
    public function getUserById(int $userId);
    public function createUser(array $userDetails);
    public function updateUser(int $userId, array $userDetails);
    public function deleteUser(int $userId);

    // Özel metodlar
    public function countUsers(): int;
    public function getRecentUsers(int $limit = 5): Collection;
    public function getUsersByRole(string $role): LengthAwarePaginator;
    public function searchUsers(string $query): LengthAwarePaginator;
    public function countUsersByRole(string $role): int;
    public function getInstructorsWithStats(): Collection;
}
