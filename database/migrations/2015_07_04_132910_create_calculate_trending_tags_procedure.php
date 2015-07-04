<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculateTrendingTagsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
DROP PROCEDURE IF EXISTS calculateTrendingTags;
CREATE PROCEDURE calculateTrendingTags(start_time DATETIME, end_time DATETIME, max_results INTEGER)
BEGIN
    SELECT id, display_title, calculateThreadMomentum(views, impressions, comment_momentum) AS momentum
    FROM (SELECT 
            *,
            (SELECT SUM(c.momentum) FROM threads AS td 
                LEFT JOIN comments c ON c.thread_id = td.id 
                WHERE td.tag_id=t.id 
                AND td.created_at >= start_time 
                AND td.created_at <= end_time) AS comment_momentum,
            (SELECT SUM(views) FROM threads 
                WHERE tag_id=t.id
                AND created_at >= start_time 
                AND created_at <= end_time) AS views,
            (SELECT SUM(impressions) FROM threads 
                WHERE tag_id=t.id
                AND created_at >= start_time 
                AND created_at <= end_time) AS impressions 
        FROM tags AS t
            WHERE t.privacy IS NULL OR t.privacy = FALSE
            AND t.nsfw IS NULL OR t.nsfw = FALSE) AS v
	ORDER BY momentum DESC
	LIMIT max_results;
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
        DB::statement("DROP PROCEDURE IF EXISTS calculateTrendingTags;");
    }
}
