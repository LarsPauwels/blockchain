<?php

	$db = "data/balance.json";
	if (file_exists($db)) {
		$balances = json_decode(file_get_contents($db), true);
	} else {
		$balances = ["Lars" => 1000000];
		file_put_contents($db, json_encode($balances));
	}

	if ("/balance" == $_SERVER["PATH_INFO"]) {
		$user = strtolower($_GET["user"]);
		printf("User %s has %d Coins.", $user, $balances[$user] ?? 0);
	} else if ("/users" == $_SERVER["PATH_INFO"] && "POST" == $_SERVER["REQUEST_METHOD"]) {
		$user = strtolower($_POST["user"]);
		if (!isset($balances[$user])) {
			http_response_code(404);
			return;
		}
		$balances[$user] = 0;
		file_put_contents($db, json_encode($balances));
		print "OK";
	} else if ("/transfer" == $_SERVER["PATH_INFO"] && "POST" == $_SERVER["REQUEST_METHOD"]) {
		$from = strtolower($_POST["from"]);
		if (!isset($balances[$from])) {
			http_response_code(404);
			return;
		}

		$to = strtolower($_POST["to"]);
		if (!isset($balances[$to])) {
			http_response_code(404);
			return;
		}

		$amount = (int) $_POST["amount"];
		if ($amount > $balances[$from]) {
			http_response_code(404);
			return;
		}
		$balances[$from] -= $amount;
		$balances[$to] += $amount;
		file_put_contents($db, json_encode($balances));
		print "OK";
	}

?>