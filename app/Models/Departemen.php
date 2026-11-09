<?php

namespace App\Models;

use Database\Factories\DepartemenFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class Departemen extends Model
{
    /** @use HasFactory<DepartemenFactory> */
    use HasFactory;

    protected $table = 'departemens';
}
