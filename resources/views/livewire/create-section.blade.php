<form wire:submit.prevent='saveSection'>
    <div class='modal-header'>
        <h5>
            @if ($isEditing)Edit section @else Add section @endif
        </h5>
        <button wire:click='close' type='button' class='close'>
            <span aria-hidden='true'>x</span>
        </button>
    </div>
    <div class='modal-body form-row'>
        <div class='form-group col-12'>
            <label for='movement'>Movement:</label>
            <select id='movement' wire:model='section.movement' class='form-control @error(' section.movement')
                is-invalid @enderror'>
                <option value=''> -- movement -- </option>
                @foreach ($movements as $movement)
                    <option value='{{ $movement->id }}'>{{ $movement->number . '. ' . $movement->title }}</option>
                @endforeach
            </select>
            @error('section.movement')
                <span class='invalid-feedback' role='alert'>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class='form-group col-12'>
            <label for='tempo_text'>Tempo Text:</label>
            <input id='tempo_text' wire:model='section.tempo_text' class='form-control' autofocus />
        </div>
        <div class='form-group col-12 col-sm-6'>
            <label for='mm_note' class='form-control-label'>Metronome Mark</label>
            <select id='mm_note' wire:model='section.mm_note' class='form-control @error(' section.mm_note') is-invalid
                @enderror'>
                <option value=''> -- note type -- </option>
                <option value='1'>Whole Note</option>
                <option value='2'>Half Note</option>
                <option value='4'>Quarter Note</option>
                <option value='8'>Eighth Note</option>
                <option value='16'>Sixteenth Note</option>
                <option value='32'>32nd Note</option>
                <option value='64'>64th Note</option>
                <option value='128'>128th Note</option>
            </select>
            @error('section.mm_note')
                <span class='invalid-feedback' role='alert'>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class='form-check my-2 text-center'>
                <input class='form-check-input' id='mm_note_dotted' type='checkbox'
                    wire:model='section.mm_note_dotted' />
                <label class='form-check-label' for='mm_note_dotted'>dotted?</label>
            </div>
        </div>
        <div class='form-group col-12 col-sm-6'>
            <label for='bpm' class='my-1'>BPM</label>
            <input id='bpm' wire:model='section.bpm' class='form-control @error(' section.bpm') is-invalid @enderror'
                placeholder="e.g. 120" />
            @error('section.bpm')
                <span class='invalid-feedback' role='alert'>
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class='form-group form-inline col-12 col-sm-4'>
            <label for='structural_note'>Fastest Structural</label>
            <select id='structural_note' wire:model='section.structural_note' class='form-control'>
                <option value=''> -- </option>
                <option value='1'>Whole Note</option>
                <option value='2'>Half Note</option>
                <option value='4'>Quarter Note</option>
                <option value='8'>Eighth Note</option>
                <option value='16'>Sixteenth Note</option>
                <option value='32'>32nd Note</option>
                <option value='64'>64th Note</option>
                <option value='128'>128th Note</option>
            </select>
            <div class='form-check my-2 text-center col'>
                <input class='form-check-input' id='structural_note_dotted' type='checkbox'
                    wire:model='section.structural_note_dotted' />
                <label class='form-check-label' for='structural_note_dotted'>dotted?</label>
            </div>
        </div>
        <div class='form-group form-inline col-12 col-sm-4'>
            <label for='staccato_note'>Fastest staccato</label>
            <select id='staccato_note' wire:model='section.staccato_note' class='form-control'>
                <option value=''> -- </option>
                <option value='1'>Whole Note</option>
                <option value='2'>Half Note</option>
                <option value='4'>Quarter Note</option>
                <option value='8'>Eighth Note</option>
                <option value='16'>Sixteenth Note</option>
                <option value='32'>32nd Note</option>
                <option value='64'>64th Note</option>
                <option value='128'>128th Note</option>
            </select>
            <div class='form-check my-2 text-center col'>
                <input class='form-check-input' id='staccato_note_dotted' type='checkbox'
                    wire:model='section.staccato_note_dotted' />
                <label class='form-check-label' for='staccato_note_dotted'>dotted?</label>
            </div>
        </div>
        <div class='form-group form-inline col-12 col-sm-4'>
            <label for='ornamental_note'>Fastest Ornamental</label>
            <select id='ornamental_note' wire:model='section.ornamental_note' class='form-control'>
                <option value=''> -- </option>
                <option value='1'>Whole Note</option>
                <option value='2'>Half Note</option>
                <option value='4'>Quarter Note</option>
                <option value='8'>Eighth Note</option>
                <option value='16'>Sixteenth Note</option>
                <option value='32'>32nd Note</option>
                <option value='64'>64th Note</option>
                <option value='128'>128th Note</option>
            </select>
            <div class='form-check my-2 text-center col'>
                <input class='form-check-input' id='ornamental_note_dotted' type='checkbox'
                    wire:model='section.ornamental_note_dotted' />
                <label class='form-check-label' for='ornamental_note_dotted'>dotted?</label>
            </div>
        </div>
        <button type='submit' class='btn btn-primary'>Save</button>
        <button wire:click.prevent='close' type='button' class='btn btn-secondary ml-auto'>Cancel</button>
    </div>
</form>
