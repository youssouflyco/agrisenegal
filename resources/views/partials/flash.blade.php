@if(session('success'))
    <div class="mb-6 rounded-xl border border-agri-light/40 bg-agri-light/10 px-4 py-3 text-agri-primary" role="alert">
        {{ session('success') }}
    </div>
@endif
@if(session('status'))
    <div class="mb-6 rounded-xl border border-agri-light/40 bg-agri-light/10 px-4 py-3 text-agri-primary" role="alert">
        {{ session('status') }}
    </div>
@endif
@if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700" role="alert">
        <ul class="list-inside list-disc text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
