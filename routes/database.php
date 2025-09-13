
<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;


use Illuminate\Database\Schema\Blueprint;





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
Route::post('/make-model', function (Request $request) { // type-hint the instance
    // Validate the request using the instance
    $request->validate([
        'model_name' => 'required|string'
    ]);

    try {
        ob_start(); // capture any unwanted output
        Artisan::call('make:model', [
            'name' => $request->model_name,
            '--migration' => true,
        ]);
        $output = trim(ob_get_clean() . Artisan::output());

        return response()->json([
            'status' => 'success',
            'message' => 'Model created successfully',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/get-non-migrated-models', function () {
    $modelFiles = File::allFiles(app_path('Models'));
    $nonMigratedModels = [];

    foreach ($modelFiles as $file) {
        $relativePath = $file->getRelativePathname();
        $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

        if (class_exists($class)) {
            $modelInstance = new $class;
            $table = $modelInstance->getTable();

            // Include only if table does NOT exist
            if (!Schema::hasTable($table)) {
                $nonMigratedModels[] = [
                    'class' => $class,
                    'name' => class_basename($class),
                    'expected_table' => $table
                ];
            }
        }
    }

    return response()->json($nonMigratedModels);
});
Route::post('/add-columns', function (Request $request) {
    $validated = $request->validate([
        'migration' => 'required|string', // e.g. "create_super_admin_menus_table"
        'columns' => 'required|array',
        'columns.*.name' => 'required|string',
        'columns.*.type' => 'required|string',
        'columns.*.nullable' => 'nullable|boolean',
        'columns.*.default' => 'nullable',
        'columns.*.unique' => 'nullable|boolean',
        'columns.*.length' => 'nullable|integer',
    ]);

    $migrationName = $validated['migration'];

    // 🔍 Find migration file by partial name
    $migrationPath = collect(File::files(database_path('migrations')))
        ->first(fn($file) => Str::contains($file->getFilename(), $migrationName));

    if (!$migrationPath) {
        return response()->json([
            'status' => 'error',
            'message' => "Migration file for `$migrationName` not found."
        ], 404);
    }

    $fileContent = File::get($migrationPath);

    // 🏗️ Build column definitions
    $columnLines = '';
    foreach ($validated['columns'] as $column) {
        $name = $column['name'];
        $type = $column['type'];
        $length = $column['length'] ?? null;

        // Example: $table->string('title', 255)
        $definition = $length
            ? "\$table->$type('$name', $length)"
            : "\$table->$type('$name')";

        if (!empty($column['nullable'])) {
            $definition .= "->nullable()";
        }
        if (array_key_exists('default', $column) && $column['default'] !== null) {
            $val = is_string($column['default']) ? "'{$column['default']}'" : $column['default'];
            $definition .= "->default($val)";
        }
        if (!empty($column['unique'])) {
            $definition .= "->unique()";
        }

        $columnLines .= "            $definition;\n";
    }

    // ✍️ Insert new columns before $table->timestamps();
    $updatedContent = str_replace(
        '$table->timestamps();',
        $columnLines . '            $table->timestamps();',
        $fileContent
    );

    File::put($migrationPath, $updatedContent);

    return response()->json([
        'status' => 'success',
        'message' => "✅ Columns added to migration file `$migrationName`. Run `php artisan migrate` to apply."
    ]);
});