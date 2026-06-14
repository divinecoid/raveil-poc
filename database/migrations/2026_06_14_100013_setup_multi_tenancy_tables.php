<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create default companies
        $raveilId = DB::table('companies')->insertGetId([
            'name' => 'RAVEIL',
            'slug' => 'raveil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $carbonizedId = DB::table('companies')->insertGetId([
            'name' => 'CARBONIZED',
            'slug' => 'carbonized',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Attach first user to both companies if exists
        $firstUser = DB::table('users')->orderBy('id')->first();
        if ($firstUser) {
            DB::table('company_user')->insert([
                ['company_id' => $raveilId, 'user_id' => $firstUser->id],
                ['company_id' => $carbonizedId, 'user_id' => $firstUser->id],
            ]);
        }

        $tables = [
            'brands', 'categories', 'products', 'settings', 'product_clicks',
            'customers', 'expenses', 'vehicles', 'sales_orders', 'sales_order_items',
            'stock_movements', 'projects', 'tasks', 'invoices', 'invoice_items', 'sales_order_services'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($raveilId) {
                // Add company_id with a default value of 1 (RAVEIL) to handle existing rows safely
                $t->foreignId('company_id')->default($raveilId)->constrained()->cascadeOnDelete();
            });
        }
        
        // Update cash_flows view to include company_id
        DB::statement("
            CREATE OR REPLACE VIEW cash_flows AS
            SELECT
                CONCAT('inv_', id) as id,
                'Income' as type,
                issue_date as date,
                invoice_number as reference,
                total as amount,
                company_id,
                created_at,
                updated_at
            FROM invoices
            WHERE status = 'Paid'

            UNION ALL

            SELECT
                CONCAT('exp_', id) as id,
                'Expense' as type,
                date as date,
                category as reference,
                amount as amount,
                company_id,
                created_at,
                updated_at
            FROM expenses
            WHERE status = 'Paid'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert cash_flows view
        DB::statement("
            CREATE OR REPLACE VIEW cash_flows AS
            SELECT
                CONCAT('inv_', id) as id,
                'Income' as type,
                issue_date as date,
                invoice_number as reference,
                total as amount,
                created_at,
                updated_at
            FROM invoices
            WHERE status = 'Paid'

            UNION ALL

            SELECT
                CONCAT('exp_', id) as id,
                'Expense' as type,
                date as date,
                category as reference,
                amount as amount,
                created_at,
                updated_at
            FROM expenses
            WHERE status = 'Paid'
        ");

        $tables = [
            'brands', 'categories', 'products', 'settings', 'product_clicks',
            'customers', 'expenses', 'vehicles', 'sales_orders', 'sales_order_items',
            'stock_movements', 'projects', 'tasks', 'invoices', 'invoice_items', 'sales_order_services'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropForeign(['company_id']);
                $t->dropColumn('company_id');
            });
        }
    }
};
