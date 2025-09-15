
<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;


use Illuminate\Database\Schema\Blueprint;



if (!defined('STDIN')) {
    define('STDIN', fopen('php://stdin', 'r'));
}






Route::get('/database', function () {
    return view('Supperadmin.database.index');
});

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
Route::post('/make-model', function (Request $request) {
    $request->validate([
        'model_name' => [
            'required',
            'string',
            'regex:/^[A-Za-z][A-Za-z0-9\/]*[A-Za-z0-9]$/'
        ]
    ]);

    try {
        Artisan::call('make:model', [
            'name' => $request->model_name,
            '--migration' => true,
        ]);

        $output = Artisan::output();

     return response()->json([
    'status' => 'success',
    'message' => 'Model created successfully',
    'model_name' => $request->model_name,
 'id' => Str::snake(class_basename($request->model_name)) . '_id',
    'full_namespace' => str_replace('/', '\\', $request->model_name), // convert / to \
]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
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
        'message' => "✅ Columns added to migration file `$migrationName`.",
       'tablename' => Str::afterLast($migrationName, 'create_') ? Str::afterLast($migrationName, 'create_') : Str::beforeLast($migrationName, '_table'),
        'migration_file' => $migrationPath->getFilename(),
        'column_count' => count($validated['columns']),
       
        
    ]);
});
Route::get('/all-models', function () {
    try {
        $modelFiles = File::allFiles(app_path('Models')); // Get all files in app/Models
        $models = [];

        foreach ($modelFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (class_exists($class)) {
                $models[] = [
                    'class' => $class,
                    'name' => class_basename($class),
                    'namespace' => $class
                ];
            }
        }

        // Debug: Log the models found
        \Log::info('Models found:', $models);

        return response()->json([
            'success' => true,
            'data' => $models,
            'count' => count($models)
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in all-models route:', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'data' => []
        ]);
    }
});

// Test route to check if models are being found
Route::get('/test-models', function () {
    $modelFiles = File::allFiles(app_path('Models'));
    $result = [];
    
    foreach ($modelFiles as $file) {
        $relativePath = $file->getRelativePathname();
        $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);
        
        $result[] = [
            'file' => $file->getPathname(),
            'relative_path' => $relativePath,
            'class' => $class,
            'class_exists' => class_exists($class)
        ];
    }
    
    return response()->json($result);
});

// Test route to check model deletion path calculation
Route::get('/test-model-path/{modelClass}', function ($modelClass) {
    $relativePath = str_replace('App\\Models\\', '', $modelClass);
    $relativePath = str_replace('\\', '/', $relativePath);
    
    $alternativePaths = [
        'path1' => app_path('Models/' . $relativePath . '.php'),
        'path2' => app_path('Models\\' . str_replace('/', '\\', $relativePath) . '.php'),
        'path3' => base_path('app/Models/' . $relativePath . '.php'),
        'path4' => base_path('app\\Models\\' . str_replace('/', '\\', $relativePath) . '.php')
    ];
    
    $results = [];
    foreach ($alternativePaths as $key => $path) {
        $results[$key] = [
            'path' => $path,
            'exists' => File::exists($path)
        ];
    }
    
    return response()->json([
        'model_class' => $modelClass,
        'relative_path' => $relativePath,
        'app_path' => app_path('Models'),
        'base_path' => base_path('app/Models'),
        'paths' => $results,
        'any_exists' => collect($results)->pluck('exists')->contains(true)
    ]);
});

Route::get('/tables-status', function () {
    $modelFiles = File::allFiles(app_path('Models'));
    $tables = [];

    foreach ($modelFiles as $file) {
        $relativePath = $file->getRelativePathname();
        $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

        if (class_exists($class)) {
            $modelInstance = new $class;
            $table = $modelInstance->getTable();
            $migrated = Schema::hasTable($table);
            $columnsCount = $migrated ? count(Schema::getColumnListing($table)) : 0;

            $tables[] = [
                'table_name' => $table,
                'columns_count' => $columnsCount,
                'migrated' => $migrated
            ];
        }
    }

    return response()->json($tables);
});


