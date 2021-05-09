<?php declare(strict_types=1);


namespace App\Tests\Domain;


use App\Application\Repository\InMemoryProductRepository;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;
use App\Domain\ProductFacade;
use App\Domain\ProductFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductFacadeTest extends WebTestCase
{
    private ProductFacade $productFacade;
    private InMemoryProductRepository $repository;

    public function testSaveProductWhenAllProductDataIsCorrect()
    {
        $firstProduct = (new ProductDto())->setName('Rower')->setAmount(2);
        $response = $this->productFacade->save($firstProduct);

        $productsInRepository = $this->repository->getProducts();

        $id = array_key_first($productsInRepository);
        $productInRepository = $productsInRepository[$id];

        $this->assertSame($firstProduct->getName(), $productInRepository->getName());
        $this->assertSame($firstProduct->getAmount(), $productInRepository->getAmount());
        $this->assertSame(201, $response->getStatusCode());
    }

    public function testGetErrorMessageWhenProductNameIsInCorrect()
    {
        $firstProduct = (new ProductDto())->setName('')->setAmount(2);
        $response = $this->productFacade->save($firstProduct);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('name', $response->getData()[0]->getField());
        $this->assertEquals(ProductFactory::ERROR_NAME_BLANK, $response->getData()[0]->getMessage());
    }

    public function testGetErrorMessageWhenProductAmountIsInCorrect()
    {
        $firstProduct = (new ProductDto())->setName('Rower')->setAmount(-2);
        $response = $this->productFacade->save($firstProduct);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals('amount', $response->getData()[0]->getField());
        $this->assertEquals(ProductFactory::ERROR_AMOUNT_VALUE, $response->getData()[0]->getMessage());
    }

    public function testGetEmptyResponseWithEmptyDB()
    {
        $wantedProduct = new ProductDto();
        $query = new ProductQuery();
        $response = $this->productFacade->get($wantedProduct, $query);

        $expected['message'] = 'Brak produktów w bazie';
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertSame($expected, $response->getData());
    }

    public function testGetAllProductWhenQueryIsEmpty()
    {
        $this->saveProductToDb();

        $wantedProduct = new ProductDto();
        $query = new ProductQuery();

        $response = $this->productFacade->get($wantedProduct, $query);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertCount(3, $response->getData());
    }

    public function testGetAvailableProducts()
    {
        $this->saveProductToDb();
        $wantedProduct = new ProductDto();
        $query = (new ProductQuery())->setAmount(0)->setType(ProductQuery::QUERY_AMOUNT_TYPE_GREATER);

        $response = $this->productFacade->get($wantedProduct, $query);

        $wantedProductNames = [];
        $expected = ['Samochod', 'Samolot'];
        foreach ($response->getData() as $datum) {
            $wantedProductNames[] = $datum->getName();
        }
        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame($expected, $wantedProductNames);
        $this->assertCount(2, $response->getData());
    }

    public function testGetNotAvailableProducts()
    {
        $this->saveProductToDb();
        $wantedProduct = new ProductDto();
        $query = (new ProductQuery())->setAmount(0)->setType(ProductQuery::QUERY_AMOUNT_TYPE_EQUAL);

        $response = $this->productFacade->get($wantedProduct, $query);

        $wantedProductNames = [];
        $expected = ['Rower'];
        foreach ($response->getData() as $datum) {
            $wantedProductNames[] = $datum->getName();
        }
        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame($expected, $wantedProductNames);
        $this->assertCount(1, $response->getData());
    }

    public function testGetProductsWhenAmountIsGreaterThanFive()
    {
        $this->saveProductToDb();
        $wantedProduct = new ProductDto();
        $query = (new ProductQuery())->setAmount(5)->setType(ProductQuery::QUERY_AMOUNT_TYPE_GREATER);

        $response = $this->productFacade->get($wantedProduct, $query);

        $wantedProductNames = [];
        $expected = ['Samolot'];
        foreach ($response->getData() as $datum) {
            $wantedProductNames[] = $datum->getName();
        }
        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame($expected, $wantedProductNames);
        $this->assertCount(1, $response->getData());
    }

    public function testGetProductByIdWhenIdIsAvailable()
    {
        $firstProduct = (new ProductDto())->setName('Rower')->setAmount(2);
        $this->productFacade->save($firstProduct);

        $productsInRepository = $this->repository->getProducts();
        $id = array_key_first($productsInRepository);

        $wantedProduct = (clone $firstProduct)->setId($id);
        $query = new ProductQuery();

        $response = $this->productFacade->get($wantedProduct, $query);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($wantedProduct, $response->getData()[0]);
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $this->productFacade = self::$kernel->getContainer()->get(\App\Domain\ProductFacade::class);
        $this->repository = self::$kernel->getContainer()->get(InMemoryProductRepository::class);

    }

    protected function tearDown(): void
    {
        $this->repository->deleteAllProducts();
    }

    private function saveProductToDb(): void
    {
        $firstProduct = (new ProductDto())->setName('Rower')->setAmount(0);
        $secondProduct = (new ProductDto())->setName('Samochod')->setAmount(5);
        $thirdProduct = (new ProductDto())->setName('Samolot')->setAmount(7);

        $this->productFacade->save($firstProduct);
        $this->productFacade->save($secondProduct);
        $this->productFacade->save($thirdProduct);
    }
}