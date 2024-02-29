<div class="card">
    <div class="card-body text-center" style="background: #efefef">
        <p style="margin-top: 5px; font-size: 15px; font-weight: 800">
            {{ fdate(now(), 'l, d F, Y') }}</p>

        <p id="current-time"
            style="font-size: 40px; font-weight: 800; margin-top: -8px; font-family: 'Orbitron', sans-serif;">
            {{ fdate(now(), 'H:i:sa') }}</p>
    </div>
</div>