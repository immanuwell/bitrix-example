<?php
use Bitrix\Main\Loader;

IncludeModuleLangFile(__FILE__);

if (class_exists('ciat_company')) {
    return;
}

class ciat_company extends CModule
{
    public $MODULE_ID = 'ciat.company';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME = "CIAT-company";
    public $PARTNER_URI = "https://www.ciat-company.com";
    
    public function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . "/install/version.php";
        
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = GetMessage("CIAT_COMPANY_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("CIAT_COMPANY_MODULE_DESC");
    }
}

CModule::AddAutoloadClasses(
    'ciat.company',
    array(
        'CIAT\\Company\\Service\\ApiClient'         => 'lib/Service/ApiClient.php',
        'CIAT\\Company\\Service\\ProductsFetcher'    => 'lib/Service/ProductsFetcher.php',
        'CIAT\\Company\\Repository\\ProductRepository' => 'lib/Repository/ProductRepository.php',
        'CIAT\\Company\\Model\\Product'             => 'lib/Model/Product.php',
    )
);
