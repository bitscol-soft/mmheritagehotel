<div class="card mm-pos-clock-card">
    <div class="card-body text-center">
        <p class="mm-pos-clock-date">
            {{ fdate(now(), 'l, d F, Y') }}</p>

        <p id="current-time" class="mm-pos-clock-time">
            {{ fdate(now(), 'H:i:sa') }}</p>
    </div>
</div>
