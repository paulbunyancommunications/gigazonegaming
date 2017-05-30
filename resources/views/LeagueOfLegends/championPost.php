<?php


//$tournament = $_POST['']; #NEW
$team = $_GET['postTeam'];


#grabe Serialized data from storage.
$data = unserialize("/app/Http/Controllers/PlayerObjectStorage");



echo $team;