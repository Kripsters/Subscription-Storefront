<?php

it('returns a successful response', function () {
    $page = visit('/');

    $page->assertSee('StockedUp');
});
