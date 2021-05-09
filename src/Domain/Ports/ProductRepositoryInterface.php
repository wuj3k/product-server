<?php declare(strict_types=1);


namespace App\Domain\Ports;


use App\Domain\Model\Product;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;

interface ProductRepositoryInterface
{
    public function isProductNameAlreadyTaken(string $productName): bool;

    public function save(Product $product): void;

    public function getProductById(string $id): ?Product;

    public function deleteProductById(string $id): void;

    /** @return ProductDto[] */
    public function getProductDtoByQuery(ProductQuery $productQuery): array;

    public function getProductDtoById(string $id): ?ProductDto;
}