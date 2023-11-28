<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoModel extends Model
{

    protected $primaryKey = 'title';
    public $incrementing = false; // Defina como falso para evitar incremento automático

    protected $table = 'eventos';
    protected $fillable = ['title', 'description','start','end','usr_responsavel'];
    public $timestamps = false; // Desabilita as colunas de data e hora
}
