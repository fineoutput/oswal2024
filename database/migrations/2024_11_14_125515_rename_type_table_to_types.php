<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTypeTableToTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::rename('types_sub', 'types_subs');
}

public function down()
{
    Schema::rename('types_sub', 'types_subs');
}
}
