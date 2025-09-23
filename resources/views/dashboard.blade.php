@extends('layouts.app')
@section('title', 'Halo Slwbew')

@section('content')
  <section class="section">
    <h3>Jadwal Kegiatan</h3>
    <div class="card table-card">
      @include('partials._schedule_table', ['rows' => $schedule, 'highlightIndex' => 1])
    </div>
  </section>

  <section class="section">
    <h3>Survey Tersedia</h3>
    <div class="card survey-card">
      <div>
        <div class="survey-title">Survey Pelatihan</div>
        <div class="survey-desc">Berikan masukan untuk pelatihan kemarin</div>
      </div>
      <button type="button" class="btn-primary"
              onclick="window.location.href='{{ route('survey') }}'">
        Isi survey
      </button>
    </div>
  </section>
@endsection
