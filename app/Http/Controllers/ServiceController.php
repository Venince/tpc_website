<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceAttachment;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function show(Service $service)
    {
        // 404 if inactive
        abort_unless($service->is_active, 404);

        $service->load(['contents', 'attachments', 'achievements.images']);

        return view('public.services.show', compact('service'));
    }

    /**
     * Save the file to the visitor's device (forces a download).
     */
    public function downloadAttachment(Service $service, ServiceAttachment $attachment)
    {
        $this->guardAttachment($service, $attachment);

        return $this->publicDisk()->download(
            $attachment->file_path,
            $attachment->download_name,
            ['X-Content-Type-Options' => 'nosniff']
        );
    }

    /**
     * Open the file inline in the browser's viewer so it can be read or printed.
     * Only PDFs and images are shown inline; anything else becomes a download.
     */
    public function viewAttachment(Service $service, ServiceAttachment $attachment)
    {
        $this->guardAttachment($service, $attachment);

        if (!$attachment->isPrintable()) {
            return redirect()->route('services.attachments.download', [$service, $attachment]);
        }

        return $this->publicDisk()->response(
            $attachment->file_path,
            $attachment->download_name,
            [
                'Content-Type'           => $attachment->inlineMimeType(),
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }

    /**
     * The service must be live, the file must belong to it, and it must exist on disk.
     */
    private function guardAttachment(Service $service, ServiceAttachment $attachment): void
    {
        abort_unless($service->is_active, 404);
        abort_unless($attachment->service_id === $service->id, 404);
        abort_unless($this->publicDisk()->exists($attachment->file_path), 404);
    }

    /**
     * The "public" disk. Typed as FilesystemAdapter so editors know about
     * download() and response(), which the generic Filesystem contract lacks.
     */
    private function publicDisk(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk;
    }
}
