<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImproveHotFuncLog10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
DROP FUNCTION if exists calculateHotnessFromMomentum;

CREATE FUNCTION calculateHotnessFromMomentum(impressions INTEGER, views INTEGER, comment_momentum DOUBLE, created DATETIME) RETURNS double
BEGIN
    DECLARE score, sign INTEGER;
    DECLARE seconds, score_log FLOAT;

    SET score = calculateThreadMomentum(impressions, views, comment_momentum);
    SET seconds = UNIX_TIMESTAMP(created) - 1430006400;

    IF (ABS(score) > 1) then
        SET score_log = LOG10(ABS(score));
    ELSE
        SET score_log = 1;
    END IF;

    IF (score > 0) THEN
        SET sign = 1;
    ELSEIF (score < 0) THEN
        SET sign = -1;
    ELSE
        SET sign = 0;
    END IF;

    RETURN ROUND(sign * score_log + seconds / 45000, 7);
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
DROP FUNCTION IF EXISTS calculateHotnessFromMomentum;
CREATE FUNCTION calculateHotnessFromMomentum(impressions INTEGER, views INTEGER, comment_momentum DOUBLE, created DATETIME)
RETURNS DOUBLE
BEGIN
    DECLARE score INTEGER;
    DECLARE seconds FLOAT;

    SET score = calculateThreadMomentum(impressions, views, comment_momentum);
    SET seconds = UNIX_TIMESTAMP(created) - 1435449600;

    RETURN ROUND(score / 200 + seconds / 45000, 7);
END
SQL;
        DB::unprepared($sql);
    }
}
