@props([
    'id',
    'title' => null,
    'size' => 'm',  // 's' | 'm' | 'l' | 'xl'
    'open' => false,
    'dismissible' => true,
])

<div class="mm-modal"
     id="{{ $id }}"
     data-mm-modal
     data-size="{{ $size }}"
     @if($open) data-open @endif
     @if(!$dismissible) data-persistent @endif
     role="dialog"
     aria-modal="true"
     @if($title) aria-labelledby="{{ $id }}-title" @endif
     tabindex="-1"
     hidden>
    <div class="mm-modal-backdrop" @if($dismissible) data-mm-modal-close @endif></div>
    <div class="mm-modal-card mm-modal-{{ $size }}" role="document">
        @if ($title)
            <header class="mm-modal-head">
                <h2 id="{{ $id }}-title" class="mm-modal-title">{{ $title }}</h2>
                @if ($dismissible)
                    <button type="button" class="mm-modal-close" data-mm-modal-close aria-label="Close">&times;</button>
                @endif
            </header>
        @endif
        <div class="mm-modal-body">
            {{ $slot }}
        </div>
        @isset($footer)
            <footer class="mm-modal-foot">
                {{ $footer }}
            </footer>
        @endisset
    </div>
</div>
