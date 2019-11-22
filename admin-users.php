<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app->get('/admin/users/:iduser/password', function($iduser) {
	
	User::verifyLogin();




	$user = new User();

	$user->get((int)$iduser);

	
	
	$page = new PageAdmin();

	$page->setTpl("users-password", array(
		"user"=>$user->getValues(),
		"msgErro"=>User::getError(),
		"msgSuccess"=>User::getSuccess()
	));
	
	
});

$app->post('/admin/users/:iduser/password', function($iduser) {
	
	User::verifyLogin();

	if (!isset($_POST["despassword"]) || $_POST["despassword"] === "")
	{
		User::setError("Preencha a nova senha");
		header("Location: /admin/users/:iduser/password");
		exit;
	}
	if (!isset($_POST["despassword-confirm"]) || $_POST["despassword-confirm"] === "")
	{
		User::setError("Preencha a confirmacao da nova senha");
		header("Location: /admin/users/:iduser/password");
		exit;
	}
	if ($_POST["despassword"] !== $_POST["despassword-confirm"])
	{
		User::setError("Confirme corretamente as senhas.");
		header("Location: /admin/users/:iduser/password");
		exit;
	}

	$user = new User();

	$user->get((int)$iduser);

	$user->setPassword(User::getPasswordHash($_POST["despassword"]));

	User::setSuccess("Alteracao concluida com exito.");


	header("Location: /admin/users/:iduser/password");
	exit;



});


$app->get('/admin/users', function() {
	User::verifyLogin();


	$user = new User();

	$values = querySearch($user, "/admin/users?");
	
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