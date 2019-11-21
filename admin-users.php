<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;


$app->get('/admin/users', function() {
	User::verifyLogin();

	// $search = (isset($_GET["search"])) ? $_GET["search"] : "";

	// $page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;

	// if ($search != "")
	// {
	// 	$pagination =  User::getPageSearch($search, $page);
	// } else 
	// {
	// 	$pagination =  User::getPage($page);
	// }

	

	// $pages = [];

	// for ($i=0; $i < $pagination["pages"]; $i++) 
	// { 
	// 	array_push($pages, [
	// 		"href"=>"/admin/users?".http_build_query([
	// 			"page"=>$i+1,
	// 			"search"=>$search
	// 		]),
	// 		"text"=>$i+1
	// 	]);
	// }

	$user = new User();

	$values = queryString($user, "/admin/users?");
	
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$values["pagination"],
		"search"=>$values["search"],
		"pages"=>$values["pages"]
	));
	
	
});

$app->get('/admin/users/create', function() {
	User::verifyLogin();

	
	$page = new PageAdmin();
	$page->setTpl("users-create");
	
});
$app->get('/admin/users/:iduser/delete', function($iduser) {
	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;
	
	
});
$app->get('/admin/users/:iduser', function($iduser) {
	User::verifyLogin();

	$user = new User();
	$user->get((int)$iduser);

	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
	
});

$app->post('/admin/users/create', function() {
	User::verifyLogin();
	
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	 // $_POST['despassword'] = User::getPasswordHash($_POST['despassword']);
	 
	 if (User::checkLoginExist($_POST["deslogin"]) === true)
	 {
		//  User::setErrorRegister("Este endereco de email ja esta sendo usado por outro usuario.");
		//  header("Location: /login");
		throw new Exception("Login existe");
		
		 exit;
	 }
	 if (User::checkEmailExist($_POST["desemail"]) === true)
    {
        // User::setErrorRegister("Este endereco de email ja esta sendo usado por outro usuario.");
		// header("Location: /login");
		throw new Exception("Email existe");
		
        exit;
    }

	
	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;


	
	
});
$app->post('/admin/users/:iduser', function($iduser) {
	User::verifyLogin();

	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();

	header("Location: /admin/users");
	exit;
	
});

?>