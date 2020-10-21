<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use Illuminate\Database\Eloquent\Relations\Pivot;
class CategorySponsors extends Pivot
{

    protected $connection = 'tenant';

    protected $table = 'exp_ap_category_sponsors';

    protected $fillable = [
        'expenses_category_id', 'sponsor_id', 'is_primary'
    ];
}
