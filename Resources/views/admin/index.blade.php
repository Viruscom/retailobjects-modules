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
                    <th>{{ __('team::admin.team.title') }}</th>
                    <th>{{ __('team::admin.team.email') }}</th>
                    <th class="width-220 text-right">{{ __('admin.actions') }}</th>
                    </thead>
                    <tbody>
                    <tbody>
                    @if(count($teamMembers))
                            <?php $i = 1; ?>
                        @foreach($teamMembers as $teamMember)
                            <tr class="t-row row-{{$teamMember->id}}">
                                <td class="width-2-percent">
                                    <div class="pretty p-default p-square">
                                        <input type="checkbox" class="checkbox-row" name="check[]" value="{{$teamMember->id}}"/>
                                        <div class="state p-primary">
                                            <label></label>
                                        </div>
                                    </div>
                                </td>
                                <td class="width-2-percent">{{$i}}</td>
                                <td>{{ $teamMember->title }}</td>
                                <td>{{ $teamMember->email }}</td>
                                <td class="pull-right">
                                @include('admin.partials.index.action_buttons', ['mainRoute' => Request::segment(2), 'models' => $teamMembers, 'model' => $teamMember, 'showInPublicModal' => false])
                                </td>
                            </tr>
                            <tr class="t-row-details row-{{$teamMember->id}}-details hidden">
                                <td colspan="2"></td>
                                <td colspan="2">
                                    <table class="table-details-titles">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <p>{{$teamMember->title}}</p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table-details">
                                        <tbody>
                                        <tr>
                                            <td width="28%">SEO:
                                                @if ($teamMember->seo_description != "")
                                                    <span class="text-purple"><i class="fa fa-check"></i></span>
                                                @else
                                                    <span class="font-grey"><i class="fa fa-times"></i></span>
                                                @endif
                                            </td>
                                            <td width="19%">снимки в хедър/галерия:
                                                <span class="text-purple">{{$teamMember->in_header}}/{{$teamMember->in_gallery}}</span>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table-details-buttons">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <span class="margin-right-10">SEO</span>
                                                <a class="btn btn-sm green" href="{{ url('/admin/seo/page/'.$teamMember->id.'/edit') }}" role="button"><i class="fas fa-pencil-alt"></i></a>
                                            </td>
                                            <td>
                                                <span class="margin-right-10">Галерия</span>
                                                {{--                                                <a class="btn btn-sm green" href="{{ url('/admin/galleries/'.$galleryContentPageTypeId.'/'.$teamMember->id.'/create') }}" role="button"><i class="fa fa-plus"></i></a>--}}
                                                {{--                                                <a class="btn btn-sm purple-a" href="{{ url('/admin/galleries/loadGalleries/'.$galleryContentPageTypeId.'/'.$teamMember->id.'/') }}" role="button"><i class="fa fa-bars"></i></a>--}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td class="width-220">
                                    <img class="thumbnail img-responsive" src="{{ $teamMember->getFileUrl() }}"/>
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
