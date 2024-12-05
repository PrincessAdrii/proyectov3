<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reticula extends Model
{
    /** @use HasFactory<\Database\Factories\ReticulaFactory> */
    use HasFactory;
    
    protected $fillable = ['idReticula','descripcion',
    'fechaEnVigor','idCarrera'];
    protected $casts = ['idReticula'=>'string'];
    public $incrementing=false;
    protected $primaryKey='idReticula';

    
  
    // En Reticula.php
public function materias()
{
    return $this->hasMany(Materia::class, 'idReticula');
}

}