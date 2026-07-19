@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h3 class="mb-0">
        <i class="bi bi-calendar-event"></i>
        Administración de Fechas
    </h3>

    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalFecha">

        <i class="bi bi-plus-circle"></i>

        Nueva Fecha

    </button>

</div>

<div class="card shadow-sm">

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th>Descripción</th>

                    <th width="120">Cupo</th>

                    <th width="100">Ocupados</th>

                    <th width="110">Disponibles</th>

                    <th width="220">Ocupación</th>

                    <th width="90">Activa</th>

                    <th width="70"></th>

                </tr>

            </thead>

            <tbody>

                @forelse($fechas as $fecha)

                    @php

                        $color='bg-success';

                        $estado='Disponible';

                        if($fecha->porcentaje>=90){

                            $color='bg-danger';

                            $estado='Llena';

                        }elseif($fecha->porcentaje>=70){

                            $color='bg-warning';

                            $estado='Casi llena';

                        }

                    @endphp

                    <tr>

                        <td>

                            {{ $fecha->descripcion }}

                        </td>

                        <td>

                            <input

                                type="number"

                                min="1"

                                class="form-control cupo"

                                data-id="{{ $fecha->id }}"

                                value="{{ $fecha->cupo_maximo }}">

                        </td>

                        <td>

                            {{ $fecha->ocupados }}

                        </td>

                        <td>

                            {{ $fecha->disponibles }}

                        </td>

                        <td>

                            <div class="progress mb-1" style="height:20px;">

                                <div

                                    class="progress-bar {{ $color }}"

                                    style="width: {{ $fecha->porcentaje }}%;">

                                    {{ $fecha->porcentaje }}%

                                </div>

                            </div>

                            <small>{{ $estado }}</small>

                        </td>

                        <td>

                            <div class="form-check form-switch">

                                <input

                                    class="form-check-input activa"

                                    type="checkbox"

                                    data-id="{{ $fecha->id }}"

                                    {{ $fecha->activa ? 'checked' : '' }}>

                            </div>

                        </td>

                        <td>

                            <button

                                class="btn btn-outline-danger btn-sm eliminar"

                                data-id="{{ $fecha->id }}">

                                <i class="bi bi-trash"></i>

                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center py-5">

                            No existen fechas registradas.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="modal fade" id="modalFecha">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Nueva Fecha

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">

                        Descripción

                    </label>

                    <input

                        type="text"

                        class="form-control"

                        id="descripcion">

                </div>

                <div>

                    <label class="form-label">

                        Cupo máximo

                    </label>

                    <input

                        type="number"

                        class="form-control"

                        id="cupo"

                        value="30">

                </div>

            </div>

            <div class="modal-footer">

                <button

                    class="btn btn-secondary"

                    data-bs-dismiss="modal">

                    Cancelar

                </button>

                <button

                    class="btn btn-primary"

                    id="guardarFecha">

                    Guardar

                </button>

            </div>

        </div>

    </div>

</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>

const token=document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('guardarFecha').addEventListener('click',async()=>{

    const descripcion=document.getElementById('descripcion').value;

    const cupo=document.getElementById('cupo').value;

    const response=await fetch('/fechas',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':token
        },

        body:JSON.stringify({

            descripcion:descripcion,

            cupo_maximo:cupo

        })

    });

    if(response.ok){

        location.reload();

    }

});

document.querySelectorAll('.cupo').forEach(input=>{

    input.addEventListener('change',async function(){

        await fetch('/fechas/'+this.dataset.id+'/cupo',{

            method:'PATCH',

            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':token
            },

            body:JSON.stringify({

                cupo_maximo:this.value

            })

        });

    });

});

document.querySelectorAll('.activa').forEach(check=>{

    check.addEventListener('change',async function(){

        await fetch('/fechas/'+this.dataset.id+'/estado',{

            method:'PATCH',

            headers:{
                'X-CSRF-TOKEN':token
            }

        });

    });

});

document.querySelectorAll('.eliminar').forEach(btn=>{

    btn.addEventListener('click',async function(){

        if(!confirm('¿Eliminar esta fecha?')){

            return;

        }

        const response=await fetch('/fechas/'+this.dataset.id,{

            method:'DELETE',

            headers:{
                'X-CSRF-TOKEN':token
            }

        });

        if(response.ok){

            location.reload();

        }else{

            const data=await response.json();

            alert(data.message);

        }

    });

});

</script>

@endsection