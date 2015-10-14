<?php

// Resetting DB
$cmd = 'app/console doctrine:database:drop --force --env=test --if-exists';
exec($cmd);
$cmd = 'app/console doctrine:database:create --env=test';
exec($cmd);
$cmd = 'app/console doctrine:schema:update --force --env=test';
exec($cmd);

$cmd = 'app/console doctrine:fixtures:load --env=test --no-interaction';
exec($cmd);

// Inserting fixtures

require __DIR__.'/bootstrap.php.cache';