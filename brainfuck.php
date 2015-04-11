<?php

/**
 * A naive brainfuck interpreter in PHP
 *
 * @author Reeze Xia <reeze@php.net>
 */

$buffer = "";
$pos = 0;

$code = "++++++++++[>+++++++>++++++++++>+++>+<<<<-]
        >++.>+.+++++++..+++.>++.<<+++++++++++++++.
        >.+++.------.--------.>+.>.";

if ($argc > 1) {
	$code = $argv[1];
}

$i = 0;
while ($i < strlen($code)) {
	$c = $code[$i];

	if (!isset($buffer[$pos])) {
		$buffer[$pos] = 0;
	}

	switch($c) {
	case '>':
		++$pos;
		break;
	case '<':
		--$pos;
		break;
	case '+':
		$buffer[$pos]++;
		break;
	case '-':
		$buffer[$pos]--;
		break;
	case '.':
		echo chr($buffer[$pos]);
		break;
	case ',':
		$buffer[$pos] = ord(fgetc(STDIN));
		break;
	case '[':
		// JMPZ
		if (!$buffer[$pos]) {
			// find the position of next ']'	
			$stack = array();
			while($i < strlen($code)) {
				++$i;
				if ($code[$i] == '[') {
					$stack[] = $i;
				} else if ($code[$i] == ']') {
					if (count($stack) == 0) {
						continue 2;
					}
					array_pop($stack);
				}
			}

			echo "Not found ]\n";
			exit;
		}
		break;
	case ']':
		// JMPNZ
		if ($buffer[$pos]) {
			$stack = array();
			while($i > -1) {
				--$i;
				if ($code[$i] == ']') {
					$stack[] = $i;
				} else if ($code[$i] == '[') {
					if (count($stack) == 0) {
						continue 2;
					}

					array_pop($stack);
				}
			}

			echo "Not found [\n";
			exit;
		}
		break;
	default:
		// ignore all of other chars
		break;
	}

	++$i;
}