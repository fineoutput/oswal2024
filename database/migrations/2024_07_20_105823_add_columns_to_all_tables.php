<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            // Check if the column exists in the current table
            if (!Schema::hasColumn($table, 'deleted_at')) {
                // Add the column to the table
                Schema::table($table, function (Blueprint $table) {
                    $table->timestamp('deleted_at')->nullable();
                    // Replace 'string' with the appropriate data type for your column
                    // Modify other column properties as necessary (nullable, default value, etc.)
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Implement the down method if necessary
        // This is typically used to rollback the changes made in the up method
    }
}
