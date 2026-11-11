<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XssController extends Controller
{
    /**
     * Levels available in the XSS lab.
     *  - level1 : no protection at all            (vulnerable)
     *  - level2 : weak blacklist filter            (vulnerable, bypassable)
     *  - secure : output encoding                  (safe)
     */
    private const LEVELS = ['level1', 'level2', 'secure'];

    public function index(Request $request)
    {
        $level = $request->query('level');
        $q = (string) $request->query('q', '');
        $output = null;

        if ($q !== '') {
            if (!in_array($level, self::LEVELS, true)) {
                abort(400, 'Invalid XSS lab level.');
            }

            $output = match ($level) {
                // Level 1: the input is reflected exactly as received.
                'level1' => $q,

                // Level 2: a naive filter is applied before reflecting the input.
                'level2' => $this->weakFilter($q),

                // Secure: HTML-encode the input (the view prints it with {{ }}).
                'secure' => e($q),
            };
        }

        // vuln.show wraps the lab view, and every variable passed here
        // is also available inside the included vuln.xss view.
        return view('vuln.show', [
            'slug' => 'xss',
            'viewPath' => 'vuln.xss',
            'level' => $level,
            'q' => $q,
            'output' => $output,
        ]);
    }

    /**
     * INTENTIONALLY WEAK FILTER (Level 2).
     *
     * It only removes the exact lowercase strings "<script>" and "</script>".
     * It is case-sensitive, it is a single pass, and it ignores every other tag,
     * so it can be bypassed. Never use a blacklist like this in real code.
     */
    private function weakFilter(string $input): string
    {
        return str_replace(['<script>', '</script>'], '', $input);
    }
}
