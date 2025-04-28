<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('CKEditor upload iniciado');
        Log::info('Conteúdo da requisição:', $request->all());
        
        // Verificar diferentes nomes de campo que o CKEditor pode usar
        $uploadedFile = $request->file('upload');
        
        if (!$uploadedFile) {
            $uploadedFile = $request->file('file');
        }
        
        if (!$uploadedFile) {
            // Verificar se há qualquer arquivo na requisição
            $allFiles = $request->allFiles();
            if (!empty($allFiles)) {
                $uploadedFile = reset($allFiles);
            }
        }
        
        if ($uploadedFile) {
            Log::info('Arquivo recebido: ' . $uploadedFile->getClientOriginalName());
            
            try {
                // Validar o arquivo
                $validator = validator([
                    'arquivo' => $uploadedFile
                ], [
                    'arquivo' => 'image|max:2048'
                ]);
                
                if ($validator->fails()) {
                    Log::error('Validação falhou: ' . $validator->errors()->first());
                    return response()->json([
                        'uploaded' => 0,
                        'error' => [
                            'message' => 'Arquivo inválido: ' . $validator->errors()->first()
                        ]
                    ], 422);
                }
                
                // Salvar o arquivo
                $path = $uploadedFile->store('news_content_images', 'public');
                $url = asset('storage/' . $path);
                
                Log::info('Arquivo salvo com sucesso: ' . $path);
                Log::info('URL gerada: ' . $url);
                
                // Formato de resposta esperado pelo CKEditor
                return response()->json([
                    'uploaded' => 1,
                    'fileName' => $uploadedFile->getClientOriginalName(),
                    'url' => $url
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao processar upload: ' . $e->getMessage());
                return response()->json([
                    'uploaded' => 0,
                    'error' => [
                        'message' => 'Erro ao processar o upload: ' . $e->getMessage()
                    ]
                ], 500);
            }
        }
        
        Log::warning('Nenhum arquivo encontrado na requisição');
        Log::warning('Campos disponíveis: ' . json_encode($request->keys()));
        
        return response()->json([
            'uploaded' => 0,
            'error' => [
                'message' => 'Nenhum arquivo encontrado na requisição'
            ]
        ], 400);
    }
}