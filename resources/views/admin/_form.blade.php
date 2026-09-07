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
    <label class="form-label">Creator</label>
    <select name="user_id" class="form-select" required>
        <option value="">-- Select a user --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                @selected(old('user_id', $creatorCode->user_id ?? null) == $user->id)>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Code</label>
    <input type="text" name="code" class="form-control"
           value="{{ old('code', $creatorCode->code ?? '') }}" placeholder="Ex: GUIGUI10" required>
</div>

<div class="mb-3">
    <label class="form-label">Commission rate (%)</label>
    <input type="number" step="0.01" min="0" max="100" name="commission_rate" class="form-control"
           value="{{ old('commission_rate', $creatorCode->commission_rate ?? 5) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">PayPal email (for automatic payment)</label>
    <input type="email" name="paypal_email" class="form-control @error('paypal_email') is-invalid @enderror"
           value="{{ old('paypal_email', $creatorCode->paypal_email ?? '') }}" placeholder="creator@paypal.com">
    @error('paypal_email')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
    <div class="form-text">Leave blank if you prefer to pay manually (bank transfer, cash, etc.).</div>
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="active" value="1" class="form-check-input" id="active"
           @checked(old('active', $creatorCode->active ?? true))>
    <label class="form-check-label" for="active">Active code</label>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ route('creatorcodes.admin.index') }}" class="btn btn-outline-secondary">Cancel</a>
