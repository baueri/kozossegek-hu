<div id="kozossegek" class="columns is-multiline">
    @foreach($groups as $i => $group)
        <div class="column is-3">
            <div class="card is-always-shady">
                <div class="card-image">
                    <figure class="image is-5by3">
                        <img src="{{ $group->getThumbnail() }}" alt="{{ $group->city }}" style="object-fit: cover">
                    </figure>
                </div>
                <div class="card-content">
                    <div class="media-content">
                        <p class="title is-4">{{ $group->name }}</p>
                        <p class="subtitle is-6 has-text-grey-light">{{ $group->city }}</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="#" class="card-footer-item">Save</a>
                    <a href="#" class="card-footer-item">Edit</a>
                    <a href="#" class="card-footer-item">Megnézem</a>
                </footer>
            </div>
        </div>
    @endforeach
</div>