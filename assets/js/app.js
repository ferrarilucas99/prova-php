var table = new DataTable('#myTable', {
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
    },
});

$('#add-user').on('submit', (e) => {
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
                mountDataTables();
                toastr.success(response.message);
                $this.trigger('reset');
                $('#add-modal').modal('hide');
            }else{
                toastr.error('Erro ao cadastrar.');
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

$(document).on('click', 'button[data-edit]', (e) => {
    var $this = $(e.currentTarget);
    var modal = $('#edit-modal');
    var json = $this.data('json');
    clearModalInputs();
    setModalInputsValues(json);
    modal.modal('show')
});

$('#edit-user').on('submit', (e) => {
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
                mountDataTables();
                toastr.success(response.message);
                $this.trigger('reset');
                $('#edit-modal').modal('hide');
            }else{
                toastr.error('Erro ao atualizar.');
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
                    mountDataTables();
                    toastr.success(response.message);
                }
            },
            error: (xhr) => {
                toastr.error('Erro na requisição.');
            }
        });
    }
});

function mountDataTables(){
    $('#myTable').DataTable().destroy();

    $('#myTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
        },
        ajax: {
            url: 'index.php?action=ajax',
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            {
                data: null,
                render: function(rowData){
                    return `<button type="button" class="btn btn-primary" data-json='${JSON.stringify(rowData)}' data-edit>
                                Editar
                            </button>`;
                }
            },
            {
                data: 'id',
                render: function(data){
                    return `<form action="index.php?action=destroy&id=${data}" method="POST" data-delete>
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
    form.attr('action', '')
    modal.find('#user-id').text('');
    modal.find('#name-edit').val('');
    modal.find('#email-edit').val('');
}

function setModalInputsValues(json){
    var modal = $('#edit-modal');
    var form = $('#edit-user');
    form.attr('action', `index.php?action=update&id=${json.id}`)
    modal.find('#user-id').text(`#${json.id}`);
    modal.find('#name-edit').val(json.name);
    modal.find('#email-edit').val(json.email);
}