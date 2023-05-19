<div class="section-maps">
    @foreach($retailObjects as $retailObject)
        <div class="map-element">
            <div class="map-wrapper" data-aos="fade-up" data-aos-delay="50">
                <iframe class="map" allowfullscreen="" frameborder="0" width="100%" height="550" src="{{ $retailObject->map_iframe }}"></iframe>
            </div>

            <h4>{{ $retailObject->title }}</h4>

            <p>{{ $retailObject->address }}</p>
            <p>tel. <a href="tel:{!! $retailObject->phone !!}">{!! $retailObject->phone !!}</a></p>
            <p><a href="mailto:{!! $retailObject->email !!}">{!! $retailObject->email !!}</a></p>
        </div>
    @endforeach
</div>
