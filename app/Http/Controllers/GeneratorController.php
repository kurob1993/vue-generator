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

    public function files()
    {
        $files = [];
        $dir = Storage::allDirectories('public/generator');
        foreach ($dir as $key => $items) {
            foreach (Storage::files($items) as $key => $item) {
                array_push($files, $item);
            }
        }
        $data = collect($files);
        $data->transform(function ($item, $key) {
            return str_replace("public", "storage", $item);
        });
        return view('files', compact('data'));
    }

    public function generate(Request $request)
    {
        
        $columns = collect($request->column);
        $columns->transform(function ($item, $key) use($request) {
            return [
                'column' => $item,
                'type' => $request->type[$key],
                'required' => $request->required[$key],
                'title' => $request->title[$key]
            ];
        });

        $table = $request->table;
        $title = $request->titleHeader;
        Storage::put(
            'public/generator/' . $table . '/' . $table . 'Create.vue',
            view('vue', compact('columns', 'table', 'title'))->render()
        );

        return redirect()->route('generator.files');
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
                'column' => $item->COLUMN_NAME,
                'type' => $this->convert($item->DATA_TYPE),
                'required' => $this->isNullable($item->IS_NULLABLE)
            ];
        });
        return view('forms', compact('columns','table','title','endpoint'));
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
            case 'enum':
            case 'set':
                return 'text';
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
