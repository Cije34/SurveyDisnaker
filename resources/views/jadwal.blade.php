@extends('layouts.app')
@section('title', 'Jadwal')

@section('topbar')
  <div class="divider"></div>
@endsection

@section('content')
  <section class="section">
    <h3>Jadwal Kegiatan</h3>
    <div class="card table-card table-rounded">
      @include('partials._schedule_table', ['rows' => $schedule, 'highlightIndex' => $highlightIndex ?? null])
    </div>
  </section>
@endsection
