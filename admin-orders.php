<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Order;

$app->get("/admin/orders/:idorder/delete", function($idorder){ // colocase em cima para a rota funcionar e nao entrar em conflito com
                                                               // a rota /admin/orders. Rotas maiores ou mais especificas ficam em cima.
    User::verifyLogin(false, false);

    $order = new Order();

    $order->get((int)$idorder);

    $order->delete();

    header("Location: /admin/orders");
    exit;
});

$app->get("/admin/orders/:idorder", function($idorder){
    User::verifyLogin(false, false);

    $order = new Order();

    $order->get((int)$idorder);

    $cart = $order->getCart();

    $page = new PageAdmin();

    $page->setTpl("order", [
        "order"=>$order->getValues(),
        "cart"=>$cart->getValues(),
        "products"=>$cart->getProducts()
    ]);
});

$app->get("/admin/orders", function(){
    User::verifyLogin(false, false);

    $page = new PageAdmin();

    $page->setTpl("orders", [
        "orders"=>Order::listAll()
    ]);
});




?>