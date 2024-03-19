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

        $view_path = '/users/index.php';
        require_once 'views/layouts/main.php';
        return;
    }

    public function create()
    {
        $validation = $this->validate($_POST);

        if(isset($validation['error']) && $validation['error']){
            echo json_encode($validation);
            return;
        }

        $request = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'colors' => isset($_POST['colors']) && !empty($_POST['colors']) ? $_POST['colors'] : null,
        ];

        $new_user = $this->user->insert($request);

        $response = [
            'success' => true,
            'user' => $new_user,
            'message' => 'Usuário adicionado com sucesso!',
            'model' => 'users',
        ];

        echo json_encode($response);
        return;
    }

    public function update($id)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT'){
            $validation = $this->validate($_POST);

            if(isset($validation['error']) && $validation['error']){
                echo json_encode($validation);
                return;
            }

            $request = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'colors' => isset($_POST['colors']) && !empty($_POST['colors']) ? $_POST['colors'] : null,
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
        return;
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
        return;
    }

    public function ajax()
    {
        $users = $this->user->get();

        echo json_encode($users);
        return;
    }

    public function validate($request)
    {
        if(empty($request['name']) || empty($request['email'])){
            $message = empty($request['name']) ? 'O campo Nome é obrigatório! <br>' : '';
            $message .= empty($request['email']) ? 'O campo Email é obrigatório!' : '';

            $response = [
                'error' => true,
                'message' => $message,
            ];

            return $response;
        }

        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.?([a-zA-Z]{1,})?$/", $request['email'])) {
            $response = [
                'error' => true,
                'message' => 'Endereço de Email precisa ser válido!',
            ];

            return $response;
        }

        return true;
    }
}