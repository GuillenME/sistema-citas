

<div>
    <h1>Promociones</h1>

    <form wire:submit.prevent="save">
        <input type="text" wire:model.defer="titulo" placeholder="Título">
        @error('titulo') <span style="color:red">{{ $message }}</span> @enderror
        <br><br>

        <textarea wire:model.defer="descripcion" placeholder="Descripción"></textarea>
        @error('descripcion') <span style="color:red">{{ $message }}</span> @enderror
        <br><br>

        <input type="number" wire:model.defer="descuento" placeholder="Descuento (%)">
        @error('descuento') <span style="color:red">{{ $message }}</span> @enderror
        <br><br>

        <input type="date" wire:model.defer="fecha_inicio">
        @error('fecha_inicio') <span style="color:red">{{ $message }}</span> @enderror
        <br><br>

        <input type="date" wire:model.defer="fecha_fin">
        @error('fecha_fin') <span style="color:red">{{ $message }}</span> @enderror
        <br><br>

        <label>
            <input type="checkbox" wire:model="publicada">
            Publicar
        </label>

        <br><br>

        <button type="submit">Guardar promoción</button>
    </form>

    <hr>

    <h2>Promociones existentes</h2>

    @foreach ($promociones as $promo)
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px">
            <strong>{{ $promo->title }}</strong><br>
            {{ $promo->description }}<br>
            <small>
                {{ $promo->discount }}% |
                {{ $promo->start_date }} - {{ $promo->end_date }}
            </small>
        </div>
    @endforeach
</div>
