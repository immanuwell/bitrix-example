<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__."/../../../..");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
Loader::includeModule("ciat.company");

\CIAT\Company\Service\ProductsFetcher::syncProducts();

echo "Sync completed.";
