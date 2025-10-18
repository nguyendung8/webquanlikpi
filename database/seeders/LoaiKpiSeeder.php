<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoaiKpi;

class LoaiKpiSeeder extends Seeder
{
    public function run()
    {
        $loaiKpis = [
            ['Ten_loai_kpi' => 'Marketing'],
            ['Ten_loai_kpi' => 'Sales'],
            ['Ten_loai_kpi' => 'IT'],
            ['Ten_loai_kpi' => 'HR'],
            ['Ten_loai_kpi' => 'Finance'],
            ['Ten_loai_kpi' => 'Operations']
        ];

        foreach ($loaiKpis as $loaiKpi) {
            LoaiKpi::create($loaiKpi);
        }
    }
}
```

```

