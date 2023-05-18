@extends('layouts.admin.app')

@section('content')
    @include('team::admin.breadcrumbs')
    @include('admin.notify')
    @include('admin.partials.index.top_search_with_mass_buttons', ['mainRoute' => Request::segment(2)])

    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <th class="width-2-percent"></th>
                    <th class="width-2-percent">{{ __('admin.number') }}</th>
                    <th>{{ __('retailobjects::admin.title') }}</th>
                    <th>{{ __('retailobjects::admin.email') }}</th>
                    <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                    </thead>
                    <tbody>
                    <tbody>
                    @if(count($retailObjects))
                            <?php $i = 1; ?>
                        @foreach($retailObjects as $retailObject)
                            <tr class="t-row row-{{$retailObject->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$retailObject->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>{{ $retailObject->title }}</td>
                                <td>{{ $retailObject->email }}</td>
                                <td class="pull-right">
                                @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(2), 'models' => $retailObjects, 'model' => $retailObject, 'showInPublicModal' => false])
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$retailObject->id}}-details hidden">
                                <td colspan="2"></td>
                                <td colspan="2">
                                    @include('admin.partials.index.table_details', ['model' => $retailObject, 'moduleName' => 'RetailObjects', 'hasChildrens' => false])
                                </td>
                                <td class="width-220">
                                    <img class="thumbnail img-responsive" src="{{ $retailObject->getFileUrl() }}"/>
                                </td>
                            </tr>
                                <?php $i++; ?>
                        @endforeach
                        <tr style="display: none;">
                            <td colspan="5" class="no-table-rows">{{ trans('team::admin.team.no_records') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="no-table-rows">{{ trans('team::admin.team.no_records') }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
