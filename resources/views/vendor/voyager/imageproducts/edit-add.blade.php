@php
	$edit = !is_null($dataTypeContent->getKey());
	$add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
	<h1 class="page-title">
		<i class="{{ $dataType->icon }}"></i>
		{{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
	</h1>
	@include('voyager::multilingual.language-selector')
@stop

@section('content')
	<div class="page-content edit-add container-fluid">
		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-bordered">
					<!-- form start -->
					<form role="form"
						class="form-edit-add"
						action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
						method="POST" enctype="multipart/form-data">
					<!-- PUT Method if we are editing -->
					@if($edit)
						{{ method_field("PUT") }}
					@endif

					<!-- CSRF TOKEN -->
					{{ csrf_field() }}

					<div class="panel-body">
						<div class="col-md-9">
							
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							<!-- Adding / Editing -->
							@php
									$dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
							@endphp

							@foreach($dataTypeRows as $row)
								<!-- GET THE DISPLAY OPTIONS -->
								@php
									$display_options = $row->details->display ?? NULL;
									if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
										$dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
									}
								@endphp
								@if (isset($row->details->legend) && isset($row->details->legend->text))
									<legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
								@endif

								<div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
									{{ $row->slugify }}
									<label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
									@include('voyager::multilingual.input-hidden-bread-edit-add')
									@if ($add && isset($row->details->view_add))
										@include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details])
									@elseif ($edit && isset($row->details->view_edit))
										@include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details])
									@elseif (isset($row->details->view))
										@include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
									@elseif ($row->type == 'relationship')
										@include('voyager::formfields.relationship', ['options' => $row->details])
									@else
										{!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
									@endif

									@foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
										{!! $after->handle($row, $dataType, $dataTypeContent) !!}
									@endforeach
									@if ($errors->has($row->field))
										@foreach ($errors->get($row->field) as $error)
											<span class="help-block">{{ $error }}</span>
										@endforeach
									@endif
								</div>
							@endforeach
						</div>
						<div class="col-md-3">
							<h3 class="panel-title">Selecciona la Categoría</h3>
							<div class="categories-product">
								@foreach($categories as $category)
									<div class="form-check">
										<input type="checkbox" name="category[]" value="{{ $category->id }}" 
											class="form-check-input" 
											@if ($category->selected)
												checked
											@endif
											>
										<label class="form-check-label">{{ $category->name }}</label>
									</div>
                @endforeach
							</div>
						</div>
					</div><!-- panel-body -->

					<div class="panel-body">
						<div class="form-gruop">
							<input type="hidden" name="setting_tab" class="setting_tab" />
							<div class="panel-heading new-setting">
								<hr>
								<h3 class="panel-title">Agregar Textos del registro</h3>
								<div id="messsage-item" class="text-danger"></div>
							</div>
							<div class="col-md-3">
								<label for="title">título</label>
								<input type="text" class="form-control" name="title" id="title" placeholder="Escribe el título">
							</div>
							<div class="col-md-3">
								<label for="description">Descrición</label>
								<textarea class="form-control" name="description" id="description" placeholder="Descripción del modelo" ></textarea>
							</div>
							<div class="col-md-3">
								<label for="group">Idioma</label>
								<select class="form-control group_select group_select_new" name="lang" id="lenguage">
									@foreach($languages as $language)
										<option value="{{ $language->id }}">{{ $language->abreviatura }} - {{ $language->name }}</option>
									@endforeach
								</select>
							</div>
							<button type="button" class="btn btn-primary pull-right new-setting-btn" 
								id="itemTextsImageProducts" onclick="addItemImageProducts()">
								<i class="voyager-plus"></i>Agregar texto
							</button>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-12">
								<div class="card px2">
									<div class="card-body">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">Título</th>
												<th scope="col">Descripción</th>
												<th scope="col">Idioma</th>
												<th scope="col">Acciones</th>
											</tr>
										</thead>
										<tbody id="columnItemText">
											@foreach($itemTexts as $key => $value)
												<tr id="item-{{ $value->language_id }}">												
													<td>
														{{ $value->title }}
														<input type="hidden" name="title[]" value="{{ $value->title }}" />
													</td>
													<td>
                            {{ $value->description }}
                            <input type="hidden" name="description[]" value="{{ $value->description }}" />
                          </td>
                          <td>
														{{ $value->abreviatura.' - '.$value->name }}
                            <input type="hidden" name="language[]" value="{{ $value->language_id }}" />                            
                          </td>
                          <td> 
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem({{ $value->language_id }})">-</button>
                          </td>
												</tr>
											@endforeach
										</tbody>
									</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					@section('item-text-imageproducts')

					@endsection

					<div class="panel-footer">
							@section('submit-buttons')
									<button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
							@stop
							@yield('submit-buttons')
					</div>
					</form>

					<div style="display:none">
						<input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
						<input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade modal-danger" id="confirm_delete_modal">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
				</div>

				<div class="modal-body">
					<h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
					<button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Delete File Modal -->
@stop

@section('javascript')
	<script>
		var params = {};
		var $file;

		function deleteHandler(tag, isMulti) {
			return function() {
				$file = $(this).siblings(tag);

				params = {
					slug:   '{{ $dataType->slug }}',
					filename:  $file.data('file-name'),
					id:     $file.data('id'),
					field:  $file.parent().data('field-name'),
					multi: isMulti,
					_token: '{{ csrf_token() }}'
				}

				$('.confirm_delete_name').text(params.filename);
				$('#confirm_delete_modal').modal('show');
			};
		}

		$('document').ready(function () {
			$('.toggleswitch').bootstrapToggle();

			//Init datepicker for date fields if data-datepicker attribute defined
			//or if browser does not handle date inputs
			$('.form-group input[type=date]').each(function (idx, elt) {
				if (elt.hasAttribute('data-datepicker')) {
					elt.type = 'text';
					$(elt).datetimepicker($(elt).data('datepicker'));
				} else if (elt.type != 'date') {
					elt.type = 'text';
					$(elt).datetimepicker({
						format: 'L',
						extraFormats: [ 'YYYY-MM-DD' ]
					}).datetimepicker($(elt).data('datepicker'));
				}
			});

			@if ($isModelTranslatable)
				$('.side-body').multilingual({"editing": true});
			@endif

			$('.side-body input[data-slug-origin]').each(function(i, el) {
				$(el).slugify();
			});

			$('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
			$('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
			$('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
			$('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

			$('#confirm_delete').on('click', function(){
				$.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
					if ( response
						&& response.data
						&& response.data.status
						&& response.data.status == 200 ) {

						toastr.success(response.data.message);
						$file.parent().fadeOut(300, function() { $(this).remove(); })
					} else {
						toastr.error("Error removing file.");
					}
				});

				$('#confirm_delete_modal').modal('hide');
			});
			$('[data-toggle="tooltip"]').tooltip();

		});
		function addItemImageProducts(){
			let title = $('#title').val()
			let description = $('#description').val()
			let language = $('#lenguage option:selected')
			$('#messsage-item').html('')
			if(title == '' || description == ''){
				$('#messsage-item').html('Los campos son obligatorios')
			}else {
				if ($('#item-'+language.val()).length) {
					$('#messsage-item').html('Ya existe un Item con el mismo idioma ' + language.text())
				}else{
					$('#columnItemText').append(`
						<tr id="item-${language.val()}">												
							<td>
								${title}
								<input type="hidden" name="title[]" value="${title}" />
							</td>
							<td>
								${description}
								<input type="hidden" name="description[]" value="${description}" />
							</td>
							<td>
								<input type="hidden" name="language[]" value="${language.val()}" />
								${language.text()}
							</td>
							<td> 
								<button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(${language.val()})">-</button>
							</td>
						</tr>
					`)
					$('#title').val('')
					$('#description').val('')
				}
			}
		}

		function deleteItem(id){
      $('#item-'+id).remove()
    }

	</script>
@stop
