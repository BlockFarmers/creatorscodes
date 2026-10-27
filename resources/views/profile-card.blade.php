@if(session('success'))
    <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
@endif

@if($creatorSupport && $creatorSupport->creatorCode)
    <p class="mb-2">
        {!! trans('creatorscodes::messages.support.current_short', [
            'creator' => '<strong>'.e($creatorSupport->creatorCode->creator->name ?? trans('creatorscodes::messages.support.this_creator')).'</strong>',
            'code' => '<strong>'.e($creatorSupport->creatorCode->code).'</strong>',
        ]) !!}
    </p>
    <form action="{{ route('creatorscodes.support.destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-x-lg"></i> {{ trans('creatorscodes::messages.support.withdraw') }}
        </button>
    </form>
@else
    <form action="{{ route('creatorscodes.support.update') }}" method="POST">
        @csrf

        <div class="input-group mb-3 @error('code') has-validation @enderror">
            <input type="text" class="form-control @error('code') is-invalid @enderror" id="creator_code" name="code"
                   value="{{ old('code') }}" placeholder="{{ trans('creatorscodes::messages.support.placeholder') }}" required>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> {{ trans('creatorscodes::messages.support.confirm') }}
            </button>

            @error('code')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </form>
@endif
