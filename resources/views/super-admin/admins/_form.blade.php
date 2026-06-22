@php $admin = $admin ?? null; @endphp

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium">Prénom *</label>
        <input type="text" name="first_name" value="{{ old('first_name', $admin?->first_name) }}" required
               class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Nom *</label>
        <input type="text" name="last_name" value="{{ old('last_name', $admin?->last_name) }}" required
               class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
    </div>
</div>

<div>
    <label class="mb-1 block text-sm font-medium">Email *</label>
    <input type="email" name="email" value="{{ old('email', $admin?->email) }}" required
           class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
</div>

<div>
    <label class="mb-1 block text-sm font-medium">Téléphone *</label>
    <input type="text" name="phone" value="{{ old('phone', $admin?->phone) }}" required placeholder="+221 77 551 12 59"
           class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
</div>

<div>
    <label class="mb-1 block text-sm font-medium">Photo</label>
    <input type="file" name="photo" accept="image/*"
           class="w-full rounded-xl border border-dashed border-soft-gray px-4 py-3 file:mr-4 file:rounded-lg file:border-0 file:bg-agri-primary file:px-4 file:py-2 file:text-white">
    @if($admin?->photo)
        <img src="{{ $admin->photo_url }}" alt="" class="mt-2 h-16 w-16 rounded-full object-cover">
    @endif
</div>

@if(!$admin)
    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium">Mot de passe *</label>
            <input type="password" name="password" required minlength="8"
                   class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Confirmer *</label>
            <input type="password" name="password_confirmation" required
                   class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
        </div>
    </div>
@endif
