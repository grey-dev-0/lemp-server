<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $casts = ['provisioned_at' => 'datetime:Y-m-d h:i:s A'];

    /**
     * @inheritdoc
     */
    protected $guarded = ['id'];

    public function project(){
        return $this->belongsTo(Project::class);
    }
}
