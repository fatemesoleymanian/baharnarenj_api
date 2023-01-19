<?php

use App\Exceptions\CustomException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('exists')) {
    /**
     * @param mixed $data
     * @return bool
     */
    function exists(mixed $data): bool
    {
        return isset($data) && $data != '' && $data != null;
    }
}

if (!function_exists('filterData')) {
    /**
     * @param array $data
     * @return array
     * @throws CustomException
     */
    function filterData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!exists($value)) {
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            throw new CustomException('درخواست شما نامعتبر است.');
        }
        return $data;

    }
}

if (!function_exists('handleFile')) {
    /**
     * @param $path
     * @param $file
     * @return string
     */
    function handleFile($path, $file): string
    {
        $fileName = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs($path, $file, $fileName);
        return $path . $fileName;
    }
}
