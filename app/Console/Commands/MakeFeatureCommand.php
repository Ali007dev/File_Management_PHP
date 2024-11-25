<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
class MakeFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:feature {name} {--si= : specify that this model stores image, and get image field name.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new feature with all related classes and files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $storesImage = $this->option('si');
        // Paths to your stubs
        $stubsPath = resource_path('stubs/feature');
        $modelStub = empty($storesImage)?"model.stub":"modelThatStoresImage.stub";
        // Define mappings from stub to destination path
        $filesToCreate = [
            "$stubsPath/controller.stub"    => app_path("Http/Controllers/{$name}Controller.php"),
            "$stubsPath/model_rules.stub"    => app_path("Models/Rules/{$name}Rules.php"),
            "$stubsPath/createRequest.stub" => app_path("Http/Requests/Create{$name}Request.php"),
            "$stubsPath/updateRequest.stub" => app_path("Http/Requests/Update{$name}Request.php"),
            "$stubsPath/$modelStub"         => app_path("Models/{$name}.php"),
            "$stubsPath/resource.stub"      => app_path("Http/Resources/{$name}Resource.php"),
            "$stubsPath/service.stub"       => app_path("Services/{$name}Service.php"),
        ];

        foreach ($filesToCreate as $stubPath => $destPath) {
            $this->createFileFromStub($stubPath, $destPath, $name);
        }

        $this->info("Feature {$name} created successfully.");
    }

    protected function createFileFromStub($stubPath, $destPath, $name)
    {
        $objectName = camelCase($name);
        if (file_exists($destPath)) {
            $this->error("File already exists: {$destPath}");
            return;
        }
        $imageFieldName = $this->option('si');
        $content = file_get_contents($stubPath);
        $content = str_replace('{{objectName}}', $objectName, $content);
        $content = str_replace('{{className}}', $name, $content);
        if(!empty($imageFieldName)){
            $content = str_replace('{{imageFieldName}}', $imageFieldName, $content);
        }
        file_put_contents($destPath, $content);
    }

}
