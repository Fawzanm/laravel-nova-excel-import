<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Salfade\NovaExcelExport\NovaExcelExport;

/*
|--------------------------------------------------------------------------
| Card API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your card. These routes
| are loaded by the ServiceProvider of your card. You're free to add
| as many additional routes to this file as your card may require.
|
*/

//

Route::post('/import', function (Request $request) {



    $model=$request->input('model');
    $fields=$request->input('required_fields');
    $file=$request->file('file');
    $validator = Validator::make($request->all(), [
        'file' =>'mimes:xlsx',
        'model'=> 'required'
    ]);
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $data=(new FastExcel)->import($file);
    $sample =$data[0];

    if($fields!=null) {
        $fields = explode(',', $fields);

        $rules = [];
        foreach ($fields as $field) {
            $rules[$field] = 'required';
        }
        $validator = Validator::make($sample, $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

    }

    $collection = (new FastExcel)->import($request->file('file'), function ($line)  use ($model){
        return $model::create($line);
    });
    $numOfEntries=$collection->count();
    return response()->json(['message' => $numOfEntries.' Entries Imported Successfully '], 200);

});
