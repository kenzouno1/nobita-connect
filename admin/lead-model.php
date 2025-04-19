<?php

class NobitaLead
{
    private $data = array();

    public function __construct($default)
    {
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

    public function to_json()
    {
        $options = get_option('nobi_connect_options');

        $modelKeys = array("fullName", "phone", "address", "email", "birthdate", "firstName"
            , "lastName", "name", "Email", "Name", "Birthdate", "FullName", "Phone", "Address");

        if (!isset($this->data["fullName"]) && isset($this->data["your-name"])) {
            $this->data["fullName"] = $this->data["your-name"];
            unset($this->data["your-name"]);
        }

        if (!isset($this->data["email"]) && isset($this->data["your-email"])) {
            $this->data["email"] = $this->data["your-email"];
            unset($this->data["your-email"]);
        }

        $model = array_filter($this->data, function ($value, $key) use ($modelKeys) {
            return isset($value) && $value != "" && in_array($key, $modelKeys);
        }, ARRAY_FILTER_USE_BOTH);

        $extra = array_filter($this->data, function ($value, $key) use ($modelKeys) {
            return isset($value) && $value != "" && !in_array($key, $modelKeys);
        }, ARRAY_FILTER_USE_BOTH);

        if (isset($_COOKIE['nobi_link']) && isset($options["use_access_link"]) && $options["use_access_link"] == 1) {
            $extra["access_link"] = $_COOKIE['nobi_link'];
        }

        $data = array(
            "model" => $model,
        );
        if (count($extra) > 0) {
            $data["queryString"] = $extra;
        }
        return json_encode($data);
    }
}
