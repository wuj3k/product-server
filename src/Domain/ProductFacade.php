<?php declare(strict_types=1);


namespace App\Domain;


use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;
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

    public function get(ProductDto $productDto, ProductQuery $productQuery): ResponseDto
    {
        $statusCode = 201;

        if(empty($productDto->getId())) $data = $this->productRepository->getProductByQuery($productQuery);
        else $data = $this->productRepository->getProductById($productDto->getId());

        if (empty($data)) $statusCode = 404;

        return $this->getResponse($statusCode, $data);
    }
    
    public function save(ProductDto $productDto): ResponseDto
    {
        $errors = $this->factory->validateProductData($productDto);

        if (!empty($errors)) {
            return $this->getResponse(422, $errors);
        }

        $product = $this->factory->getProduct($productDto->getName(), $productDto->getAmount());

        try {
            $this->productRepository->save($product);
            return $this->getResponse(201, [], 'Zapis produktu przebieg pomyślnie');
        } catch (\Exception $exception) {
            return $this->getResponse($exception->getCode(), [], 'Wystapił błąd, proszę spróbować później');
        }
    }


    public function update(ProductDto $productDto): ResponseDto
    {
        $errors = $this->factory->validateProductData($productDto);

        if (!empty($errors)) {
            return $this->getResponse(422, $errors);
        }

        $updatedProduct = $this->productRepository->getProductById($productDto->getId());

        if (!$updatedProduct) return $this->getResponse(400, [], 'Nie ma takiego produktu z podanym Id');

        $updatedProduct->updateData($productDto);

        try {
            $this->productRepository->save($updatedProduct);
            return $this->getResponse(204, [], 'Produkt został zaaktualizowany');
        } catch (\Exception $exception) {
            return $this->getResponse($exception->getCode(), [], 'Wystapił błąd, proszę spróbować później');
        }
    }

    public function delete(ProductDto $productDto): ResponseDto
    {
        try {
            $this->productRepository->deleteProductById($productDto->getId());
            return $this->getResponse(204, [], 'Produkt został usunięty pomyślnie');
        } catch (\Exception $exception) {
            return $this->getResponse($exception->getCode(), [], 'Wystapił błąd, proszę spróbować później');
        }
    }

    private function getResponse(int $statusCode, array $data, string $message = ''): ResponseDto
    {
        if (empty($data)) $data['message'] = $message;
        return new ResponseDto($data, $statusCode);
    }
}