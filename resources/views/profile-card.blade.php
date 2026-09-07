@if(session('success'))
    <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
@endif

@if($creatorSupport && $creatorSupport->creatorCode)
    <p class="mb-2">
        You support <strong>{{ $creatorSupport->creatorCode->creator->name ?? 'this creator' }}</strong>
        with the code <strong>{{ $creatorSupport->creatorCode->code }}</strong>.
    </p>
    <form action="{{ route('creatorcodes.support.destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-x-lg"></i> Withdraw my support
        </button>
    </form>
@else
    <form action="{{ route('creatorcodes.support.update') }}" method="POST">
        @csrf

        <div class="input-group mb-3 @error('code') has-validation @enderror">
            <input type="text" class="form-control @error('code') is-invalid @enderror" id="creator_code" name="code"
                   value="{{ old('code') }}" placeholder="Ex: GUIGUI10" required>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Confirm
            </button>

            @error('code')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </form>
@endif
