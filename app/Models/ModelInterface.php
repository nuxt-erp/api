<?php

namespace App\Models;
interface ModelInterface
{
    /**
     * Return array of rules
     *
     * @param int $id
     * @return array
     */
    public function getRules($request, $item = null);

}
