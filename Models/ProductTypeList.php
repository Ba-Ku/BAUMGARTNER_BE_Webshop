<?php


class ProductTypeList
{
    public function __construct()
    {

    }

    public function queryDatabaseForProductTypes()
    {
        $linkToDatabase = new Database();
        $pdo = $linkToDatabase->getDatabase();
        $sqlQuery = "Select id, name from product_types order by name";
        try {
            $preparedStatement = $pdo->prepare($sqlQuery);
            $preparedStatement->execute();
            $fetchedArray = $preparedStatement->fetchAll();
            return $fetchedArray;

        } catch (PDOException $exception) {
            echo "ERROR! PDO Exception: " . $exception->getMessage();
        }
    }
}