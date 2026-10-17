<?php

namespace App\Controller;

use App\Service\AdminService;
use Exception;

class AdminController {
    private AdminService $service;

    public function __construct() {
        $this->service = new AdminService();
    }

    
    public function criar() : void {
        try {
            $dados = json_decode(
                file_get_contents("php://input"),
                true
            );

            $admin = $this->service->criar(
                $dados["email"],
                $dados["senha"],
            );

            http_response_code(201);

            echo json_encode([
                "id" => $admin->id,
                "email" => $admin->email,
                "senha" => $admin->senha,
            ]);


        } catch (Exception $e) {
            http_response_code(400);

            echo json_encode([
                "erro" => $e->getMessage()
            ]);
        }
    }

}