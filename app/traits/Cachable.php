<?php
namespace App\Traits;

trait Cachable {
    public function cache($key, $data)
    {
        \Cache::forever($key, $data);

        return \Cache::get($key);
    }

    public function updateCache($key, $data)
    {
        if (\Cache::has($key)) {
            $collection = \Cache::get($key);
            $collection->map(function($item, $key) use ($data) {
                if ($item->id === $data->id) {
                    return $data;
                }

                return $item;
            });

            \Cache::forever($key, $collection);
        }
    }

    public function deleteCache($key)
    {
        \Cache::forget($key);
    }

}