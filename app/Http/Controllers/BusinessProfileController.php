<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;
use App\Enums\UserRole;
use App\Constants\UserRoleConstants;
use Illuminate\Http\RedirectResponse;
use App\Services\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BusinessProfileController extends Controller
{
    public function __construct(private FileUploader $fileUploader) {}

    /**
     * Show the business profile form.
     */
    public function create(): View
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, array_values(UserRoleConstants::BUSINESS_PROFILE_ROLES)),
            403
        );

        $profile = $user->businessProfile;

        return view('business-profiles.create', compact('profile'));
    }

    /**
     * Submit a business profile.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, array_values(UserRoleConstants::BUSINESS_PROFILE_ROLES)),
            403
        );

        // Don't allow modification while waiting for admin approval.
        if (
            $user->businessProfile &&
            $user->businessProfile->verification_status === 'PENDING'
        ) {
            return back()->with(
                'error',
                'Votre demande est déjà en cours de vérification.'
            );
        }

    $rules = [
    'cni' => ['required', 'string', 'max:50'],
    'cni_front_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    'cni_back_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ];

if ($user->role === UserRole::Producer) {
    $rules += [
        'farm_name' => ['required', 'string', 'max:255'],
        'production_type' => ['required', 'string', 'max:255'],
        'farm_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ];
}

if ($user->role === UserRole::Distributor) {
    $rules += [
        'business_name' => ['required', 'string', 'max:255'],
        'business_type' => ['required', 'string', 'max:255'],
        'business_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
    ];
}

$validated = $request->validate($rules);

DB::transaction(function () use ($request, $validated, $user) {
        $profile = $user->businessProfile;

            $files = [
                'cni_front_photo',
                'cni_back_photo',
                'farm_photo',
                'business_photo',
            ];

            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    if ($profile?->{$file}) {
                        Storage::disk('public')->delete(
                            $profile->{$file}
                        );
                    }

                    $validated[$file] = $this->fileUploader->uploadFile(
                        $request->file($file)
                    );
                }
            }


            $validated['user_id'] = $user->id;
            $validated['status'] = 'PENDING';
            $validated['rejection_reason'] = null;
            $validated['approved_at'] = null;

            BusinessProfile::updateOrCreate(
                ['user_id' => $user->id],
                $validated
            );
        });

        return redirect()
            ->route('business-profile.show')
            ->with(
                'success',
                'Votre demande a été envoyée. Elle sera vérifiée par notre équipe.'
        );
    }

    /**
     * Show the current user's business profile and verification status.
     */
    public function show(): View
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, array_values(UserRoleConstants::BUSINESS_PROFILE_ROLES)),
            403
        );

        $profile = $user->businessProfile;

        return view('business-profiles.show', compact('profile'));
    }

    /**
     * Admin: list of business profiles.
     */
    public function index(Request $request, string $businessType): View
    {
       $profiles = BusinessProfile::query()
        ->where('business_type', $businessType)
        ->latest()
        ->paginate(20)
        ->withQueryString();

        return view('business-profiles.admin.index', [
        'title' => $businessType ?? ucfirst($businessType),
        'businessType' => $businessType,
        'profiles' => $profiles,
        ]);

    }

    /**
     * Admin: show a business profile for review.
     */
    public function review(string $id): View
    {
      $profile = BusinessProfile::with('user')->findOrFail($id);

      return view('business-profiles.admin.review', compact('profile'));
    }

    /**
     * Admin: approve a business profile.
     */
    public function approve(
        string $id
    ): RedirectResponse {
        $businessProfile = BusinessProfile::findOrFail($id);

        if ($businessProfile->status !== 'PENDING') {
            return back()->with(
                'error',
                'Cette demande a déjà été traitée.'
            );
        }

        $businessProfile->update([
            'status' => 'APPROVED',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('super-admin.business-profiles.review', $businessProfile->id)
            ->with(
                'success',
                'Le profil a été approuvé avec succès.'
            );
    }

    /**
     * Admin: reject a business profile.
     */
    public function reject(
        string $id,
        Request $request,
    ): RedirectResponse {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $businessProfile = BusinessProfile::findOrFail($id);

        if ($businessProfile->status !== 'PENDING') {
            return back()->with(
                'error',
                'Cette demande a déjà été traitée.'
            );
        }

        $businessProfile->update([
            'status' => 'REJECTED',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_at' => null,
        ]);

        return redirect()
            ->route('super-admin.business-profiles.review', $businessProfile->id)
            ->with(
                'success',
                'Le profil a été rejeté.'
            );
    }

    public function update(Request $request): RedirectResponse
    {
    $profile = $request->user()->businessProfile;

    abort_unless($profile->status === 'REJECTED', 403);

    $validated = $request->validate([
        'cni' => ['nullable', 'string', 'max:50'],
        'cni_front_photo' => ['nullable', 'image', 'max:5120'],
        'cni_back_photo' => ['nullable', 'image', 'max:5120'],
        'farm_name' => ['nullable', 'string', 'max:255'],
        'production_type' => ['nullable', 'string', 'max:255'],
        'farm_photo' => ['nullable', 'image', 'max:5120'],
        'business_name' => ['nullable', 'string', 'max:255'],
        'business_type' => ['nullable', 'string', 'max:255'],
        'business_photo' => ['nullable', 'image', 'max:5120'],
    ]);

    $files = [
        'cni_front_photo',
        'cni_back_photo',
        'farm_photo',
        'business_photo',
    ];

            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    if ($profile?->{$file}) {
                        Storage::disk('public')->delete(
                            $profile->{$file}
                        );
                    }

                    $validated[$file] = $this->fileUploader->uploadFile(
                        $request->file($file)
                    );

                }
        }

    $profile->update([
        'cni' => $validated['cni'] ?? $profile->cni,
        'farm_name' => $validated['farm_name'] ?? $profile->farm_name,
        'production_type' => $validated['production_type'] ?? $profile->production_type,
        'business_name' => $validated['business_name'] ?? $profile->business_name,
        'business_type' => $validated['business_type'] ?? $profile->business_type,
        'status' => 'PENDING',
        'rejection_reason' => null,
        'cni_front_photo' => $validated['cni_front_photo'] ?? $profile->cni_front_photo,
        'cni_back_photo' => $validated['cni_back_photo'] ?? $profile->cni_back_photo,
        'farm_photo' => $validated['farm_photo'] ?? $profile->farm_photo,
        'business_photo' => $validated['business_photo'] ?? $profile->business_photo,
    ]);

      return redirect()
        ->route('business-profile.show')
        ->with('success', 'Votre profil a été modifié et soumis à nouveau pour vérification.');
    }
}
