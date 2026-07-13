<?php

$migrationsDir = __DIR__ . '/../database/migrations';
$modelsDir = __DIR__ . '/../app/Models';

// 1. Refactor Migrations
$migrationFiles = glob($migrationsDir . '/*.php');
foreach ($migrationFiles as $file) {
    $basename = basename($file);
    if (str_contains($basename, 'create_cache_table') || str_contains($basename, 'create_jobs_table')) {
        continue;
    }

    $content = file_get_contents($file);
    $original = $content;

    // Replace $table->id() or $table->id('...') with $table->uuid('id')->primary()
    $content = preg_replace('/\$table->id\(\s*\);/', "\$table->uuid('id')->primary();", $content);

    // Replace $table->foreignId(...) with $table->foreignUuid(...)
    $content = preg_replace('/\$table->foreignId\(/', "\$table->foreignUuid(", $content);

    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Refactored migration: $basename\n";
    }
}

// 2. Refactor Models
$modelFiles = glob($modelsDir . '/*.php');
foreach ($modelFiles as $file) {
    $basename = basename($file);
    $content = file_get_contents($file);
    $original = $content;

    // Check if it's a model class
    if (str_contains($content, 'class ') && !str_contains($content, 'trait ')) {
        // Check if HasUuids is already used
        if (!str_contains($content, 'use HasUuids;')) {
            // Find namespace line to insert use statement
            if (preg_match('/namespace\s+[^;]+;/', $content, $matches)) {
                $namespaceLine = $matches[0];
                $content = str_replace(
                    $namespaceLine,
                    $namespaceLine . "\n\nuse Illuminate\Database\Eloquent\Concerns\HasUuids;",
                    $content
                );
            }

            // Find "class ... {" and inject right after the bracket
            if (preg_match('/(class\s+\w+\s+(extends\s+\w+\s+)?(implements\s+[^\{]+)?\{)/', $content, $matches)) {
                $classDecl = $matches[0];
                $content = str_replace(
                    $classDecl,
                    $classDecl . "\n    use HasUuids;",
                    $content
                );
            }
        }
    }

    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Refactored model: $basename\n";
    }
}

echo "Done!\n";
