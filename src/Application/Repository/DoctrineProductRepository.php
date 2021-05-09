<?php declare(strict_types=1);


namespace App\Application\Repository;


use App\Domain\Model\Product;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;
use App\Domain\Ports\ProductRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function isProductNameAlreadyTaken(string $productName): bool
    {
        // TODO: Implement isProductNameAlreadyTaken() method.
    }

    public function save(Product $product): void
    {
        // TODO: Implement save() method.
    }

    public function getProductById(string $id): ?Product
    {
        // TODO: Implement getProductById() method.
    }

    public function deleteProductById(string $id): void
    {
        // TODO: Implement deleteProductById() method.
    }

    /**
     * @inheritDoc
     */
    public function getProductDtoByQuery(ProductQuery $productQuery): array
    {
        // TODO: Implement getProductDtoByQuery() method.
    }

    public function getProductDtoById(string $id): ?ProductDto
    {
        // TODO: Implement getProductDtoById() method.
    }
}