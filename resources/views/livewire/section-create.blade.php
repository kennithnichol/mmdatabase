<div>
    @if($saved)

    @endif

    <form wire:submit.prevent='saveForm'>
        @csrf
        <div>
            <div>Piece</div>
            <select wire:change='updateMovements($event.target.value)'>
                @foreach($pieces as $piece)
                <option value='{{ $piece->id }}'>{{ $piece->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <div>Sections</div>
            <table>
                <thead>
                <tr>
                    <th>Movement</th>
                    <th>Tempo Text</th>
                    <th>MM Note</th>
                    <th>BPM</th>
                    <th>Structural Note</th>
                    <th>Stacatto Note</th>
                    <th>Ornamental Note</th>
                </tr>
            </thead>
            <tbody wire:sortable='updateOrder'>
                @forelse($sections as $index => $section)
                <tr wire:sortable.item='{{ $index }}' wire:key='section-{{ $index }}'>
                    <td>
                        <select name='movement'>
                            @foreach($movements as $movement)
                                <option value='{{ $movement }}'>{{ $movement }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type='text' />
                    <td colspan='5'></td>
                </tr>
                @empty
                <tr><td colspan='7'>No sections added.</td></tr>
                @endforelse
            </tbody>
            </table>

        </div>
        <div>
            <button wire:click.prevent='addSection' class=''>Add Section</button>
        </div>
    </form>
</div>
