<?php
namespace Controllers;

use Models\Color;
use Models\User;

class ColorsController
{
    private $color;
    private $user;

    public function __construct()
    {
        $this->color = new Color();
        $this->user = new User();
    }

    public function index()
    {
        $colors = $this->color->get();
        $users = $this->user->get();

        $view_path = '/colors/index.php';
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
            'name' => isset($_POST['color_specific']) ? $_POST['color_specific'] : $_POST['color_simple'],
            'users' => isset($_POST['users']) ? $_POST['users'] : null,
        ];

        $new_color = $this->color->insert($request);

        $response = [
            'success' => true,
            'color' => $new_color,
            'message' => 'Cor adicionada com sucesso!',
            'model' => 'colors',
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
                'name' => isset($_POST['color_specific']) ? $_POST['color_specific'] : $_POST['color_simple'],
                'users' => isset($_POST['users']) && !empty($_POST['users']) ? $_POST['users'] : null,
            ];

            $update_color = $this->color->update($request, $id);
    
            $response = [
                'success' => true,
                'user' => $update_color,
                'message' => 'Cor atualizada com sucesso!',
                'model' => 'colors'
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
                $this->color->delete($id);

                $response = [
                    'success' => true,
                    'message' => 'Cor removida com sucesso!',
                    'model' => 'colors',
                ];
                
            } catch (\Throwable $th) {
                $response = [
                    'error' => true,
                    'message' => 'Erro ao excluir cor: '. $th->getMessage(),
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
        $colors = $this->color->get();

        echo json_encode($colors);
        return;
    }

    public function validate($request)
    {
        if(empty($request['color_simple'])){
            if(!isset($request['color_specific']) || empty($request['color_specific'])){
                $message = 'O campo Cor simples ou Cor específica é obrigatório!';

                $response = [
                    'error' => true,
                    'message' => $message,
                ];

                return $response;
            }else{
                return true;
            }
        }

        return true;
    }
}