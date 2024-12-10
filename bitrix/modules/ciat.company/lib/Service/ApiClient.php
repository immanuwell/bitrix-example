<?php
namespace CIAT\Company\Service;

use Bitrix\Main\Web\HttpClient;

class ApiClient
{
    protected $apiUrl;
    protected $apiKey;
    
    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->apiKey = $apiKey;
    }
    
    public function fetchProducts(): array
    {
        $client = new HttpClient();
        $client->setHeader('Authorization', 'Bearer ' . $this->apiKey);
        $response = $client->get($this->apiUrl);
        
        if ($response && $client->getStatus() == 200) {
            $data = json_decode($response, true);
            return is_array($data) ? $data : [];
        }
        
        return [];
    }
}
