<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'bimestre1',
        'bimestre2',
        'bimestre3',
        'bimestre4',
        'media',
        'conceito',
        'nota_recuperacao',
        'aprovado_recuperacao',
    ];

    protected $casts = [
        'bimestre1'            => 'float',
        'bimestre2'            => 'float',
        'bimestre3'            => 'float',
        'bimestre4'            => 'float',
        'media'                => 'float',
        'nota_recuperacao'     => 'float',
        'aprovado_recuperacao' => 'boolean',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    // ─── Cálculo da média ──────────────────────────────────────────────────────

    public static function calcularMedia(float $b1, float $b2, float $b3, float $b4): float
    {
        return round(($b1 + $b2 + $b3 + $b4) / 4, 2);
    }

    // ─── Conceito ─────────────────────────────────────────────────────────────

    public static function calcularConceito(float $media): string
    {
        if ($media > 9)  return 'A';
        if ($media > 7)  return 'B';
        if ($media > 4)  return 'C';
        return 'D';
    }

    // ─── Situação ─────────────────────────────────────────────────────────────

    public static function calcularSituacao(string $conceito): string
    {
        return match ($conceito) {
            'A' => 'Aprovado com Louvor',
            'B' => 'Aluno Aprovado',
            'C' => 'Recuperação, sua chance de passar',
            'D' => 'Poxa vida, vamos tentar novamente ano que vem',
        };
    }

    // ─── Recuperação ──────────────────────────────────────────────────────────

    /**
     * Regra: média + nota_recuperação >= 10  →  aprovado
     */
    public static function calcularRecuperacao(float $media, float $notaRecuperacao): bool
    {
        return ($media + $notaRecuperacao) >= 10;
    }

    // ─── Helpers de instância ─────────────────────────────────────────────────

    public function getSituacaoAttribute(): string
    {
        return self::calcularSituacao($this->conceito);
    }

    public function getConceitoBadgeClassAttribute(): string
    {
        return match ($this->conceito) {
            'A' => 'success',
            'B' => 'primary',
            'C' => 'warning',
            'D' => 'danger',
            default => 'secondary',
        };
    }

    public function precisaRecuperacao(): bool
    {
        return $this->conceito === 'C';
    }

    public function recuperacaoRealizada(): bool
    {
        return $this->nota_recuperacao !== null;
    }
}
