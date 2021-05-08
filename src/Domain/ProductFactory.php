<?php declare(strict_types=1);


namespace App\Domain;


use App\Domain\Model\Exceptions\BusinessLogicException;
use App\Domain\Model\Product;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Ports\ProductRepositoryInterface;

class ProductFactory
{
    public const ERROR_NAME_BLANK = 'Nazwa produktu musi posiadać conajmniej jeden znak';
    public const ERROR_NAME_EXISTS = 'Istnieje produkt z podaną nazwą produktu';
    public const ERROR_AMOUNT_VALUE = 'Ilość produktu musi być większa od zera';

    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getProduct(string $name, int $amount): Product
    {
        return new Product($name, $amount);
    }

    public function validateProductData(ProductDto $productDto): array
    {
        $errors = [];
        if ($productDto->getName() === '') $errors[] = new BusinessLogicException('name', self::ERROR_NAME_BLANK);
        if ($this->repository->isProductNameAlreadyTaken($productDto->getName())) $errors[] = new BusinessLogicException('name', self::ERROR_NAME_EXISTS);
        if ($productDto->getAmount() <= 0) $errors[] = new BusinessLogicException('amount', self::ERROR_AMOUNT_VALUE);

        /** @var BusinessLogicException[] */
        return $errors;
    }
}