Route::post('/migrate-table', function (\Illuminate\Http\Request $request) {
    $table = $request->table;

    // Find migration file that contains this table name
    $migrationFile = collect(File::files(database_path('migrations')))
        ->first(fn($file) => str_contains(File::get($file), "Schema::create('$table'"));

    if (!$migrationFile) {
        return response()->json(['message' => "Migration for table '$table' not found"], 404);
    }

    try {
        Artisan::call('migrate', ['--path' => 'database/migrations/' . $migrationFile->getFilename(), '--force' => true]);

        return response()->json([
            'message' => "✅ Table '$table' migrated successfully",
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
});

// Get migrated tables with their current columns
Route::get('/migrated-tables', function () {
    $modelFiles = File::allFiles(app_path('Models'));
    $migratedTables = [];

    foreach ($modelFiles as $file) {
        $relativePath = $file->getRelativePathname();
        $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

        if (class_exists($class)) {
            $modelInstance = new $class;
            $table = $modelInstance->getTable();
            
            // Only include migrated tables
            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
                $migratedTables[] = [
                    'class' => $class,
                    'name' => class_basename($class),
                    'table_name' => $table,
                    'columns' => $columns,
                    'columns_count' => count($columns)
                ];
            }
        }
    }

    return response()->json($migratedTables);
});

// Add new columns to existing migrated table
Route::post('/add-columns-to-table', function (Request $request) {
    $validated = $request->validate([
        'table_name' => 'required|string',
        'columns' => 'required|array',
        'columns.*.name' => 'required|string',
        'columns.*.type' => 'required|string',
        'columns.*.nullable' => 'nullable|boolean',
        'columns.*.default' => 'nullable',
        'columns.*.unique' => 'nullable|boolean',
        'columns.*.length' => 'nullable|integer',
        'columns.*.unsigned' => 'nullable|boolean',
        'columns.*.index' => 'nullable|boolean',
    ]);

    $tableName = $validated['table_name'];
    
    // Check if table exists
    if (!Schema::hasTable($tableName)) {
        return response()->json([
            'status' => 'error',
            'message' => "Table '$tableName' does not exist."
        ], 404);
    }

    try {
        // Create a new migration file for adding columns
        $timestamp = now()->format('Y_m_d_His');
        $migrationName = "add_columns_to_{$tableName}_table";
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath = database_path("migrations/{$fileName}");

        // Build migration content
        $migrationContent = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('{$tableName}', function (Blueprint \$table) {";

        foreach ($validated['columns'] as $column) {
            $name = $column['name'];
            $type = $column['type'];
            $length = $column['length'] ?? null;

            // Check if column already exists
            if (Schema::hasColumn($tableName, $name)) {
                continue; // Skip existing columns
            }

            // Build column definition
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
            if (!empty($column['unsigned'])) {
                $definition .= "->unsigned()";
            }
            if (!empty($column['index'])) {
                $definition .= "->index()";
            }

            $migrationContent .= "\n            $definition;";
        }

        $migrationContent .= "\n        });\n    }\n\n    public function down()\n    {\n        Schema::table('{$tableName}', function (Blueprint \$table) {";

        // Add drop columns in down method
        foreach ($validated['columns'] as $column) {
            if (!Schema::hasColumn($tableName, $column['name'])) {
                $migrationContent .= "\n            \$table->dropColumn('{$column['name']}');";
            }
        }

        $migrationContent .= "\n        });\n    }\n};";

        // Write migration file
        File::put($filePath, $migrationContent);

        // Run the migration
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'status' => 'success',
            'message' => "✅ New columns added to table '$tableName' successfully",
            'migration_file' => $fileName,
            'output' => Artisan::output()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Delete columns from existing table
Route::post('/delete-columns-from-table', function (Request $request) {
    $validated = $request->validate([
        'table_name' => 'required|string',
        'columns' => 'required|array',
        'columns.*' => 'required|string',
    ]);

    $tableName = $validated['table_name'];
    $columnsToDelete = $validated['columns'];
    
    // Check if table exists
    if (!Schema::hasTable($tableName)) {
        return response()->json([
            'status' => 'error',
            'message' => "Table '$tableName' does not exist."
        ], 404);
    }

    // Check if all columns exist
    $existingColumns = Schema::getColumnListing($tableName);
    $nonExistentColumns = array_diff($columnsToDelete, $existingColumns);
    
    if (!empty($nonExistentColumns)) {
        return response()->json([
            'status' => 'error',
            'message' => "Columns not found in table '$tableName': " . implode(', ', $nonExistentColumns)
        ], 400);
    }

    try {
        // Create a new migration file for dropping columns
        $timestamp = now()->format('Y_m_d_His');
        $migrationName = "drop_columns_from_{$tableName}_table";
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath = database_path("migrations/{$fileName}");

        // Build migration content
        $migrationContent = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('{$tableName}', function (Blueprint \$table) {";

        foreach ($columnsToDelete as $column) {
            $migrationContent .= "\n            \$table->dropColumn('{$column}');";
        }

        $migrationContent .= "\n        });\n    }\n\n    public function down()\n    {\n        Schema::table('{$tableName}', function (Blueprint \$table) {";

        // In down method, we would need to recreate the columns
        // For now, we'll add a comment about manual restoration
        $migrationContent .= "\n            // Note: Column restoration requires manual implementation\n";
        $migrationContent .= "            // Original columns dropped: " . implode(', ', $columnsToDelete);

        $migrationContent .= "\n        });\n    }\n};";

        // Write migration file
        File::put($filePath, $migrationContent);

        // Run the migration
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'status' => 'success',
            'message' => "✅ Columns " . implode(', ', $columnsToDelete) . " deleted from table '$tableName' successfully",
            'migration_file' => $fileName,
            'output' => Artisan::output()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Delete model and its database table
Route::post('/delete-model', function (Request $request) {
    $validated = $request->validate([
        'model_class' => 'required|string',
    ]);

    $modelClass = $validated['model_class'];
    
    try {
        // Check if model class exists
        if (!class_exists($modelClass)) {
            return response()->json([
                'status' => 'error',
                'message' => "Model class '$modelClass' does not exist."
            ], 404);
        }

        // Get model instance to get table name
        $modelInstance = new $modelClass;
        $tableName = $modelInstance->getTable();

        // Check if table exists in database
        $tableExists = Schema::hasTable($tableName);

        // Create migration to drop table if it exists
        if ($tableExists) {
            $timestamp = now()->format('Y_m_d_His');
            $migrationName = "drop_{$tableName}_table";
            $fileName = "{$timestamp}_{$migrationName}.php";
            $filePath = database_path("migrations/{$fileName}");

            // Build migration content
            $migrationContent = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('{$tableName}');
    }

    public function down()
    {
        // Note: Table restoration requires manual implementation
        // Original table dropped: {$tableName}
        // You would need to recreate the table structure here
    }
};";

            // Write migration file
            File::put($filePath, $migrationContent);

            // Run the migration
            Artisan::call('migrate', ['--force' => true]);
        }

        // Delete the model file - improved path calculation
        $relativePath = str_replace('App\\Models\\', '', $modelClass);
        $relativePath = str_replace('\\', '/', $relativePath);
        $modelPath = app_path('Models/' . $relativePath . '.php');
        
        \Log::info('Model deletion attempt:', [
            'model_class' => $modelClass,
            'relative_path' => $relativePath,
            'model_path' => $modelPath,
            'file_exists' => File::exists($modelPath),
            'app_path' => app_path('Models')
        ]);
        
        // Also try alternative path calculations
        $alternativePaths = [
            app_path('Models/' . $relativePath . '.php'),
            app_path('Models\\' . str_replace('/', '\\', $relativePath) . '.php'),
            base_path('app/Models/' . $relativePath . '.php'),
            base_path('app\\Models\\' . str_replace('/', '\\', $relativePath) . '.php')
        ];
        
        $modelFileDeleted = false;
        $deletedPath = null;
        
        foreach ($alternativePaths as $altPath) {
            if (File::exists($altPath)) {
                File::delete($altPath);
                $modelFileDeleted = true;
                $deletedPath = $altPath;
                \Log::info('Model file deleted successfully:', ['path' => $altPath]);
                break;
            }
        }
        
        if (!$modelFileDeleted) {
            \Log::warning('Model file not found in any of these paths:', $alternativePaths);
        }

        // Also delete any related migration files for this table
        $migrationFiles = collect(File::files(database_path('migrations')))
            ->filter(fn($file) => str_contains($file->getFilename(), $tableName));

        foreach ($migrationFiles as $migrationFile) {
            // Don't delete the drop migration we just created
            if (!str_contains($migrationFile->getFilename(), "drop_{$tableName}_table")) {
                File::delete($migrationFile->getPathname());
            }
        }

        $message = "✅ Model '$modelClass' deleted successfully";
        if ($tableExists) {
            $message .= " and table '$tableName' dropped";
        }
        if (!$modelFileDeleted) {
            $message .= " (Note: Model file not found)";
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'table_dropped' => $tableExists,
            'model_file_deleted' => $modelFileDeleted,
            'migration_file' => $tableExists ? $fileName : null,
            'output' => $tableExists ? Artisan::output() : null
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Delete table directly (with model)
Route::post('/delete-table', function (Request $request) {
    $validated = $request->validate([
        'table_name' => 'required|string',
    ]);

    $tableName = $validated['table_name'];
    
    try {
        // Check if table exists
        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status' => 'error',
                'message' => "Table '$tableName' does not exist."
            ], 404);
        }

        // Find the model that corresponds to this table
        $modelFiles = File::allFiles(app_path('Models'));
        $modelToDelete = null;
        
        foreach ($modelFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);
            
            if (class_exists($class)) {
                $modelInstance = new $class;
                if ($modelInstance->getTable() === $tableName) {
                    $modelToDelete = [
                        'class' => $class,
                        'file' => $file
                    ];
                    break;
                }
            }
        }

        // Create migration to drop table
        $timestamp = now()->format('Y_m_d_His');
        $migrationName = "drop_{$tableName}_table";
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath = database_path("migrations/{$fileName}");

        // Build migration content
        $migrationContent = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('{$tableName}');
    }

    public function down()
    {
        // Note: Table restoration requires manual implementation
        // Original table dropped: {$tableName}
        // You would need to recreate the table structure here
    }
};";

        // Write migration file
        File::put($filePath, $migrationContent);

        // Run the migration
        Artisan::call('migrate', ['--force' => true]);

        // Delete the model file if found
        $modelDeleted = false;
        if ($modelToDelete) {
            if (File::exists($modelToDelete['file']->getPathname())) {
                File::delete($modelToDelete['file']->getPathname());
                $modelDeleted = true;
            }
        }

        // Delete related migration files for this table
        $migrationFiles = collect(File::files(database_path('migrations')))
            ->filter(fn($file) => str_contains($file->getFilename(), $tableName));

        foreach ($migrationFiles as $migrationFile) {
            // Don't delete the drop migration we just created
            if (!str_contains($migrationFile->getFilename(), "drop_{$tableName}_table")) {
                File::delete($migrationFile->getPathname());
            }
        }

        $message = "✅ Table '$tableName' deleted successfully";
        if ($modelDeleted) {
            $message .= " and model file removed";
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'model_deleted' => $modelDeleted,
            'model_class' => $modelToDelete ? $modelToDelete['class'] : null,
            'migration_file' => $fileName,
            'output' => Artisan::output()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

