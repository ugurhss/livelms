<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(array $filters = []): LengthAwarePaginator
    {
        $query = User::query();

        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%'.$filters['search'].'%')
                  ->orWhere('email', 'like', '%'.$filters['search'].'%');
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getUserById(int $userId)
    {
        return User::findOrFail($userId);
    }

    public function createUser(array $userDetails)
    {
        return User::create($userDetails);
    }

    public function updateUser(int $userId, array $userDetails)
    {
        $user = User::findOrFail($userId);
        $user->update($userDetails);
        return $user;
    }

    public function deleteUser(int $userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();
        return $user;
    }

    public function countUsers(): int
    {
        return User::count();

    }

    public function getRecentUsers(int $limit = 5): Collection
    {
        return User::latest()->limit($limit)->get();
    }

    public function getUsersByRole(string $role): LengthAwarePaginator
    {
        return User::where('role', $role)->paginate();
    }

    public function searchUsers(string $query): LengthAwarePaginator
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->paginate();
    }

    public function countUsersByRole(string $role): int
    {
        return User::where('role', $role)->count();
    }

    public function getInstructorsWithStats(): Collection
    {
        return User::where('role', 'instructor')
            ->withCount(['courses', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->get();
    }
}
