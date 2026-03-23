<div>
    <style>
        .employee-breaks {
            display: grid;
            gap: 14px;
            padding: 18px;
            border-radius: 18px;
            border: 1px solid rgba(252, 204, 124, 0.18);
            background: linear-gradient(165deg, rgba(18, 12, 7, 0.92), rgba(11, 8, 5, 0.9));
        }

        .employee-breaks__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .employee-breaks__eyebrow {
            display: inline-flex;
            margin-bottom: 8px;
            color: #efc97f;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .employee-breaks__head p {
            margin: 0;
            color: #ccb693;
            line-height: 1.55;
        }

        .employee-breaks__list {
            display: grid;
            gap: 12px;
        }

        .employee-break-card {
            display: grid;
            gap: 14px;
            padding: 16px;
            border-radius: 16px;
            border: 1px solid rgba(252, 204, 124, 0.18);
            background: linear-gradient(160deg, rgba(26, 15, 9, 0.94), rgba(14, 10, 6, 0.88));
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03);
        }

        .employee-break-card__top {
            display: grid;
            grid-template-columns: minmax(150px, 0.9fr) minmax(150px, 0.9fr) minmax(240px, 1.5fr) auto;
            gap: 12px;
            align-items: end;
        }

        .employee-break-card__bottom {
            display: grid;
            grid-template-columns: minmax(160px, 0.8fr) minmax(160px, 0.8fr) minmax(220px, 1.2fr);
            gap: 12px;
            align-items: end;
        }

        .employee-break-field {
            min-width: 0;
        }

        .employee-break-field label {
            display: block;
            margin-bottom: 8px;
            color: #e2c98f;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 11px;
            font-weight: 700;
        }

        .employee-break-card .schedule-toggle-btn {
            width: 100%;
            min-width: 0;
            min-height: 44px;
        }

        .employee-break-remove {
            min-height: 42px;
            padding: 0 14px;
            border-radius: 12px;
            border: 1px solid rgba(248, 113, 113, 0.36);
            background: rgba(127, 29, 29, 0.18);
            color: #ffd5d5;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            appearance: none;
            width: 100%;
        }

        .employee-break-remove:hover {
            background: rgba(127, 29, 29, 0.28);
            border-color: rgba(248, 113, 113, 0.48);
        }

        .employee-break-note {
            display: grid;
            align-content: center;
            gap: 6px;
            min-height: 44px;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px dashed rgba(252, 204, 124, 0.24);
            background: rgba(252, 204, 124, 0.06);
        }

        .employee-break-note span {
            color: #f4ddaf;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .employee-break-note p {
            margin: 0;
            color: #d4c0a2;
            font-size: 13px;
            line-height: 1.5;
        }

        .employee-breaks__empty {
            padding: 18px 16px;
            border-radius: 16px;
            border: 1px solid rgba(252, 204, 124, 0.18);
            background: linear-gradient(160deg, rgba(20, 12, 8, 0.9), rgba(12, 9, 6, 0.86));
        }

        .employee-breaks__empty p {
            margin: 0;
            color: #cdb898;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .employee-breaks__head,
            .employee-break-card__top,
            .employee-break-card__bottom {
                grid-template-columns: 1fr;
            }

            .employee-breaks__head {
                display: grid;
            }
        }
    </style>

    <div class="card">

        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" wire:model.defer="nombre">
            </div>

            <div class="form-group">
                <label>Teléfono</label>
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

                <small>Selecciona hasta 5 servicios. Al llegar al límite, los demás se desactivan.</small>
                @error('serviciosSeleccionados')
                    <span class="error">{{ $message }}</span>
                @enderror
                @error('serviciosSeleccionados.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full">
                <label>Horario base semanal</label>
                <small>Esta es la jornada regular del empleado. Los bloqueos por fecha se configuran abajo como excepciones.</small>

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
                                    <button type="button" class="schedule-toggle-btn {{ $enabled ? 'is-on' : 'is-off' }}"
                                        wire:click="toggleLabora({{ $index }})">
                                        <span class="switch-dot" aria-hidden="true"></span>
                                        <span>{{ $enabled ? 'Jornada activa' : 'Dia libre' }}</span>
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

            <div class="form-group full">
                <div class="employee-breaks">
                    <div class="employee-breaks__head">
                        <div>
                            <span class="employee-breaks__eyebrow">Bloqueos y descansos especiales</span>
                            <p>Usa esta seccion para permisos, comida, capacitacion o dias libres puntuales sin tocar el horario base.</p>
                        </div>
                        <button type="button" class="schedule-add-btn" wire:click="addDescanso">
                            Agregar descanso
                        </button>
                    </div>

                    <div class="employee-breaks__list">
                    @forelse ($descansos as $index => $descanso)
                        <div class="employee-break-card">
                            <div class="employee-break-card__top">
                                <div class="employee-break-field">
                                    <label>Fecha</label>
                                    <input type="date" wire:model.defer="descansos.{{ $index }}.date" min="{{ today()->toDateString() }}">
                                    @error("descansos.$index.date")
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="employee-break-field">
                                    <label>Tipo</label>
                                    <button type="button"
                                        class="schedule-toggle-btn {{ !empty($descanso['is_all_day']) ? 'is-on' : 'is-off' }}"
                                        wire:click="$toggle('descansos.{{ $index }}.is_all_day')">
                                        <span class="switch-dot" aria-hidden="true"></span>
                                        <span>{{ !empty($descanso['is_all_day']) ? 'Todo el día' : 'Por horas' }}</span>
                                    </button>
                                </div>

                                <div class="employee-break-field">
                                    <label>Motivo</label>
                                    <input type="text" wire:model.defer="descansos.{{ $index }}.reason"
                                        placeholder="Comida, permiso, capacitacion...">
                                </div>

                                <div class="employee-break-field">
                                    <label>Accion</label>
                                    <button type="button" class="employee-break-remove" wire:click="removeDescanso({{ $index }})">
                                        Quitar
                                    </button>
                                </div>
                            </div>

                            <div class="employee-break-card__bottom">
                                <div class="employee-break-field">
                                    <label>Desde</label>
                                    <input type="time" wire:model.defer="descansos.{{ $index }}.start_time"
                                        @disabled(!empty($descanso['is_all_day']))>
                                    @error("descansos.$index.start_time")
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="employee-break-field">
                                    <label>Hasta</label>
                                    <input type="time" wire:model.defer="descansos.{{ $index }}.end_time"
                                        @disabled(!empty($descanso['is_all_day']))>
                                    @error("descansos.$index.end_time")
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if (!empty($descanso['is_all_day']))
                                    <div class="employee-break-note">
                                        <span>Dia completo bloqueado</span>
                                        <p>Este descanso ocultara al empleado de la agenda para esa fecha.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="employee-breaks__empty">
                            <p>No hay descansos manuales programados. Puedes bloquear dias completos o rangos por horas.</p>
                        </div>
                    @endforelse
                    </div>
                </div>
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
