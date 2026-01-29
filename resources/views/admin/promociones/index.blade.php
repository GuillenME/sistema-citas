@extends('layouts.admin')

@section('title', 'Promociones')

@section('header-actions')
    <a href="{{ route('admin.promociones.create') }}" class="btn-create">
        + Nueva promoción
    </a>
@endsection

@section('content')

 
    <div class="card table-card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descuento</th>
                        <th>Servicios</th>
                        <th>Fecha</th>
                        <th>Publicada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promociones as $promo)
                        <tr>
                            <td>{{ $promo->title }}</td>
                            <td>{{ $promo->discount }}%</td>
                            <td>
                                @foreach ($promo->servicios as $servicio)
                                    <span class="badge-service">
                                        {{ $servicio->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }}
                                -
                                {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                @if ($promo->published)
                                    <span class="badge badge-on">Sí</span>
                                @else
                                    <span class="badge badge-off">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.promociones.edit', $promo) }}" class="btn-edit">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
