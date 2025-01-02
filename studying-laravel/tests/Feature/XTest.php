<?php

describe('class X', function() {
    beforeEach(function() {
        XBase::method();
        X::$page = null;
    });

    test('getTemplate', function() {
        expect(X::getTemplate('parts.tests.test', 0, ['var' => 'foo']))->toBe("test foo\n");
        expect(X::getTemplate('parts.tests.test', 1, ['var' => 'foo']))->toBe("  test foo\n");
    });
});
