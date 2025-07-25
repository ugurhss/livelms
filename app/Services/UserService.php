<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllUsers(array $filters = [])
    {
        return $this->repository->getAllUsers($filters);
    }

    public function getUser(int $userId)
    {
        return $this->repository->getUserById($userId);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->createUser($data);
    }

    public function updateUser(int $userId, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->repository->updateUser($userId, $data);
    }

    public function deleteUser(int $userId)
    {
        // Admin silinemez kontrolü
        $user = $this->repository->getUserById($userId);
        if ($user->role === 'admin') {
            throw ValidationException::withMessages([
                'error' => 'Admin kullanıcıları silinemez'
            ]);
        }

        return $this->repository->deleteUser($userId);
    }



    public function getInstructorsWithStats()
    {
        return $this->repository->getInstructorsWithStats();
    }
      public function getSystemStats(): array
    {
        return [
            'toplam_kullanici' => $this->repository->countUsers(),
            'toplam_ogrenci' => $this->repository->countUsersByRole('student'),
            'toplam_egitmen' => $this->repository->countUsersByRole('instructor'),
            'yeni_kullanicilar' => $this->repository->getRecentUsers()
        ];
    }
}
