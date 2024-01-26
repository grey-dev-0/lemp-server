<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model{
    use HasFactory;

    // Project Types
    const DYNAMIC_PHP = 1;
    const DYNAMIC_LARAVEL = 2;
    const STATIC_HTML = 3;
    const OTHER = 10;

    /**
     * @var string[] $types Project Types.
     */
    public static array $types = [
        self::DYNAMIC_PHP => 'php',
        self::DYNAMIC_LARAVEL => 'laravel',
        self::STATIC_HTML => 'html',
        self::OTHER => 'other'
    ];

    /**
     * @inheritdoc
     */
    protected $guarded = ['id'];

    public function domains(){
        return $this->hasMany(Domain::class);
    }
}
