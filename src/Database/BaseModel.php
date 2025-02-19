<?php

namespace Statamic\Eloquent\Database;

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('statamic.eloquent_driver.table_prefix', '').$this->getTable());

        if ($connection = config('statamic.eloquent_driver.connection', false)) {
            $this->setConnection($connection);
        }
    }
}
