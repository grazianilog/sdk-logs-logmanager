<?php

namespace LogManager\SdkLogs\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MongoDbModel extends Eloquent
{
    protected $connection;
    protected $collection;

    function __construct($connection, $collection)
    {
        $this->connection = $connection;
        $this->collection = $collection;
    }

    /**
     * Set the table associated with the model.
     *
     * @param  string  $table
     * @return $this
     */
    public function setCollection($name){
        $this->collection = $name;
    }
}