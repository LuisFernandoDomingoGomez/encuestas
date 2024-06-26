@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Form
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                <h2>Crea tu formulario</h2>
                    <input type="text" id="formName" placeholder="Nombre del formulario" class="form-control mb-2">
                    <div id="fields">
                        <!-- Aquí se agregarán los campos dinámicamente -->
                    </div>
                    <button id="addField" class="btn btn-primary mb-2">Agregar Campo</button>
                    <button id="saveForm" class="btn btn-success">Guardar Formulario</button>
                </div>

            </div>
        </div>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let fields = [];

    $('#addField').click(function() {
        if (fields.length >= 10) {
            alert('No puedes agregar más de 10 preguntas.');
            return;
        }

        const fieldIndex = fields.length;
        fields.push({ label: '', type: 'text', options: [] });

        $('#fields').append(`
            <div class="field mb-2" data-index="${fieldIndex}">
                <input type="text" class="form-control mb-1 field-label" placeholder="Etiqueta del campo">
                <select class="form-control mb-1 field-type">
                    <option value="text">Texto</option>
                    <option value="number">Número</option>
                    <option value="date">Fecha</option>
                    <option value="select">Selección Múltiple</option>
                    <option value="checkbox">Checkbox</option>
                </select>
                <div class="options mt-2"></div>
                <button class="btn btn-danger removeField">Eliminar</button>
            </div>
        `);
    });

    $(document).on('change', '.field-type', function() {
        const fieldType = $(this).val();
        const fieldIndex = $(this).closest('.field').data('index');
        const optionsContainer = $(this).siblings('.options');

        fields[fieldIndex].type = fieldType;

        if (fieldType === 'select' || fieldType === 'checkbox') {
            optionsContainer.html(`
                <button class="btn btn-secondary mb-2 addOption">Agregar Opción</button>
            `);
        } else {
            optionsContainer.html('');
        }
    });

    $(document).on('click', '.addOption', function() {
        const fieldIndex = $(this).closest('.field').data('index');
        const optionsContainer = $(this).parent();

        fields[fieldIndex].options.push('');

        optionsContainer.append(`
            <div class="option mb-1">
                <input type="text" class="form-control option-value" placeholder="Opción">
                <button class="btn btn-danger removeOption">Eliminar</button>
            </div>
        `);
    });

    $(document).on('click', '.removeOption', function() {
        const fieldIndex = $(this).closest('.field').data('index');
        const optionIndex = $(this).parent().index() - 1; // Adjusted the index to account for the Add Option button

        fields[fieldIndex].options.splice(optionIndex, 1);
        $(this).parent().remove();
    });

    $(document).on('click', '.removeField', function() {
        const fieldIndex = $(this).parent().data('index');
        fields.splice(fieldIndex, 1);
        $(this).parent().remove();
    });

    $('#saveForm').click(function() {
        $('#fields .field').each(function(index, element) {
            const fieldIndex = $(element).data('index');
            fields[fieldIndex].label = $(element).find('.field-label').val();
            fields[fieldIndex].type = $(element).find('.field-type').val();

            const options = [];
            $(element).find('.option-value').each(function(optionIndex, optionElement) {
                options.push($(optionElement).val());
            });
            fields[fieldIndex].options = options;
        });

        const formName = $('#formName').val();

        $.ajax({
            url: '{{ route("forms.store") }}',
            method: 'POST',
            data: {
                name: formName,
                structure: fields,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                window.location.href = '{{ route("forms.index") }}';
            }
        });
    });
});
</script>