<?php declare(strict_types=1);


namespace App\Domain\Ports;


use App\Domain\Model\Product;

interface ProductRepositoryInterface
{
    public function isProductNameAlreadyTaken(string $productName): bool;

    public function save(Product $product): void;
}