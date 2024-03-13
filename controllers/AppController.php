<?php
namespace Controllers;

use Models\User;

class AppController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $users = $this->user->get();

        require_once 'views/users/index.php';
    }

    public function create()
    {
        $new_user = $this->user->insert($_REQUEST);

        $response = [
            'success' => true,
            'user' => $new_user,
            'message' => 'Usuário adicionado com sucesso!',
        ];

        echo json_encode($response);
    }

    public function update()
    {
        $update_user = $this->user->update($_REQUEST);

        $response = [
            'success' => true,
            'user' => $update_user,
            'message' => 'Usuário atualizado com sucesso!',
        ];

        echo json_encode($response);
    }

    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
            $id = $_REQUEST['id'];

            try {
                $this->user->delete($id);

                $response = [
                    'success' => true,
                    'message' => 'Usuário removido com sucesso!',
                ];
                
            } catch (\Throwable $th) {
                $response = [
                    'error' => true,
                    'message' => 'Erro ao excluir usuário: '. $th->getMessage(),
                ];
            }

            echo json_encode($response);
        }else{
            throw new \Exception("Method not allowed");
        }
    }

    public function ajax()
    {
        $users = $this->user->get();

        echo json_encode($users);
    }
}