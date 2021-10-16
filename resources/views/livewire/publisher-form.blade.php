<div>
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}:</label>
            <input id="name" type="website" wire:model="publisherName" class="form-control @error('publisherName') is-invalid @enderror" />
            @error('name')
            <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
