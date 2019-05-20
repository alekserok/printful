<?php

namespace App;

use Exception;

class CacheShippingOptions implements CacheInterface
{


    /**
     * Store a mixed type value in cache for a certain amount of seconds.
     * Allowed values are primitives and arrays.
     *
     * @param string $key
     * @param mixed $value
     * @param int $duration Duration in seconds
     * @return mixed
     * @throws Exception
     */
    public function set(string $key, $value, int $duration)
    {
        $h = fopen($this->getFileName($key),'w');

        if (!$h) throw new Exception('Could not write to cache');

        $data = serialize([time() + $duration, $value]);

        if (fwrite($h, $data) === false) {
            throw new Exception('Could not write to cache');
        }
        fclose($h);

        return $value;
    }

    /**
     * Retrieve stored item.
     * Returns the same type as it was stored in.
     * Returns null if entry has expired.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        $filename = $this->getFileName($key);

        if (!file_exists($filename) || !is_readable($filename)) return null;

        $data = file_get_contents($filename);

        $data = @unserialize($data);
        if (!$data) {

            unlink($filename);
            return null;

        }

        if (time() > $data[0]) {

            unlink($filename);
            return null;

        }

        return $data[1];
    }

    private function getFileName($key) {

        return '../tmp/cached_' . md5($key);

    }
}
