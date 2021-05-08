<?php declare(strict_types=1);


namespace App\Domain\Model;


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
}