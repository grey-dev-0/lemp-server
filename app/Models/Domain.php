<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $guarded = ['id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
