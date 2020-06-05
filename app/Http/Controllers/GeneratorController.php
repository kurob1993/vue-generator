<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class GeneratorController extends Controller
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        return view('home',compact('tables'));
    }

    public function generate(Request $request)
    {
        $table = $request->table;
        $title = $request->title;
        $nameOfTable = $columns = DB::select( DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$table."' "));
        $columns = collect($nameOfTable);
        $columns->transform(function ($item, $key) use ($table) {
            return [
                'column' => $item->COLUMN_NAME,
                'type' => $this->convert($item->DATA_TYPE),
                'required' => $this->isNullable($item->IS_NULLABLE)
           ];
        });
        Storage::put(
            'public/generator/'.$table.'/create.vue', 
            view('vue',compact('columns','table','title'))->render()
        );
    }

    public function getTableColumns()
    {
        // $table = 'dokter';
        // $nameOfTable = Schema::getColumnListing($table);
        // $collection = collect($nameOfTable);
        // $collection->transform(function ($item, $key) use ($table) {
        //     return [
        //         'column' => $item,
        //         'type' => $this->convert( Schema::getColumnType($table, $item))
        //    ];
        // });
        // dd($collection);

        // foreach ($nameOfTable as $key => $value) {
        //     echo Schema::getColumnType('dokter',$value);
        // }

        // // // return $nameOfTable;
        $table = 'dokter';
        $columns = DB::select( DB::raw("select * from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='dokter' "));
        dd($columns);
        // foreach($columns as $column) {
        //     $name = $column->Field;
        //     $type = $column->Type;
        // }
        
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
            case 'int':
                return 'number';
                break;

            case 'varchar':
                return 'text';
                break;

            case 'text':
                return 'text';
                break;
            
            default:
                return 'text';
                break;
        }
    }
}
