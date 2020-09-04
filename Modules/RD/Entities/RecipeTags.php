<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use App\Models\Parameter;
use Illuminate\Validation\Rule;

class RecipeTags extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_tags';

    protected $fillable = ['recipe_id', 'tag_id'];

}
