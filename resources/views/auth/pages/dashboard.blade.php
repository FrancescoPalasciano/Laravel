
@extends('auth.master-auth')

@section('title', 'Dashboard')

@section('content-auth')
    
<div class="flex flex-1 flex-col w-full min-w-0">
    <div class="@container/main flex flex-1 flex-col gap-2 w-full">
        <div class="flex flex-col gap-4 py-4 md:gap-6 md:py-6">
            <section-cards></section-cards>
            <div class="px-4 lg:px-6 w-full overflow-x-hidden">
                <chart-area-interactive></chart-area-interactive>
            </div>
        </div>
    </div>
</div>
@endsection