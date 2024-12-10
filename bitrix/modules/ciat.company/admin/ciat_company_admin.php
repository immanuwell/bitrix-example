<?php
use Bitrix\Main\Loader;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
$module_id = "ciat.company";

if(!Loader::includeModule($module_id)){
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetTitle(GetMessage("CIAT_COMPANY_ADMIN_TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

echo BeginNote();
echo GetMessage("CIAT_COMPANY_ADMIN_INFO");
echo EndNote();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
