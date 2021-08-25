<div>
    <form wire:submit.prevent='save' method='POST'>
        @csrf
        <div class='form-group row'>
            <label for='composer' class='col-md-4 col-form-label text-md-right'>{{ __('Composer') }}</label>
            <div class='col-md-6'>
                <select wire:model='composer' id='composer' class='form-control' required autocomplete='composer' autofocus>
                    <option value=''>-- select a composer --</option>
                    @foreach ($composers as $composer)
                        <option value='{{ $composer->id }}'>{{ $composer->name }}</option>
                    @endforeach
                </select>

                @error('composer')
                    <span class='invalid-feedback' role='alert'>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class='form-group row'>
            <label for='piece' class='col-md-4 col-form-label text-md-right'>{{ __('Piece') }}</label>
            <div class='col-md-6'>
                <select wire:model='piece' id='piece' class='form-control' required autocomplete='piece'>
                    @if ($pieces->count() == 0)
                        <option value=''>-- select a composer first --</option>
                    @endif
                    @foreach ($pieces as $piece)
                        <option value='{{ $piece->id }}'>{{ $piece->title }}</option>
                    @endforeach
                </select>

                @error('piece')
                    <span class='invalid-feedback' role='alert'>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class='form-group row'>
            <label for='year_published' class='col-md-4 col-form-label text-md-right'>{{ __('Year published (optional):') }}</label>
            <div class='col-md-6'>
                <input id='year_published' type='number' wire:model='year_published' class='form-control @error('year_published') is-invalid @enderror' placeholder='e.g. 1792' />
                @error('year_published')
                    <span class='invalid-feedback' role='alert'>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class='form-group row'>
            <label for='publisher' class='col-md-4 col-form-label text-md-right'>{{ __('Publisher (optional)') }}</label>
            <div class='col-md-6'>
                <select wire:model='publisher' id='publisher' class='form-control'>
                    <option value=''></option>
                    @foreach ($publishers as $publisher)
                        <option value='{{ $publisher->id }}'>{{ $publisher->title }}</option>
                    @endforeach
                </select>

                @error('publisher')
                    <span class='invalid-feedback' role='alert'>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class='form-group row'>
            <label for='editor' class='col-md-4 col-form-label text-md-right'>{{ __('Editor (optional)') }}</label>
            <div class='col-md-6'>
                <select wire:model='editor' id='editor' class='form-control' autocomplete='editor' >
                    <option value=''></option>
                    @foreach ($editors as $editor)
                        <option value='{{ $editor->id }}'>{{ $editor->title }}</option>
                    @endforeach
                </select>

                @error('editor')
                    <span class='invalid-feedback' role='alert'>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class='form-group row'>
            <label for='link' class='col-md-4 col-form-label text-md-right'>{{ __('Link (optional):') }}</label>
            <div class='col-md-6'>
                <input id='link' type='website' wire:model='link' class='form-control @error('link') is-invalid @enderror' placeholder='http://...' />
                @error('link')
                    <span class='invalid-feedback' role='alert'>
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class='form-group'>
            <h2>Sections</h2>
            <table class='table'>
                <thead>
                    <tr>
                        <th>Movement</th>
                        <th>Time Signature</th>
                        <th>Tempo Text</th>
                        <th>MM</th>
                        <th>Structural Note</th>
                        <th>Stacatto Note</th>
                        <th>Ornamental Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $index => $section)
                        @php
                            $section_movement =  $movements->where('id', $section['movement'])->first();                            
                            $time_signature = $section_movement->timeSignature;
                        @endphp                            
                    <tr>
                        <td>{{ $section_movement->number }}</td>
                        
                        <td>{{ $time_signature->count . ($time_signature->note ? '/' . $time_signature->note : '') }}</td>
                        <td>{{ $section['tempo_text'] }}</td>
                        <td>{{ $section['mm_note'] . ($section['mm_note_dotted'] ? '' : '') . ' = ' . $section['bpm'] }}</td>
                        <td>{{ $section['structural_note'] . ($section['structural_note_dotted'] ? '&bull;' : '') }}</td>
                        <td>{{ $section['stacatto_note'] . ($section['stacatto_note_dotted'] ? '&bull;' : '') }}</td>
                        <td>{{ $section['ornamental_note'] . ($section['ornamental_note_dotted'] ? '&bull;' : '') }}</td>
                        <td>
                            <button wire:click.prevent='editSection({{ $index }})'>Edit</button>
                            <button wire:click.prevent='removeSection({{ $index }})'>Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan='7' class='text-center'>No sections added</td>
                    </tr>
                    @endforelse
            </table>
            @isset($piece)
            <div class='row'>
                <button wire:click.prevent='addSection')' type='button' class='mr-auto'>Add Section</button>
                <button type='submit' class='ml-auto'>Save</button>
            </div>
            @endisset

            @if($errors)
                @foreach($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            @endif
        </div>
    </form>

    <div class='modal bg-dark' @if($showModal) style='display: block' @endif data-backdrop='true' role='dialog'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <form wire:submit.prevent='saveSection'>
                    <div class='modal-header'>
                        <h5>@if($isEditing)Edit section @else Add section @endif</h5>
                        <button wire:click='close' type='button' class='close'>
                            <span aria-hidden='true'>x</span>
                        </button>
                    </div>
                    <div class='modal-body form-row'>
                        <div class='form-group col-12'>
                            <label for='movement'>Movement:</label>
                            <select id='movement' wire:model='section.movement' class='form-control @error('section.movement') is-invalid @enderror'>
                                <option value=''> -- movement -- </option>
                                @foreach ($movements as $movement)
                                    <option value='{{ $movement->id }}'>{{ $movement->number . '. ' . $movement->title }}</option>
                                @endforeach
                            </select>
                            @error('section.movement')
                            <span class='invalid-feedback' role='alert'>
                                <strong>{{  $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class='form-group col-12'>
                            <label for='tempo_text'>Tempo Text:</label>
                            <input id='tempo_text' wire:model='section.tempo_text' class='form-control' autofocus />
                        </div>
                        <div class='form-group col-12 col-sm-6'>
                            <label for='mm_note' class='form-control-label'>Metronome Mark</label>
                            <select id='mm_note' wire:model='section.mm_note' class='form-control @error('section.mm_note') is-invalid @enderror'>
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
                                <strong>{{  $message }}</strong>
                            </span>
                            @enderror
                            <div class='form-check my-2 text-center'>
                                <input class='form-check-input' id='mm_note_dotted' type='checkbox' wire:model='section.mm_note_dotted' />
                                <label class='form-check-label' for='mm_note_dotted'>dotted?</label>
                            </div>                            
                        </div>
                        <div class='form-group col-12 col-sm-6'>
                            <label for='bpm' class='my-1'>BPM</label>
                            <input id='bpm' wire:model='section.bpm' class='form-control @error('section.bpm') is-invalid @enderror' placeholder="e.g. 120" />
                            @error('section.bpm')
                            <span class='invalid-feedback' role='alert'>
                                <strong>{{  $message }}</strong>
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
                                <input class='form-check-input' id='structural_note_dotted' type='checkbox' wire:model='section.structural_note_dotted' />
                                <label class='form-check-label' for='structural_note_dotted'>dotted?</label>
                            </div>
                        </div>
                        <div class='form-group form-inline col-12 col-sm-4'>
                            <label for='stacatto_note'>Fastest Stacatto</label>
                            <select id='stacatto_note' wire:model='section.stacatto_note' class='form-control'>
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
                                <input class='form-check-input' id='stacatto_note_dotted' type='checkbox' wire:model='section.stacatto_note_dotted' />
                                <label class='form-check-label' for='stacatto_note_dotted'>dotted?</label>
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
                                <input class='form-check-input' id='ornamental_note_dotted' type='checkbox' wire:model='section.ornamental_note_dotted' />
                                <label class='form-check-label' for='ornamental_note_dotted'>dotted?</label>
                            </div>
                        </div>
                        <button type='submit' class='btn btn-primary'>Save</button>
                        <button wire:click.prevent='close' type='button' class='btn btn-secondary ml-auto'>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
