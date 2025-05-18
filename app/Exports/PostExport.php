<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Post::all()->map(function ($data) {
            return [
                $data->title,
                $data->body,
                $data->user->name,
                $data->status,
                $data->created_at->format('d/m/Y'),
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
        return ["Title", "Body","Author", "Status", "Date"];
    }
}
