<?php


class ShoppingCart
{

    public function __construct()
    {
        if (empty($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
    }

    private function createArticle($inputId)
    {
        $linkToDatabase = new Database();
        $pdo = $linkToDatabase->getDatabase();
        $sqlQuery = "SELECT name, id, price_of_sale FROM products WHERE id={$inputId} LIMIT 1";
        try {
            $preparedStatement = $pdo->prepare($sqlQuery);
            $preparedStatement->execute();
            $fetchedArray = $preparedStatement->fetchAll();
            foreach ($fetchedArray as $item) {
                $article = array();
                $article['articleName'] = $item['name'];
                $article['articleId'] = $item['id'];
                $article['articlePrice'] = $item['price_of_sale'];
                $article['amount'] = 0;
                $_SESSION['cart'][$article['articleId']] = $article;
            }

        } catch (PDOException $exception) {
            echo "ERROR! Exception occured: " . $exception->getMessage();
        }
    }

    private function queryProductsForArticleId($inputId)
    {
        $linkToDatabase = new Database();
        $pdo = $linkToDatabase->getDatabase();
        $sqlQuery = "SELECT name FROM products WHERE id={$inputId}";
        try {
            $preparedStatement = $pdo->prepare($sqlQuery);
            $preparedStatement->execute();
            $fetchedArray = $preparedStatement->fetchAll();
            $existingArticleIds = count($fetchedArray);
            return $existingArticleIds;

        } catch (PDOException $exception) {
            echo "ERROR! Exception occured: " . $exception->getMessage();
        }
    }

    private function checkSessionForArticleIdAndAmount($inputId)
    {
        foreach ($_SESSION['cart'] as $item) {
            if ($item['articleId'] == $inputId && $item['amount'] > 0) {
                return true;
            }
        }
    }

    public function addArticle($inputId)
    {
        $articleExistsInDb = $this->queryProductsForArticleId($inputId);
        if ($articleExistsInDb <= 0) {
            return false;
        } else {
            $articleIsInCart = $this->checkSessionForArticleIdAndAmount($inputId);
            if ($articleIsInCart != true) {
                $this->createArticle($inputId);
            }
            $_SESSION['cart'][$inputId]['amount']++;
            $_SESSION['cart'][$inputId]['totalSumForArticle'] = $_SESSION['cart'][$inputId]['articlePrice'] * $_SESSION['cart'][$inputId]['amount'];
            return true;
        }
    }

    public function removeArticle($inputId)
    {
        $articleIsInCart = $this->checkSessionForArticleIdAndAmount($inputId);
        if ($articleIsInCart == true) {
            $_SESSION['cart'][$inputId]['amount']--;
            $_SESSION['cart'][$inputId]['totalSumForArticle']-=$_SESSION['cart'][$inputId]['articlePrice'];
            return true;
        } else {
            $this->deleteArticleFromCart($inputId);
            return false;
        }

    }

    public function deleteArticleFromCart($inputId)
    {
        unset ($_SESSION['cart'][$inputId]);
    }

    public function listCartContent()
    {
        $cartContent = array();
        foreach ($_SESSION['cart'] as $item) {
            if ($item['amount'] > 0) {
                $cartContent[] = $item;
            }
        }
        $sortByName = array_column($cartContent, 'articleName');
        array_multisort($sortByName, SORT_ASC, $cartContent);
        return $cartContent;
    }

    public function deleteAllArticleGroup($inputId)
    {
        $articleIsInCart = $this->checkSessionForArticleIdAndAmount($inputId);
        if ($articleIsInCart == true) {
            unset ($_SESSION['cart'][$inputId]);
            return true;
        } else {
            return false;
        }
    }

    public function emptyCart()
    {
        unset($_SESSION['cart']);
        return true;
    }

    public function calculateTotalPrice()
    {
        $totalsum = 0;
        foreach ($_SESSION['cart'] as $article) {
            $articleSum = $article['articlePrice'] * $article['amount'];
            $totalsum += $articleSum;
        }
        return $totalsum;
    }
}