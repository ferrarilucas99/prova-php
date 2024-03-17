<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Novo usuário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/users/create" method="POST" id="add-form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" placeholder="Nome" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" placeholder="Email" name="email" required>
                        </div>
                        <div class="col-12">
                            <?php 
                                foreach($colors as $color){
                                    echo '<div class="form-check">
                                            <input class="form-check-input input-colors" type="checkbox" id="color-'.$color->id.'" name="colors[]" value="'.$color->id.'">
                                            <label class="form-check-label d-flex" for="color-'.$color->id.'">
                                                '.$color->name.'
                                                <span class="color" style="background-color: '.$color->name.'"></span>
                                            </label>
                                        </div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                        <span role="status">Adicionar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>