<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;
use App\Enums\UserRole;
use App\Constants\UserRoleConstants;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BusinessProfileController extends Controller
{
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
     * Submit or update a business profile.
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

                    $validated[$file] = $request
                        ->file($file)
                        ->store('business-profiles', 'public');
                }
            }

            $validated['user_id'] = $user->id;
            $validated['verification_status'] = 'PENDING';
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
     * Admin: list pending business profiles.
     */
    public function pending(): View
    {
        $profiles = BusinessProfile::query()
            ->with('user')
            ->where('verification_status', 'PENDING')
            ->latest()
            ->paginate(20);

        return view('admin.business-profiles.pending', compact('profiles'));
    }

    /**
     * Admin: show a business profile for review.
     */
    public function review(BusinessProfile $businessProfile): View
    {
        $businessProfile->load('user');

        return view(
            'admin.business-profiles.review',
            compact('businessProfile')
        );
    }

    /**
     * Admin: approve a business profile.
     */
    public function approve(
        BusinessProfile $businessProfile
    ): RedirectResponse {
        if ($businessProfile->verification_status !== 'PENDING') {
            return back()->with(
                'error',
                'Cette demande a déjà été traitée.'
            );
        }

        $businessProfile->update([
            'verification_status' => 'APPROVED',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('admin.business-profiles.pending')
            ->with(
                'success',
                'Le profil a été approuvé avec succès.'
            );
    }

    /**
     * Admin: reject a business profile.
     */
    public function reject(
        Request $request,
        BusinessProfile $businessProfile
    ): RedirectResponse {
        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        if ($businessProfile->verification_status !== 'PENDING') {
            return back()->with(
                'error',
                'Cette demande a déjà été traitée.'
            );
        }

        $businessProfile->update([
            'verification_status' => 'REJECTED',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_at' => null,
        ]);

        return redirect()
            ->route('admin.business-profiles.pending')
            ->with(
                'success',
                'Le profil a été rejeté.'
            );
    }
}
