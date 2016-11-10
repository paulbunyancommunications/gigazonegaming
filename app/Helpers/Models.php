<?php

if (!function_exists('getModels')) {
    /**
     * Get all models in app
     * http://stackoverflow.com/a/34054171/405758
     * @param $path
     * @return array
     */
    function getModels($path)
    {
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }
            $filename = $path . '/' . $result;
            if (is_dir($filename)) {
                $out = array_merge($out, getModels($filename));
            } else {
                $out[] = substr($filename, 0, -4);
            }
        }
        return $out;
    }
}
