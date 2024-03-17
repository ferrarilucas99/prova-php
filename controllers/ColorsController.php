<?php
namespace Controllers;

use Models\Color;

class ColorsController
{
    private $color;

    public function __construct()
    {
        $this->color = new Color();
    }

    public function index()
    {
        $colors = $this->color->get();

        require_once 'views/colors/index.php';
    }

    public function create()
    {
        $request = [
            'name' => isset($_REQUEST['color_specific']) ? $_REQUEST['color_specific'] : $_REQUEST['color_simple'],
        ];

        $new_color = $this->color->insert($request);

        $response = [
            'success' => true,
            'color' => $new_color,
            'message' => 'Cor adicionada com sucesso!',
            'model' => 'colors',
        ];

        echo json_encode($response);
    }

    public function update($id)
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT'){
            $request = [
                'name' => isset($_REQUEST['color_specific']) ? $_REQUEST['color_specific'] : $_REQUEST['color_simple'],
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
    }

    public function ajax()
    {
        $colors = $this->color->get();

        echo json_encode($colors);
    }
}