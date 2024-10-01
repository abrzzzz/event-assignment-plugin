<div>
    <h3>Events</h3>
    <ul>
        @foreach ($events as $event)
            <li>{{ $event->post_title }}</li>
        @endforeach
    </ul>

</div>