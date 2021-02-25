<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectStatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'technical_opinion' => 'required',
            'status' => 'required|in:1,2,3,4,5,6',
        ]);

        $params = $request->all();
        $params['user_id'] = auth()->id() ?? 2;

        $project->projectStatus()->create($params);

        return true;
    }
}
