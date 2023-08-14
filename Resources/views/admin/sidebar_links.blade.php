@php use App\Helpers\WebsiteHelper;use Illuminate\Http\Request; @endphp
<div class="panel panel-default">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            <a href="{{ route('admin.retail-objects.index') }}" class="{{ WebsiteHelper::isActiveRoute('admin.retail-objects.*') ? 'active' : '' }}">
                <i class="fas fa-cubes"></i> <span>@lang('retailobjects::admin.index')</span>
            </a>
        </h4>
    </div>
</div>
