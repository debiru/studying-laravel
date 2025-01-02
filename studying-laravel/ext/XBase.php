<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (! trait_exists('XBaseTrait')) {
    trait XBaseTrait {
        public static function config($key) {
            return config($key);
        }

        public static function rootUrl() {
            return url()->getRequest()->schemeAndHttpHost();
        }

        public static function basePath() {
            return url()->getRequest()->getBaseUrl();
        }

        public static function baseUrl() {
            return url('/');
        }

        public static function fullUrl() {
            return url()->full();
        }

        public static function currentUrl() {
            return url()->current();
        }

        public static function route($path, $params = null, $absolute = true) {
            return route($path, $params, $absolute);
        }

        public static function asset($path) {
            return asset($path);
        }

        public static function public_path($path) {
            return public_path($path);
        }

        public static function fileExists($systemPath) {
            return File::exists($systemPath);
        }

        public static function lastModified($systemPath) {
            return File::lastModified($systemPath);
        }

        public static function lstr($str, $prefix) {
            return Str::start($str, $prefix);
        }

        public static function rstr($str, $suffix) {
            return Str::finish($str, $suffix);
        }

        public static function lchop($str, $prefix) {
            return Str::chopStart($str, $prefix);
        }

        public static function rchop($str, $suffix) {
            return Str::chopEnd($str, $suffix);
        }
    }
}

class XBase {
    use XBaseTrait;
}
