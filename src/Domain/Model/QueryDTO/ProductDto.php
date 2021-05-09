<?php declare(strict_types=1);


namespace App\Domain\Model\QueryDTO;


class ProductDto
{
    private string $name;
    private string $id = '';
    private int $amount;

    public function __construct(string $name = '', int $amount = 0, string $id = '')
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->id = $id;
    }

    public function __toArray(): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'amount' => $this->getAmount(),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}