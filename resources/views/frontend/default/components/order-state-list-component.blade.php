<div class="refund_disputes_sidebar">
    @foreach ($processes as $key => $process)
        <div class="single_disputes_sidebar">
            <h4>{{ $process->name }}</h4>
            <p>{{ $process->description }}</p>
        </div>
    @endforeach
</div>