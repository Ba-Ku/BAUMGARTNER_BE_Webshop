<?php


class JsonView
{
    public function __construct()
    {
        header('Content-Type: application/json');
    }

    public function streamOutput($input)
    {
        $jsonOutput = json_encode($input, JSON_UNESCAPED_SLASHES);
        echo $jsonOutput;
    }
}