@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label class="form-label">{{ trans('creatorscodes::admin.form.creator') }}</label>
    <select name="user_id" class="form-select" required>
        <option value="">{{ trans('creatorscodes::admin.form.select_user') }}</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                @selected(old('user_id', $creatorCode->user_id ?? null) == $user->id)>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">{{ trans('creatorscodes::admin.form.code') }}</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $creatorCode->code ?? '') }}" placeholder="{{ trans('creatorscodes::admin.form.code_placeholder') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">{{ trans('creatorscodes::admin.form.commission_rate') }}</label>
    <input type="number" step="0.01" min="0" max="100" name="commission_rate" class="form-control"
           value="{{ old('commission_rate', $creatorCode->commission_rate ?? 5) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">{{ trans('creatorscodes::admin.form.paypal_email') }}</label>
    <input type="email" name="paypal_email" class="form-control @error('paypal_email') is-invalid @enderror"
           value="{{ old('paypal_email', $creatorCode->paypal_email ?? '') }}" placeholder="{{ trans('creatorscodes::admin.form.paypal_placeholder') }}">
    @error('paypal_email')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    <div class="form-text">{{ trans('creatorscodes::admin.form.paypal_help') }}</div>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="active" value="1" class="form-check-input" id="active"
           @checked(old('active', $creatorCode->active ?? true))>
    <label class="form-check-label" for="active">{{ trans('creatorscodes::admin.form.active') }}</label>
</div>

<button type="submit" class="btn btn-primary">{{ trans('creatorscodes::admin.actions.save') }}</button>
<a href="{{ route('creatorscodes.admin.index') }}" class="btn btn-outline-secondary">{{ trans('creatorscodes::admin.actions.cancel') }}</a>
