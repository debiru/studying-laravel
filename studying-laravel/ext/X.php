<?php

final class X extends XBase {
    const SLASH = '/';

    public static $page = null;

    public static function obj() {
        return (object)[];
    }

    public static function isset(&$arg) {
        return isset($arg) && $arg !== null && $arg !== '' && $arg !== [];
    }

    public static function ltrim($str, $char = self::SLASH) {
        return ltrim($str, $char);
    }

    public static function rtrim($str, $char = self::SLASH) {
        return rtrim($str, $char);
    }

    public static function lslash($str, $char = self::SLASH) {
        return $char . ltrim($str, $char);
    }

    public static function rslash($str, $char = self::SLASH) {
        return rtrim($str, $char) . $char;
    }

    public static function rootUrl() {
        return self::rslash(parent::rootUrl());
    }

    public static function basePath() {
        return self::rslash(parent::basePath());
    }

    public static function baseUrl() {
        return self::rslash(parent::baseUrl());
    }

    public static function currentUrl($full = false) {
        $url = $full ? parent::fullUrl() : parent::currentUrl();
        if ($url === self::rtrim(self::rootUrl())) $url = self::rslash($url);
        return $url;
    }

    public static function route($path, $params = null, $absolute = false) {
        if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
        return parent::route($path, $params, $absolute);
    }

    public static function parseUrl($url) {
        // refs. https://www.php.net/manual/ja/function.parse-url.php
        $parts = parse_url($url);
        $keys = ['scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment'];
        foreach ($keys as $key) {
            if (!isset($parts[$key])) $parts[$key] = '';
        }
        return $parts;
    }

    public static function urlToPath($url) {
        if (isset(self::$method[__FUNCTION__])) return self::$method[__FUNCTION__](func_get_args());
        return self::parseUrl($url)['path'];
    }

    public static function getPage() {
        $pagePath = self::lchop(self::ltrim(self::urlToPath(self::currentUrl())), self::basePath());
        if ($pagePath === '') $pagePath = '/';
        return $pagePath;
    }

    public static function isPage($argPagePath) {
        $pagePath = self::getPage();
        if ($argPagePath !== '/') $argPagePath = self::ltrim($argPagePath);
        return $pagePath === $argPagePath;
    }

    public static function assets($path, $cacheBuster = false) {
        $cbQuery = '';
        if ($cacheBuster) {
            $systemPath = parent::public_path('') . parent::asset($path);
            if (parent::fileExists($systemPath)) {
                $cbQuery = sprintf('?%s', parent::lastModified($systemPath));
            }
        }
        return parent::asset($path) . $cbQuery;
    }

    public static function assetsUrl($path, $cacheBuster = false) {
        return self::rslash(self::rootUrl()) . self::ltrim(self::assets($path, $cacheBuster));
    }

    public static function img($path) {
        return self::assets('img/' . self::ltrim($path));
    }

    public static function pageInit() {
        self::$page ??= self::obj();
        self::$page->cssList ??= [];
        self::$page->jsList ??= [];
    }

    public static function pageTitle($str) {
        self::pageInit();
        self::$page->title = $str;
    }

    public static function loadCSS($pathList) {
        self::pageInit();
        $pathList = array_map(fn($path) => self::lstr(self::ltrim($path), 'css/'), $pathList);
        self::$page->cssList = array_merge(self::$page->cssList, $pathList);
    }

    public static function loadJS($pathList) {
        self::pageInit();
        $pathList = array_map(fn($path) => self::lstr(self::ltrim($path), 'js/'), $pathList);
        self::$page->jsList = array_merge(self::$page->jsList, $pathList);
    }

    public static function indent($text, $level, $char = '  ') {
        if ($level > 0) {
            $preSpaces = str_repeat($char, $level);
            $text = preg_replace('/^(?!$)/m', $preSpaces, $text);
        }
        return $text;
    }

    public static function getTemplate($__templateKey, $__indent = 0, $__data = null) {
        $__data ?? [];
        $__data = ['args' => $__data];
        $__data['__env'] = app(\Illuminate\View\Factory::class);
        $__source = sprintf('@include("%s")', $__templateKey);
        $__php = Blade::compileString($__source);

        $__obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);

        try {
            eval('?' . '>' . $__php);
        }
        catch (Exception $e) {
            while (ob_get_level() > $__obLevel) ob_end_clean();
            throw $e;
        }
        catch (Throwable $e) {
            while (ob_get_level() > $__obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }

        $__buf = ob_get_clean();
        return self::indent($__buf, $__indent);
    }

    public static function include($key, $indent = 0, $data = null) {
        echo self::getTemplate($key, $indent, $data);
    }

    public static function esc_html($str, $flag = null) {
        $flag ??= ENT_QUOTES;
        if (is_null($str)) return null;
        return htmlspecialchars($str, $flag, 'UTF-8');
    }

    public static function esc_json($str) {
        $entSingle = ENT_QUOTES ^ ENT_COMPAT;
        return self::esc_html($str, $entSingle);
    }

    public static function jsonEncode($object, $oneline = false) {
        $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if (!$oneline) $options |= JSON_PRETTY_PRINT;
        return json_encode($object, $options);
    }

    public static function jsonDecode($json, $associative = true) {
        if ($json === null) return null;
        return json_decode($json, $associative, 512, JSON_BIGINT_AS_STRING);
    }

    public static function make_attrs($attrsAssoc) {
        $attrsStr = '';
        foreach ((array)$attrsAssoc as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', array_filter($value, fn($v) => strlen((string)$v)));
                if (empty($value)) $value = null;
            }
            if ($value === null) continue;
            if ($value === true) {
                $attrsStr .= sprintf(' %s', self::esc_html($key));
            }
            else {
                $attrsStr .= sprintf(' %s="%s"', self::esc_html($key), self::esc_html($value));
            }
        }
        return $attrsStr;
    }

    public static function attrs($attrsAssoc) {
        echo self::make_attrs($attrsAssoc);
    }

    public static function if_val($cond, $value = null, $default = null) {
        $value ??= $cond;
        return $cond ? $value : $default;
    }

    public static function regex_mailaddress() {
        return '^[^@]+@[^@\.]+(\.[^@\.]+)+$';
    }
}
