<?php
if(!preg_match('/[A-Z]{2}/', $_GET['statecode'])) {
	$output = "alert('Input pattern incorrect');\n";
}
elseif(!$link = mysql_connect('localhost', 'user', 'password')) {
	$output = "alert('Could not connect to database');\n";
}
elseif(!mysql_select_db('database_name')) {
	$output = "alert('Could not select database');\n";
}
else {
	if(!$rs = mysql_query("SELECT DISTINCT city FROM zip_code_dist WHERE state_code = '$_GET[statecode]' AND TRIM(city) != '' ORDER BY city LIMIT 80")) {
		$output = "alert('Error getting city list from database');\n";
	}
	elseif(mysql_num_rows($rs) == 0) {
		$output = "alert('No records found');\n";
	}
	else {
		$i = 0;
		while($row = mysql_fetch_array($rs)) {
			if($i % 5 == 0) {
				$output .= "cityOptions.push(new Option('--------------------', ''));\n";
			}
			$output .= "cityOptions.push(new Option('$row[city]', '$row[city]'));\n";
			$i++;
		}
	}
}

header('Content-type: text/plain');
echo $output;
?>