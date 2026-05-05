<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h4 class="mb-0">{{ $title }}</h4>
            @isset($subtitle)
                <small class="text-muted">{{ $subtitle }}</small>
            @endisset
        </div>
        <div class="col-sm-6 text-left text-sm-right">
            @isset($actions)
                {!! $actions !!}
            @endisset
        </div>
    </div>
</div>
