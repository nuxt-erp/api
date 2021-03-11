<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AvailabilitiesExport implements FromView, ShouldAutoSize
{

    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function view(): View
    {
        return view('availabilities/availabilities', [
        'results' => $this->result, 
        'headers' => [
                ['key'  => 'product_name',  'value'       => 'Product'   ],
                ['key'  => 'location_name', 'value'       => 'Location'  ],
                ['key'  => 'available',     'value'       => 'Available' ],
                ['key'  => 'on_hand',       'value'       => 'On Hand'   ],
                ['key'  => 'on_order',      'value'       => 'On Order'  ],
                ['key'  => 'allocated',     'value'       => 'Allocated' ],
                ['key'  => 'bin_name',      'value'       => 'Bin'       ]
            ] 
        ]);
    }
}
