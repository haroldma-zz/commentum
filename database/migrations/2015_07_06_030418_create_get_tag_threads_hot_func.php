<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetTagThreadsHotFunc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
DROP PROCEDURE IF EXISTS getThreadsByHotness;

CREATE PROCEDURE getThreadsByHotness(tag INTEGER, nsfw_mode INTEGER, results_offset INTEGER, max_results INTEGER)
BEGIN
    SELECT *,
    calculateHotnessFromMomentum(impressions, views, total_momentum, created_at) as sort
    FROM (SELECT t.*, sum(c.momentum) as total_momentum FROM threads t
    LEFT JOIN comments c ON c.thread_id = t.id
    INNER JOIN tags tt ON tt.id = t.tag_id
    WHERE (nsfw_mode = 0 or (t.nsfw = 0 and nsfw_mode = -1) or (t.nsfw = 1 and nsfw_mode = 1))
    and (t.tag_id = tag OR tag = 0 or (tag = -1 and tt.is_default = 1))
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
        DB::statement("DROP PROCEDURE IF EXISTS getThreadsByHotness;");
    }
}
