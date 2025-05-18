<?php

namespace App\Exports;

use App\Models\File;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FileExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return File::all()->map(function ($data) {
            return [
                $data->original_name,
                asset($data->generated_name),
                $data->Post->count(),
                $data->created_at->format("d/mY"),
            ];
        });
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Filename", "Directory","used", "Date"];
    }
}
