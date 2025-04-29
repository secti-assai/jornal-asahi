<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('upload')) {
            return response()->json([
                'error' => [
                    'message' => 'Nenhum arquivo foi enviado.'
                ]
            ], 400);
        }

        $uploadedFile = $request->file('upload');
        
        if (!$uploadedFile->isValid()) {
            Log::error('Arquivo inválido: ' . $uploadedFile->getErrorMessage());
            return response()->json([
                'error' => [
                    'message' => 'O arquivo enviado é inválido.'
                ]
            ], 400);
        }

        try {
            // Salvar o arquivo - APENAS SALVAR O ARQUIVO, sem tentar inserir no banco
            $path = $uploadedFile->store('news_content_images', 'public');
            $url = asset('storage/' . $path);
            
            Log::info('CKEditor - Arquivo salvo com sucesso: ' . $path);
            Log::info('CKEditor - URL gerada: ' . $url);

            // MUDANÇA IMPORTANTE: Usar o formato esperado pelo CKEditor 5
            return response()->json([
                'uploaded' => 1,  // Importante: use 1 não true
                'fileName' => $uploadedFile->getClientOriginalName(),
                'url' => $url
            ]);
        } catch (\Exception $e) {
            Log::error('CKEditor - Erro ao salvar arquivo: ' . $e->getMessage());
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => 'Erro ao salvar o arquivo: ' . $e->getMessage()
                ]
            ], 500);
        }
    }
}