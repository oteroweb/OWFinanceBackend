<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceGenerator extends Command {
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

    public function __construct() {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */

    public function handle() {
        $name = $this->argument( 'name' );
        if ( Schema::hasTable( $this->getTableName( $name ) ) ) {
            $this->entity( $name );
            $this->controller( $name );
            $this->repository( $name );
            $this->route( $name );
        } else {

            echo 'the table '.$this->getTableName( $name ) .' does not exist,its neccesary to create services files, please create it first '.'\n';

        }
    }

    protected function getStub( $type ) {

        return file_get_contents( resource_path( "stubs/$type.stub" ) );
    }
    protected function entity( $name ) {
        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{tableName}}',
                '{{fieldList}}'
            ],
            [
                $name,
                $this->getTableName( $name ),
                $this->getFieldList( $this->getTableName( $name ) ),
            ],
            $this->getStub( 'Model' )
        );

        file_put_contents( app_path( "/Http/Models/Entities/{$name}.php" ), $modelTemplate );
    }
    protected function controller( $name ) {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{TableHumanName}}',
                '{{validatorList}}',
                '{{dataList}}',
                '{{dataRequest}}',
            ],
            [
                $name,
                $this->getNameSingularLowerCase( $name ),
                $this->getTableHumanName( $name ),
                $this->getValidatorList( $name ),
                $this->getDataList( $name ),
                $this->getDataRequest( $name ),
            ],
            $this->getStub( 'Controller' )
        );

        file_put_contents( app_path( "/Http/Controllers/{$name}Controller.php" ), $controllerTemplate );
    }
    protected function repository( $name ) {
        $repositoryTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}'
            ],
            [
                $name,
                $this->getNameSingularLowerCase( $name ),
            ],
            $this->getStub( 'Repo' )
        );
        if ( !file_exists( $path = app_path( '/Http/Models/Repositories' ) ) )mkdir( $path, 0777, true );
        file_put_contents( app_path( "/Http/Models/Repositories/{$name}Repo.php" ), $repositoryTemplate );
    }
    protected function route( $name ) {
        $routeTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameRouteName}}',
            ],
            [
                $name,
                $this->getNameRouteName( $name ),
            ],
            $this->getStub( 'Route' )
        );
        if ( !file_exists( $path = base_path( '/routes/api' ) ) )mkdir( $path, 0777, true );
        file_put_contents( base_path( '/routes/api/'.strtolower( $name ).'.php' ), $routeTemplate );
    }

    protected function getTableHumanName( $name ) {
        return trim( ucwords( preg_replace( '/(?<!\ )[A-Z]/', ' $0', $name ) ) );
    }
    protected function getNameSingularLowerCase( $name ) {
        return strtolower( $name );
    }
    protected function getNameRouteName( $name ) {
        return Str::of( $name )->snake()->plural();
    }
    protected function getTableName( $name ) {
        return Str::snake( Str::plural( $name ) );
    }
    protected function getFieldList( $name ) {
        return $fields = "'" . implode ( "', '", $this->getArray( $this->getTableName( $name ) ) ) . "'";
    }
    protected function getArray( $name ) {
        return DB::getSchemaBuilder()->getColumnListing( $this->getTableName( $name ) );
    }
    protected function getValidatorList( $name ) {

        $validatorList = null;
        $table = DB::select( DB::raw( 'SHOW COLUMNS FROM '.$this->getTableName( $name ) ) );
        foreach ( $table as $key => $value ) {
            if ( $value->Field === 'id' || $value->Field === 'created_at' || $value->Field === 'updated_at' || $value->Field === 'deleted_at' || $value->Field === 'active' ) {
                continue;
            } else {
                $nullable = ( $value->Null == 'YES' ) ? null : 'required|';
                $max = ( $value->Type === 'double(8,2)' ) ? null : 'max:'.$this->get_string_between( $value->Type, '(', ')' ).'|';
                $double = ( $value->Type == 'double(8,2)' ) ? "regex:/^[0-9]+(\.[0-9][0-9]?)?$/" : null;
                $validatorList .= "'$value->Field' =>'{$nullable}{$max}{$double}',\n            ";
            }
        }
        return $validatorList;
    }
    protected function getDataList( $name ) {
        $fields = DB::getSchemaBuilder()->getColumnListing( $this->getTableName( $name ) );

        $dataList = null;
        foreach ( $fields as $key => $value ) {
            if ( $value === 'id' || $value === 'created_at' || $value === 'updated_at' || $value === 'deleted_at' || $value === 'active' ) {
                continue;
            }

            $dataList .= "'".$value."'=> $"."request->input('".$value."'),\n            ";
        }
        return $dataList;
    }

    protected function getDataRequest( $name ) {
        $fields = DB::getSchemaBuilder()->getColumnListing( 'transactions' );

        $dataRequest = null;
        foreach ( $fields as $value ) {
            if ( $value === 'id' || $value === 'created_at' || $value === 'updated_at' || $value === 'deleted_at' || $value === 'active' ) {
                continue;
            }

            $dataRequest .= "if (($"."request->input('".$value."'))) { $"."data += ['".$value."' => $"."request->input('".$value."')]; };\n                ";
        }
        return $dataRequest;
    }
    protected function get_string_between( $string, $start, $end ) {
        $string = ' ' . $string;
        $ini = strpos( $string, $start );
        if ( $ini == 0 ) return '';
        $ini += strlen( $start );
        $len = strpos( $string, $end, $ini ) - $ini;
        return substr( $string, $ini, $len );
    }
}
