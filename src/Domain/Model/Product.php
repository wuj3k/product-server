<?php declare(strict_types=1);


namespace App\Domain\Model;


use App\Domain\Model\QueryDTO\ProductDto;

class Product
{
    use IdMaker;
    private string $id;
    private string $name;
    private int $amount;

    public function __construct(string $name, int $amount)
    {
        $this->id = $this->makeId();
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