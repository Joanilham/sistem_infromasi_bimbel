{{--
  SKELETON LOADER COMPONENT
  Penggunaan: @include('components.skeleton', ['rows' => 3, 'type' => 'card'])

  Tipe tersedia: 'card', 'list', 'table', 'profile', 'article'
--}}

@php
  $type  = $type  ?? 'card';
  $rows  = $rows  ?? 3;
  $class = $class ?? '';
@endphp

@if ($type === 'card')
  <div class="skeleton-container {{ $class }}" aria-label="Memuat konten..." aria-busy="true">
    <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 16px;">
      <div class="skeleton skeleton-circle" style="width: 44px; height: 44px; flex-shrink: 0;"></div>
      <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
        <div class="skeleton skeleton-text" style="width: 60%;"></div>
        <div class="skeleton skeleton-text" style="width: 40%; height: 10px;"></div>
      </div>
    </div>
    <div class="skeleton skeleton-block" style="height: 160px; margin-bottom: 12px;"></div>
    @for ($i = 0; $i < $rows; $i++)
      <div class="skeleton skeleton-text" style="width: {{ 95 - ($i * 10) }}%; margin-bottom: 8px;"></div>
    @endfor
  </div>

@elseif ($type === 'list')
  <div class="skeleton-container {{ $class }}" aria-label="Memuat daftar..." aria-busy="true">
    @for ($i = 0; $i < $rows; $i++)
      <div style="display: flex; gap: 12px; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0;">
        <div class="skeleton skeleton-circle" style="width: 36px; height: 36px; flex-shrink: 0;"></div>
        <div style="flex: 1; display: flex; flex-direction: column; gap: 6px;">
          <div class="skeleton skeleton-text" style="width: {{ 50 + ($i % 3) * 15 }}%;"></div>
          <div class="skeleton skeleton-text" style="width: {{ 30 + ($i % 2) * 20 }}%; height: 10px;"></div>
        </div>
        <div class="skeleton skeleton-text" style="width: 60px; height: 10px;"></div>
      </div>
    @endfor
  </div>

@elseif ($type === 'table')
  <div class="skeleton-container {{ $class }}" aria-label="Memuat tabel..." aria-busy="true">
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 12px;">
      @for ($i = 0; $i < 4; $i++)
        <div class="skeleton skeleton-text" style="height: 10px;"></div>
      @endfor
    </div>
    @for ($i = 0; $i < $rows; $i++)
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
        @for ($j = 0; $j < 4; $j++)
          <div class="skeleton skeleton-text" style="height: 12px; width: {{ 60 + ($j * 10) }}%;"></div>
        @endfor
      </div>
    @endfor
  </div>

@elseif ($type === 'profile')
  <div class="skeleton-container {{ $class }}" aria-label="Memuat profil..." aria-busy="true">
    <div style="text-align: center; padding: 24px 0;">
      <div class="skeleton skeleton-circle" style="width: 80px; height: 80px; margin: 0 auto 16px;"></div>
      <div class="skeleton skeleton-text" style="width: 40%; margin: 0 auto 8px;"></div>
      <div class="skeleton skeleton-text" style="width: 60%; margin: 0 auto; height: 10px;"></div>
    </div>
    @for ($i = 0; $i < $rows; $i++)
      <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
        <div class="skeleton skeleton-text" style="width: 30%;"></div>
        <div class="skeleton skeleton-text" style="width: 50%;"></div>
      </div>
    @endfor
  </div>

@elseif ($type === 'article')
  <div class="skeleton-container {{ $class }}" aria-label="Memuat artikel..." aria-busy="true">
    <div class="skeleton skeleton-block" style="height: 200px; margin-bottom: 20px;"></div>
    <div class="skeleton skeleton-text" style="width: 80%; height: 20px; margin-bottom: 12px;"></div>
    <div class="skeleton skeleton-text" style="width: 50%; height: 12px; margin-bottom: 20px;"></div>
    @for ($i = 0; $i < $rows; $i++)
      <div class="skeleton skeleton-text" style="width: {{ $i === $rows - 1 ? '60%' : '100%' }}%; margin-bottom: 10px;"></div>
    @endfor
  </div>
@endif
