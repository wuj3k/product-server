<?php declare(strict_types=1);


namespace App\Application\Repository;


use App\Domain\Model\Product;
use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;
use App\Domain\Ports\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DoctrineProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function isProductNameAlreadyTaken(string $productName): bool
    {
        $result = $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->where('t.name = :name')
            ->setParameter('name', $productName)
            ->getQuery()
            ->getSingleScalarResult();
        return (bool)$result;
    }

    public function save(Product $product): void
    {
        try {
            $this->getEntityManager()->beginTransaction();
            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->commit();
        } catch (\Exception $exception) {
            $this->getEntityManager()->rollback();
            throw $exception;
        }
    }

    public function getProductById(string $id): ?Product
    {
        return $this->getEntityManager()->find(Product::class, $id);
    }

    public function deleteProductById(string $id): void
    {
        $product = $this->getEntityManager()->find(Product::class, $id);
        if (!$product) return;
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
    }

    public function getProductDtoByQuery(ProductQuery $productQuery): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->select('NEW \App\Domain\Model\QueryDTO\ProductDto(t.name,t.amount,t.id)');
        if($productQuery->getAmount() !== null && (string)$productQuery) {
            $where = 't.'. $productQuery . ' :amount';
            $queryBuilder
                ->where($where)
                ->setParameter('amount', $productQuery->getAmount());
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function getProductDtoById(string $id): ?ProductDto
    {
        return $this->createQueryBuilder('t')
            ->select('NEW App\Domain\Model\QueryDTO\ProductDto(t.name,t.amount,t.id)')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}