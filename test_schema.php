<?php
$c = new mysqli('localhost', 'root', '', 'cyberaware_db');
if ($c->connect_error) die('Error');
$r = $c->query('DESCRIBE user_responses');
if (!$r) die($c->error);
while($row = $r->fetch_assoc()) print_r($row);
?>
