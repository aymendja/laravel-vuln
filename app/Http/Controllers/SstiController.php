<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Throwable;

class SstiController extends Controller
{
    /**
     * Levels available in the SSTI lab.
     *  - level1 : the user's input IS the template         (vulnerable)
     *  - level2 : weak blacklist filter before compiling    (vulnerable, bypassable)
     *  - secure : the user's input is only ever a VARIABLE  (safe)
     */
    private const LEVELS = ['level1', 'level2', 'secure'];

    public function index(Request $request)
    {
        $level = $request->query('level');
        $template = (string) $request->query('template', '');
        $output = null;
        $error = null;

        if ($template !== '') {
            if (!in_array($level, self::LEVELS, true)) {
                abort(400, 'Invalid SSTI lab level.');
            }

            try {
                $output = match ($level) {
                    // Level 1: the raw user-supplied string is compiled AND executed
                    // as a Blade template. Any PHP expression inside {{ }} runs.
                    'level1' => Blade::render($template),

                    // Level 2: a naive blacklist is stripped out first, then the
                    // (still attacker-controlled) string is compiled as a template.
                    'level2' => Blade::render($this->weakFilter($template)),

                    // Secure: the template string is a FIXED, developer-written
                    // string. The user's input is only ever passed in as a plain
                    // variable, so it can never be compiled or executed as code.
                    'secure' => Blade::render('Hello, {{ $name }}!', ['name' => $template]),
                };
            } catch (Throwable $e) {
                // A malicious/broken template can throw a compile or runtime error.
                // We surface it so the lab shows what happened, instead of crashing.
                $error = $e->getMessage();
            }
        }

        return view('vuln.show', [
            'slug' => 'ssti',
            'viewPath' => 'vuln.ssti',
            'level' => $level,
            'template' => $template,
            'output' => $output,
            'error' => $error,
        ]);
    }

    /**
     * INTENTIONALLY WEAK FILTER (Level 2).
     *
     * It only removes the exact lowercase substrings "system(" and "exec(".
     * It is case-sensitive, single-pass, and ignores every other dangerous
     * function or operator, so it can be bypassed. Never use a blacklist like
     * this to try to make template compilation "safe".
     */
    private function weakFilter(string $input): string
    {
        return str_replace(['system(', 'exec('], '', $input);
    }
}
