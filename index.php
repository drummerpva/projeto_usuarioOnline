<?php
try {
    $pdo = new PDO("mysql:dbname=projeto_usuariosonline;host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    die("Erro: " . $ex->getMessage());
}
$ip = $_SERVER['REMOTE_ADDR'];
$hora = date("H:i:s");

$sql = $pdo->prepare("INSERT into acessos (ip,hora) VALUES(:ip,:hora)");
$sql->bindValue(":ip", $ip);
$sql->bindValue(":hora", $hora);
$sql->execute();
//exclui registros velhos
$sql = $pdo->prepare("DELETE FROM acessos WHERE hora < :hora");
$sql->bindValue(":hora",date("H:i:s",strtotime("-2 minutes")));
$sql->execute();

$sql = $pdo->prepare("SELECT  * FROM acessos WHERE hora > ? GROUP BY ip");
$sql->execute(array(date("H:i:s",strtotime("-2 minutes"))));
$sql = $sql->rowCount();
echo "<p>Online: (".$sql.") usuários!</p>";