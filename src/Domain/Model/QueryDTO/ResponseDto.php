<?php declare(strict_types=1);


namespace App\Domain\Model\QueryDTO;


class ResponseDto
{
    private int $statusCode;
    private array $data;

    public function __construct(array $message, int $statusCode)
    {
        $this->data = $message;
        $this->statusCode = $statusCode;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}