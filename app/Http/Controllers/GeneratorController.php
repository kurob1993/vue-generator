<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class GeneratorController extends Controller
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        return view('home', compact('tables'));
    }

    public function getTableColumns(Request $request)
    {
        $databaseName = Config::get('database.connections.' . Config::get('database.default'));
        $table = $request->table;
        $title = $request->title;
        $endpoint = $request->endpoint;
        $nameOfTable = $columns = DB::select(DB::raw("
            select 
                * 
            from 
                INFORMATION_SCHEMA.COLUMNS 
            where TABLE_NAME='" . $table . "' and 
            TABLE_SCHEMA ='" . $databaseName['database'] . "'
        "));

        $columns = collect($nameOfTable);
        $columns->transform(function ($item, $key) use ($table) {
            return [
                'column' => str_replace("_","", $item->COLUMN_NAME),
                'type' => $this->convert($item->DATA_TYPE),
                'required' => $this->isNullable($item->IS_NULLABLE),
                'max' => $item->CHARACTER_MAXIMUM_LENGTH,
                'pk' => $item->COLUMN_KEY !== "" ? true : false
            ];
        });
        return view('forms', compact('columns', 'table', 'title', 'endpoint'));
    }

    public function generate(Request $request)
    {
        
        $columns = collect($request->column);
        $columns->transform(function ($item, $key) use ($request) {
            return [
                'column' => $item,
                'type' => $request->type[$key],
                'required' => $request->required[$key],
                'title' => $request->title[$key],
                'max' => $request->max[$key],
                'pk' => $request->pk[$key]
            ];
        });

        $table = $request->table;
        $title = $request->titleHeader;
        $endpoint = $request->endpoint;
        
        foreach ($columns as $key => $value) {
            if ($value['type'] == 'select') {
                Storage::put(
                    'public/generator/' . $table . '/model/' . $value['column'] . '.js',
                    view('template-data-select', compact('columns', 'table', 'title','endpoint'))->render()
                );
            }
        }
        Storage::put(
            'public/generator/' . $table . '/page/' . $table . '.vue',
            view('template-vue', compact('columns', 'table', 'title','endpoint'))->render()
        );

        Storage::put(
            'public/generator/' . $table . '/model/' . $table . '.js',
            view('template-model', compact('columns', 'table', 'title','endpoint'))->render()
        );
        
        Storage::put(
            'public/generator/' . $table . '/service/' . $table . '.service.js',
            view('template-service', compact('columns', 'table', 'title','endpoint'))->render()
        );

        return redirect()->route('generator.files');
    }

    public function files(Request $request)
    {
        $dir = Storage::allDirectories('public/generator');        
        $data = collect($dir);
        $data = $data->map(function($item, $key){
            return Storage::files($item);
        })->reject(function($item, $key){
            return count($item) == 0;
        });

        $ret = [];
        foreach ($data as $key => $values) {
            foreach ($values as $key => $value) {
                $ret[explode('/',$value)[2]][] = str_replace("public", "storage", $value);
            }
        }
        $data = collect($ret);

        $group = $data;
        if (isset($request->cari)) {
            $data = collect($data->get($request->cari))
            ->mapToGroups(function($item, $key) use($request) {
                return [$request->cari => $item];
            });
        }
        return view('files', compact('data','group'));
    }

    public function isNullable($data)
    {
        if ($data == 'NO') {
            return 'required';
        }
        return '';
    }

    public function convert($data)
    {
        switch ($data) {

            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
            case 'decimal':
            case 'float':
            case 'double':
            case 'bit':
                return 'number';
                break;

            case 'char':
            case 'varchar':
            case 'binary':
            case 'varbinary':
            case 'tinyblob':
            case 'set':
                return 'text';
                break;

            case 'enum':
                return 'switch';
                break;

            case 'blob':
            case 'mediumblob':
            case 'longblob':
            case 'tinytext':
            case 'text':
            case 'mediumtext':
            case 'longtext':
                return 'textarea';
                break;

            case 'date':
            case 'datetime':
            case 'timestamp':
                return 'date';
                break;

            case 'time':
                return 'time';
                break;

            default:
                return 'text';
                break;
        }
    }
}
