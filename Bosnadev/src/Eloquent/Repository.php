<?php
namespace Bosnadev\Repositories\Eloquent;

use Bosnadev\Repositories\Contracts\CriteriaInterface;
use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Exceptions\RepositoryException;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
abstract class Repository implements RepositoryInterface,CriteriaInterface{
	private $app;

	protected $model;

	protected $criteria;

	protected $skipCriteria = false;

	public function __construct(App $app,Collection $collection){
		$this->app = $app;
		$this->criteria = $collection;
		$this->resetScope();
		$this->makeModel();
	}

	abstract function model();

	public function all($columns = array('*')){
		return $this->model->get($columns);
	}

	public function paginate($perPage = 15,$columns = array('*')){
		return $this->model->paginate($perPage,$columns);
	}

	public function create(array $data){
		return $this->model->create($data);
	}

	public function update(array $data,$id,$attribute="id"){
		return $this->model->where($attribute,'=',$id)->update($data);
	}

	public function delete($id){
		return $this->model->destroy($id);
	}

	public function find($id,$columns = array('*')){
		return $this->model->find($id,$columns);
	}

	public function findBy($attribute,$value,$columns=array('*')){
		return $this->model->where($attribute,'=',$value)->first($columns);
	}

	public function makeModel(){
		$model = $this->app->make($this->model);

		if(!$model instanceof Model)
			throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

			return $this->model = $model->newQuery();
	}

	public function resetScope(){
		$this->skipCriteria(false);
		return $this;
	}

	public function skipCriteria($status = true){
		$this->skipCriteria = $status;
		return $this;
	}

	public function getCriteria(){
		return $this->criteria;
	}
	public function getByCriteria(Criteria $criteria){
		$this->model = $criteria->apply($this->model,);
	}

    public function pushCriteria(Criteria $criteria){
        $this->criteria->push($criteria);
        return $this;
    }

    public function applyCriteria(Criteria $criteria){
        if($this->skipCriteria ===true)
            return $this;

        foreach($this->getCriteria() as $criteria){
            if($criteria instanceof Criteria) 
                $this->model = $criteria->apply($this->model,$this);
        }
        return $this;

    }


}
