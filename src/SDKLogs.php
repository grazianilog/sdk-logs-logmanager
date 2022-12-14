<?php

namespace LogManager\SdkLogs;

use Carbon\Carbon;
use Throwable;
use LogManager\SdkLogs\Models\MongoDbModel;

class SDKLogs
{   
    private $model;
    private $connection;
    private $collection;

    private $forever = false;
    private $deleteAt;

    private $steps = [];
    private $props = [];
    
    public function __construct()
    {
        $this->connection = config('sdk-logs.connection_name');
        $this->collection = config('sdk-logs.default_collection');
        $this->model = new MongoDbModel($this->connection, $this->collection);
        
        $this->deleteAt = now()->addDays(config('sdk-logs.default_days_to_delete'));
    }

    /** Getter / Setter **/

    /**
     * Get the current connection name for the model.
     *
     * @return string|null
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set the connection associated with the model.
     *
     * @param  string|null  $name
     * @return $this
     */
    public function setConnection($name)
    {
        $this->connection = $name;
        $this->model->setConnection($this->connection);

        return $this;
    }

    /**
     * Get the current collection name for the model.
     *
     * @return string|null
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set the collection associated with the model.
     *
     * @param  string|null  $name
     * @return $this
     */
    public function setCollection($name)
    {
        $this->collection = $name;
        $this->model->setCollection($this->collection);

        return $this;
    }

    /**
     * Get the current model.
     *
     * @return MongoDbModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set key value to current log instance.
     *
     * @param  string|null  $name
     * @param  any  $value
     * @return $this
     */
    public function setKey($name, $value){
        try {
            $this->model->{$name} = json_decode(json_encode($value), true);   
        } catch (Throwable $th) {
            if(app()->bound("sentry")){
                app('sentry')->captureException($th);
            }else{
                logger("File: {$th->getFile()} -> {$th->getMessage()}:{$th->getLine()}");
            }
        }

        return $this;
    }

    /**
     * Set reference id to current log instance.
     *
     * @param  any  $value
     * @return $this
     */
    public function setRefId($value){
        try {
            $this->setKey('ref_id', $value);
        } catch (Throwable $th) {
            if(app()->bound("sentry")){
                app('sentry')->captureException($th);
            }else{
                logger("File: {$th->getFile()} -> {$th->getMessage()}:{$th->getLine()}");
            }
        }

        return $this;
    }

    /**
     * Get steps of current log instance.
     *
     * @return $steps
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Get props of current log instance.
     *
     * @return $steps
     */
    public function getProps()
    {
        return $this->props;
    }

    /**
     * Set current log instance to forever
     *
     * @return $this
     */
    public function setForever()
    {
        $this->forever = true;
        return $this;
    }

    /**
     * Get current log instance to delete at
     *
     * @return $deleteAt
     */
    public function getDeleteAt()
    {
        return $this->deleteAt;
    }

    /**
     * Set current log instance to delete at
     *
     * @return $this
     */
    public function setDeleteAt(Carbon $value)
    {
        $this->deleteAt = $value;
        return $this;
    }

    /** logging functions **/    
     
    /**
     * Prepare and start basic information for logging
     *
     * @return $this
     */
    public function start($logType){
        $this->setKey('log_type', $logType);
        $this->setKey('app', env('APP_NAME', 'Laravel'));
        $this->setKey('env', env('APP_ENV', 'production'));

        return $this;
    }

    /**
     * add step to current log instance.
     *
     * @param  string  $value
     * @return $this
     */
    public function addStep($step){
        try {
            $this->steps = array_merge(
                json_decode(json_encode($this->steps), true),
                [ str_replace("{1}", (count($this->steps) + 1), "step_{1}") => $step ]
            );
        } catch (Throwable $th) {
            if(app()->bound("sentry")){
                app('sentry')->captureException($th);
            }else{
                logger("File: {$th->getFile()} -> {$th->getMessage()}:{$th->getLine()}");
            }
        }

        return $this;
    }

    /**
     * add step to current log instance.
     *
     * @param  string  $value
     * @return $this
     */
    public function addProp($prop, $value){
        try {
            $this->props = array_merge(
                json_decode(json_encode($this->props), true),
                [ $prop => json_decode(json_encode($value), true) ]
            );
        } catch (Throwable $th) {
            if(app()->bound("sentry")){
                app('sentry')->captureException($th);
            }else{
                logger("File: {$th->getFile()} -> {$th->getMessage()}:{$th->getLine()}");
            }
        }

        return $this;
    }

    /**
     * Prepare and start basic information for logging
     *
     * @return $this
     */
    public function close(){
        try {
            $this->setKey('steps', $this->steps);
            $this->setKey('props', $this->props);

            if(!$this->forever){
                $this->model->delete_at = new \MongoDB\BSON\UTCDateTime($this->deleteAt->toDateTime());
            }            

            $this->model->save();
        } catch (Throwable $th) {
            if(app()->bound("sentry")){
                app('sentry')->captureException($th);
            }else{
                logger("File: {$th->getFile()} -> {$th->getMessage()}:{$th->getLine()}");
            }
        }

        return $this;
    }
}