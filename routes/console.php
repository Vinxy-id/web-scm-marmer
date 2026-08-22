<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('escm:status', function () {
    $this->info('E-SCM Marmer Tulungagung CLI Operational.');
})->purpose('Menampilkan status operasional CLI E-SCM');
