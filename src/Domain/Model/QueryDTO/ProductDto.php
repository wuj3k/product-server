<?php declare(strict_types=1);


namespace App\Domain\Model\QueryDTO;


class ProductDto
{
    private string $name;
    private string $id = '';
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

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function __toArray(): array
    {
        return [
          'name' => $this->getName(),
          'id' => $this->getId(),
          'amount' => $this->getAmount(),
        ];
    }
}