<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
 
class MateriaAbierta extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'idMateria',
        'idPeriodo',
        'idCarrera',
    ];
 
   
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'idCarrera');
    }
 
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'idMateria');
    }
 
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'idPeriodo');
    }
}
 