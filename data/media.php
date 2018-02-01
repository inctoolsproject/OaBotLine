<?php
$username = $_GET['id'];

function cut_str($str, $left, $right) {
	$front = explode($left, $str);
	$returnVar = array();
	
	foreach($front as $value) {
		if(strpos($value, $right) !== false) {
			$end = explode($right, $value);
			$returnVar[] = $end[0];
		}
	}
	
	foreach($returnVar as $key => $value) {
		if(substr($value, 0, 9) == '<!DOCTYPE') unset($returnVar[$key]);
	}
	
	$returnVar = array_values($returnVar);
	return $returnVar;
}

$data = file_get_contents($username);

$data = cut_str($data, 'window._sharedData = ', ';</script>');
$data = json_decode($data[0], 1);
header('Content-Type: application/json');
echo json_encode($data["entry_data"]["PostPage"][0]["graphql"]);
?>