<?php

namespace App\Exports;

use App\Models\Tag;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TagExports implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tag::all()->map(function ($data) {
            return [
                $data->name,
                $data->posts()->count(),
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
        return ["Name", "Used"];
    }
}