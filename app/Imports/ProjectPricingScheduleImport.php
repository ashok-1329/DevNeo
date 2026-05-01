<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProjectPricingScheduleImport implements ToCollection
{
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Example save using MODEL
            \App\Models\ProjectPricingSchedule::create([
                'project_id' => $this->projectId,
                'code_id' => (int) $row[0] ?? null,
                'item' => $row[1] ?? null,
                'description' => $row[2] ?? null,
                'qty' => (int) $row[3] ?? null,
                'unit' => $row[4] ?? null,
                'rate' => (int) $row[5] ?? null,
                'amount' => (int) $row[6] ?? null,
                'code' => $row[7] ?? null,
            ]);
        }
    }
}
