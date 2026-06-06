<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$abs = \Illuminate\Support\Facades\DB::table('absensis')->select('status_masuk', \Illuminate\Support\Facades\DB::raw('count(*) as count'))->groupBy('status_masuk')->get();
echo json_encode($abs);
