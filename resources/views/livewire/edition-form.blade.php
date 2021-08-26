<div>
    <form wire:submit.prevent="ave" method="POST">
        @csrf
        <div class="row g-2">
            <div class="col-md-6">
                <label for="composer" class="form-label">{{ __("Composer") }}</label>
                <select wire:model="composer" id="composer" class="form-select" required
                        autocomplete="composer" autofocus>
                    <option value="">-- select a composer --</option>
                    @foreach ($composers as $composer)
                        <option value="{{ $composer->id }}">{{ $composer->name }}</option>
                    @endforeach
                </select>

                @error("composer")
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="piece" class="form-label">{{ __("Piece") }}</label>
                <select wire:model="piece" id="piece" class="form-select" required autocomplete="piece">
                    @forelse ($pieces as $piece)
                        <option value="{{ $piece->id }}">{{ $piece->title }}</option>
                    @empty
                        <option value="">-- select a composer first --</option>
                    @endforelse
                </select>
                @error("piece")
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <label for="publisher" class="form-label">{{ __("Publisher (optional)") }}</label>
                <select wire:model="publisher" id="publisher" class="form-select">
                    <option value=""></option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->title }}</option>
                    @endforeach
                </select>
                @error("publisher")
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="editor" class="form-label">{{ __("Editor (optional)") }}</label>
                <select wire:model="editor" id="editor" class="form-select" autocomplete="editor">
                    <option value=""></option>
                    @foreach ($editors as $editor)
                        <option value="{{ $editor->id }}">{{ $editor->title }}</option>
                    @endforeach
                </select>
                @error("editor")
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="col-sm-auto">
                    <label for="year_published" class="form-label">{{ __("Year published (optional):") }}</label>
                    <input id="year_published" type="number" wire:model="year_published"
                           class="form-control @error("year_published") is-invalid @enderror" placeholder="e.g. 1792"/>
                    @error("year_published")
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-8">
                <label for="link" class="form-label">{{ __("Link (optional):") }}</label>
                <input id="link" type="website" wire:model="link"
                       class="form-control @error("link") is-invalid @enderror" placeholder="http://..."/>
                @error("link")
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <h2>Sections</h2>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Movement</th>
                <th scope="col">Time Signature</th>
                <th scope="col">Tempo Text</th>
                <th scope="col">MM</th>
                <th scope="col">Structural Note</th>
                <th scope="col">Staccato Note</th>
                <th scope="col">Ornamental Note</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($sections as $index => $section)
                @php
                    $section_movement =  $movements->where("id", $section["movement"])->first();
                    $time_signature = $section_movement->timeSignature;
                @endphp
                <tr>
                    <th scope="row">{{ $index }}</th>
                    <td>{{ $section_movement->number }}</td>
                    <td>{{ $time_signature->count . ($time_signature->note ? "/" . $time_signature->note : "") }}</td>
                    <td>{{ $section["tempo_text"] }}</td>
                    <td>{{ $section["mm_note"] . ($section["mm_note_dotted"] ? "" : "") . " = " . $section["bpm"] }}</td>
                    <td>{{ $section["structural_note"] . ($section["structural_note_dotted"] ? "&bull;" : "") }}</td>
                    <td>{{ $section["staccato_note"] . ($section["staccato_note_dotted"] ? "&bull;" : "") }}</td>
                    <td>{{ $section["ornamental_note"] . ($section["ornamental_note_dotted"] ? "&bull;" : "") }}</td>
                    <td>
                        <button wire:click.prevent="editSection({{ $index }})">Edit</button>
                        <button wire:click.prevent="removeSection({{ $index }})">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="" class="text-center">No sections added</td>
                </tr>
            @endforelse
        </table>
        @isset($piece)
            <div class="row">
                <button wire:click.prevent="addSection" type="button" data-bs-toggle="modal" data-bs-target="#section-modal">Add Section</button>
                <button type="submit">Save</button>
            </div>
        @endisset

        @if($errors)
            @foreach($errors->all() as $error)
                <span>{{ $error }}</span>
            @endforeach
        @endif
    </form>

    <div wire:ignore.self id="section-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="saveSection">
                    <div class="modal-header">
                        <h5>@if($isEditing)Edit section @else Add section @endif</h5>
                        <button wire:click="close" type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body form-row">
                        <div class="col-12">
                            <label for="movement">Movement:</label>
                            <select id="movement" wire:model="section.movement"
                                    class="form-control @error("section.movement") is-invalid @enderror">
                                <option value=""> -- movement --</option>
                                @foreach ($movements as $movement)
                                    <option
                                        value="{{ $movement->id }}">{{ $movement->number . ". " . $movement->title }}</option>
                                @endforeach
                            </select>
                            @error("section.movement")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{  $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="tempo_text">Tempo Text:</label>
                            <input id="tempo_text" wire:model="section.tempo_text" class="form-control" autofocus/>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="mm_note" class="form-control-label">Metronome Mark</label>
                            <select id="mm_note" wire:model="section.mm_note"
                                    class="form-control @error("section.mm_note") is-invalid @enderror">
                                <option value=""> -- note type --</option>
                                <option value="1">Whole Note</option>
                                <option value="2">Half Note</option>
                                <option value="4">Quarter Note</option>
                                <option value="8">Eighth Note</option>
                                <option value="16">Sixteenth Note</option>
                                <option value="32">32nd Note</option>
                                <option value="64">64th Note</option>
                                <option value="128">128th Note</option>
                            </select>
                            @error("section.mm_note")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{  $message }}</strong>
                            </span>
                            @enderror
                            <div class="form-check my-2 text-center">
                                <input class="form-check-input" id="mm_note_dotted" type="checkbox"
                                       wire:model="section.mm_note_dotted"/>
                                <label class="form-check-label" for="mm_note_dotted">dotted?</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="bpm" class="my-1">BPM</label>
                            <input id="bpm" wire:model="section.bpm"
                                   class="form-control @error("section.bpm") is-invalid @enderror"
                                   placeholder="e.g. 120"/>
                            @error("section.bpm")
                            <span class="invalid-feedback" role="alert">
                                <strong>{{  $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="structural_note">Fastest Structural</label>
                            <select id="structural_note" wire:model="section.structural_note" class="form-control">
                                <option value=""> --</option>
                                <option value="1">Whole Note</option>
                                <option value="2">Half Note</option>
                                <option value="4">Quarter Note</option>
                                <option value="8">Eighth Note</option>
                                <option value="16">Sixteenth Note</option>
                                <option value="32">32nd Note</option>
                                <option value="64">64th Note</option>
                                <option value="128">128th Note</option>
                            </select>
                            <div class="form-check my-2 text-center col">
                                <input class="form-check-input" id="structural_note_dotted" type="checkbox"
                                       wire:model="section.structural_note_dotted"/>
                                <label class="form-check-label" for="structural_note_dotted">dotted?</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="staccato_note">Fastest Staccato</label>
                            <select id="staccato_note" wire:model="section.staccato_note" class="form-control">
                                <option value=""> --</option>
                                <option value="1">Whole Note</option>
                                <option value="2">Half Note</option>
                                <option value="4">Quarter Note</option>
                                <option value="8">Eighth Note</option>
                                <option value="16">Sixteenth Note</option>
                                <option value="32">32nd Note</option>
                                <option value="64">64th Note</option>
                                <option value="128">128th Note</option>
                            </select>
                            <div class="form-check my-2 text-center col">
                                <input class="form-check-input" id="staccato_note_dotted" type="checkbox"
                                       wire:model="section.staccato_note_dotted"/>
                                <label class="form-check-label" for="staccato_note_dotted">dotted?</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="ornamental_note">Fastest Ornamental</label>
                            <select id="ornamental_note" wire:model="section.ornamental_note" class="form-control">
                                <option value=""> --</option>
                                <option value="1">Whole Note</option>
                                <option value="2">Half Note</option>
                                <option value="4">Quarter Note</option>
                                <option value="8">Eighth Note</option>
                                <option value="16">Sixteenth Note</option>
                                <option value="32">32nd Note</option>
                                <option value="64">64th Note</option>
                                <option value="128">128th Note</option>
                            </select>
                            <div class="form-check my-2 text-center col">
                                <input class="form-check-input" id="ornamental_note_dotted" type="checkbox"
                                       wire:model="section.ornamental_note_dotted"/>
                                <label class="form-check-label" for="ornamental_note_dotted">dotted?</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button wire:click="close" type="button" class="btn btn-secondary ml-auto" data-bs-dismiss="modal">Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
