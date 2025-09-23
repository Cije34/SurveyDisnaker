@extends('layouts.app')
@section('title', 'Survey')

@section('topbar')
  <div class="divider"></div>
@endsection

@section('content')
  @if(empty($available))
    <div class="empty-state"><span>Survey Tidak Ada</span></div>
  @else
    <section class="section">
      <h3>Survey Tersedia</h3>
      <div class="survey-grid">
        @foreach ($available as $s)
          <div class="card survey-item">
            <div class="survey-title">{{ $s['title'] }}</div>
            <div class="survey-desc">{{ $s['desc'] }}</div>
            <a class="btn-primary" href="#">Isi Survey</a>
          </div>
        @endforeach
      </div>
    </section>
  @endif
@endsection
