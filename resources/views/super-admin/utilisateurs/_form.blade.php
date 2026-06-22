<div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Nom</label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
           class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
           class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Rôle</label>
    <select name="role" required class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
        @foreach($roles as $role)
            <option value="{{ $role->value }}" @selected(old('role', ($user->role->value ?? null)) === $role->value)>
                {{ $role->label() }}
            </option>
        @endforeach
    </select>
    @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Mot de passe {{ isset($user) ? '(laisser vide pour ne pas changer)' : '' }}</label>
    <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
           class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Confirmation mot de passe</label>
    <input type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
           class="w-full rounded-xl border border-soft-gray px-4 py-3 focus:border-agri-primary focus:ring-2 focus:ring-agri-light/30">
</div>
