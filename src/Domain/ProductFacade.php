<?php declare(strict_types=1);


namespace App\Domain;


use App\Domain\Model\Product;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ResponseDto;
use App\Domain\Ports\ProductRepositoryInterface;

class ProductFacade
{
    private ProductFactory $factory;
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductFactory $factory, ProductRepositoryInterface $productRepository)
    {
        $this->factory = $factory;
        $this->productRepository = $productRepository;
    }

    public function save(ProductDto $productDto): ResponseDto
    {
        $errors = $this->factory->validateProductData($productDto);

        if (!empty($errors)) {
            return $this->getResponse(422, $errors);
        }

        $product = new Product($productDto->getName(), $productDto->getAmount());

        try {
            $this->productRepository->save($product);
            return $this->getResponse(201, [], 'Zapis produktu przebieg pomyślnie');
        } catch (\Exception $exception) {
            return $this->getResponse($exception->getCode(), [], 'Wystapił błąd, proszę spróbować później');
        }
    }

    private function getResponse(int $statusCode, array $data, string $message = ''): ResponseDto
    {
        if (empty($data)) return new ResponseDto(['message' => $message], $statusCode);
        return new ResponseDto($data, $statusCode);
    }
}