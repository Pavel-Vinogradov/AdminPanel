<?php

namespace App\Domain\Users\Controllers;

use App\Core\DTO\PaginationDTO;

use App\Domain\Users\Resources\UserResource;
use App\Domain\Users\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
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

    /**
     * Обработка SOAP-запросов.
     */
    public function soap(): Response
    {
        $wsdlPath = storage_path('app/wsdl/users.wsdl');

        $server = new \SoapServer($wsdlPath, [
            'cache_wsdl' => WSDL_CACHE_NONE,
        ]);

        // Привязываем класс с логикой
        $server->setClass(SoapUserService::class, $this->userService);

        // Обработка входящего SOAP-запроса
        ob_start();
        $server->handle();
        $response = ob_get_clean();

        // Возвращаем ответ клиенту
        return response($response, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
        ]);
    }

}
