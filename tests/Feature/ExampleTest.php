<?php

test('returns a successful response upon visiting "/"', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
