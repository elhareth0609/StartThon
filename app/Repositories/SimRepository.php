<?php

namespace App\Repositories;

use App\Interfaces\SimRepositoryInterface;
use App\Models\Sim;

class SimRepository implements SimRepositoryInterface {
    private $sim;

    public function __construct(Sim $sim) {
        $this->sim = $sim;
    }

    public function find($id) {
        return $this->sim->findOrFail($id);
    }

    public function create(array $data) {
        return $this->sim->create($data);
    }

    public function update($id, array $data) {
        $sim = $this->find($id);
        $sim->update($data);
        return $sim;
    }

    public function delete($id) {
        return $this->find($id)->delete();
    }

}
