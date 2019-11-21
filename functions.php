<?php

use \Hcode\Model\User;
use \Hcode\Model\Cart;
use \Hcode\Model\Category;

	function formatPrice($vlprice)
	{
		if (!$vlprice > 0) $vlprice = 0;
		return number_format( $vlprice, 2, ",", ".");
	}

	function formatDate($date)
	{
		return date("d/m/Y", strtotime($date));
	}

	function querySearch(Object $model, $domain)
	{
		$search = (isset($_GET["search"])) ? $_GET["search"] : "";

		$page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;

		if ($search != "")
		{
			$pagination =  $model::getPageSearch($search, $page);
		} else 
		{
			$pagination =  $model::getPage($page);
		}

		$pages = [];

		for ($i=0; $i < $pagination["pages"]; $i++) 
		{ 
			array_push($pages, [
				"href"=>$domain.http_build_query([
					"page"=>$i+1,
					"search"=>$search
				]),
				"text"=>$i+1
			]);
		}
		$values = [
			"pagination"=>$pagination["data"],
			"search"=>$search,
			"pages"=>$pages
		];
		return $values;

	}

	function checkLogin($inadmin = true)
	{
	
		return User::checkLogin($inadmin);
	
	}
	
	function getUserName()
	{
	
		$user = User::getFromSession();
	
		return $user->getdesperson();
	
	}
	function dateRegister()
	{

		$user = User::getFromSession();
	
		return $user->getdtregister();
	
	}
	function getCartNrQtd()
	{
		$cart = Cart::getFromSession();

		$totals = $cart->getProductsTotals();

		return $totals["nrqtd"];
	}
	function getCartVlSubTotal()
	{
		$cart = Cart::getFromSession();

		$totals = $cart->getProductsTotals();

		return formatPrice($totals["vlprice"]);
	}

?>