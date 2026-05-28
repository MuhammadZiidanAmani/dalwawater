<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$content = file_get_contents('resources/views/transactions/receipt.blade.php');
$compiled = Blade::compileString($content);
file_put_contents('compiled.php', $compiled);
echo "Done";
