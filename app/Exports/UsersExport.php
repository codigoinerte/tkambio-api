<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $inicio;
    private $fin;

    public function __construct($inicio, $fin) 
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    public function collection()
    {
        return  User::where('birth_date', '>=', $this->inicio)
                ->where('birth_date', '<=', $this->fin)
                ->get();
    }
}
