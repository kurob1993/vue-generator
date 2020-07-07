<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

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
        $folder = $request->folder;
        $table = $request->table;
        $title = $request->title;
        $endpoint = $request->endpoint;
        $nameOfTable = $columns = DB::select(DB::raw("
            select
                a.TABLE_NAME,
                a.COLUMN_NAME,
                a.COLUMN_COMMENT,
                a.DATA_TYPE,
                a.IS_NULLABLE,
                a.CHARACTER_MAXIMUM_LENGTH,
                a.CHARACTER_MAXIMUM_LENGTH,
                a.COLUMN_KEY,
                b.REFERENCED_TABLE_NAME
            from
                INFORMATION_SCHEMA.columns as a
                left join INFORMATION_SCHEMA.KEY_COLUMN_USAGE as b
                on b.COLUMN_NAME = a.COLUMN_NAME and 
                b.CONSTRAINT_SCHEMA = '" . $databaseName['database'] . "' and 
                b.TABLE_NAME = '" . $table . "' and 
                b.CONSTRAINT_NAME <> 'PRIMARY' and 
                b.COLUMN_NAME <> 'companyid'
            where
                a.TABLE_NAME = '" . $table . "'
                and a.TABLE_SCHEMA = '" . $databaseName['database'] . "'
            order by a.ORDINAL_POSITION asc
        "));
        $columns = collect($nameOfTable);
        $columns->transform(function ($item, $key) use ($table) {
            return [
                'column' => Str::camel($item->COLUMN_NAME),
                'title' => $item->COLUMN_COMMENT ? $item->COLUMN_COMMENT : Str::camel($item->COLUMN_NAME),
                'type' => $this->convert($item),
                'required' => $this->isNullable($item->IS_NULLABLE),
                'max' => $item->CHARACTER_MAXIMUM_LENGTH,
                'pk' => $item->COLUMN_KEY == "PRI" ? true : false,
                'relasi' => $item->REFERENCED_TABLE_NAME
            ];
        });
        return view('forms', compact('columns', 'table', 'title', 'endpoint', 'folder'));
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
                'pk' => $request->pk[$key],
                'relasi' => $request->relasi[$key]
            ];
        });

        $folder = $request->folder;
        $table = $request->table;
        $title = $request->titleHeader;
        $endpoint = $request->endpoint;
        $module = Str::limit($table,2,'');
        
        $relasional = $columns->reject(function ($value, $key) {
            return $value['relasi'] == null;
        })->map(function ($value, $key) use($table) {
            return $this->isJson($value['relasi']) ? 
                $value['column'].'|'.Str::title($table).Str::title($value['column']) : 
                $value['column'].'|'.Str::lower($value['relasi']);
        });
        // dd($relasional);
        // generate model static
        foreach ($columns as $key => $value) {
            if ( $this->isJson($value['relasi']) ) {
                $column = $value['column'];
                $relasi = json_decode($value['relasi'], true);
                Storage::put(
                    'public/generator/' . $module . '/model/' . Str::title($table).Str::title($value['column']) . '.js',
                    view('template-data-select', compact('column', 'table', 'title','endpoint', 'relasi'))->render()
                );
                $file = base_path('storage\app\public\generator\\' . $module . '\model\\'.Str::title($table).Str::title($value['column']).'.js');
                $dir = $folder.'\src\models\\' . $module;
                $local = $dir.'\\'.Str::title($table).Str::title($value['column']) . '.js';
                if (!file_exists($dir)) {
                    mkdir(dirname($local), 0777, true);
                }
                copy($file, $local);
            }
        }
        // generate model static
        
        Storage::put(
            'public/generator/' . $module . '/page/' . $table . '.vue',
            view('template-vue', compact('columns', 'table', 'title','endpoint', 'relasional'))->render()
        );
        $file = base_path('storage\app\public\generator\\' . $module . '\page\\'.$table.'.vue');
        $dir = $folder.'\src\views\pages\\' . $module;
        $local = $dir . '\\'.$table . '.vue';
        if (!file_exists($dir)) {
            mkdir(dirname($local), 0777, true);
        }
        copy($file, $local);

        Storage::put(
            'public/generator/' . $module . '/model/' . $table . '.js',
            view('template-model', compact('columns', 'table', 'title','endpoint'))->render()
        );
        $file = base_path('storage\app\public\generator\\' . $module . '\model\\'.$table.'.js');
        $dir = $folder.'\src\models\\' . $module;
        $local = $dir . '\\'.$table . '.js';
        if (!file_exists($dir)) {
            mkdir(dirname($local), 0777, true);
        }
        copy($file, $local);

        Storage::put(
            'public/generator/' . $module . '/service/' . $table . '.service.js',
            view('template-service', compact('columns', 'table', 'title','endpoint'))->render()
        );
        $file = base_path('storage\app\public\generator\\' . $module . '\service\\'.$table.'.service.js');
        $dir = $folder.'\src\services\\' . $module;
        $local = $dir .'\\'.$table . '.service.js';
        if (!file_exists($dir)) {
            mkdir(dirname($local), 0777, true);
        }
        copy($file, $local);

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
        $data_type = $data->REFERENCED_TABLE_NAME ? 'select' : $data->DATA_TYPE;
        switch ($data_type) {

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

            case 'select':
                return 'select';
                break;

            default:
                return 'text';
                break;
        }
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
