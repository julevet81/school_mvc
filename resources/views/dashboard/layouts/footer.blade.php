<footer class="bg-white p-4">
    <div class="row">
        <div class="col-md-6">
            <p class="mb-0">{{ config('app.name') }} © {{ now()->year }}</p>
        </div>
        <div class="col-md-6 text-md-right">
            <span class="badge badge-soft">{{ __('app.messages.real_project_ready') }}</span>
        </div>
    </div>
</footer>
