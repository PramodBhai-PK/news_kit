<?php

namespace App\Http\Controllers;

use App\Models\CitizenTip;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CitizenTipController extends Controller
{
    /**
     * Show the public Citizen News Tip / Whistleblower submission form
     */
    public function create()
    {
        $states = State::with('cities')->orderBy('name')->get();
        return view('portal.citizen-tip', compact('states'));
    }

    /**
     * Store submitted news tip from citizen reporter
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'witness_name' => ['nullable', 'string', 'max:150'],
            'witness_phone' => ['nullable', 'string', 'max:50'],
            'witness_email' => ['nullable', 'email', 'max:150'],
            'is_anonymous' => ['nullable', 'boolean'],
            'media_file' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,mp4,webm,mov,pdf', 'max:25600'], // max 25MB
        ]);

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $mime = $file->getMimeType();
            $storedPath = $file->store('uploads/citizen_tips', 'public');
            $mediaPath = Storage::url($storedPath);

            if (str_starts_with($mime, 'image/')) {
                $mediaType = 'image';
            } elseif (str_starts_with($mime, 'video/')) {
                $mediaType = 'video';
            } else {
                $mediaType = 'document';
            }
        }

        $tip = CitizenTip::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location_name' => $validated['location_name'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'witness_name' => $request->boolean('is_anonymous') ? 'गोपनीय नागरिक' : ($validated['witness_name'] ?? 'नागरिक पत्रकार'),
            'witness_phone' => $validated['witness_phone'] ?? null,
            'witness_email' => $validated['witness_email'] ?? null,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('portal.citizen-tip')
            ->with('success', 'धन्यवाद! आपकी न्यूज़ टिप हमारे संपादकीय डेस्क को सुरक्षित रूप से प्राप्त हो गई है। (संदर्भ संख्या: #' . $tip->id . ')');
    }
}
