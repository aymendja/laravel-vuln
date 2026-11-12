<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PathTraversalController extends Controller
{
    /**
     * Levels available in the Path Traversal lab.
     *  - level1 : the filename is appended to the folder as-is   (vulnerable)
     *  - level2 : weak filter removes "../" once                  (vulnerable, bypassable)
     *  - secure : resolved path must stay inside the docs folder  (safe)
     */
    private const LEVELS = ['level1', 'level2', 'secure'];

    /** Only this many characters of a file are shown, to keep the page readable. */
    private const MAX_OUTPUT = 4000;

    public function index(Request $request)
    {
        $level = $request->query('level');
        $file = (string) $request->query('file', '');
        $output = null;
        $error = null;

        if ($file !== '') {
            if (!in_array($level, self::LEVELS, true)) {
                abort(400, 'Invalid Path Traversal lab level.');
            }

            $result = match ($level) {
                'level1' => $this->readVulnerable($file),
                'level2' => $this->readVulnerable($this->weakFilter($file)),
                'secure' => $this->readSecure($file),
            };

            [$output, $error] = $result;
        }

        return view('vuln.show', [
            'slug' => 'path-traversal',
            'viewPath' => 'vuln.path-traversal',
            'level' => $level,
            'file' => $file,
            'output' => $output,
            'error' => $error,
            'docs' => $this->listDocs(),
        ]);
    }

    /**
     * INTENTIONALLY VULNERABLE (Level 1 and Level 2).
     *
     * The user-controlled filename is concatenated onto the documents folder.
     * Nothing checks that the final path is still INSIDE that folder, so
     * "../" sequences walk up and out of it.
     */
    private function readVulnerable(string $file): array
    {
        $path = $this->docsPath() . '/' . $file;

        if (!is_file($path) || !is_readable($path)) {
            return [null, 'File not found.'];
        }

        return [$this->truncate((string) file_get_contents($path)), null];
    }

    /**
     * INTENTIONALLY WEAK FILTER (Level 2).
     *
     * It removes the exact string "../" ONE time. It is single-pass, so the
     * string "....//" collapses into "../" after the removal. It also never
     * looks at anything else. Never sanitize paths with a blacklist like this.
     */
    private function weakFilter(string $input): string
    {
        return str_replace('../', '', $input);
    }

    /**
     * SECURE: resolve the real path first, then verify it stays inside the
     * documents folder. realpath() collapses "../" and symlinks, so the check
     * runs against where the file REALLY is, not what the user typed.
     */
    private function readSecure(string $file): array
    {
        $base = realpath($this->docsPath());
        $resolved = realpath($base . DIRECTORY_SEPARATOR . $file);

        $inside = $resolved !== false
            && str_starts_with($resolved, $base . DIRECTORY_SEPARATOR)
            && is_file($resolved);

        if (!$inside) {
            return [null, 'Access denied or file not found.'];
        }

        return [$this->truncate((string) file_get_contents($resolved)), null];
    }

    private function docsPath(): string
    {
        return resource_path('lab-files/docs');
    }

    private function listDocs(): array
    {
        $files = array_map('basename', glob($this->docsPath() . '/*.txt') ?: []);
        sort($files);

        return $files;
    }

    private function truncate(string $content): string
    {
        return mb_strimwidth($content, 0, self::MAX_OUTPUT, "\n[...truncated]");
    }
}
