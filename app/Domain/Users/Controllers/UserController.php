<?php

namespace App\Domain\Users\Controllers;

use App\Core\DTO\PaginationDTO;

use App\Domain\Users\Resources\UserResource;
use App\Domain\Users\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class UserController extends Controller
{

    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function index(Request $request): View
    {
        $dto = PaginationDTO::fromRequest($request->toArray());
        $users = $this->userService->paginate($dto);
        $users = UserResource::collection($users);

        return view('users.index', compact('users'));
    }


}
