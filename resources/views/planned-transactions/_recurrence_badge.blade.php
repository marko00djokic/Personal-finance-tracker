@php
    $labels = [
        'none'    => 'Jednokratno',
        'daily'   => 'Dnevno',
        'weekly'  => 'Nedeljno',
        'monthly' => 'Mesečno',
        'yearly'  => 'Godišnje',
    ];
    $colors = [
        'none'    => 'bg-gray-100 text-gray-600',
        'daily'   => 'bg-blue-100 text-blue-700',
        'weekly'  => 'bg-purple-100 text-purple-700',
        'monthly' => 'bg-indigo-100 text-indigo-700',
        'yearly'  => 'bg-orange-100 text-orange-700',
    ];
    $label = $labels[$pt->recurrence_type] ?? $pt->recurrence_type;
    $color = $colors[$pt->recurrence_type] ?? 'bg-gray-100 text-gray-600';
@endphp
<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $color }}">
    {{ $label }}
    @if($pt->recurrence_type === 'monthly' && $pt->recurrence_day)
        ({{ $pt->recurrence_day }}.)
    @endif
</span>
