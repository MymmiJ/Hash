<?php

namespace MymmiJ\Hash;

const ASCII_0 = 48;
const ASCII_A = 65;
const CHAR_OFFSET = 10;

function convert_decimal_to_base($int,$base) {
	$string = '';

	while($int > 0.99) { //php does not like type safety - prevents coercion to float
		$value = $int % $base;

		$ascii;

		if($value <= CHAR_OFFSET) {
			$ascii = $value + ASCII_0;
		} else {
			$ascii = $value + ASCII_A - CHAR_OFFSET;
		}

		$string .= chr($ascii);

		$int -= ($int % $base);

		$int /= $base;
	}

	return $string;
}

function create_hash($input) {
	$byte_array = unpack('C*', $input);

	$int_array = array_map (
		function($b,$i) { return $b * $i; },
		$byte_array,
		array_keys($byte_array)
		);

	$string_array = array_map(
		function($i) { return convert_decimal_to_base($i,62); },
		$int_array
		);

	$hash = str_pad(substr(join($string_array),0,32),16,"0",STR_PAD_LEFT);

	echo "<p>Hash: " . $hash ."</p>";

	return $hash;
}

//debugging helper function
function generate_string() {
	$numbers = array();

	$n = rand(1,50);
	$i = 0;
	while( $i < $n) {
		$x = rand(32,126);

		array_push($numbers,$x);

		++$i;
	}

	return join(array_map('chr', $numbers));;
}

$test = generate_string();

echo "<p>String: " . $test ."</p>";

create_hash($test);

?>