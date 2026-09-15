<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application loads the homepage', function () {
    $this->get('/')
        ->assertOk();
});
