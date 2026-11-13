<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    /**
     * Levels available in the File Upload lab.
     *  - level1 : any file is accepted, saved under its own name   (vulnerable)
     *  - level2 : only trusts the Content-Type sent by the client  (vulnerable, bypassable)
     *  - secure : real content check + random name + private disk  (safe)
     */
    private const LEVELS = ['level1', 'level2', 'secure'];

    /** Content types the Level 2 "filter" accepts. */
    private const IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

    public function index()
    {
        return view('vuln.show', [
            'slug' => 'file-upload',
            'viewPath' => 'vuln.file-upload',
            // Set by store() through the session after the redirect.
            'result' => session('upload_result'),
        ]);
    }

    public function store(Request $request)
    {
        $level = $request->input('level');

        if (!in_array($level, self::LEVELS, true)) {
            abort(400, 'Invalid File Upload lab level.');
        }

        $file = $request->file('upload');

        if (!$file instanceof UploadedFile || !$file->isValid()) {
            $result = $this->failure('No valid file was received.');
        } else {
            $result = match ($level) {
                'level1' => $this->saveWithoutChecks($file),
                'level2' => $this->saveWithContentTypeCheck($file),
                'secure' => $this->saveSecurely($file),
            };
        }

        $result['level'] = $level;

        // Redirect (Post/Redirect/Get) so refreshing the page does not re-upload.
        return redirect(route('vulns.file-upload') . '#' . $level)
            ->with('upload_result', $result);
    }

    /**
     * INTENTIONALLY VULNERABLE (Level 1).
     *
     * Nothing is checked: not the extension, not the type, not the content.
     * The file lands inside public/, which the web server serves directly,
     * under the name the client chose. A .php file placed there is EXECUTED
     * by PHP when someone requests its URL.
     */
    private function saveWithoutChecks(UploadedFile $file): array
    {
        return $this->moveToPublic($file, 'level1');
    }

    /**
     * INTENTIONALLY WEAK FILTER (Level 2).
     *
     * It checks getClientMimeType(), which is just the "Content-Type" header
     * the CLIENT wrote for that file part of the request. An attacker controls
     * it, so a .php file labelled "image/png" passes. The extension and the
     * real content are never looked at. Never trust client-supplied metadata.
     */
    private function saveWithContentTypeCheck(UploadedFile $file): array
    {
        $type = $file->getClientMimeType();

        if (!in_array($type, self::IMAGE_TYPES, true)) {
            return $this->failure("Only images are allowed. Your file was sent as \"{$type}\".");
        }

        return $this->moveToPublic($file, 'level2');
    }

    /**
     * SECURE.
     *  1. Allowlist (not blacklist) of what is accepted.
     *  2. The check uses the file's REAL content (finfo), not client headers.
     *  3. A size limit.
     *  4. The server picks a random name; the client's name is never used.
     *  5. The file is stored OUTSIDE public/, so it can never be executed
     *     or even requested by URL.
     */
    private function saveSecurely(UploadedFile $file): array
    {
        $validator = Validator::make(['upload' => $file], [
            'upload' => [
                'required',
                'file',
                'max:2048', // kilobytes
                'mimes:jpg,jpeg,png,gif',
                'mimetypes:image/jpeg,image/png,image/gif',
            ],
        ]);

        if ($validator->fails()) {
            return $this->failure($validator->errors()->first('upload'));
        }

        // hashName() = 40 random characters + an extension guessed from the content.
        $storedName = $file->hashName();

        Storage::disk('local')->putFileAs('lab-uploads', $file, $storedName);

        return [
            'ok' => true,
            'message' => 'File accepted and stored safely.',
            'name' => $storedName,
            'location' => 'storage/app/private/lab-uploads/' . $storedName,
            'url' => null, // not reachable from the web
        ];
    }

    private function moveToPublic(UploadedFile $file, string $folder): array
    {
        // Symfony already strips any directory part, so only the file name remains.
        $name = $file->getClientOriginalName();

        if ($name === '') {
            return $this->failure('The file has no name.');
        }

        $directory = public_path('uploads/' . $folder);
        File::ensureDirectoryExists($directory);

        $file->move($directory, $name);

        return [
            'ok' => true,
            'message' => 'File uploaded.',
            'name' => $name,
            'location' => 'public/uploads/' . $folder . '/' . $name,
            'url' => url('uploads/' . $folder . '/' . rawurlencode($name)),
        ];
    }

    private function failure(string $message): array
    {
        return ['ok' => false, 'message' => $message];
    }
}
