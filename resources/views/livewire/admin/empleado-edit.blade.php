<div>

    <div class="card">

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" wire:model.defer="nombre">
            </div>

            <div class="form-group">
                <label>Telefono</label>
                <input type="tel" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" wire:model.defer="telefono"
                    oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)">
                @error('telefono')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full">
                @php
                    $selectedIds = collect($serviciosSeleccionados)->map(fn($id) => (int) $id)->all();
                    $selectedCount = count($selectedIds);
                    $limitReached = $selectedCount >= 5;
                    $diasSemana = [
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miercoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sabado',
                    ];
                @endphp

                <div class="services-picker-head">
                    <label>Servicios / Especialidad (max. 5)</label>
                    <span class="services-picker-counter {{ $limitReached ? 'is-limit' : '' }}">
                        {{ $selectedCount }}/5 seleccionados
                    </span>
                </div>

                <div class="services-picker-scroll-card">
                    <div class="services-picker-scroll-body employee-services-scroll">
                        <div class="services-picker-grid employee-services-grid">
                            @foreach ($servicios as $servicio)
                                @php
                                    $isSelected = in_array($servicio->id, $selectedIds, true);
                                    $isDisabled = !$isSelected && $limitReached;
                                @endphp
                                <button type="button"
                                    class="service-pick-card {{ $isSelected ? 'is-selected' : '' }} {{ $isDisabled ? 'is-disabled' : '' }}"
                                    wire:click="toggleServicio({{ $servicio->id }})" @disabled($isDisabled)>
                                    <span class="service-pick-check" aria-hidden="true">&#10003;</span>
                                    <span class="service-pick-name">{{ $servicio->name }}</span>
                                    <span class="service-pick-meta">
                                        {{ $servicio->duration_minutes }} min -
                                        ${{ number_format((float) $servicio->price, 2) }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <small>Selecciona hasta 5 servicios. Al llegar al limite, los demas se desactivan.</small>
                @error('serviciosSeleccionados')
                    <span class="error">{{ $message }}</span>
                @enderror
                @error('serviciosSeleccionados.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full">
                <label>Horarios de trabajo</label>

                <div class="schedule-table">
                    <div class="schedule-table-head">
                        <span>Dia de la semana</span>
                        <span>Hora entrada (desde)</span>
                        <span>Hora salida (hasta)</span>
                        <span>Accion</span>
                    </div>

                    <div class="schedule-table-body">
                        @foreach ($horarios as $index => $horario)
                            @php
                                $day = (int) ($horario['day_of_week'] ?? ($index + 1));
                                $enabled = (bool) ($horario['enabled'] ?? false);
                            @endphp
                            <div class="schedule-table-row {{ $enabled ? '' : 'is-disabled' }}">
                                <div class="schedule-col">
                                    <div class="schedule-day-pill">{{ $diasSemana[$day] ?? 'Dia' }}</div>
                                </div>

                                <div class="schedule-col">
                                    <input type="time" wire:model.defer="horarios.{{ $index }}.start_time"
                                        @disabled(!$enabled)>
                                </div>

                                <div class="schedule-col">
                                    <input type="time" wire:model.defer="horarios.{{ $index }}.end_time"
                                        @disabled(!$enabled)>
                                </div>

                                <div class="schedule-col schedule-col-action">
                                    <button type="button" class="schedule-toggle-btn {{ $enabled ? '' : 'is-off' }}"
                                        wire:click="toggleLabora({{ $index }})">
                                        {{ $enabled ? 'No labora' : 'Labora' }}
                                    </button>
                                </div>
                            </div>
                            <div class="schedule-errors">
                                @error("horarios.$index.start_time")
                                    <span class="error">{{ $message }}</span>
                                @enderror
                                @error("horarios.$index.end_time")
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                @error('horarios')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <div class="actions">
            <a href="{{ route('admin.empleados.index') }}" class="btn btn-cancel">
                Cancelar
            </a>

            <button class="btn btn-save" wire:click="actualizar">
                Guardar cambios
            </button>
        </div>

    </div>

</div>
