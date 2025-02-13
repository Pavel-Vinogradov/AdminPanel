<?php

declare(strict_types=1);

namespace App\Core\DTO;

use Tizix\DataTransferObject\DataTransferObject;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

final class PaginationDTO extends DataTransferObject
{
    public int $perPage = 20; // Количество элементов на странице (по умолчанию 20)

    public int $currentPage = 1; // Текущая страница (по умолчанию 1)

    public ?string $search = null; // Поисковый запрос (опционально)

    /**
     * Создание DTO из массива данных.
     *
     * @return static
     *
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public static function fromRequest(array $data): self
    {
        return new self([
            'perPage' => isset($data['per_page']) && is_numeric($data['per_page'])
                ? max(1, (int) $data['per_page']) // Минимальное значение 1
                : 20,
            'currentPage' => isset($data['page']) && is_numeric($data['page'])
                ? max(1, (int) $data['page']) // Минимальное значение 1
                : 1,
            'search' => isset($data['search']) ? trim($data['search']) : null,
        ]);
    }
}
