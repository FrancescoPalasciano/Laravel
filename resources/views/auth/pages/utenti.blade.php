
@extends('auth.master-auth')

@section('title', 'Utenti')

@section('content-auth')
    @props(['data' => $data ?? []])

<div class="flex flex-1 flex-col">
    <div class="@container/main flex flex-1 flex-col gap-2">
        <div class="flex flex-col gap-4 py-4 md:gap-6 md:py-6">   
            <h1 class="text-3xl font-bold px-4 lg:px-6">Gestione Utenti</h1>
            <div class="px-4 lg:px-6"> 
                <data-table :data='@json($data)'></data-table> 
            </div>
        </div>
    </div>
</div>
@endsection