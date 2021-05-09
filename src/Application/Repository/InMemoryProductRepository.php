<?php declare(strict_types=1);


namespace App\Application\Repository;


use App\Domain\Model\Product;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;
use App\Domain\Ports\ProductRepositoryInterface;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    /** @var Product[] */
    private array $products = [];
    private ProductQuery $query;

    public function isProductNameAlreadyTaken(string $productName): bool
    {
        $result = false;
        foreach ($this->products as $product) {
            if ($product->getName() === $productName) $result = true;
        }
        return $result;
    }

    public function save(Product $product): void
    {
        $this->products[$product->getId()] = $product;
    }

    public function deleteProductById(string $id): void
    {
        unset($this->products[$id]);
    }

    public function getProductDtoById(string $id): ?ProductDto
    {
        $product = $this->getProductById($id);
        if (empty($product)) return null;

        return new ProductDto($product->getName(), $product->getAmount(), $product->getId());
    }

    public function getProductById(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function getProductDtoByQuery(ProductQuery $productQuery): array
    {
        $this->query = $productQuery;
        switch ($productQuery->getType()) {
            case ProductQuery::QUERY_AMOUNT_TYPE_EQUAL:
                $result = array_filter($this->products, [$this, 'equal']);
                break;
            case ProductQuery::QUERY_AMOUNT_TYPE_LESS:
                $result = array_filter($this->products, [$this, 'less']);
                break;
            case ProductQuery::QUERY_AMOUNT_TYPE_GREATER:
                $result = array_filter($this->products, [$this, 'greater']);
                break;
            default:
                $result = $this->products;
                break;
        }
        return $result;
    }

    public function equal(Product $productDto): bool
    {
        return $productDto->getAmount() === $this->query->getAmount();
    }

    public function less(Product $productDto): bool
    {
        return $productDto->getAmount() < $this->query->getAmount();
    }

    public function greater(Product $productDto): bool
    {
        return $productDto->getAmount() > $this->query->getAmount();
    }

    public function deleteAllProducts(): void
    {
        $this->products = [];
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}