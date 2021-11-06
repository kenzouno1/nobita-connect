<?php

class NobitaLead
{
   private $data = array();

   function __construct($default) {
        $this->data = $default;
   }

   public function __set($name, $value)
   {
       $this->data[$name] = $value;
   }

   public function __get($name)
   {
       if (array_key_exists($name, $this->data)) {
           return $this->data[$name];
       }

       return null;
   }

   public function to_json(){
       $modelKeys = array("fullName","phone","address","email","birthdate","firstName","lastName");

        $model = array_filter($this->data,function($value,$key) use ($modelKeys){
            return isset($value) && $value != "" && in_array($key,$modelKeys);
        },ARRAY_FILTER_USE_BOTH );

        $extra = array_filter($this->data,function($value,$key) use ($modelKeys){
            return isset($value) && $value != "" && !in_array($key,$modelKeys);
        },ARRAY_FILTER_USE_BOTH );

        $data = array(
			"model" => $model,
        );
        if(count($extra)>0){
			$data["queryString"] = $extra;
        }
       return json_encode($data);
   }
}

 