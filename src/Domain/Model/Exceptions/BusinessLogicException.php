<?php declare(strict_types=1);


namespace App\Domain\Model\Exceptions;


class BusinessLogicException
{
    private string $field;
    private string $message;

    public function __construct(string $field, string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}