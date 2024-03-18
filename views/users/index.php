<section id="table" class="container mt-5">
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#add-modal">
                Adicionar
            </button>
            <table id="myTable" class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th width="30px">ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th width="55px"></th>
                        <th width="55px"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($users as $user) {
                        $json_escaped = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                        $html = '<tr>
                                            <td>' . $user->id . '</td>
                                            <td>' . $user->name . '</td>
                                            <td>' . $user->email . '</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-model="users" data-json="' . $json_escaped . '" data-edit>
                                                    Editar
                                                </button>
                                            </td>
                                            <td>
                                                <form action="/users/delete/' . $user->id . '" method="POST" data-delete>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>';

                        echo $html;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include_once 'modalAdd.php'; ?>

<?php include_once 'modalEdit.php'; ?>