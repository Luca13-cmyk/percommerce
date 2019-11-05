<?php

use \Hcode\Page;

$app->get('/', function() {
    // $page = new Page();
	// $page->setTpl("index");
    echo $_SERVER["DOCUMENT_ROOT"];

});

?>