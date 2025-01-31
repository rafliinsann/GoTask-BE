<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
            $table->dropColumn('workspace_id');
        });
    }
};
