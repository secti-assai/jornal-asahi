<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class PopulateNewsImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:populate-images {--force : Continue mesmo se a estrutura da tabela não estiver perfeita}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate news_images table with existing images from news content';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Verificar se a tabela news_images existe
        if (!Schema::hasTable('news_images')) {
            $this->error('A tabela news_images não existe. Execute as migrações primeiro.');
            return Command::FAILURE;
        }

        // Verificar qual coluna existe: is_cover ou is_featured
        $coverColumnName = 'is_cover';
        if (!Schema::hasColumn('news_images', 'is_cover')) {
            if (Schema::hasColumn('news_images', 'is_featured')) {
                $coverColumnName = 'is_featured';
                $this->warn("Coluna 'is_cover' não encontrada, usando 'is_featured' em seu lugar.");
                
                if (!$this->option('force')) {
                    if (!$this->confirm('Deseja continuar mesmo assim?')) {
                        return Command::FAILURE;
                    }
                }
            } else {
                $this->error('Nem a coluna is_cover nem is_featured existem na tabela news_images.');
                return Command::FAILURE;
            }
        }

        $this->info('Iniciando processamento das imagens...');
        $news = News::all();
        $totalNewsProcessed = 0;
        $totalImagesFound = 0;

        foreach ($news as $newsItem) {
            $this->info("Processando notícia ID: {$newsItem->id} - {$newsItem->title}");
            
            // Processar imagem de capa
            if ($newsItem->image) {
                // Verificar se já existe
                $query = NewsImage::where('news_id', $newsItem->id)
                                 ->where('path', $newsItem->image);
                
                if ($coverColumnName === 'is_cover') {
                    $query->where('is_cover', true);
                } else {
                    $query->where('is_featured', true);
                }
                
                $exists = $query->exists();
                
                if (!$exists) {
                    $data = [
                        'news_id' => $newsItem->id,
                        'path' => $newsItem->image,
                        'caption' => $newsItem->title
                    ];
                    
                    if ($coverColumnName === 'is_cover') {
                        $data['is_cover'] = true;
                    } else {
                        $data['is_featured'] = true;
                    }
                    
                    NewsImage::create($data);
                    
                    $totalImagesFound++;
                    $this->line("  → Imagem de capa registrada");
                }
            }
            
            // Processar imagens do conteúdo
            preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i', $newsItem->content, $matches);
            
            if (!empty($matches[1])) {
                foreach ($matches[1] as $imageUrl) {
                    // Verificar se a URL é de uma imagem armazenada no storage
                    if (strpos($imageUrl, 'storage/news_content_images') !== false) {
                        // Extrair o caminho relativo da URL
                        $path = str_replace(url('storage/'), '', $imageUrl);
                        
                        // Verificar se esta imagem já está registrada
                        $exists = NewsImage::where('news_id', $newsItem->id)
                                         ->where('path', $path)
                                         ->exists();
                        
                        if (!$exists) {
                            $data = [
                                'news_id' => $newsItem->id,
                                'path' => $path,
                            ];
                            
                            if ($coverColumnName === 'is_cover') {
                                $data['is_cover'] = false;
                            } else {
                                $data['is_featured'] = false;
                            }
                            
                            NewsImage::create($data);
                            
                            $totalImagesFound++;
                            $this->line("  → Imagem de conteúdo registrada: {$path}");
                        }
                    }
                }
            }
            
            $totalNewsProcessed++;
        }
        
        $this->info("Processamento finalizado!");
        $this->info("{$totalNewsProcessed} notícias processadas.");
        $this->info("{$totalImagesFound} novas imagens registradas.");
        
        if ($coverColumnName === 'is_featured') {
            $this->warn("IMPORTANTE: A coluna utilizada foi 'is_featured' em vez de 'is_cover'.");
            $this->warn("Execute a migração para renomear a coluna de 'is_featured' para 'is_cover'.");
        }
        
        return Command::SUCCESS;
    }
}