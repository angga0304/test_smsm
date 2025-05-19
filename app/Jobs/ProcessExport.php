<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Exports\TagExports;
use App\Exports\PostExport;
use App\Exports\FileExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\File;
use App\Models\Activity;

class ProcessExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $export = null;
        switch ($this->data['type']) {
            case 'tag':
                $export = new TagExports;
                break;

            case 'file':
                $export = new FileExport;
                break;

            case 'post':
                $export = new PostExport;
                break;
            
            default:
                # code...
                break;
        }
        if(!empty($export)) {
            $filename = $this->data['type'] .'_'. time() .'.xlsx';
            Excel::queue($export, "/public/$filename");
            $fid = File::create([
                'original_name' => $filename,
                'generated_name' => "/storage/$filename",
            ]);
        }
    }
}
