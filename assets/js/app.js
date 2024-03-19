var table = new DataTable('#myTable', {
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
    },
});

$('#add-form').on('submit', (e) => {
    e.preventDefault();

    var $this = $(e.currentTarget);
    var btn = $this.find('button[type=submit]');
    var spinner = $this.find('.spinner');
    var formData = new FormData(e.currentTarget);

    // btn.prop('disabled', true);
    // spinner.removeClass('d-none');

    $.ajax({
        url: $this.attr('action'),
        method: $this.attr('method'),
        data: formData,
        contentType: false,
        processData: false,
        success: (response) => {
            response = JSON.parse(response);

            if(response.success){
                mountDataTables(response.model);
                toastr.success(response.message);
                $this.trigger('reset');
                $('#add-modal').modal('hide');
            }else{
                if(response.error && response.message){
                    toastr.error(response.message);
                }else{
                    toastr.error('Erro ao cadastrar.');
                }
            }
        },
        error: (xhr) => {
            toastr.error('Erro na requisição.');
        }
    }).always(() => {
        btn.prop('disabled', false);
        spinner.addClass('d-none');
    })
});

$(document).on('click', '[data-color]', (e) => {
    var $this = $(e.currentTarget);
    var input_group = $this.next('.color-group');

    var color_simple = input_group.find('.color_simple_block');
    var color_specific = input_group.find('.color_specific_block');
    
    var color_simple_input = color_simple.find('input');
    var color_specific_input = color_specific.find('input');

    if(color_simple.hasClass('d-none') && !color_specific.hasClass('d-none')){
        $this.text('Usar cor específica');

        color_simple.removeClass('d-none');
        color_specific.addClass('d-none');

        color_simple_input.prop('required', true);
        color_specific_input.attr('name', '').prop('required', false);
    }else {
        $this.text('Usar cor simples');

        color_specific.removeClass('d-none');
        color_simple.addClass('d-none');

        color_specific_input.attr('name', 'color_specific').prop('required', true);
        color_simple_input.prop('required', false);
    }
});

$(document).on('click', 'button[data-edit]', (e) => {
    var $this = $(e.currentTarget);
    var modal = $('#edit-modal');
    var model = $this.data('model');
    var json = $this.data('json');
    clearModalInputs();
    setModalInputsValues(json, model);
    modal.modal('show')
});

$('form[data-edit]').on('submit', (e) => {
    console.log(e.currentTarget);
    e.preventDefault();

    var $this = $(e.currentTarget);
    var btn = $this.find('button[type=submit]');
    var spinner = $this.find('.spinner');
    var formData = new FormData(e.currentTarget);

    btn.prop('disabled', true);
    spinner.removeClass('d-none');

    $.ajax({
        url: $this.attr('action'),
        method: $this.attr('method'),
        data: formData,
        contentType: false,
        processData: false,
        success: (response) => {
            response = JSON.parse(response);
            if(response.success){
                mountDataTables(response.model);
                toastr.success(response.message);
                $this.trigger('reset');
                $('#edit-modal').modal('hide');
            }else{
                if(response.error && response.message){
                    toastr.error(response.message);
                }else{
                    toastr.error('Erro ao atualizar.');
                }
            }
        },
        error: (xhr) => {
            toastr.error('Erro na requisição.');
        }
    }).always(() => {
        btn.prop('disabled', false);
        spinner.addClass('d-none');
    });
});

$(document).on('submit', 'form[data-delete]', (e) => {
    e.preventDefault();

    var $this = $(e.currentTarget);
    var formData = new FormData(e.currentTarget);

    if(confirm('Deseja excluir este usuário? Está ação é irreversível!')){
        $.ajax({
            url: $this.attr('action'),
            method: $this.attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            success: (response) => {
                response = JSON.parse(response);
                if(response.success){
                    mountDataTables(response.model);
                    toastr.success(response.message);
                }
            },
            error: (xhr) => {
                toastr.error('Erro na requisição.');
            }
        });
    }
});

function mountDataTables(model){
    $('#myTable').DataTable().destroy();

    $('#myTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
        },
        ajax: {
            url: `/${model}/ajax`,
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { 
                data: model == 'users' ? 'email' : 'name',
                render: function(data){
                    if(model == 'users'){
                        return data;
                    }else{
                        return `<div class="color" style="background-color: ${data}">`;
                    }
                }
            },
            {
                data: null,
                render: function(rowData){
                    return `<button type="button" class="btn btn-primary" data-model="${model}" data-json='${JSON.stringify(rowData)}' data-edit>
                                Editar
                            </button>`;
                }
            },
            {
                data: 'id',
                render: function(data){
                    return `<form action="/${model}/delete/${data}" method="POST" data-delete>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">
                                    Excluir
                                </button>
                            </form>`;
                }
            }
        ],
    });
};

function clearModalInputs(){
    var modal = $('#edit-modal');
    var form = $('#edit-user');
    var colors = modal.find('input[name="colors[]"');
    var users = modal.find('input[name="users[]"');
    
    form.attr('action', '')
    modal.find('#user-id').text('');
    modal.find('#name-edit').val('');
    modal.find('#email-edit').val('');

    modal.find('.form-control-simple-color').val('');
    modal.find('.form-control-color').val('');

    if(colors.length > 0){
        colors.each((key, element) => {
            $(element).prop('checked', false);
        });
    }

    if(users.length > 0){
        users.each((key, element) => {
            $(element).prop('checked', false);
        });
    }
}

function setModalInputsValues(json, model){
    var modal = $('#edit-modal');
    var form = $('form[data-edit]');
    var colors = modal.find('input[name="colors[]"');
    var users = modal.find('input[name="users[]"');
    
    form.attr('action', `${window.location.origin}/${model}/update/${json.id}`)
    modal.find('#model-id').text(`#${json.id}`);
    modal.find('#name-edit').val(json.name);
    modal.find('#email-edit').val(json.email);

    modal.find('#color_simple_input').val(json.name);
    modal.find('#color_specific_input').val(json.name);

    if(Array.isArray(json.colors) && colors.length > 0){
        colors.each((key, element) => {
            if(json.colors.includes($(element).val())){
                $(element).prop('checked', true);
            }
        });
    }

    if(Array.isArray(json.users) && users.length > 0){
        users.each((key, element) => {
            if(json.users.includes($(element).val())){
                $(element).prop('checked', true);
            }
        });
    }
}