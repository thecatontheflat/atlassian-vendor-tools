<?php

// Resetting DB
$cmd = 'app/console doctrine:database:drop --force --env=test';
exec($cmd);
$cmd = 'app/console doctrine:database:create --env=test';
exec($cmd);
$cmd = 'app/console doctrine:schema:update --force --env=test';
exec($cmd);

//TODO: Replace it with importing sales from file
$cmd = 'app/console doctrine:fixtures:load --env=test --no-interaction';
exec($cmd);

$cmd = 'app/console app:import:license --env=test src/AppBundle/Tests/_fixtures/licenseReport.csv';
exec($cmd, $output);

require __DIR__.'/bootstrap.php.cache';