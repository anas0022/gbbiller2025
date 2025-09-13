
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

Route::post('/add-columns', function(Request $request) {
    $request->validate([
        'model' => 'required|string',
        'columns' => 'required|array',
        'columns.*.name' => 'required|string',
        'columns.*.type' => 'required|string',
        'columns.*.nullable' => 'nullable|boolean',
        'columns.*.default' => 'nullable|string',
        'columns.*.unique' => 'nullable|boolean',
        'columns.*.length' => 'nullable|integer',
    ]);

    $model = $request->model;

    // Try to resolve actual table name from model
    if (class_exists($model)) {
        $instance = new $model;
        $table = $instance->getTable();
    } else {
        // fallback to snake plural
        $table = Str::snake(Str::plural(class_basename($model)));
    }

    // ✅ If table does not exist, create it
    if (!Schema::hasTable($table)) {
        Schema::create($table, function (Blueprint $tableBlueprint) use ($request) {
            $tableBlueprint->id();
            foreach ($request->columns as $column) {
                $name = $column['name'];
                $type = $column['type'];
                $length = $column['length'] ?? null;

                $col = $length 
                    ? $tableBlueprint->$type($name, $length)
                    : $tableBlueprint->$type($name);

                if (!empty($column['nullable'])) $col->nullable();
                if (!empty($column['default'])) $col->default($column['default']);
                if (!empty($column['unique'])) $col->unique();
            }
            $tableBlueprint->timestamps();
        });

        return response()->json([
            'status' => 'success',
            'message' => "Table `$table` created and columns added."
        ]);
    }

    // ✅ If table exists, just add columns
    Schema::table($table, function (Blueprint $tableBlueprint) use ($request, $table) {
        foreach ($request->columns as $column) {
            $name = $column['name'];
            $type = $column['type'];
            $length = $column['length'] ?? null;

            if (Schema::hasColumn($table, $name)) continue;

            $col = $length 
                ? $tableBlueprint->$type($name, $length)
                : $tableBlueprint->$type($name);

            if (!empty($column['nullable'])) $col->nullable();
            if (!empty($column['default'])) $col->default($column['default']);
            if (!empty($column['unique'])) $col->unique();
        }
    });

    return response()->json([
        'status' => 'success',
        'message' => "Columns added successfully to `$table`."
    ]);
});