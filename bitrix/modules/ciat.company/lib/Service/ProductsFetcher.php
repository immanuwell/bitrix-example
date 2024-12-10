<?php
namespace CIAT\Company\Service;

use CIAT\Company\Repository\ProductRepository;
use CIAT\Company\Model\Product;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

/**
 * Класс для автоматической синхронизации товаров с внешним сервисом.
 */
class ProductsFetcher
{
    protected $apiClient;
    protected $productRepo;

    public function __construct(ApiClient $apiClient, ProductRepository $productRepo)
    {
        $this->apiClient = $apiClient;
        $this->productRepo = $productRepo;
    }

    public static function syncProducts()
    {
        $moduleId = "ciat.company";
        $apiUrl = Option::get($moduleId, "API_URL", "");
        $apiKey = Option::get($moduleId, "API_KEY", "");

        $apiClient = new ApiClient($apiUrl, $apiKey);
        $productRepo = new ProductRepository();
        $fetcher = new self($apiClient, $productRepo);

        $data = $fetcher->apiClient->fetchProducts();
        foreach($data as $item) {
            $product = new Product($item['ID'], $item['NAME'], $item['PRICE']);
            $fetcher->productRepo->save($product);
        }

        return "CIAT\\Company\\Service\\ProductsFetcher::syncProducts();";
    }
}
