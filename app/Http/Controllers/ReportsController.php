<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use SplTempFileObject;
use App\Models\reports;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use App\Jobs\generateReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        // TODO: mostrar todos los reportes
        
        $page = $request->input('page', 1);
        
        $perPage = $request->input('per_page', 20);


        $reports = reports::orderBy('created_at', 'asc')->paginate($perPage, ['*'], 'page', $page);

        // Devolver los usuarios en formato JSON
        return response()->json($reports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: crear reporte

        $titulo = $request->input('titulo');
        $inicio = $request->input('inicio');
        $fin = $request->input('fin');


        $rules = [
            'titulo'    => 'required',
            'inicio'    => 'required|date',
            'fin'       => 'required|date',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            
            return response()->json([
                ...$validator->errors()->all()
            ])->setStatusCode(400); 

        }

        $report = generateReport::dispatch($titulo, $inicio, $fin);

        return response()->json([
            "msg" => "El documento se añadio a la cola"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // TODO: traer reporte guardado en excel
        /*
        if($id == ''){
            return response()->json([
                "msg"=>"Falta el id"
            ])->setStatusCode(400);
        }

        $reports = reports::where('id', $id)->latest();
                
        $path = storage_path('app/public/'.$reports->report_link);

        if (File::exists($path)) {
            
            return Storage::download($path);

        } else {
            return response()->json([]);
        }
        */
        
        if($id == ''){
            return response()->json([
                "msg"=>"Falta el id"
            ])->setStatusCode(400);
        }

        $report_link = reports::where('id', $id)->first()->report_link;
                
        $path = storage_path('app/public/'.$report_link);

        if (File::exists($path)) {
            
            // return Storage::download($path);
            $path = Storage::path('app/public/'.$report_link);
            return response()->download($path);

        } else {
            return response()->json([]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function edit(reports $reports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, reports $reports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function destroy(reports $reports)
    {
        //
    }
}
