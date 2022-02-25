@if(session()->has('success'))
    <div class="alert alert-success">
        <p class="text-success">{{ session()->get('success') }}</p>
    </div>
@endif

@if(session()->has('warning'))
    <div class="alert alert-warning">
        <p class="text-warning">{{ session()->get('warning') }}</p>
    </div>
@endif

@if(session()->has('danger'))
    <div class="alert alert-danger">
        <p class="text-danger">{{ session()->get('danger') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-warning">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif