<?php declare(strict_types=1);


namespace App\Infrastructure\Controller;


use App\Domain\Model\QueryDTO\ProductDto;
use App\Domain\Model\QueryDTO\ProductQuery;
use App\Domain\ProductFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController
{
    private ProductFacade $productFacade;
    private SerializerInterface $serializer;

    public function __construct(ProductFacade $productFacade)
    {
        $this->productFacade = $productFacade;
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);;
    }

    public function listAction(string $id, Request $request): Response
    {
        $query = new ProductQuery();

        $wantedProduct = new ProductDto('', 0, $id);

        if ($request->query->has('type') && $request->query->has('amount')) {
            $query->setAmount((int)$request->query->get('amount'))->setType((int)$request->query->get('type'));
        }
        $response = $this->productFacade->get($wantedProduct, $query);

        $data = $this->serializer->normalize($response->getData());
        return new JsonResponse($data, $response->getStatusCode());
    }

    public function newAction(Request $request): Response
    {
        $newProduct = $this->serializer->deserialize($request->getContent(), ProductDto::class,'json');

        $response = $this->productFacade->save($newProduct);

        $data = $this->serializer->normalize($response->getData());
        return new JsonResponse($data, $response->getStatusCode());
    }

    public function editAction(string $id, Request $request): Response
    {
        $rawData = json_decode($request->getContent(), true);
        $rawData['id'] = $id;

        $updatedProduct = $this->serializer->denormalize($rawData, ProductDto::class);

        $response = $this->productFacade->update($updatedProduct);

        $data = $this->serializer->normalize($response->getData());
        return new JsonResponse($data, $response->getStatusCode());
    }

    public function deleteAction(string $id): Response
    {
        $response = $this->productFacade->delete(new ProductDto('',0, $id));

        $data = $this->serializer->normalize($response->getData());
        return new JsonResponse($data, $response->getStatusCode());
    }
}