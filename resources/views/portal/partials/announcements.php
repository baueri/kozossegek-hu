@foreach($announcements as $i => $announcement)
    <input type="radio" name="announcement" id="announcement-{{ $i }}" style="display: none;" @checked($i == 0)>
    <div class='announcement'>
        @if($announcement->header_image)
            <img src='{{ $announcement->header_image }}' alt='{{ $announcement->title }}' class='img-fluid mb-2' style='height: 230px; width: 100%; object-fit: cover;'>
        @endif
        <h3 class='announcement-header text-left text-sm-center my-3'>
            {{ $announcement->title }}
        </h3>
        <div class='announcement-content'>{{ $announcement->content }}</div>
        <p class="text-center nav-buttons">
            <span class="announcement-no">{{ $i +1 }}</span> / {{ $announcements->count() }} <br/>
            <label class="btn btn-sm btn-common prev-announcement" for="announcement-{{ $i -1 }}"><u>Előző</u></label>
            <label class="btn btn-sm btn-common next-announcement" for="announcement-{{ $i +1 }}"><u>Következő</u></label>
        </p>
    </div>
@endforeach

