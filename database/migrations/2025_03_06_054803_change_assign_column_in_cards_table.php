<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            // Ubah kolom `assign` menjadi JSON jika belum JSON
            $table->json('assign')->nullable()->change();
        });

        // Perbaiki data yang masih berbentuk string menjadi array JSON
        DB::statement("UPDATE cards SET assign = '[]' WHERE assign IS NULL OR assign = ''");

        // Ubah semua `assign` yang masih string menjadi JSON yang valid
        $cards = DB::table('cards')->get();
        foreach ($cards as $card) {
            $decoded = json_decode($card->assign, true);
            if (!is_array($decoded)) {
                $fixedAssign = json_encode(explode(',', $card->assign)); // Pisahkan jika masih string biasa
                DB::table('cards')->where('id', $card->id)->update(['assign' => $fixedAssign]);
            }
        }
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->text('assign')->nullable()->change(); // Jika ingin revert ke string
        });
    }
};
