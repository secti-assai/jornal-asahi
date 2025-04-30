<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lista de usuários do formulário
        $users = [
            [
                'name' => 'Amanda Alves do Nascimento',
                'username' => 'amanda_alves',
                'email' => 'alvesdonascimentoamanda@gmail.com',
                'description' => 'Meu nome é Amanda Alves, sou uma pessoa movida por propósito e gosto de estar no meio do que faz sentido, do que transforma e inspira.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Ana Laura Janegitz',
                'username' => 'ana_laura',
                'email' => 'anajanegitz234@icloud.com',
                'description' => 'Tenho 15 anos e sou apaixonada pela área da comunicação, gosto e sempre gostei de me expressar através de vídeos (só nunca tive coragem de posta-los), cada vez estou me encontrando mais por meio de projetos incríveis. ❤️',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Brayan Kevin Bignardi dos Santos Oliveira',
                'username' => 'brayan_bignard',
                'email' => 'Brayanbignard12@gmail.com',
                'description' => 'Brayan tem 15 anos, é curioso por natureza e apaixonado por mudanças. Gosta de descobrir coisas novas, viver experiências diferentes e compartilhar tudo isso com leveza e bom humor. Cheio de energia e sempre de bem com a vida, Brayan está em constante evolução, buscando crescer, aprender e inspirar quem está ao seu redor.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Eloah Kamilly Soares do Nascimento',
                'username' => 'eloah_nascimento',
                'email' => 'Eloah.soares.nascimento@escola.pr.gov.br',
                'description' => 'Sou uma pessoa fácil de lidar, com opinião própria, e me considero um pouco simpática',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Emanuella da Silva Duarte',
                'username' => 'emanuella_duarte',
                'email' => 'emanuella.silva.duarte@gmail.com',
                'description' => 'Estou atualmente aluna do 1º ano do Ensino Médio, dedicada, e em constante busca por novos aprendizados. Interessada em ampliar seus conhecimentos acadêmicos.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Emanuelly da Silva Antunes',
                'username' => 'emanuelly',
                'email' => 'emanuellyantunes29@gmail.com',
                'description' => 'Meu nome é Manu sou uma menina extrovertida, que adora fazer novas amizades. Amo criar vídeos criativos e me expressar de forma leve e alegre. Estou sempre buscando aprender e crescer, sem deixar de ser quem sou.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter (Âncora)
            ],
            [
                'name' => 'Felipe Tomaz Rocha',
                'username' => 'felipe_tomaz',
                'email' => 'felipetomazrocha2@gmail.com',
                'description' => 'Eu me chamo Felipe mas me chamam de Felps, eu gosto de jogar futebol e videogame, eu sou um dos reportes mirims, sou repórter de rua.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Giovana Emanuelly Lira da Silva',
                'username' => 'giovana_emanuelly',
                'email' => 'giovanaliradasilva8@gmail.com',
                'description' => 'Meu nome é Giovana Emanuelly Lira, tenho 15 anos e sou reporte do Jornal Asahi, como reporte de rua.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Giovanna Freitas Araujo',
                'username' => 'giovanna_araujo',
                'email' => 'freitasgiovanna101@gmail.com',
                'description' => 'Meu nome é Giovanna Freitas Araújo e nasci no dia 10 de novembro de 2009, em Assaí. Sou uma garota sonhadora, curiosa e cheia de vontade de aprender. Moro com meus pais e meu irmão mais velho, e nossa casa está sempre cheia de risadas e alegria.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Guilherme César de Paula',
                'username' => 'guilherme_cesar',
                'email' => 'Guilherme.cezar.paula@gmail.com',
                'description' => 'Sou uma pessoa competitiva, comunicativa é apaixonado por aprender coisas novas. Gosto de colaborar em projetos criativos, desafios que me ajudem a crescer não só nos estudos.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter (Fotógrafo)
            ],
            [
                'name' => 'Isabelly Ribeiro dos Santos',
                'username' => 'isabelly',
                'email' => 'ribeirodossantosi897@gmail.com',
                'description' => 'Então eu me chama Isabelly, eu tenho 11 anos vou fazer 12 anos em 9 de maio nasci dia 9/05/2013, nasci no Assaí, faz 11 anos que moro no Assaí, estudo no Walerian Wrosz, estou no sexto ano A, estou no projeto ciências maker tendo repórter mirim, sou bem participativa, prestativa, entre outras qualidades e amo muito minha cidade natal.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Jyovanna Lorena Rodrigues Custódio',
                'username' => 'jyovanna_custodio',
                'email' => 'jyovanna.custodio@escola.gov.br',
                'description' => 'Meu nome é Jyovanna Lorena Rodrigues Custódio e nasci no dia 20 de agosto de 2010, em Assai. Sou uma garota sonhadora, curiosa e cheia de vontade de aprender coisas novas.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Kamila Kaori Galassio Tamae',
                'username' => 'kamila_kaori',
                'email' => 'kaoritamae13@gmail.com',
                'description' => 'Sou estudante do 3º ano do Ensino Médio, apaixonada por comunicação e criatividade. Atuo como criadora de conteúdo digital, com foco em marketing, fotografia e redes sociais. Comprometida com causas sociais e com olhar atento às transformações do mundo, sempre busco inovar, aprender e contribuir com ideias que inspiram a juventude e valorizam a comunidade local.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Âncora 
            ],
            [
                'name' => 'Karoline Araújo',
                'username' => 'karoline_araujo',
                'email' => 'karoline.araujo@escola.pr.gov.br',
                'description' => 'Sou comunicativa, gosto de aprender e de ajudar os outros. Estou sempre em busca de novos desafios.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Klara',
                'username' => 'klara',
                'email' => 'klara@escola.pr.gov.br',
                'description' => 'Olá, sou a Klara! Adoro comunicação e criação de conteúdo para mídias digitais.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Lara Garcia Simonini',
                'username' => 'lara_simonini',
                'email' => 'larag.simonini@gmail.com',
                'description' => 'Meu nome é Lara Garcia Simonini, eu tenho 12 anos, gosto de jogar beach tennis, me dedico aos estudos e amo minha família',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Laura Alves',
                'username' => 'laura_alves',
                'email' => 'laura20132508@gmail.com',
                'description' => 'Bom eu sou a Laura, tenho 12 anos, estudo no Colégio Estadual Conselheiro Carrão e moro aquiem Assaí.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Âncora
            ],
            [
                'name' => 'Laura Lucatto Ribeiro Silva',
                'username' => 'laura_lucatto',
                'email' => 'laura.lucatto@escola.pr.gov.br',
                'description' => 'Sou comunicativa e adoro interagir com as pessoas. Gosto de descobrir histórias interessantes e compartilhar conhecimento.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Laura Nakamura Souza',
                'username' => 'laura_nakamura',
                'email' => 'laura.nakamura.souza@escola.pr.gov.br',
                'description' => 'Sou Laura, estudante do Colégio Carrão, e exerço as funções de fotógrafa e responsável pelo marketing. Trabalho com dedicação para capturar imagens que representem bem nossos projetos e para criar ações de divulgação que ampliem nosso alcance e engajamento.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Âncora/Fotógrafa
            ],
            [
                'name' => 'Lavinia Gabrielly Machado',
                'username' => 'lavinia_machado',
                'email' => 'laviniamachado91@gmail.com',
                'description' => 'Sou do colégio Conselheiro Carrão, ensino medio, e sou fotografa do Jornal Asahi.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Fotógrafa
            ],
            [
                'name' => 'Lorena Lima Silva',
                'username' => 'lorena_lima',
                'email' => 'lorelima2silva@gmail.com',
                'description' => 'Sou a Lorena, estudante do Colégio Carrão e, no Jornal Asahi, sou a responsável pelas fotos que registram nossos melhores momentos!',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Fotógrafa
            ],
            [
                'name' => 'Maria Alice De Jesus Kicheleski Martire Ezequiel',
                'username' => 'maria_alice',
                'email' => 'mariaalicelinda28@gmail.com',
                'description' => 'Faço parte do colégio Carrão, gosto muito de fazer esporte e da área de comunicação!',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Maria Luiza Teixeira de Lima',
                'username' => 'maria_luiza',
                'email' => 'maria.teixeira.lima@escola.pr.gov.br',
                'description' => 'Adoro comunicar e compartilhar histórias. Sou curiosa e sempre estou em busca de aprender coisas novas.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Mariana Yuka Tsuda Pereira',
                'username' => 'mariana_yuka',
                'email' => 'maryyukaztp@gmail.com',
                'description' => 'Oie, meu nome é Mariana Yuka, gosto de animais e desenhar. E espero poder contribuir com os meus colegas!',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Helloisa Beatriz Máximo',
                'username' => 'helloisa_maximo',
                'email' => 'helloisa.beatriz@escola.pr.gov.br',
                'description' => 'Gosto de comunicação e de trabalhar em equipe. Sempre procuro dar o meu melhor em tudo que faço.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Otávio Lima',
                'username' => 'otavio_lima',
                'email' => 'otavio.lima@escola.pr.gov.br',
                'description' => 'Sou comunicativo e gosto de trabalhar em equipe. Tenho interesse em tecnologia e inovação.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Pietro',
                'username' => 'pietro',
                'email' => 'pietro@escola.pr.gov.br',
                'description' => 'Olá! Sou o Pietro, adoro comunicação e estou sempre em busca de novas histórias para contar.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Sthefany Rosa Antunes Leonel',
                'username' => 'sthefany_rosa',
                'email' => 'sthefany.rosa@escola.pr.gov.br',
                'description' => 'Comunicativa e curiosa, gosto de aprender sobre diferentes assuntos e compartilhar conhecimentos.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Talita Vitória da Silva',
                'username' => 'talita',
                'email' => 'talita.silva@escola.pr.gov.br',
                'description' => 'Repórter mirim com muita energia e vontade de aprender. Adoro fazer novas amizades.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Âncora
            ],
            [
                'name' => 'Júlia Garcia Simonini',
                'username' => 'julia_simonini',
                'email' => 'julia.garcia.simonini@escola.pr.gov.br',
                'description' => 'Adoro comunicação e trabalhar em equipe. Estou sempre em busca de novos conhecimentos.',
                'education_level' => 'Ensino Fundamental',
                'role_id' => 1, // Reporter
            ],
            [
                'name' => 'Ana Matsubara',
                'username' => 'ana_matsubara',
                'email' => 'ana.matsubara@escola.pr.gov.br',
                'description' => 'Gosto de fotografia e de criar conteúdo para redes sociais. Comunicativa e criativa.',
                'education_level' => 'Ensino Médio',
                'role_id' => 1, // Reporter
            ],
        ];

        // Criando usuários com senha padrão
        foreach ($users as $userData) {
            if (!isset($userData['relation'])) {
                // Define um valor padrão com base no nome do usuário ou outro critério
                if (strpos($userData['name'], 'Marketing') !== false) {
                    $userData['relation'] = 'Marketing';
                } elseif (strpos($userData['description'], 'âncora') !== false || 
                          strpos($userData['description'], 'Âncora') !== false) {
                    $userData['relation'] = 'Âncora';
                } else {
                    $userData['relation'] = 'Rua';
                }
            }
            
            // Adicionar dados comuns
            $commonData = [
                'password' => Hash::make('senha1234'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ];
            
            // Atualizar ou criar o usuário
            User::updateOrCreate(
                ['email' => $userData['email']],  // Critério de busca
                array_merge($userData, $commonData)  // Dados para atualização/criação
            );
        }

        // Notifica no console sobre a conclusão
        $this->command->info('Usuários de teste criados com sucesso!');
    }
}
