@if($announcements && $announcements->count() > 0)
<div class="modal fade" id="announcementsModal" tabindex="-1" aria-labelledby="announcementsModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title h5 fw-bold" id="announcementsModalLabel">Közlemények</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>
            <div class="modal-body pt-2">
                @foreach($announcements as $announcement)
                    <article class="announcement-item rounded-3 border bg-light p-3 p-md-4 mb-4">
                        @if($announcement->header_image)
                            <img src="{{ $announcement->header_image }}" alt="{{ $announcement->title }}" class="img-fluid rounded-3 mb-3 w-100" style="height: 230px; object-fit: cover;">
                        @endif
                        <h3 class="h5 text-center mb-3">{{ $announcement->title }}</h3>
                        <div class="announcement-content text-secondary">{{ $announcement->content }}</div>
                    </article>
                @endforeach
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-orange rounded-pill px-4" data-bs-dismiss="modal">Bezárás</button>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var modalEl = document.getElementById('announcementsModal');
    if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        return;
    }
    var ids = <?php echo json_encode($announcements->pluck('id')->values()->all()); ?>;
    var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
    modalEl.addEventListener('hidden.bs.modal', function () {
        if (!ids.length || !token) {
            return;
        }
        if (typeof $ !== 'undefined') {
            $.post('<?php echo route('api.announcements.set_seen'); ?>', { _token: token, ids: ids });
        }
    });
})();
</script>
@endif
