<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles, permissions, and the default admin/cashier users.
        $this->call(RolePermissionSeeder::class);

        // 2. Demo supplier & customer (created before purchases reference them).
        Supplier::updateOrCreate(
            ['name' => 'SpineCare Medical Ltd.'],
            ['company' => 'SpineCare BD', 'phone' => '01700000000', 'email' => 'sales@spinecare.com.bd', 'status' => 'active']
        );
        Customer::updateOrCreate(
            ['name' => 'Walk-in Customer'],
            ['phone' => null, 'status' => 'active']
        );

        // 3. Import the full product catalog + categories from the Excel file.
        $this->command->info('Importing products from public/products.xlsx ...');
        Artisan::call('app:import-products');
        $this->command->line(trim(Artisan::output()));

        // 4. Set realistic prices and build FIFO cost layers (2 batches per product).
        $this->command->info('Seeding prices and FIFO stock layers ...');
        Artisan::call('app:seed-fifo-data');
        $this->command->line(trim(Artisan::output()));
    }
}
