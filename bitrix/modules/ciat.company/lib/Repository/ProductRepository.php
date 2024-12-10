<?php
namespace CIAT\Company\Repository;

use CIAT\Company\Model\Product;
use Bitrix\Main\Application;

/**
 * Репозиторий для хранения продуктов.
 * Можно было бы использовать Bitrix ORM или hl-блоки
 */
class ProductRepository
{
    protected $tableName = 'b_ciat_company_products';

    public function __construct()
    {
        // При необходимости можно проверять наличие таблицы, или работать с HL-блоком.
    }

    public function save(Product $product): bool
    {
        $connection = Application::getConnection();
        $existing = $connection->query("SELECT ID FROM {$this->tableName} WHERE ID='".intval($product->getId())."'")->fetch();

        if ($existing) {
            $sql = "UPDATE {$this->tableName} SET 
                NAME='".$connection->getSqlHelper()->forSql($product->getName())."',
                PRICE='".floatval($product->getPrice())."'
                WHERE ID='".intval($product->getId())."'";
        } else {
            $sql = "INSERT INTO {$this->tableName}(ID, NAME, PRICE) VALUES('".intval($product->getId())."', '".$connection->getSqlHelper()->forSql($product->getName())."', '".floatval($product->getPrice())."')";
        }

        return $connection->query($sql)->isSuccess();
    }

}
