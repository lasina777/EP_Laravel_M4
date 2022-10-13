<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запуск миграции для создания таблицы telegram_commands
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_commands', function (Blueprint $table) {
            $table->id();
            $table->string('command');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Отмена миграции
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_commands');
    }
};
