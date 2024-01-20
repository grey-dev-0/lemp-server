<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $guarded = ['id'];

    public function domains(){
        return $this->hasMany(Domain::class);
    }
}
