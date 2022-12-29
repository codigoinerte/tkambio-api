<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use SplTempFileObject;
use App\Models\reports;
use Illuminate\Support\Str;
use App\Exports\UsersExport;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class generateReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $inicio;
    protected $fin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $inicio, $fin)
    {
        $this->title = $title;
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // proceso para generar el reporte

        $startDate = $this->inicio;
        $endDate = $this->fin;

        // Convertir las fechas a objetos Carbon
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        ###########################
       
        $current_timestamp = Carbon::now()->timestamp;
        $nombreArchivo = Str::slug($this->title, "-").$current_timestamp.".xlsx";
        $public_path = public_path($nombreArchivo);
        $url = asset('storage/'.$nombreArchivo);

        $respuesta = Excel::store(new UsersExport($startDate, $endDate), $nombreArchivo, 'xlsx');

        $report = reports::create([
            'title' => $respuesta,
            'report_link' => $url, //$url, // url del archivo
        ]);

        return $report;
        ###########################

        

    }
}
