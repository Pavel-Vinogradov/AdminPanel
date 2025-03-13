<?php

namespace App\Domain\Users\Controllers;

use App\Core\DTO\PaginationDTO;

use App\Domain\Users\Resources\UserResource;
use App\Domain\Users\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use SoapServer;
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

    public function wsdl(): Response
    {
        $wsdl = Storage::disk('public')->get('users.wsdl');
        return response($wsdl, 200, [
            'Content-Type' => 'text/xml; charset=utf-8'
        ]);
    }
}
