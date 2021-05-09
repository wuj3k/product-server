<?php declare(strict_types=1);


namespace App\Domain\Model\QueryDTO;


class ProductQuery
{
    public const QUERY_AMOUNT_TYPE_TURN_OFF = 0;
    public const QUERY_AMOUNT_TYPE_EQUAL = 1;
    public const QUERY_AMOUNT_TYPE_LESS = 2;
    public const QUERY_AMOUNT_TYPE_GREATER = 3;
    public const QUERY_AMOUNT_TYPE_CHAR = [
        self::QUERY_AMOUNT_TYPE_EQUAL => '=',
        self::QUERY_AMOUNT_TYPE_LESS => '<',
        self::QUERY_AMOUNT_TYPE_GREATER => '>',
    ];
    private ?int $amount = null;
    private int $type = self::QUERY_AMOUNT_TYPE_TURN_OFF;

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): ProductQuery
    {
        $this->amount = $amount;
        return $this;
    }

    public function __toString(): string
    {
        $result = '';
        if (self::QUERY_AMOUNT_TYPE_CHAR[$this->getType()] ?? null) {
            $result = 'amount ' . self::QUERY_AMOUNT_TYPE_CHAR[$this->getType()];
        }
        return $result;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): ProductQuery
    {
        $this->type = $type;
        return $this;
    }
}