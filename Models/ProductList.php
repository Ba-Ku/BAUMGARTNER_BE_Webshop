<?php


class ProductList
{
    public function __construct()
    {

    }

    public function queryDatabaseForProducts($inputTypeId)
    {
        $linkToDatabase = new Database();
        $pdo = $linkToDatabase->getDatabase();
        $sqlQuery = "SELECT p.name AS productName, p.description AS productDescription, p.base_unit AS productUnit, p.price_of_sale AS productPrice, p.id AS productId, t.name AS productTypeName FROM products p JOIN product_types t ON p.id_product_types=t.id WHERE t.id = {$inputTypeId}";
        try {
            $preparedStatement = $pdo->prepare($sqlQuery);
            $preparedStatement->execute();
            $fetchedArray = $preparedStatement->fetchAll();
            return $fetchedArray;
        } catch (PDOException $exception) {
            echo "ERROR! Exception: " . $exception->getMessage();
        }
    }
}