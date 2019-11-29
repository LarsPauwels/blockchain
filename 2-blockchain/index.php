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

$b = new Blockchain("User Lars has 1000000 Coins."); //Create new blockchain with first message
$b->add('User Lars transfered 10000 Coins.'); //Add a message
$b->add('User Bram has 10000 Coins.'); //Add a message
//[$b->blocks[0], $b->blocks[1]] = [$b->blocks[1], $b->blocks[0]]; //Reverse block 0 and 1
print "Blockchain: \n";
print "--------------------------------------------------------------------\n";
print $b."\n\n\n";//Print the blockchain

print "Valid: \n";
print "--------------------------------------------------------------------\n";
var_export($b->isValid());//Check if the blockchain is valid