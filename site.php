<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;





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
	$page->setTpl("category", [
		"category"=>$category->getvalues(),
        "products"=>$pagination["data"],
        'pages'=>$pages,
        'dir'=>substr(strstr($_SERVER["HTTP_REFERER"], "/"), -1)
        
	]);

});

?>