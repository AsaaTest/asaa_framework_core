<?php

use Asaa\Database\DB;
use Asaa\Database\Migrations\Migration;

return new class () implements Migration {
    public function up()
    {
        DB::statement('$UP');
    }

    public function down()
    {
        DB::statement('$DOWN');
    }
};
