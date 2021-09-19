<div>
    <form wire:submit.prevent="save" method="POST">
        @csrf
        <div class="mb-3">
            <label for="composerName"" class="form-label">{{ __('Name') }}:</label>
            <input id="composerName"" type="website" wire:model="composerName"" class="form-control @error('composerName') is-invalid @enderror" />
            @error('composerName')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="born" class="form-label">{{ __('Born (optional)') }}:</label>
            <input id="born" type="website" wire:model="born" class="form-control @error('born') is-invalid @enderror" />
            @error('born')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="deceased" class="form-label">{{ __('Deceased (optional)') }}:</label>
            <input id="deceased" type="website" wire:model="deceased"
                class="form-control @error('deceased') is-invalid @enderror" />
            @error('deceased')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
