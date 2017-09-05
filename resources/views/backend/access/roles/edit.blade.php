@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.roles.management') . ' | ' . trans('labels.backend.access.roles.edit'))
{{ Html::style('css//default/style.min.css') }}
  
@section('page-header')
    <h1>
        {{ trans('labels.backend.access.roles.management') }}
        <small>{{ trans('labels.backend.access.roles.edit') }}</small>
    </h1>
@endsection

@section('content')

    {{ Form::model($role, ['route' => ['admin.access.role.update', $role], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'id' => 'edit-role']) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.access.roles.edit') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.role-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.access.roles.name'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.roles.name')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->



                <div class="form-group">
                    {{ Form::label('sort', trans('validation.attributes.backend.access.roles.sort'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('sort', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.access.roles.sort')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
            </div><!-- /.box-body -->

            <div class="col-md-4">
                <h5>Permissions</h5>
                <div class="form-group">
                    <div class="col-lg-10">
                        @if ($role->id != 1)
                            {{-- Administrator has to be set to all --}}
                            {{ Form::select('associated-permissions', ['all' => 'All', 'custom' => 'Custom'], $role->all ? 'all' : 'custom', ['class' => 'form-control']) }}
                        @else
                            <span class="label label-success">{{ trans('labels.general.all') }}</span>
                        @endif

                        <div id="available-permissions" class="hidden mt-20">
                            <div class="row">
                                <div class="col-xs-12">
                                    
                                    <div id="ajax" class="demo">
                                        <!--<ul>
                                            <ul role="group" class="jstree-children">
                                                <li role="treeitem" data-dependencies="[]" aria-selected="true" aria-level="2" aria-labelledby="31_anchor" id="31" class="jstree-node  jstree-leaf">
                                                    <i class="jstree-icon jstree-ocl" role="presentation"></i>
                                                    <a class="jstree-anchor  jstree-clicked" href="#" tabindex="-1" id="31_anchor">
                                                        <i class="jstree-icon jstree-checkbox" role="presentation"></i>
                                                        <i class="jstree-icon jstree-themeicon" role="presentation"></i>
                                                        View Client Backend
                                                    </a>
                                                </li>
                                        </ul>-->

                                    </div>

                                    {{-- dd(json_encode($permissions)) }}
                                    @if ($permissions->count())
                                        @foreach ($permissions as $perm)
                                            <input type="checkbox" name="permissions[{{ $perm->id }}]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" {{ is_array(old('permissions')) ? (in_array($perm->id, old('permissions')) ? 'checked' : '') : (in_array($perm->id, $role_permissions) ? 'checked' : '') }} /> <label for="perm_{{ $perm->id }}">{{ $perm->display_name }}</label><br/>
                                        @endforeach
                                    @else
                                        <p>There are no available permissions.</p>
                                    @endif --}}
                                </div><!--col-lg-6-->
                            </div><!--row-->
                        </div><!--available permissions-->
                    </div><!--col-lg-3-->
                    
                </div><!--form control-->
            </div>

            </div>
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.access.role.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {{ Form::hidden('permissions', null, ['id' => 'permissions'])}}
    {{ Form::close() }}
@endsection

@section('after-scripts')
    {{ Html::script('js/jstree/jstree.min.js') }}
    {{ Html::script('js/backend/access/roles/script.js') }}
    <script>
        
    jQuery('#ajax').jstree({
         "checkbox" : {
            "keep_selected_style" : false
        },
        "plugins" : [ "checkbox" ],
        'core' : {
            'data' : {!! json_encode($permissions) !!}
        }
    });

    jQuery(document).ready(function()
    {
        var form    = document.getElementById("edit-role");
        
        //Get list of the checked items and send them to the serer
        form.onsubmit = function()
        {
            var associated      = document.querySelector("input[name='permissions']"),
                checked_ids     = jQuery('#ajax').jstree("get_checked", false);

            jQuery("#permissions").val(checked_ids);
            return true;
        }
    });
    </script>
@endsection