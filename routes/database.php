
<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // ✅ add this

Route::get('/migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Migration completed',
            'output' => trim(Artisan::output()) ?: 'No migrations were pending.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/updatemigrate', function () {
    try {
        Artisan::call('migrate:refresh', ['--force' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Migrations refreshed',
            'output' => trim(Artisan::output())
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
Route::get('/seed/{seeder?}', function ($seeder = null) {
    try {
        if ($seeder) {
            // Run the specified seeder
            Artisan::call('db:seed', [
                '--class' => $seeder,
                '--force' => true
            ]);
            $ranSeeder = $seeder;
            $output = trim(Artisan::output());
        } else {
            // Run the default DatabaseSeeder
            Artisan::call('db:seed', ['--force' => true]);
            $ranSeeder = 'DatabaseSeeder';
            $output = trim(Artisan::output());
        }

        return response()->json([
            'status' => 'success',
            'seeder_ran' => $ranSeeder,
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
