<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Statamic\Eloquent\Database\BaseMigration as Migration;

class CreateAssetContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('statamic.eloquent-driver.table_prefix', '').'asset_containers')) {
            Schema::create(config('statamic.eloquent-driver.table_prefix', '').'asset_containers', function (Blueprint $table) {
                $table->id();
                $table->string('handle');
                $table->string('title');
                $table->string('disk');
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('statamic.eloquent-driver.table_prefix', '').'asset_containers');
    }
}
