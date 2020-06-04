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
        Storage::prepend('public/generator/file.vue', view('vue')->render());
    }

    public function getTableColumns()
    {
        // $nameOfTable = Schema::getColumnListing('dokter');
        // foreach ($nameOfTable as $key => $value) {
        //     echo Schema::getColumnType('dokter',$value);
        // }

        // return $nameOfTable;
        $table = 'dokter';
        $columns = DB::select( DB::raw('SHOW COLUMNS FROM `'.$table.'`'));
            // dd($columns);
        foreach($columns as $column) {
            $name = $column->Field;
            $type = $column->Type;
        }
        
    }
}
