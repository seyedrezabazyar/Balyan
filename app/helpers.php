<?php

if (!function_exists('formatFileSize')) {
    function formatFileSize($sizeInBytes)
    {
        if ($sizeInBytes >= 1073741824) {
            $size = number_format($sizeInBytes / 1073741824, 2) . ' GB';
        } elseif ($sizeInBytes >= 1048576) {
            $size = number_format($sizeInBytes / 1048576, 2) . ' MB';
        } elseif ($sizeInBytes >= 1024) {
            $size = number_format($sizeInBytes / 1024, 2) . ' KB';
        } else {
            $size = $sizeInBytes . ' Bytes';
        }

        return $size;
    }
}
