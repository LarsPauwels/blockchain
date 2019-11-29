<?php

class PoW
{
	
}

class Block
{

}

class Blockchain
{
	
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