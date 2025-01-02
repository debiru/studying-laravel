<?php

use Illuminate\Support\Str;

if (! defined('LARAVEL_START')) {
    trait XBaseTrait {
        const ROOT_URL = 'https://example.com';
        const BASE_PATH = 'subdir';
        const CURRENT_PATH = 'path/to/current';
        const QUERY = 'id=1';
        const ASSET_URL = '/assets/';

        private static $method = [];

        public static function method($key = null, $implementation = null) {
            if ($key === null) {
                self::$method = [];
            }
            else if ($implementation === null) {
                unset(self::$method[$key]);
            }
            else {
                self::$method[$key] = $implementation;
            }
        }

        public static function config($key) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return $key;
        }

        public static function rootUrl() {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return self::ROOT_URL;
        }

        public static function basePath() {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return self::BASE_PATH;
        }

        public static function baseUrl() {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return sprintf('%s/%s', self::rootUrl(), self::basePath());
        }

        public static function fullUrl() {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return sprintf('%s?%s', self::currentUrl(), self::QUERY);
        }

        public static function currentUrl() {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return sprintf('%s/%s', self::baseUrl(), self::CURRENT_PATH);
        }

        public static function route($path, $params = null, $absolute = true) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            $query = '';
            if ($params !== null) $query = http_build_query($params, '', null, PHP_QUERY_RFC3986);
            if ($query !== '') $query = '?' . $query;
            return $path . $query;
        }

        public static function asset($path) {
            return self::ASSET_URL . ltrim($path, '/');
        }

        public static function public_path($path) {
            return rtrim(dirname(__DIR__, 3) . '/public/' . ltrim($path, '/'), '/');
        }

        public static function fileExists($systemPath) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return file_exists($systemPath);
        }

        public static function lastModified($systemPath) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return strtotime("2025-01-01");
        }

        public static function lstr($str, $prefix) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return Str::start($str, $prefix);
        }

        public static function rstr($str, $suffix) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return Str::finish($str, $suffix);
        }

        public static function lchop($str, $prefix) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return Str::chopStart($str, $prefix);
        }

        public static function rchop($str, $suffix) {
            if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
            return Str::chopEnd($str, $suffix);
        }
    }
}
