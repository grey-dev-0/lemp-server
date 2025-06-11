<?php
namespace App\Http\Requests;

use App\Models\Domain;
use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool{
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array{
        return [
            'project' => 'required|integer|exists:projects,id',
            'domain' => 'required|string|ends_with:.docker',
            'config' => 'required|string'
        ];
    }

    public $attributes = [
        'domain' => 'Primary Domain'
    ];

    public function messages(){
        return [
            'domain.ends_with' => 'The field :attribute must end with .docker'
        ];
    }

    /**
     * Handles create / update domain request.
     *
     * @return void
     */
    public function handle(){
        $data = ['project_id' => $this->project, 'name' => $this->domain];
        $data['https'] = preg_match('#listen +443#iu', $this->config);
        if($domain = $this->route('domain'))
            $domain->update($data);
        else
            $domain = Domain::create($data);
        \App\Jobs\ProvisionDomain::dispatch($domain);
    }
}
