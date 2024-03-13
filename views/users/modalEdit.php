<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar usuário <span id="user-id"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="edit-user">
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name-edit" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name-edit" placeholder="Nome" name="name" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email-edit" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email-edit" placeholder="Email" name="email" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                        <span role="status">Salvar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>