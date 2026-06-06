@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
    $activeKantorName = \App\Models\MasterData\Kantor::where('id', session('kantor_id'))->value('nama_kantor') ?? 'Belum Dipilih';
    $activePeriodeYear = \App\Models\MasterData\Periode::where('id', session('periode_id'))->value('tahun_periode') ?? 'Belum Dipilih';
    $allPeriodes = \App\Models\MasterData\Periode::orderBy('tahun_periode', 'desc')->get();
@endphp

<div x-data="{ 
    ...dashboardClock(),
    showListModal: false,
    modalTitle: '',
    modalType: '',
    openList(type, title) {
        this.modalType = type;
        this.modalTitle = title;
        this.showListModal = true;
    }
}">
    @include('admin.dashboard-components.header')
    @include('admin.dashboard-components.banner-periode')
    @include('admin.dashboard-components.stat-cards')
    @include('admin.dashboard-components.grafik')
    @include('admin.dashboard-components.pendaftar-terbaru')
    @include('admin.dashboard-components.log-aktivitas')
    @include('admin.dashboard-components.modal-peserta')
</div>

@endsection

@push('scripts')
    @include('admin.dashboard-components.scripts')
@endpush
