<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.team.index') }}" class="text-black">@lang('team::admin.team.index')</a>
        </li>
        @if(url()->current() === route('admin.team.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.team.create') }}" class="text-purple">@lang('team::admin.team.create')</a>
            </li>
        @elseif(Request::segment(3) !== null && url()->current() === route('admin.team.edit', ['id' => Request::segment(3)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.team.edit', ['id' => Request::segment(3)]) }}" class="text-purple">@lang('team::admin.team.edit')</a>
            </li>
        @endif
    </ul>
</div>
