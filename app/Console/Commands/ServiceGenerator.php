<?php

namespace App\Console\Commands;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ServiceGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create APIREST services';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->controller($name);
        $this->entity($name);
        $this->repository($name);
        $this->route($name);

    // File::append(base_path('routes/api.php'), 'Route::resource(\'' . str_plural(strtolower($name)) . "', '{$name}Controller');");

    }
    protected function getStub($type)
        {   
            return file_get_contents(resource_path("stubs/$type.stub"));
        }
    protected function entity($name)
        {
            $modelTemplate = str_replace(
                [
                    '{{modelName}}',
                    '{{tableName}}',
                ],
                [
                    $name,
                    Str::plural(Str::of($name)->snake())
                ],
                $this->getStub('Model')
            );

            file_put_contents(app_path("/Http/Models/Entities/{$name}.php"), $modelTemplate);
        }
    protected function controller($name)
        {
            $controllerTemplate = str_replace(
                [
                    '{{modelName}}',
                    '{{modelNameSingularLowerCase}}',
                    '{{tableName}}'
                ],
                [
                    $name,
                    strtolower($name),
                    ucwords(preg_replace('/(?<!\ )[A-Z]/', '$0', $name)),
                ],
                $this->getStub('Controller')
            );

            file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
        }
    protected function repository($name)
        {
            $repositoryTemplate = str_replace(
                [
                    '{{modelName}}',
                    '{{modelNameSingularLowerCase}}'
                ],
                [
                    $name,
                    strtolower($name)
                ],
                $this->getStub('Repo')
            );
            if(!file_exists($path = app_path("/Http/Models/Repositories")))mkdir($path, 0777, true);
            file_put_contents(app_path("/Http/Models/Repositories/{$name}Repo.php"), $repositoryTemplate);
        }
        protected function route($name)
        {
            $routeTemplate = str_replace(
                [
                    '{{modelName}}',
                    '{{modelNameSingularLowerCase}}',
                ],
                [
                    $name,
                    Str::of($name)->snake(),

                ],
                $this->getStub('Route')
            );
            if(!file_exists($path = base_path("/routes/api")))mkdir($path, 0777, true);
            file_put_contents(base_path("/routes/api/".strtolower($name).".php"), $routeTemplate);
        }
}
