<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;





$app->get('/', function() {

    $products = Product::listAll();

    $page = new Page();
	$page->setTpl("index", [
        "products"=>Product::checkList($products)
    ]);
    // echo $_SERVER["DOCUMENT_ROOT"];

});
$app->get("/categories/:idcategory", function($idcategory){

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

	$category = new Category();

	$category->get((int)$idcategory);
	$pagination = $category->getProductsPage($page);
	$page = new Page();
    $pages = [];
    for ($i=1; $i <= $pagination['pages']; $i++) { 
        array_push($pages, [
            'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
            'page'=>$i
        ]);
    }
    $dir = ($_SERVER['QUERY_STRING']) ? (int)substr(strstr($_SERVER['QUERY_STRING'], "="), 1) : 1;
    
	$page->setTpl("category", [
		"category"=>$category->getvalues(),
        "products"=>$pagination["data"],
        'pages'=>$pages,
        'dir'=>$dir
        
	]);

});

$app->get("/products/:desurl", function($desurl) {

    $product = new Product();

    $product->getFromURL($desurl);

    $page = new Page();

    $page->setTpl("product-detail", [
        "product"=>$product->getValues(),
        "categories"=>$product->getCategories()
    ]);


});
$app->get("/cart", function() {

    $cart = Cart::getFromSession();

    $page = new Page();

    $page->setTpl("cart", [
        'cart'=>getValues(),
        'products'=>$cart->getProducts()
    ]);


});
$app->get("/cart/:idproduct/add", function($idproduct) {

    $product = new Product();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();

    $cart->addProduct($idproduct);

    header("Location: /cart");
    exit;


});
$app->get("/cart/:idproduct/minus", function($idproduct) {

    $product = new Product();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product);

    header("Location: /cart");
    exit;


});
$app->get("/cart/:idproduct/remove", function($idproduct) {

    $product = new Product();

    $product->get((int)$idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product, true);

    header("Location: /cart");
    exit;


});
?>