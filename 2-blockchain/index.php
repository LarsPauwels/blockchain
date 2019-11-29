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
		$data = $this->message.$this->previous;
		$this->nonce = PoW::findNonce($data);
		$this->hash = PoW::hash($data.$this->nonce);
	}

	public function isValid(): bool
	{
		return PoW::isValidNonce($this->message.$this->previous, $this->nonce);
	}

	public function __toString(): string
	{
		return sprintf("Previous: %s \n Nonce: %s \n Hash: %s \n Message: %s", $this->previous, $this->nonce, $this->hash, $this->message);
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

	public function __toString()
	{
		return implode("\n\n", $this->blocks);
	}
}

$b = new Blockchain("User Lars has 1000000 Coins.");
$b->add('User Lars transfered 10000 Coins.');
$b->add('User Bram has 10000 Coins.');
//[$b->blocks[0], $b->blocks[1]] = [$b->blocks[1], $b->blocks[0]];
print "Blockchain: \n";
print "--------------------------------------------------------------------\n";
print $b."\n\n\n";

print "Valid: \n";
print "--------------------------------------------------------------------\n";
var_export($b->isValid());