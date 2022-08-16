<?php

/**
 * Helper function to return an iterator for php files in the relavent subdirectory
 * 
 */

if (! function_exists('SubmoduleDirs')) {
    function SubmoduleDirs(string $from, string $subdir) {
        return glob($from . '/*/' . $subdir, GLOB_ONLYDIR);
    }
}
