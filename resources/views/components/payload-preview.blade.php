<div class="fi-su-dns">
    {{-- Read-only preview of what a pixel injects; escaped on purpose so the snippet is shown, never executed. --}}
    @foreach ($payload as $label => $value)
        <div class="fi-su-dns-group">
            <p class="fi-su-dns-group-title">{{ $label }}</p>
            <pre class="fi-su-dns-table" style="display: block; overflow-x: auto; white-space: pre-wrap;"><code>{{ $value ?? '—' }}</code></pre>
        </div>
    @endforeach
</div>
