<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('routes:summary', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Custom command untuk ringkasan routes
Artisan::command('routes:summary', function () {
    $this->info('=== GRIYA KREATIF DEYALINA ROUTES SUMMARY ===');
    
    $this->newLine();
    $this->info('PUBLIC ROUTES:');
    $this->line('  GET  /                          : Welcome page');
    
    $this->newLine();
    $this->info('AUTHENTICATION ROUTES:');
    $this->line('  GET  /login                     : Login form');
    $this->line('  POST /login                     : Process login');
    $this->line('  GET  /register                  : Registration form');
    $this->line('  POST /register                  : Process registration');
    $this->line('  POST /logout                    : Logout');
    
    $this->newLine();
    $this->info('CUSTOMER ROUTES (Total: 17):');
    $this->line('  GET  /home                      : Product catalog');
    $this->line('  GET  /products/{id}             : Product details');
    $this->line('  GET  /cart                      : View cart');
    $this->line('  POST /cart                      : Add to cart');
    $this->line('  GET  /checkout                  : Checkout');
    $this->line('  GET  /orders                    : Order history');
    $this->line('  GET  /messages                  : Customer messages');
    
    $this->newLine();
    $this->info('ADMIN ROUTES (Total: 28):');
    $this->line('  GET  /admin/dashboard           : Dashboard');
    $this->line('  *    /admin/products            : Product management (7 routes)');
    $this->line('  *    /admin/categories          : Category management (6 routes)');
    $this->line('  *    /admin/orders              : Order management (5 routes)');
    $this->line('  *    /admin/messages            : Message management (4 routes)');
    
    $this->newLine();
    $this->info('Total Application Routes: ~52');
})->purpose('Display routes summary');