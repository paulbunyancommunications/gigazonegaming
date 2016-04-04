<?php
$contextFiles = glob(__DIR__ . '/context-*.php');
$context = [];
for($i=0; $i < count($contextFiles); $i++) {
    include(locate_template(pathinfo($contextFiles[$i], PATHINFO_BASENAME)));
};