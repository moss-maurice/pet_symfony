<?php

namespace App\Tests\Service;

use App\Service\GeneratorService;
use App\Service\ProductService;
use App\Tests\Traits\TestHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ProductControllerTest extends WebTestCase
{
    use TestHelper;

    private KernelBrowser $client;
    private TestContainer $container;
    private EntityManagerInterface $entityManager;
    private GeneratorService $generatorService;
    private ProductService $productService;
    private SerializerInterface $serialize;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->container = static::getContainer();

        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->generatorService = $this->container->get(GeneratorService::class);

        $this->productService = $this->container->get(ProductService::class);

        $this->serialize = $this->container->get(SerializerInterface::class);
    }

    protected function insertProductsItems(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $product = $this->generatorService->product();

            $productJson = $this->serialize->serialize($product, JsonEncoder::FORMAT, [
                'groups' => ['generator'],
            ]);

            $this->productService->createProductFromJson($productJson);
        }
    }

    public function testIndexPageExpectResponseIsSuccessful(): void
    {
        $this->insertProductsItems(20);

        $crawler = $this->client->request('GET', '/api/product', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');

        $this->assertResponseIsSuccessful();
    }

    public function testIndexPageExpectProductItemsSuccessful(): void
    {
        $this->insertProductsItems(20);

        $crawler = $this->client->request('GET', '/api/product', [], [], ['CONTENT_TYPE' => 'application/json'], '{"page": 1, "limit": 20}');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();

        $this->assertCount(20, $responseData['items']);
    }

    public function testIndexPageExpectProductItemsJsonStructureSuccessful(): void
    {
        $this->insertProductsItems(20);

        $crawler = $this->client->request('GET', '/api/product', [], [], ['CONTENT_TYPE' => 'application/json'], '{"page": 1, "limit": 20}');

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();

        $this->assertJsonStructure([
            'items' => [
                [
                    'id',
                    'name',
                    'description',
                    'cost',
                    'tax',
                    'version',
                    'measurements' => [
                        'weight',
                        'length',
                        'width',
                        'height',
                    ],
                ],
            ],
            'total',
            'page',
            'limit',
        ], $responseData);
    }
}
