<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Order;
use \Hcode\Model\OrderStatus;

$app->get("/admin/orders/:idorder/status", function($idorder){

    User::verifyLogin();

    $order = new Order();

    $order->get((int)$idorder);


    $page = new PageAdmin();

    $page->setTpl("order-status", [
        "msgError"=>Order::getError(),
        "msgSuccess"=>Order::getSuccess(),
        "order"=>$order->getValues(),
        "status"=>$orderStatus::listAll()
    ]);
});

$app->post("/admin/orders/:idorder/status", function($idorder){

    User::verifyLogin();

    if (!isset($_POST["idstatus"]) || !(int)$_POST["idstatus"] > 0)
    {
        Order::setError("Informe o status atual.");
        header("Location: /admin/orders".$idorder."/status");
        exit;
    }

    $order = new Order();

    $order->get((int)$idorder);

    $order->setidstatus((int)$_POST["idstatus"]);

    $order->save();

    $Order->setSuccess("Status atualizado.");

    header("Location: /admin/orders".$idorder."/status");
    exit;

});

$app->get("/admin/orders/:idorder/delete", function($idorder){ // colocase em cima para a rota funcionar e nao entrar em conflito com
                                                               // a rota /admin/orders. Rotas maiores ou mais especificas ficam em cima.
    User::verifyLogin();

    $order = new Order();

    $order->get((int)$idorder);

    $order->delete();

    header("Location: /admin/orders");
    exit;
});

$app->get("/admin/orders/:idorder", function($idorder){
    User::verifyLogin();

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
    User::verifyLogin();

    $page = new PageAdmin();

    $order = new Order();

    $values = querySearch($order, "/admin/orders?");

    $page->setTpl("orders", [
        "orders"=>$values["pagination"],
        "search"=>$values["search"],
		"pages"=>$values["pages"]
    ]);
});




?>