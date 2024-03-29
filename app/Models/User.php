<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const TYPE_GERENTE = 'A';
    const TYPE_TECNICO_MEDIO = 'TM';
    const TYPE_TECNICO_SUPERIOR = 'TS';

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function translateType()
    {
        $types = [
            self::TYPE_GERENTE => 'Chefe de departamento',
            self::TYPE_TECNICO_MEDIO => 'Técnico de nível médio',
            self::TYPE_TECNICO_SUPERIOR => 'Técnico de nível superior',
        ];

        return $types[$this->type];
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function projectStatus()
    {
        return $this->hasMany(ProjectStatus::class);
    }
}
