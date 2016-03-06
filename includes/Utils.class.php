<?php
/**
 * @Author: printempw
 * @Date:   2016-01-16 23:01:33
 * @Last Modified by:   printempw
 * @Last Modified time: 2016-03-06 15:44:32
 */

class Utils
{
    /**
     * Custom error handler
     *
     * @param  int $errno
     * @param  string $msg, message to show
     * @return void
     */
    public static function raise($errno = -1, $msg = "Error occured.") {
        $exception['errno'] = $errno;
        $exception['msg'] = $msg;
        header('Content-type: application/json; charset=utf-8');
        die(json_encode($exception));
    }

    /**
     * Rename uploaded file
     *
     * @param  array $file, files uploaded via HTTP POST
     * @return string $hash, sha256 hash of file
     */
    public static function upload($file) {
        $dir = dirname(dirname(__FILE__));
        move_uploaded_file($file["tmp_name"], "$dir/textures/tmp.png");
        $hash = hash_file('sha256', "$dir/textures/tmp.png");
        rename("$dir/textures/tmp.png", "$dir/textures/".$hash);
        return $hash;
    }

    /**
     * Read a file and return bin data
     *
     * @param  string $filename
     * @return resource, binary data
     */
    public static function fread($filename) {
        return fread(fopen($filename, 'r'), filesize($filename));
    }

    /**
     * Remove a file
     *
     * @param  $filename
     * @return $bool
     */
    public static function remove($filename) {
        if(file_exists($filename)) {
            if (!unlink($filename)) {
                self::raise(-1, "删除 $filename 的时候出现了奇怪的问题。。请联系作者");
            } else {
                return true;
            }
        }
    }

    /**
     * Recursively count the size of specified directory
     *
     * @param  string $dir
     * @return int, total size in bytes
     */
    public static function getDirSize($dir) {
        $dh = opendir($dir);
        $size = 0;
        while(false !== ($file = @readdir($dh))) {
            if ($file!='.' && $file!='..') {
                $path = $dir.'/'.$file;
                if (is_dir($path)) {
                    $size += $this->getDirSize($path);
                } else if (is_file($path)) {
                    $size += filesize($path);
                }
            }
        }
        closedir($dh);
        return $size;
    }

    /**
     * Simple SQL injection protection
     *
     * @param  string $string
     * @return string
     */
    public static function convertString($string) {
        return stripslashes(trim($string));
    }

    /**
     * Get the value of key in an array if index exist
     *
     * @param  string $key
     * @param  array $array
     * @return object
     */
    public static function getValue($key, $array) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return false;
    }

}
