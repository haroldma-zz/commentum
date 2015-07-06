<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NoZeroMomentum extends Migration
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
    DECLARE momentum DOUBLE;
    SET momentum = (views * (views / impressions) + comment_momentum) * 10;

    IF (momentum >= 1) then
    	return momentum;
    END IF;
    return 1;
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
}
