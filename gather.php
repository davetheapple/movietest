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

include "helper.php";

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

			if($col == "genre_ids" ) $insert .= "'" . json_encode($movie->{$col}) . "',";
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
