<?php declare(strict_types=1);


namespace App\Domain\Model\QueryDTO;


class ProductDto
{
    private string $name;
    private int $amount;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name = ''): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount = 0): self
    {
        $this->amount = $amount;
        return $this;
    }
}