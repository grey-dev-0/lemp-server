<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController{
    use AuthorizesRequests, ValidatesRequests;

    public function postProjects(){
        return \DataTables::of(Project::query())
            ->addColumn('actions', fn($project) => view('actions.projects', compact('project'))->render())->make();
    }

    public function postDomains(){
        // TODO
    }
}
