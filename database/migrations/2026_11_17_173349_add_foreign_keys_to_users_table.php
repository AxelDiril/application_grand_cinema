
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->char('role_code', 1)->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'comment')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('comment', 250)->nullable();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_code', 'fk_users_roles')
                ->references('role_code')
                ->on('roles')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_roles');
        });
    }
};
