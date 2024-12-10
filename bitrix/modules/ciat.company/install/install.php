<?php
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ciat_company_installer
{
    const MODULE_ID = "ciat.company";
    const MODULE_TABLE_NAME = "b_ciat_company_products";
    const AGENT_FUNCTION = "CIAT\\Company\\Service\\ProductsFetcher::syncProducts();";

    public function DoInstall()
    {
        global $APPLICATION;
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();
        RegisterModule(self::MODULE_ID);

        $this->InstallDefaultOptions();

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("CIAT_COMPANY_INSTALL_TITLE"),
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/step.php"
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();
        UnRegisterModule(self::MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("CIAT_COMPANY_UNINSTALL_TITLE"),
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/unstep.php"
        );
    }

    protected function InstallDB()
    {
        $connection = Application::getConnection();
        $helper = $connection->getSqlHelper();

        // Создание таблицы для хранения данных о продуктах
        if(!$connection->isTableExists(self::MODULE_TABLE_NAME))
        {
            $sql = "CREATE TABLE IF NOT EXISTS `".self::MODULE_TABLE_NAME."` (
                `ID` INT(11) NOT NULL,
                `NAME` VARCHAR(255) NOT NULL,
                `PRICE` DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (`ID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            $connection->queryExecute($sql);
        }
    }

    protected function UnInstallDB()
    {
        $connection = Application::getConnection();
        if($connection->isTableExists(self::MODULE_TABLE_NAME))
        {
            $connection->queryExecute("DROP TABLE `".self::MODULE_TABLE_NAME."`");
        }
    }

    protected function InstallEvents()
    {
        // Регистрация агента, который будет раз в сутки вызывать синхронизацию
        \CAgent::AddAgent(
            self::AGENT_FUNCTION, 
            self::MODULE_ID, 
            "N", 
            86400, 
            "", 
            "Y",
            "", 
            100,
            false,
            false
        );
    }

    protected function UnInstallEvents()
    {
        \CAgent::RemoveModuleAgents(self::MODULE_ID);
    }

    protected function InstallFiles()
    {
        // Копируем админские файлы
        $adminSource = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/admin/ciat_company_admin.php";
        $adminDest = $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/ciat_company_admin.php";

        if (!file_exists($adminDest) && file_exists($adminSource)) {
            @copy($adminSource, $adminDest);
        }

        // Копируем файл для tools
        $toolsSource = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/tools/sync.php";
        $toolsDestDir = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/";
        if(file_exists($toolsSource))
        {
            @copy($toolsSource, $toolsDestDir."ciat_company_sync.php");
        }
    }

    protected function UnInstallFiles()
    {
        // Удаляем ранее скопированные административные файлы
        $adminFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/ciat_company_admin.php";
        if (file_exists($adminFile)) {
            @unlink($adminFile);
        }

        $toolsFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/ciat_company_sync.php";
        if (file_exists($toolsFile)) {
            @unlink($toolsFile);
        }
    }

    protected function InstallDefaultOptions()
    {
        $defaultOptions = include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/default_option.php");
        if (is_array($defaultOptions)) {
            foreach($defaultOptions as $optionName => $optionValue) {
                if (Option::get(self::MODULE_ID, $optionName, null) === null) {
                    Option::set(self::MODULE_ID, $optionName, $optionValue);
                }
            }
        }
    }
}
