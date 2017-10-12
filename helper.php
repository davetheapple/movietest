<?php 

$params = json_decode(file_get_contents('php://input'),true);
$_POST = array_merge($_POST, $params);

if(isset($_POST['GetData'])) {
	
	$data = array(
		array("id" => 1, "title" => "test data", "popularity" => 100)
	);//connect("SELECT * FROM movies")->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($data);

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
