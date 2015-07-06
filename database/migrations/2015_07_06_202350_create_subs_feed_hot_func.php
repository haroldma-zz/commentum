<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsFeedHotFunc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
DROP PROCEDURE IF EXISTS getThreadsByHotnessForSubscription;

CREATE PROCEDURE getThreadsByHotnessForSubscription(results_user_id INTEGER, nsfw_mode INTEGER, results_offset INTEGER, max_results INTEGER)
BEGIN
    SELECT *,
    calculateHotnessFromMomentum(impressions, views, total_momentum, created_at) as sort
    FROM (SELECT t.*, sum(c.momentum) as total_momentum FROM (SELECT th.* FROM
            (SELECT tag_id as tag_id FROM tag_subscribers s
            WHERE user_id = results_user_id) subs
    INNER JOIN threads th
    ON th.tag_id = subs.tag_id) t
    LEFT JOIN comments c ON c.thread_id = t.id
    INNER JOIN tags tt ON tt.id = t.tag_id
    WHERE (nsfw_mode = 0 or (t.nsfw = 0 and nsfw_mode = -1) or (t.nsfw = 1 and nsfw_mode = 1))
        and t.deleted_at is null
    GROUP BY t.id) as f
    ORDER BY sort desc
    LIMIT max_results OFFSET results_offset;
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
        DB::statement("DROP PROCEDURE IF EXISTS getThreadsByHotnessForSubscription;");
    }
}
