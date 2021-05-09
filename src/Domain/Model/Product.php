<?php declare(strict_types=1);


namespace App\Domain\Model;


use App\Domain\Model\QueryDTO\ProductDto;

class Product
{
    private string $id;
    private string $name;
    private int $amount;

    public function __construct(string $name, int $amount)
    {
        $this->id = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $this->name = $name;
        $this->amount = $amount;
    }

    public function updateData(ProductDto $productDto): void
    {
        if ($productDto->getName() !== $this->name) $this->name = $productDto->getName();
        if ($productDto->getAmount() !== $this->amount) $this->amount = $productDto->getAmount();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}