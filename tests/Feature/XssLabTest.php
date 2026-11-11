<?php

function xssLab(string $level, string $payload)
{
    return test()->get('/vulns/xss?' . http_build_query(['level' => $level, 'q' => $payload]));
}

test('the xss lab page loads', function () {
    $this->get('/vulns/xss')->assertOk();
});

test('level 1 reflects the input without any protection', function () {
    $payload = "<script>alert('lvl1-marker')</script>";

    xssLab('level1', $payload)->assertOk()->assertSee($payload, false);
});

test('level 2 removes lowercase script tags', function () {
    $payload = "<script>alert('lvl2-marker')</script>";

    xssLab('level2', $payload)
        ->assertOk()
        ->assertDontSee($payload, false)
        ->assertDontSee('<script>alert(\'lvl2-marker\')', false);
});

test('level 2 can be bypassed with mixed case, another tag, or nesting', function (string $payload) {
    xssLab('level2', $payload)->assertOk()->assertSee($payload, false);
})->with([
    'mixed case' => '<ScRiPt>alert(1)</ScRiPt>',
    'svg tag' => '<svg onload=alert(1)>',
    'img tag' => '<img src=x onerror=alert(1)>',
]);

test('level 2 nested payload becomes a working script tag', function () {
    xssLab('level2', '<scr<script>ipt>alert(1)</scr</script>ipt>')
        ->assertOk()
        ->assertSee('<script>alert(1)</script>', false);
});

test('the secure level encodes every payload', function (string $payload) {
    xssLab('secure', $payload)
        ->assertOk()
        ->assertDontSee($payload, false)
        ->assertSee(e($payload), false);
})->with([
    'script' => "<script>alert('x')</script>",
    'mixed case' => '<ScRiPt>alert(1)</ScRiPt>',
    'svg' => '<svg onload=alert(1)>',
    'img' => '<img src=x onerror=alert(1)>',
]);

test('an unknown level is rejected', function () {
    xssLab('nope', 'hello')->assertStatus(400);
});
