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
CREATE FUNCTION calculateThreadMomentum(impressions INTEGER, views INTEGER, comment_momentum DOUBLE, created DATETIME) RETURNS DOUBLE
	BEGIN
		DECLARE score INTEGER;
		DECLARE seconds FLOAT;
    
		SET score = (views * (views / impressions) + comment_momentum);
		SET seconds = UNIX_TIMESTAMP(created) - 1430006400;
    
		RETURN ROUND(score + seconds / 45000, 7);
	END
SQL;
	DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "DROP FUNCTION IF EXISTS calculateThreadMomentum;";
	DB::connection()->getPdo()->exec($sql);
    }
}
