@if ($data->status == 'Ordered')
    <span class="badge badge-primary">
        {{ $data->status }}
    </span>
@elseif ($data->status == 'Completed')
    <span class="badge badge-success">
        {{ $data->status }}
    </span>
@else
    <span class="badge badge-info">
        {{ $data->status }}
    </span>
@endif
