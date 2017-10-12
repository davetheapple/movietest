<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set ("America/Boise");

include ".config.php";

$params = json_decode(file_get_contents('php://input'),true);
$_POST = array_merge(isset($_POST) ? $_POST : array(), isset($params) ? $params : array());

// get a list of top 20 movies
if(isset($_POST['GetData'])) {
	
	// test data
	// $data = array(
	// 	array("title" => "test data", "release_date" => "test data", "vote_count" => 100, "id" => 1, "overview" => "an overview of text", "backdrop_path" => "imgurl", "poster_path" => "anotherimgurl")
	// );
	$data = connect("SELECT * FROM movies ORDER BY vote_count DESC LIMIT 20")->fetchAll(PDO::FETCH_ASSOC);
	$response = array();

	// format it
	foreach($data as $item) {
		$response[] = array(
			'title' => '<a class="movie" data-toggle="modal" data-target="#detail" data-item="'.$item['id'].'">' . $item['title'] . '</a>',
			'title_reg' => $item['title'],
			'release_date' => date("m/d/Y", strtotime($item['release_date'])),
			'vote_count' => $item['vote_count'],
			'overview' => $item['overview'],
			'backdrop_path' => "http://image.tmdb.org/t/p/original/" . $item['backdrop_path'],
			'poster_path' => "http://image.tmdb.org/t/p/w185/" . $item['poster_path'],
			'id' => $item['id']
		);
	}
	
	echo json_encode($response);

}

// get the movie detail data
if(isset($_POST['GetMovie'])) {
	$id = $_POST['GetMovie'];
	$data = connect("SELECT * FROM movies WHERE id = :id", array(":id" => $id))->fetchAll(PDO::FETCH_ASSOC);
	$data[0]['genre_ids'] 		= json_decode($data[0]['genre_ids']); // dont double convert the json

	// create urls
	$data[0]['backdrop_path'] 	= "http://image.tmdb.org/t/p/original/" . $data[0]['backdrop_path'];
	$data[0]['poster_path'] 	= "http://image.tmdb.org/t/p/w185/" . $data[0]['poster_path'];

	// not really needed now
	$data[0]['title_reg'] 		= $data[0]['title'];

	echo json_encode($data[0]);
}


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
