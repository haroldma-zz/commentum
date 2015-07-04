<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculateThreadMomentumFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
DROP FUNCTION IF EXISTS calculateThreadMomentum;
CREATE FUNCTION calculateThreadMomentum(impressions INTEGER, views INTEGER, comment_momentum DOUBLE)
RETURNS DOUBLE
BEGIN
    RETURN (views * (views / impressions) + comment_momentum) * 10;
END
SQL;
	DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	DB::statement("DROP FUNCTION IF EXISTS calculateThreadMomentum;");
    }
}
