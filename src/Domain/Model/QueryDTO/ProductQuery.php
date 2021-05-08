<?php declare(strict_types=1);


namespace App\Domain\Model\QueryDTO;


class ProductQuery
{
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}