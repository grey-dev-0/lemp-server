<?php

namespace App\Http\Controllers;

use App\Jobs\ProvisionProject;
use App\Models\Domain;
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

    public function postProject(){
        $project = Project::create(request()->only(['path', 'type', 'database']));
        $project->domains()->createMany(request('domains'));
        dispatch(new ProvisionProject($project));
        return redirect()->route('projects')->with(['success' => true]);
    }

    public function postDomains(){
        return \DataTables::of(Domain::query())
            ->addColumn('actions', fn($domain) => view('actions.domains', compact('domain'))->render())->make();
    }
}
