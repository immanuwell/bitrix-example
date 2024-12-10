<?php
$arModuleVersion = array();
include(__DIR__."/install/version.php");

$arModuleDescription = array(
    "VERSION" => $arModuleVersion["VERSION"],
    "VERSION_DATE" => $arModuleVersion["VERSION_DATE"],
    "NAME" => GetMessage("CIAT_COMPANY_MODULE_NAME"),
    "DESCRIPTION" => GetMessage("CIAT_COMPANY_MODULE_DESC"),
    "PARTNER_NAME" => "CIAT-company",
    "PARTNER_URI" => "https://www.ciat-company.com",
);
