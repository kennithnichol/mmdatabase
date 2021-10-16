<div>
	<form wire:submit.prevent="save" method="POST">
		@csrf
		<div class="row g-2">
			<div class="col-12">
				<label for="title" class="form-label">{{ __('Title') }}</label>
				<input id="title" type="text" wire:model="title"
					   class="form-control @error('title') is-invalid @enderror">
				@error('title')
				<span class="invalid-feedback" role="alert">{{ $message }}</span>
				@enderror
			</div>
			<div class="col-12">
				<label for="year_composed" class="form-label">{{ __('Year of composition') }}</label>
				<input id="year_composed" type="tel" maxlength="4" wire:model="year_composed"
					   class="form-control @error('year_composed') is-invalid @enderror">
				@error('year_composed')
				<span class="invalid-feedback" role="alert">{{ $message }}</span>
				@enderror
			</div>
			<div class="col-12">
				<h2>Movements</h2>
				<table class="table">
					<thead>
					<tr>
						<th scope="col" title="order #">#</th>
						<th scope="col" title="Movement number text">Movement</th>
						<th scope="col">Title</th>
						<th></th>
					</tr>
					</thead>
					<tbody wire:sortable="updateMovementOrder">
					@php dump($movements) @endphp
					@forelse($movements as $index => $movement)
						<tr wire:sortable.item="{{ $movement->id }}" wire:key="movement-{{ $movement->order }}">
							<th wire:sortable.handle scope="row">{{ $movement['order'] }}</th>
							<td>{{ $movement->number }}</td>
							<td>{{ $movement->title }}</td>
							<td>
								<button wire:click.prevent="editMovement({{ $index }})"
										data-bs-toggle="modal" data-bs-target="#movement-modal"
										class="btn btn-outline-primary">Edit
								</button>
								<button wire:click.prevent="removeMovement({{ $index }})"
										class="btn btn-danger">Edit
								</button>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" class="text-center">No movements added</td>
						</tr>
					@endforelse
					</tbody>
				</table>
				<button wire:click.prevent="addMovement" type="button" data-bs-toggle="modal"
						data-bs-target="#movement-modal" class="btn btn-outline-primary">Add Movement
				</button>
				<button type="submit" class="btn btn-primary">Save</button>

				@if ($errors && !$this->showModal)
					@foreach ($errors->all() as $error)
						<span>{{ $error }}</span>
					@endforeach
				@endif
			</div>
		</div>
	</form>

	<div wire:ignore.self id="movement-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5>Add movement</h5>
					<button wire:click.prevent="closeMovement" type="button" class="btn-close"
							data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body form-row">
					-Movement Form-
					<form>
						<button type="submit" class="btn btn-primary">Save</button>
						<button wire:click.prevent="close" type="button" class="btn btn-outline-secondary"
								data-bs-dismiss="modal">Cancel
						</button>
						<input type="hidden" wire:model="movement.order"
							   value="{{ $movement->order ?? $movements->count() + 1 }}"/>
					</form>
				</div>
			</div>
		</div>
	</div>
	{{ $composer }}
	{{ $inModal }}
</div>
