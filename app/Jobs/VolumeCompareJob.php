<?php

namespace App\Jobs;

use App\Models\VolumeCompare;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VolumeCompareJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pairFromSocket;
    public $savedPairs;
    /**
     * Create a new job instance.
     * @param $pairFromSocket array - пара пришедшая с бинанса
     * @param array $savedPairs array - сохраненные пары для отслеживания
     * @return void
     */
    public function __construct(array $pairFromSocket, array $savedPairs)
    {
        $this->pairFromSocket = $pairFromSocket;
        $this->savedPairs = $savedPairs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        VolumeCompare::volumeCompareAndGetWriteResults($this->pairFromSocket,$this->savedPairs);
    }
}
