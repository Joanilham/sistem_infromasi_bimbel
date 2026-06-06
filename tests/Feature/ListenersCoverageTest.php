<?php

use App\Listeners\LogAuthenticationActivity;

test('LogAuthenticationActivity handle method runs successfully', function () {
    $listener = new LogAuthenticationActivity();
    $listener->handle(new stdClass());
    
    // Assert true to ensure no exception is thrown
    expect(true)->toBeTrue();
});
