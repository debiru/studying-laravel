<?php

describe('class X', function() {
    beforeEach(function() {
        XBase::method();
        X::$page = null;
    });

    test('obj', function() {
        expect(get_class(X::obj()))->toBe('stdClass');
    });

    test('isset', function() {
        $null = null;
        $str1 = '1';
        $str0 = '0';
        $num1 = 1;
        $num0 = 0;
        $array = [1];
        $emptyArray = [];
        expect(X::isset($undef))->toBeFalse();
        expect(X::isset($null))->toBeFalse();
        expect(X::isset($str1))->toBeTrue();
        expect(X::isset($str0))->toBeTrue();
        expect(X::isset($num1))->toBeTrue();
        expect(X::isset($num0))->toBeTrue();
        expect(X::isset($array))->toBeTrue();
        expect(X::isset($emptyArray))->toBeFalse();
    });

    test('ltrim', function() {
        expect(X::ltrim('/path/to/'))->toBe('path/to/');
        expect(X::ltrim('path/to/'))->toBe('path/to/');
        expect(X::ltrim('/path/to'))->toBe('path/to');
        expect(X::ltrim('path/to'))->toBe('path/to');
    });

    test('rtrim', function() {
        expect(X::rtrim('/path/to/'))->toBe('/path/to');
        expect(X::rtrim('path/to/'))->toBe('path/to');
        expect(X::rtrim('/path/to'))->toBe('/path/to');
        expect(X::rtrim('path/to'))->toBe('path/to');
    });

    test('lslash', function() {
        expect(X::lslash('/path/to/'))->toBe('/path/to/');
        expect(X::lslash('path/to/'))->toBe('/path/to/');
        expect(X::lslash('/path/to'))->toBe('/path/to');
        expect(X::lslash('path/to'))->toBe('/path/to');
    });

    test('rslash', function() {
        expect(X::rslash('/path/to/'))->toBe('/path/to/');
        expect(X::rslash('path/to/'))->toBe('path/to/');
        expect(X::rslash('/path/to'))->toBe('/path/to/');
        expect(X::rslash('path/to'))->toBe('path/to/');
    });

    test('rootUrl', function() {
        expect(X::rootUrl())->toBe('https://example.com/');
    });

    test('basePath', function() {
        expect(X::basePath())->toBe('subdir/');
    });

    test('baseUrl', function() {
        expect(X::baseUrl())->toBe('https://example.com/subdir/');
    });

    test('currentUrl', function() {
        expect(X::currentUrl())->toBe('https://example.com/subdir/path/to/current');
        expect(X::currentUrl(true))->toBe('https://example.com/subdir/path/to/current?id=1');
    });

    test('route', function() {
        expect(X::route('/'))->toBe('/');
        expect(X::route('login'))->toBe('login');
        expect(X::route('login', ['id' => 1]))->toBe('login?id=1');
    });

    test('parseUrl', function() {
        expect(X::parseUrl('https://user:pass@example.com:9999/path/to?query=1#hash'))->toEqual([
            'scheme' => 'https',
            'host' => 'example.com',
            'port' => '9999',
            'user' => 'user',
            'pass' => 'pass',
            'path' => '/path/to',
            'query' => 'query=1',
            'fragment' => 'hash',
        ]);
        expect(X::parseUrl('/path/to'))->toEqual([
            'scheme' => '',
            'host' => '',
            'port' => '',
            'user' => '',
            'pass' => '',
            'path' => '/path/to',
            'query' => '',
            'fragment' => '',
        ]);
    });

    test('urlToPath', function() {
        expect(X::urlToPath('https://user:pass@example.com:9999/path/to?query=1#hash'))->toBe('/path/to');
        expect(X::urlToPath('/path/to'))->toBe('/path/to');
    });

    test('getPage', function() {
        XBase::method('currentUrl', fn() => X::baseUrl());
        expect(X::getPage())->toBe('/');
        XBase::method('currentUrl', fn() => X::baseUrl() . 'login');
        expect(X::getPage())->toBe('login');
    });

    test('isPage', function() {
        XBase::method('currentUrl', fn() => X::baseUrl());
        expect(X::isPage('/'))->toBeTrue();
        XBase::method('currentUrl', fn() => X::baseUrl() . 'login');
        expect(X::isPage('login'))->toBeTrue();
        expect(X::isPage('/login'))->toBeTrue();

        XBase::method('basePath', fn() => '');
        XBase::method('currentUrl', fn() => X::baseUrl());
        expect(X::isPage('/'))->toBeTrue();
        XBase::method('currentUrl', fn() => X::baseUrl() . 'login');
        expect(X::isPage('login'))->toBeTrue();
        expect(X::isPage('/login'))->toBeTrue();
    });

    test('assets', function() {
        expect(X::assets('path/to/file'))->toBe('/assets/path/to/file');
        expect(X::assets('path/to/file', true))->toBe('/assets/path/to/file');
        XBase::method('fileExists', fn() => true);
        expect(X::assets('path/to/file', true))->toBe('/assets/path/to/file?1735657200');
    });

    test('assetsUrl', function() {
        expect(X::assetsUrl('path/to/file'))->toBe('https://example.com/assets/path/to/file');
        expect(X::assetsUrl('path/to/file', true))->toBe('https://example.com/assets/path/to/file');
        XBase::method('fileExists', fn() => true);
        expect(X::assetsUrl('path/to/file', true))->toBe('https://example.com/assets/path/to/file?1735657200');
    });

    test('img', function() {
        expect(X::img('path/to/file'))->toBe('/assets/img/path/to/file');
    });

    test('pageInit', function() {
        expect(X::$page)->toBeNull();
        X::pageInit();
        expect(X::$page)->not->toBeNull();
        expect(X::$page->cssList)->not->toBeNull();
        expect(X::$page->jsList)->not->toBeNull();
    });

    test('pageTitle', function() {
        X::pageTitle('pageTitle');
        expect(X::$page->title)->toBe('pageTitle');
    });

    test('loadCSS', function() {
        X::loadCSS(['path/to/a.css', 'path/to/b.css']);
        X::loadCSS(['path/to/c.css', 'path/to/d.css']);
        expect(X::$page->cssList)->toEqual([
            'css/path/to/a.css',
            'css/path/to/b.css',
            'css/path/to/c.css',
            'css/path/to/d.css',
        ]);
    });

    test('loadJS', function() {
        X::loadJS(['path/to/a.js', 'path/to/b.js']);
        X::loadJS(['path/to/c.js', 'path/to/d.js']);
        expect(X::$page->jsList)->toEqual([
            'js/path/to/a.js',
            'js/path/to/b.js',
            'js/path/to/c.js',
            'js/path/to/d.js',
        ]);
    });

    test('indent', function() {
        $html = "<div>\n  foo\n</div>";
        $html2 = "  <div>\n    foo\n  </div>";
        expect(X::indent($html, 0))->toBe($html);
        expect(X::indent($html, 1))->toBe($html2);
    });

    test('esc_html', function() {
        $str = '"<Do> & <Don\'t>"';
        expect(X::esc_html($str))->toBe('&quot;&lt;Do&gt; &amp; &lt;Don&#039;t&gt;&quot;');
    });

    test('esc_json', function() {
        $str = '"<Do> & <Don\'t>"';
        expect(X::esc_json($str))->toBe('"&lt;Do&gt; &amp; &lt;Don&#039;t&gt;"');
    });

    test('jsonEncode', function() {
        $obj = ['a' => null, 'b' => 1, 'c' => 'abcあいう/<>&\'"123'];
        expect(X::jsonEncode($obj))->toBe("{\n    \"a\": null,\n    \"b\": 1,\n    \"c\": \"abcあいう/<>&'\\\"123\"\n}");
        expect(X::jsonEncode($obj, true))->toBe("{\"a\":null,\"b\":1,\"c\":\"abcあいう/<>&'\\\"123\"}");
    });

    test('jsonDecode', function() {
        $obj = ['a' => null, 'b' => 1, 'c' => 'abcあいう/<>&\'"123'];
        $json1 = "{\n    \"a\": null,\n    \"b\": 1,\n    \"c\": \"abcあいう/<>&'\\\"123\"\n}";
        $json2 = "{\"a\":null,\"b\":1,\"c\":\"abcあいう/<>&'\\\"123\"}";
        expect(X::jsonDecode($json1))->toEqual($obj);
        expect(X::jsonDecode($json2))->toEqual($obj);
    });

    test('make_attrs', function() {
        $attrs = ['class' => ['foo', 'bar'], 'id' => 'foo'];
        expect(X::make_attrs($attrs))->toBe(' class="foo bar" id="foo"');
    });

    test('if_val', function() {
        $attrs = ['class' => [X::if_val(true, 'foo')]];
        expect(X::make_attrs($attrs))->toBe(' class="foo"');
        $attrs = ['class' => [X::if_val(false, 'foo')]];
        expect(X::make_attrs($attrs))->toBe('');
        $attrs = ['class' => [X::if_val(false, 'foo', 'bar')]];
        expect(X::make_attrs($attrs))->toBe(' class="bar"');
        $attrs = ['class' => [X::if_val('foo')]];
        expect(X::make_attrs($attrs))->toBe(' class="foo"');
    });

    test('regex_mailaddress', function() {
        $test = function($str) {
            $ret = preg_match(sprintf('/%s/', X::regex_mailaddress()), $str);
            if ($ret === false) return null;
            return (bool)$ret;
        };
        expect($test('a'))->toBeFalse();
        expect($test('a@a'))->toBeFalse();
        expect($test('a@a.a'))->toBeTrue();
        expect($test('a@a@a.a'))->toBeFalse();
        expect($test('"!#$%&\'*+-/=?^_{|}~`"@a.a'))->toBeTrue();
        expect($test('"!#$%&\'*+-/=?^_{|}~`@"@a.a'))->toBeFalse();
        expect($test('your@example.com'))->toBeTrue();
        expect($test('your@sub.sub.sub.sub.example.com'))->toBeTrue();
        expect($test('your.dot@gmail.com'))->toBeTrue();
        expect($test('your+plus@gmail.com'))->toBeTrue();
    });
});
