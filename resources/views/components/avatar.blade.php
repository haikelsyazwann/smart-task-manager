@props(['avatar' => 'boy1', 'size' => 'md'])

@php
$sizes = [
    'xs'  => 'w-5 h-5 text-[8px]',
    'sm'  => 'w-7 h-7 text-[10px]',
    'md'  => 'w-8 h-8 text-xs',
    'lg'  => 'w-12 h-12 text-base',
    'xl'  => 'w-16 h-16 text-xl',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];

$avatars = [
    'boy1'  => ['bg' => '#dbeafe', 'fg' => '#1d4ed8'],
    'boy2'  => ['bg' => '#dcfce7', 'fg' => '#15803d'],
    'boy3'  => ['bg' => '#fef9c3', 'fg' => '#a16207'],
    'boy4'  => ['bg' => '#fee2e2', 'fg' => '#b91c1c'],
    'boy5'  => ['bg' => '#f3e8ff', 'fg' => '#7e22ce'],
    'girl1' => ['bg' => '#fce7f3', 'fg' => '#be185d'],
    'girl2' => ['bg' => '#ffedd5', 'fg' => '#c2410c'],
    'girl3' => ['bg' => '#e0f2fe', 'fg' => '#0369a1'],
    'girl4' => ['bg' => '#d1fae5', 'fg' => '#065f46'],
    'girl5' => ['bg' => '#ede9fe', 'fg' => '#5b21b6'],
];

$colors = $avatars[$avatar] ?? $avatars['boy1'];
@endphp

<div class="{{ $sizeClass }} rounded-full flex items-center justify-center flex-shrink-0"
     style="background:{{ $colors['bg'] }}">
    @if(str_starts_with($avatar, 'boy'))
    @switch($avatar)
    @case('boy1')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <rect x="13" y="8" width="14" height="5" rx="2" fill="{{ $colors['fg'] }}" opacity=".7"/>
    </svg>
    @break
    @case('boy2')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <rect x="12" y="7" width="16" height="4" rx="2" fill="{{ $colors['fg'] }}" opacity=".6"/>
        <rect x="12" y="7" width="4" height="8" rx="1" fill="{{ $colors['fg'] }}" opacity=".6"/>
    </svg>
    @break
    @case('boy3')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <path d="M13 10 Q20 4 27 10" stroke="{{ $colors['fg'] }}" stroke-width="3" fill="none" opacity=".7"/>
    </svg>
    @break
    @case('boy4')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <rect x="14" y="7" width="12" height="3" rx="1.5" fill="{{ $colors['fg'] }}" opacity=".6"/>
        <rect x="14" y="7" width="3" height="9" rx="1.5" fill="{{ $colors['fg'] }}" opacity=".6"/>
        <rect x="23" y="7" width="3" height="9" rx="1.5" fill="{{ $colors['fg'] }}" opacity=".6"/>
    </svg>
    @break
    @case('boy5')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <circle cx="20" cy="9" r="3" fill="{{ $colors['fg'] }}" opacity=".6"/>
    </svg>
    @break
    @endswitch
    @else
    @switch($avatar)
    @case('girl1')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <path d="M12 12 Q20 4 28 12 Q26 8 20 7 Q14 8 12 12Z" fill="{{ $colors['fg'] }}" opacity=".6"/>
    </svg>
    @break
    @case('girl2')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <path d="M12 14 Q12 6 20 6 Q28 6 28 14 Q26 8 20 8 Q14 8 12 14Z" fill="{{ $colors['fg'] }}" opacity=".6"/>
        <circle cx="13" cy="15" r="2" fill="{{ $colors['fg'] }}" opacity=".5"/>
        <circle cx="27" cy="15" r="2" fill="{{ $colors['fg'] }}" opacity=".5"/>
    </svg>
    @break
    @case('girl3')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <path d="M13 13 Q13 5 20 5 Q27 5 27 13" stroke="{{ $colors['fg'] }}" stroke-width="2.5" fill="none" opacity=".6"/>
        <path d="M13 13 Q10 16 11 20" stroke="{{ $colors['fg'] }}" stroke-width="2" fill="none" opacity=".5"/>
        <path d="M27 13 Q30 16 29 20" stroke="{{ $colors['fg'] }}" stroke-width="2" fill="none" opacity=".5"/>
    </svg>
    @break
    @case('girl4')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <path d="M13 15 Q13 6 20 6 Q27 6 27 15 L29 12 Q27 4 20 4 Q13 4 11 12Z" fill="{{ $colors['fg'] }}" opacity=".6"/>
    </svg>
    @break
    @case('girl5')
    <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full p-0.5">
        <circle cx="20" cy="20" r="20" fill="{{ $colors['bg'] }}"/>
        <circle cx="20" cy="15" r="7" fill="{{ $colors['fg'] }}"/>
        <path d="M6 36c0-7.732 6.268-14 14-14s14 6.268 14 14" fill="{{ $colors['fg'] }}"/>
        <path d="M12 14 Q14 5 20 5 Q26 5 28 14 Q24 9 20 9 Q16 9 12 14Z" fill="{{ $colors['fg'] }}" opacity=".6"/>
        <circle cx="20" cy="6" r="2" fill="{{ $colors['fg'] }}" opacity=".5"/>
    </svg>
    @break
    @endswitch
    @endif
</div>
