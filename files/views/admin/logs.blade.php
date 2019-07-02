<style>
    .wpk-log-container {
        height: 350px;
        margin: 20px 0;
        overflow: auto;
        background: #f6f6f6;
        border: 1px solid #bcaaaa;
    }

    .wpk-log {
        font-weight: 1.25em;
        padding: 5px;
    }

    .wpk-error .wpk-log-message {
        color: #bc2a2a;
        font-weight: bold;
    }
</style>

<div class="wpk-log-container">

    @foreach($logs as $data)
        <div class="wpk-log wpk-{{ $data['type'] }}">
            <strong class="wpk-timestamp">{{ $data['date'] }}</strong>
            <span class="wpk-log-message"> - {!! $data['log']  !!}</span>
        </div>
    @endforeach

</div>
