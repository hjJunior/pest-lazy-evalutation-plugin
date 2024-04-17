<?php

use function Pest\Let\context;
use function Pest\Let\expectSubject;
use function Pest\Let\get;
use function Pest\Let\let;
use function Pest\Let\subject;

function testMe($param)
{
    return $param;
}

subject(fn () => testMe(get('param2')));
let('param1', fn () => 1);

it('can get param1 from root let', function () {
    expect(get('param1'))->toEqual(1);
});

it('cannot get non defined variables', function () {
    get('non-existent-param');
})->throws('Attempt to read non-existent-param, when was not set');

context('when param2 is 3', function () {
    let('param2', fn () => 3);

    it('returns param3 value', function () {
        expectSubject()->toEqual(3);
    });

    it('returns param3 value multiple times in same scope', function () {
        expectSubject()->toEqual(3);
    });
});

context('when param2 is 4', function () {
    let('param2', fn () => 4);

    it('can work', function () {
        expectSubject()->toEqual(4);
    });
});
