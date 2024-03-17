<?php
namespace Controllers;

use Models\User;
use Models\Color;

class UsersController
{
    private $user;
    private $color;

    public function __construct()
    {
        $this->user = new User();
        $this->color = new Color();
    }

    public function index()
    {
        $users = $this->user->get();
        $colors = $this->color->get();

        require_once 'views/users/index.php';
    }

    public function create()
    {
        $request = [
            'name' => $_REQUEST['name'],
            'email' => $_REQUEST['email'],
            'colors' => isset($_REQUEST['colors']) && !empty($_REQUEST['colors']) ? $_REQUEST['colors'] : null,
        ];

        $new_user = $this->user->insert($request);

        $response = [
            'success' => true,
            'user' => $new_user,
            'message' => 'Usuário adicionado com sucesso!',
            'model' => 'users',
        ];

        echo json_encode($response);
    }

    public function update($id)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT'){
            $request = [
                'name' => $_REQUEST['name'],
                'email' => $_REQUEST['email'],
            ];
    
            $update_user = $this->user->update($request, $id);
    
            $response = [
                'success' => true,
                'user' => $update_user,
                'message' => 'Usuário atualizado com sucesso!',
                'model' => 'users',
            ];
        }else{
            $response = [
                'error' => true,
                'message' => 'Metodo não permitido!',
            ];
        }

        echo json_encode($response);
    }

    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
            try {
                $this->user->delete($id);

                $response = [
                    'success' => true,
                    'message' => 'Usuário removido com sucesso!',
                    'model' => 'users',
                ];
                
            } catch (\Throwable $th) {
                $response = [
                    'error' => true,
                    'message' => 'Erro ao excluir usuário: '. $th->getMessage(),
                ];
            }
        }else{
            $response = [
                'error' => true,
                'message' => 'Metodo não permitido!',
            ];
        }

        echo json_encode($response);
    }

    public function ajax()
    {
        $users = $this->user->get();

        echo json_encode($users);
    }
}