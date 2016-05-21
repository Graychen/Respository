<?php
namespace App\Repositories\Criteria\Films;

use Bosnadev\Repositories\Contracts\CriteriaInterface;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class LengthOverTwoHours implements CriteriaInterface{
    public function apply($model,Repository $repository){
        $query = $model->where('length','>',120);
        return $query;
    } 
}

