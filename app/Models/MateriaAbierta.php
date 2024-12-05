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
 
   
    // En MateriaAbierta.php
public function materia()
{
    return $this->belongsTo(Materia::class, 'idMateria');
}

public function carrera()
{
    return $this->belongsTo(Carrera::class, 'idCarrera');
}

public function periodo()
{
    return $this->belongsTo(Periodo::class, 'idPeriodo');
}

}
 