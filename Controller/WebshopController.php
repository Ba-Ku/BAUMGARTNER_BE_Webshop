<?php


class WebshopController extends InputValidation
{
    public function __construct()
    {

    }

    public function route()
    {
        if (isset($_GET['action'])) {
            $selectedAction = $_GET['action'];
            $validatedAction = $this->validateString($selectedAction);
            $this->selectOptions($validatedAction);
        }
    }

    public function manageSession()
    {
        $sessionManager = new SessionManager();
        $sessionManager->logIn();
        $sessionManager->setCookie();
    }

    private function getTypeId()
    {
        if (isset($_GET['typeId'])) {
            $selectedTypeId = $_GET['typeId'];
            $validatedTypeId = $this->validateString($selectedTypeId);
            return $validatedTypeId;
        }
    }

    private function getArticleId()
    {
        if (isset($_GET['articleId'])) {
            $selectedArticleId = $_GET['articleId'];
            $validatedArticleId = $this->validateString($selectedArticleId);
            return $validatedArticleId;
        }
    }

    private function selectOptions($selectedAction)
    {
        switch ($selectedAction) {
            case "LISTPRODUCTSBYTYPEID":
                $typeId = $this->getTypeId();
                $this->displayProductList($typeId);
                break;
            case "LISTTYPES":
                $this->displayProductTypeList();
                break;
            case "ADDARTICLE":
                $articleId = $this->getArticleId();
                $this->addArticleToCart($articleId);
                break;
            case "REMOVEARTICLE":
                $articleId = $this->getArticleId();
                $this->removeArticleFromCart($articleId);
                break;
            case "LISTCART":
                $this->displayCartContent();
                break;
            case "EMPTYCART":
                $this->emptyCart();
                break;
            case "DELETEARTICLETYPE":
                $articleId = $this->getArticleId();
                $this->deleteArticleTypeFromCart($articleId);
                break;
            case "CALCULATETOTALSUM":
                $this->calculateTotalSum();
                break;
            default:
                echo "No action selected!";//errormessage funktion
                break;
        }
    }

    private function queryProductTypeList()
    {
        $productTypeList = new ProductTypeList();
        $productTypeListTable = array();
        $fetchedProductTypeList = $productTypeList->queryDatabaseForProductTypes();
        foreach ($fetchedProductTypeList as $item) {
            $productType = array();
            $productType['productType'] = $item['name'];
            $productType['url'] = URL_ENDPOINT_PRODUCTTYPES . $item['id'];
            $productTypeListTable[] = $productType;
        }
        $sortByName = array_column($productTypeListTable, "productType");
        array_multisort($sortByName, SORT_ASC, $productTypeListTable);
        return $productTypeListTable;
    }

    private function displayProductTypeList()
    {
        $jsonView = new JsonView();
        $productTypeListTable = $this->queryProductTypeList();
        $jsonView->streamOutput($productTypeListTable);
    }

    private function queryProductList($inputId)
    {
        $productList = new ProductList();
        $productListTable = array();
        $fetchedProductList = $productList->queryDatabaseForProducts($inputId);
        foreach ($fetchedProductList as $item) {
            $productListTable['productType'] = $item['productTypeName'];
            $product['name'] = $item['productName'];
            $product['description'] = $item['productDescription'];
            $product['unit'] = $item['productUnit'];
            $product['price'] = $item['productPrice'];
            $product['id'] = $item['productId'];
            $productListTable['products'][] = $product;
            $productListTable['url'] = URL_ENDPOINT_PRODUCTS;
        }
        $sortByName = array_column($productListTable['products'], "name");
        array_multisort($sortByName, SORT_ASC, $productListTable['products']);
        return $productListTable;
    }

    private function displayProductList($inputId)
    {
        $jsonView = new JsonView();
        $productListTable = $this->queryProductList($inputId);
        $jsonView->streamOutput($productListTable);
    }

    private function addArticleToCart($inputId)
    {
        $shoppingCart = new ShoppingCart();
        $jsonView = new JsonView();
        $addingArticleIsPossible = $shoppingCart->addArticle($inputId);
        if ($addingArticleIsPossible == true) {
            $stateOk = $this->displayState("OK");
            $jsonView->streamOutput($stateOk);
        } else {
            $stateError = $this->displayState("ERROR");
            $jsonView->streamOutput($stateError);
        }
    }

    private function removeArticleFromCart($inputId)
    {
        $shoppingCart = new ShoppingCart();
        $jsonView = new JsonView();
        $removingArticleIsPossible = $shoppingCart->removeArticle($inputId);
        if ($removingArticleIsPossible == true) {
            $stateOk = $this->displayState("OK");
            $jsonView->streamOutput($stateOk);
        } else {
            $stateError = $this->displayState("ERROR");
            $jsonView->streamOutput($stateError);
        }
    }

    private function displayState($inputString)
    {
        $state = array();
        $state['state'] = $inputString;
        return $state;
    }

    private function displayCartContent()
    {
        $shoppingCart = new ShoppingCart();
        $jsonView = new JsonView();
        $cartContent = $shoppingCart->listCartContent();
        $cart = array();
        foreach ($cartContent as $item) {
            $cart[] = $item;
        }
        $sortByArticleId = array_column($cart, "articleName");
        array_multisort($sortByArticleId, SORT_ASC, $cart);
        $jsonView->streamOutput($cart);
    }

    private function emptyCart()
    {
        $shoppingCart = new ShoppingCart();
        $jsonView = new JsonView();
        $emptyingCartIsPossible = $shoppingCart->emptyCart();
        if ($emptyingCartIsPossible == true) {
            $stateOk = $this->displayState("OK");
            $jsonView->streamOutput($stateOk);
        } else {
            $stateError = $this->displayState("ERROR");
            $jsonView->streamOutput($stateError);
        }
    }

    private function deleteArticleTypeFromCart($inputId)
    {
        $shoppingCart = new ShoppingCart();
        $jsonView = new JsonView();
        $articleCanBeDeleted = $shoppingCart->deleteAllArticleGroup($inputId);
        if ($articleCanBeDeleted == true) {
            $stateOk = $this->displayState("OK");
            $jsonView->streamOutput($stateOk);
        } else {
            $stateError = $this->displayState("ERROR");
            $jsonView->streamOutput($stateError);
        }
    }

    private function calculateTotalSum()
    {
        $shoppingCart = new ShoppingCart();
        $jsonView = new JsonView();
        $totalSum = $shoppingCart->calculateTotalPrice();
        $totalSumArray = $this->displayTotalSum($totalSum);
        $jsonView->streamOutput($totalSumArray);
    }

    private function displayTotalSum($inputInteger)
    {
        $totalSum = array();
        $totalSum['totalSum'] = $inputInteger;
        return $totalSum;
    }
}