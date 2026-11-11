<?php

function sstiLab(string $level, string $payload)
{
    return test()->get('/vulns/ssti?' . http_build_query(['level' => $level, 'template' => $payload]));
}

test('the ssti lab page loads', function () {
    $this->get('/vulns/ssti')->assertOk();
});

test('level 1 compiles and executes the raw template', function () {
    sstiLab('level1', '{{ 6*7 }}')->assertOk()->assertSee('42');
});

test('level 2 blocks a plain system( call', function () {
    sstiLab('level2', "{{ system('id') }}")
        ->assertOk()
        ->assertDontSee('uid=');
});

test('level 2 can be bypassed with case change, another function, or backticks', function (string $payload) {
    sstiLab('level2', $payload)->assertOk()->assertSee('uid=');
})->with([
    'mixed case function' => "{{ SyStem('id') }}",
    'different function' => "{{ passthru('id') }}",
    'backtick operator' => '{{ `id` }}',
]);

test('the secure level never compiles the input as a template', function (string $payload) {
    sstiLab('secure', $payload)->assertOk()->assertSee('Hello, ' . e($payload) . '!', false);
})->with([
    'arithmetic' => '{{ 6*7 }}',
    'system call' => "{{ system('id') }}",
    'backticks' => '{{ `id` }}',
]);

test('an unknown level is rejected', function () {
    sstiLab('nope', 'hello')->assertStatus(400);
});
