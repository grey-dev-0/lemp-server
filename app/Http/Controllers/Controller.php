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
            ->addColumn('actions', fn($project) => view('actions.projects', compact('project'))->render())
            ->rawColumns(['actions'])->make();
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

    public function getCreateDomain(?Project $project = null){
        return view('create-domain', ['title' => 'Create Domain', 'projects' => Project::pluck('path', 'id')]
            + compact('project'));
    }

    public function postDomain(\App\Http\Requests\DomainRequest $request, ?Domain $domain = null){
        $request->handle();
        return redirect()->route('domains')->with(['success' => true]);
    }

    public function postStub(?Project $project = null){
        return response(view('stubs.nginx', [
            'serverName' => request('domain', '[WRITE YOUR DOMAIN HERE e.g. app.docker]'),
            'type' => $project->type?? Project::DYNAMIC_PHP,
            'docRoot' => $project->path?? '[WRITE YOUR APP DIRECTORY HERE e.g. app]',
            'tls' => true
        ])->render(), 200, ['Content-Type' => 'text/plain']);
    }
}
