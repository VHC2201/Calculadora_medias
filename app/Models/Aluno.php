<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = ['turma_id', 'nome', 'matricula'];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function nota()
    {
        return $this->hasOne(Nota::class);
    }
}
