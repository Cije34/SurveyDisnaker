@extends('layouts.app')
@section('title', 'Halo Thomas')

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
      <button class="btn-primary">Isi Survey</button>
    </div>
  </section>
@endsection
