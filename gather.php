<?php
/*
	Gather:
	1. Use cURL to request data from The Movie DB API
	2. Insert that data into the database using PDO

	Display:
	3. Use an Angular service to get all data through AJAX.
	4. Display data with angular binding and angular datatables
	5. Retrieve/display detail data using Angulars $http, JSON, and a bootstrap modal.
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

include ".config.php";

// table reference minus `id`
$ref = array(
	"id",
	"adult",
	"backdrop_path",
	"genre_ids",
	"original_language",
	"original_title",
	"overview",
	"popularity",
	"poster_path",
	"release_date",
	"title",
	"video",
	"vote_average",
	"vote_count"
);

echo "<xmp>";

$filters = array(
	"certification" 		=> "R",
	"primary_release_date" 	=> "2015",
	"with_genre"			=> "878",
	"sort_by" 				=> "popularity.desc"
);
$data =  json_decode(gather("discover", "movie", $filters));

if(isset($data->results)) {
	$columns = "(`".implode("`,`", $ref)."`)";
	$insert = "INSERT INTO `movies` $columns VALUES";
	$count = 0;
	foreach($data->results as $movie) {

		if($count > 100) break;

		$insert .= "(";
		foreach($ref as $col) {

			if($col == "genre_ids" ) $insert .= "'" . implode(",", $movie->{$col}) . "',";
			else if($col == "adult" || $col == "video" ) $insert .= "'" . ($movie->{$col} ? "true" : "false") . "',";
			else $insert .= "'" . addslashes($movie->{$col}) . "',";
		}
		$insert = rtrim($insert, ",");
		$insert .= "),";
		
		$count++;

	}
	$insert = rtrim($insert, ",");
	echo "Inserting 100 of " . $data->total_results . " Movies.\n";
	connect($insert);
	echo "\nComplete.\n";

} else if(isset($data->success)) {
	echo $data->status_code . "\n";
	echo $data->status_message . "\n";
} else {
	echo "Something has gone horribly wrong.";
}
echo "</xmp>";


function gather($search, $type, $values) {

	$endpoint = "https://api.themoviedb.org/3/" . $search . "/" . $type;
	$values['api_key'] = "cbfd87b8fec0703b46683c7e89edfb9b";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $endpoint . "?" . http_build_query($values) );
	curl_setopt($ch, CURLOPT_HTTPGET, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);
	curl_close ($ch);

	return $server_output;
}

function connect($cmd, $arr = array()) {

    # database login
    global $host;
    global $admin;
    global $adminPass;
    global $dbname;

    $sql = null;
    try {

        $db = new PDO("mysql:dbname=$dbname;host=$host", $admin, $adminPass);

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $db->prepare($cmd, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $result = $sql->execute($arr);

        $db = null;
    

    } catch (PDOException $e) {
        echo("\nCMD: \n$cmd\n");
        echo("\nERR:\n ".$e->getMessage() . "\n");
        
    }

    return $sql;
}
