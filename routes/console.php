<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('epaper:generate-dummy', function (\App\Services\EpaperGeneratorService $generator) {
    $this->info('Generating high-resolution broadsheet newspaper pages...');
    $res = $generator->generateAll();
    $this->info("Generated 6 authentic newspaper pages in: public/demo/epaper/");
    $this->info("Generated complete multi-page PDF: public/demo/epaper/newskit_daily_edition.pdf");
    $this->info("Updated database edition ID: {$res['edition']->id} with 6 pages.");
})->purpose('Generate high-resolution realistic dummy newspaper broadsheet pages and PDF for demo');

