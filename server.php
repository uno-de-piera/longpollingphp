<?php
$loopStart = time();
$updateAt = 3;
$loopSeconds = 10;

$pdo = new PDO("mysql:host=localhost;dbname=longpolling", "root", "");

if(isset($_POST["timestamp"]))
{
	$timestamp = $_POST["timestamp"];
}
else
{
	$current = $pdo->prepare("SELECT NOW() AS now");
	$current->execute();
	$row = $current->fetchObject();
	$timestamp = $row->now;
}

$timestamp = date($timestamp, time() + $updateAt);

$sql = $pdo->prepare("SELECT * FROM notificaciones WHERE timestamp > :timestamp ORDER BY id");
$sql->bindParam(":timestamp", $timestamp, PDO::PARAM_STR);

$newMessages = false;
$notificaciones = array();

while(!$newMessages && (time() - $loopStart) < $loopSeconds)
{
	$sql->execute();
	while($row = $sql->fetch(PDO::FETCH_ASSOC))
	{
		$notificaciones[] = $row;
		$newMessages = true;
	}
	sleep($updateAt);
}

$current = $pdo->prepare("SELECT NOW() AS now");
$current->execute();
$row = $current->fetchObject();
$timestamp = $row->now;

$data = array("notificaciones" => $notificaciones, "timestamp" => $timestamp);
echo json_encode($data);
exit;