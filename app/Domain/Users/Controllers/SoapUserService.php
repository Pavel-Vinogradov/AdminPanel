<?php

declare(strict_types=1);

namespace App\Domain\Users\Controllers;
use App\Domain\Users\Services\UserServiceInterface;


readonly class SoapUserService
{
    public function __construct(private UserServiceInterface $userService)
    {
    }

    /**
     * Получение пользователя по ID.
     */
    public function getUserById(array $request): array
    {
        if (!isset($request['id'])) {
            throw new \SoapFault('InvalidRequest', 'Отсутствует параметр "id"');
        }

        $id = $request['id'];
        $user = $this->userService->find($id);

        if (!$user) {
            throw new \SoapFault('UserNotFound', 'Пользователь не найден');
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
