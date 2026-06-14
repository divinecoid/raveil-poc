<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS cash_flows");
    }
};
