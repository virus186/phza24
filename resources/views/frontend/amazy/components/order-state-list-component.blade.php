@foreach ($processes as $key => $process)
    <div class="dashboard_white_box style3 rounded-0 bg-white mb_20">
        <div class="dashboard_white_box_body">
        <h4 class="font_20 f_w_700 mb-2">{{ $process->name }}</h4>
        <p class="lineHeight1 font_14 f_w_400 mb-0">{{ $process->description }}</p>
        </div>
    </div>
@endforeach