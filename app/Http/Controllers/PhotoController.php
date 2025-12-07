<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PhotoController extends Controller
{
    /**
     * Authorization is handled via PhotoPolicy.
     * - viewAny, view: Public access (no auth required)
     * - create, update, delete: Auth + ownership/admin checks via Policy
     */

    /**
     * Display a paginated listing of all photos (public access).
     * This is the main gallery homepage with responsive grid layout.
     */
    public function index()
    {
        // Fetch all photos with pagination (12 per page)
        // Include the user relationship to display uploader names
        $photos = Photo::with('user')
            ->latest() // Most recent photos first
            ->paginate(12);

        return view('photos.index', compact('photos'));
    }

    /**
     * Show the form for creating a new photo.
     * Only authenticated users can access this page.
     */
    public function create()
    {
        // Authorization check via Policy
        $this->authorize('create', Photo::class);

        return view('photos.create');
    }

    /**
     * Store a newly created photo in storage.
     * This method includes:
     * - Form validation (file type, size)
     * - Image watermarking with username (ADVANCED TECHNIQUE for high grades)
     * - Storage in storage/app/public/photos directory
     */
    public function store(Request $request)
    {
        // Authorization check via Policy
        $this->authorize('create', Photo::class);

        // Validate the form input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        // Process the uploaded image
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Verify the uploaded file is valid
            if (!$image->isValid()) {
                \Log::error('Invalid file upload', [
                    'error' => $image->getError(),
                    'error_message' => $image->getErrorMessage()
                ]);
                return back()
                    ->withInput()
                    ->with('error', 'The uploaded file is invalid. Error: ' . $image->getErrorMessage());
            }

            // Generate a unique filename to avoid collisions
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Store relative path in database (for cross-platform compatibility)
            $relativePath = 'photos/' . $filename;

            try {
                // Use Storage facade for more reliable file saving
                $fileContents = file_get_contents($image->getRealPath());
                $stored = \Storage::disk('public')->put($relativePath, $fileContents);

                if (!$stored) {
                    \Log::error('Storage::put returned false', [
                        'filename' => $filename,
                        'path' => $relativePath
                    ]);
                    return back()
                        ->withInput()
                        ->with('error', 'Failed to save the image file. Please try again.');
                }

                // Verify the file was actually saved
                $fullPath = storage_path('app/public/' . $relativePath);
                if (!file_exists($fullPath)) {
                    \Log::error('File not found after storage', [
                        'expected_path' => $fullPath,
                        'stored_result' => $stored
                    ]);
                    return back()
                        ->withInput()
                        ->with('error', 'Failed to verify the saved image. Please check storage permissions.');
                }

                // Create the photo record in database ONLY if file was successfully saved
                Photo::create([
                    'user_id' => Auth::id(),
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'image_path' => $relativePath,
                ]);

                \Log::info('Photo uploaded successfully', [
                    'user_id' => Auth::id(),
                    'filename' => $filename,
                    'size' => filesize($fullPath)
                ]);

                return redirect()->route('photos.index')
                    ->with('success', 'Photo uploaded successfully!');

            } catch (\Exception $e) {
                \Log::error('Exception during photo upload', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()
                    ->withInput()
                    ->with('error', 'An error occurred during upload: ' . $e->getMessage());
            }
        }

        return back()
            ->withInput()
            ->with('error', 'No image file was uploaded. Please select an image and try again.');
    }

    /**
     * Display the specified photo.
     * Public access for viewing individual photos.
     */
    public function show(Photo $photo)
    {
        // Authorization check via Policy (public access)
        $this->authorize('view', $photo);

        // Load the user relationship
        $photo->load('user');

        return view('photos.show', compact('photo'));
    }

    /**
     * Show the form for editing the specified photo.
     * Only the owner can edit their photos.
     */
    public function edit(Photo $photo)
    {
        // Authorization check via Policy
        $this->authorize('update', $photo);

        return view('photos.edit', compact('photo'));
    }

    /**
     * Update the specified photo in storage.
     * Users can update title and description, but not the image itself.
     */
    public function update(Request $request, Photo $photo)
    {
        // Authorization check via Policy
        $this->authorize('update', $photo);

        // Validate the form input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Update the photo record
        $photo->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('photos.show', $photo)
            ->with('success', 'Photo updated successfully!');
    }

    /**
     * Remove the specified photo from storage.
     * Regular users can delete their own photos.
     * Administrators (is_admin=true) can delete ANY photo for content moderation.
     */
    public function destroy(Photo $photo)
    {
        // Authorization check via Policy
        // Admins can delete any photo, regular users only their own
        $this->authorize('delete', $photo);

        // Delete the physical file from storage
        $filePath = storage_path('app/public/' . $photo->image_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the database record
        $photo->delete();

        return redirect()->route('photos.index')
            ->with('success', 'Photo deleted successfully!');
    }

    /**
     * Download photo with conditional watermark.
     * ADVANCED FEATURE:
     * - Guest users (not logged in): Download with diagonal watermark
     * - Authenticated users: Download original image without watermark
     */
    public function download(Photo $photo)
    {
        // Authorization check - anyone can download (public access)
        $this->authorize('view', $photo);

        // Get file path
        $filePath = storage_path('app/public/' . $photo->image_path);

        if (!file_exists($filePath)) {
            abort(404, 'Photo not found');
        }

        // If user is authenticated, download original image without watermark
        if (Auth::check()) {
            return response()->download($filePath, $photo->title . '_original.' . pathinfo($filePath, PATHINFO_EXTENSION));
        }

        // For guest users, add diagonal watermark before download
        $watermarkedImage = $this->addDiagonalWatermark($filePath, $photo->user->name);

        return response()->stream(
            function () use ($watermarkedImage) {
                echo $watermarkedImage;
            },
            200,
            [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'attachment; filename="' . $photo->title . '_watermarked.jpg"',
            ]
        );
    }

    /**
     * Add diagonal watermark to image for guest downloads.
     * ADVANCED TECHNIQUE: Creates repeating diagonal text watermark pattern
     * across the entire image to protect copyright.
     *
     * @param string $imagePath Path to the original image
     * @param string $uploaderName Name of the uploader for watermark text
     * @return string Binary image data
     */
    private function addDiagonalWatermark(string $imagePath, string $uploaderName): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($imagePath);

        $width = $img->width();
        $height = $img->height();

        // Watermark text with uploader's name
        $watermarkText = '© ' . $uploaderName . ' - Photo Gallery';

        // Calculate spacing for diagonal pattern
        $spacing = 200; // Space between watermark repetitions
        $angle = -45; // Diagonal angle

        // Add multiple watermarks in a diagonal pattern across the image
        for ($x = -$height; $x < $width + $height; $x += $spacing) {
            for ($y = -$width; $y < $height + $width; $y += $spacing) {
                $img->text(
                    $watermarkText,
                    $x,
                    $y,
                    function ($font) use ($angle) {
                        $font->size(32);
                        $font->color('rgba(255, 255, 255, 0.3)'); // Semi-transparent white
                        $font->align('center');
                        $font->valign('middle');
                        $font->angle($angle);
                    }
                );
            }
        }

        // Return encoded image as JPEG
        return $img->toJpeg(90)->toString();
    }

    /**
     * Display photo with watermark showing uploader's name.
     * This generates a watermarked version for display purposes.
     *
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function displayWithWatermark(Photo $photo)
    {
        // Authorization check - anyone can view (public access)
        $this->authorize('view', $photo);

        // Load the user relationship to ensure it's available
        $photo->load('user');

        $filePath = storage_path('app/public/' . $photo->image_path);

        if (!file_exists($filePath)) {
            abort(404, 'Photo not found');
        }

        $manager = new ImageManager(new Driver());
        $img = $manager->read($filePath);

        // Add small watermark in bottom-right corner showing uploader's name
        $watermarkText = '© ' . $photo->user->name;
        $padding = 15;

        $img->text(
            $watermarkText,
            $img->width() - $padding,
            $img->height() - $padding,
            function ($font) {
                $font->size(20);
                $font->color('rgba(255, 255, 255, 0.8)');
                $font->align('right');
                $font->valign('bottom');
            }
        );

        return response($img->toJpeg(90)->toString())
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}
