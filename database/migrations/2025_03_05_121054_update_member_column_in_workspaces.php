<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::table('workspaces', function (Blueprint $table) {
            // Ubah kolom `member` menjadi JSON jika belum JSON
            $table->json('member')->nullable()->change();
        });

        // Perbaiki data yang masih berbentuk string menjadi array JSON
        DB::statement("UPDATE workspaces SET member = '[]' WHERE member IS NULL OR member = ''");

        // Ubah semua `member` yang masih string menjadi JSON yang valid
        $workspaces = DB::table('workspaces')->get();
        foreach ($workspaces as $workspace) {
            $decoded = json_decode($workspace->member, true);
            if (!is_array($decoded)) {
                $fixedMember = json_encode(explode(',', $workspace->member)); // Pisahkan jika masih string biasa
                DB::table('workspaces')->where('id', $workspace->id)->update(['member' => $fixedMember]);
            }
        }
    }

    public function down()
    {
        Schema::table('workspaces', function (Blueprint $table) {
            $table->text('member')->nullable()->change(); // Jika ingin revert ke string
        });
    }
};
