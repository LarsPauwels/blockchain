<?php

class PoW
{
	
	public static function hash($message)
	{
		return hash('sha256', $message);
	}

	public static function findNonce($message)
	{
		$nonce = 0;
		while (!self::isValidNonce($message, $nonce)) {
			++$nonce;
		}
		return $nonce;
	}

	public static function isValidNonce($message, $nonce)
	{
		return 0 === strpos(hash('sha256', $message.$nonce), '0000');
	}
}

class Block
{
	public $previous;
	public $hash;
	public $message;

	public function __construct($message, ?Block $previous)
	{
		$this->previous = $previous ? $previous->hash : null;
		$this->message = $message;
		$this->mine();
	}

	public function mine()
	{
		$data = $this->message["message"].$this->previous;
		$this->nonce = PoW::findNonce($data);
		$this->hash = PoW::hash($data.$this->nonce);
	}

	public function isValid(): bool
	{
		return PoW::isValidNonce($this->message["message"].$this->previous, $this->nonce);
	}

	public function __toArray(): array
	{
		return [
			"Previous" => $this->previous,
			"Nonce" => $this->nonce,
			"Hash" => $this->hash,
			"Message" => $this->message["message"]
		];
	}
}

class Blockchain
{
	public $blocks = [];

	public function __construct($message)
	{
		$this->blocks[] = new Block($message, null);
	}

	public function add($message)
	{
		$this->blocks[] = new Block($message, $this->blocks[count($this->blocks) - 1]);
	}

	public function isValid() : bool
	{
		foreach ($this->blocks as $i => $block) {
			if (!$block->isValid()) {
				return false;
			}
			if ($i != 0 && $this->blocks[$i - 1]->hash != $block->previous) {
				return false;
			}
		}
		return true;
	}
}

$b = new Blockchain([
	"type" => "Balance",
	"from" => "lars",
	"amount" => 1000000,
	"message" => "User Lars has 1000000 Coins."
]);
$b->add([
	"type" => "Balance",
	"from" => "lars",
	"amount" => 9000000,
	"message" => "User Lars has 9000000 Coins."
]);
//$b->add('User Lars transfered 10000 Coins.');
//$b->add('User Bram has 10000 Coins.');

if (isset($_SERVER['PATH_INFO'])) {
	print "Result: \n";
	print "--------------------------------------------------------------------\n";
	if ("/balance" == $_SERVER["PATH_INFO"]) {
		$user = strtolower($_GET["user"]);

		foreach ($b->blocks as $block) {
			if ($block->message["from"] == $user) {
				$amount = $block->message["amount"];
			}
		}
		printf("Your blance is now %d Coins.", $amount);
	}
}

print "\n\n\nBlockchain: \n";
print "--------------------------------------------------------------------\n";
foreach ($b->blocks as $block) {
	printf("Previous: %s \n Nonce: %s \n Hash: %s \n Message: %s \n\n", $block->previous, $block->nonce, $block->hash, $block->message["message"]);
}

print "Valid: \n";
print "--------------------------------------------------------------------\n";
var_export($b->isValid());