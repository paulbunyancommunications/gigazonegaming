<?php
$contextFiles = glob(__DIR__ . '/context/*.php');
$context = [];
for ($i = 0; $i < count($contextFiles); $i++) {
    include $contextFiles[$i];
};
