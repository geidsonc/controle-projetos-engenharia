<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    protected $table = 'project_status';

    protected $fillable = [
        'project_id',
        'user_id',
        'technical_opinion',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function translateStatus()
    {
        $status = [
            '1' => 'Em análise',
            '2' => 'Em diligência',
            '3' => 'Em execução',
            '4' => 'Concluído com pendência',
            '5' => 'Concluído sem pendência',
            '6' => 'Cancelado',
        ];

        return $status[$this->status];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
