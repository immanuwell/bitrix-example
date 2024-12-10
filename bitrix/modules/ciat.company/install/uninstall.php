<?php
include_once(__DIR__ . "/install.php");

$installer = new ciat_company_installer();
$installer->DoUninstall();
