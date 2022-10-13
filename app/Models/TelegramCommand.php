<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramCommand extends Model
{
    use HasFactory;

    /**
     * Защита колонки id от изменения
     *
     * @var string[]
     */
    protected $guarded = ['id'];
}
