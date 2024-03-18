<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cores</title>

    <!-- jQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- DATATABLES -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- TOASTR -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="../../assets/css/app.css">
</head>
<body>
    <?php
        include_once __DIR__ .'/../components/header.php';
    ?>

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
                            <th>Cor</th>
                            <th width="35px"></th>
                            <th width="55px"></th>
                            <th width="55px"></th>
                        </tr>
                    </thead>
            
                    <tbody>
                        <?php
                            foreach($colors as $color){
                                $json_escaped = htmlspecialchars(json_encode($color), ENT_QUOTES, 'UTF-8');
                                $html = '<tr>
                                            <td>'.$color->id.'</td>
                                            <td>'.$color->name.'</td>
                                            <td><div class="color" style="background-color: '.$color->name.'"></div></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-model="colors" data-json="'.$json_escaped.'" data-edit>
                                                    Editar
                                                </button>
                                            </td>
                                            <td>
                                                <form action="/colors/delete/'.$color->id.'" method="POST" data-delete>
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

    <!-- BOOTSTRAP 5.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- DATATABLES -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="../../assets/js/app.js"></script>
</body>
</html>