<div class="breadcrumbs">
    <ul>
        <li>
            <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i></a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.retail-objects.index') }}" class="text-black">@lang('retailobjects::admin.index')</a>
        </li>
        @if(url()->current() === route('admin.retail-objects.create'))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.retail-objects.create') }}" class="text-purple">@lang('retailobjects::admin.create')</a>
            </li>
        @elseif(Request::segment(3) !== null && url()->current() === route('admin.retail-objects.edit', ['id' => Request::segment(3)]))
            <li>
                <i class="fa fa-angle-right"></i>
                <a href="{{ route('admin.retail-objects.edit', ['id' => Request::segment(3)]) }}" class="text-purple">@lang('retailobjects::admin.edit')</a>
            </li>
        @endif
    </ul>
</div>
