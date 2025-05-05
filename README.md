# Jornal Asahi

## Visão Geral

O Jornal Asahi é um portal jornalístico digital desenvolvido para jovens da comunidade de Assaí, oferecendo uma plataforma onde repórteres jovens podem publicar notícias, entrevistas e conteúdo multimídia para sua comunidade.

## Funcionalidades Principais

- **Sistema de Notícias**: Criação, edição, aprovação e publicação de notícias
- **Gerenciamento de Imagens**: Upload e organização de imagens para notícias com galeria integrada
- **Transmissões ao Vivo**: Sistema para gerenciar e destacar transmissões ao vivo
- **Entrevistas**: Publicação de entrevistas em vídeo com integração ao YouTube
- **Sistema de Perfis**: Perfis de usuários com diferentes níveis de permissão

## Estrutura de Usuários

O sistema possui três níveis de acesso:

1. **Repórteres** (role_id: 1): Podem criar e editar notícias
2. **Aprovadores** (role_id: 2): Podem aprovar e publicar notícias
3. **Administradores** (role_id: 3): Acesso completo ao sistema

## Tecnologias Utilizadas

- **Backend**: PHP/Laravel
- **Banco de Dados**: PostgreSQL
- **Editor de Conteúdo**: CKEditor 5
- **Armazenamento de Arquivos**: Laravel Storage

## Principais Componentes

### Modelos

- `News`: Gerencia notícias e seus estados de aprovação
- `User`: Controla usuários e níveis de permissão
- `NewsImage`: Gerencia imagens associadas às notícias
- `Interview`: Administra entrevistas em vídeo
- `LiveStream`: Controla transmissões ao vivo

### Controladores

- `NewsController`: Gerencia o fluxo de criação, edição e aprovação de notícias
- `GalleryController`: Controla a exibição de imagens na galeria
- `ProfileController`: Gerencia perfis de usuário e interações
- `HomeController`: Controla a exibição da página inicial
- `DashboardController`: Gerencia o painel administrativo

## Fluxo de Publicação

1. Repórteres criam notícias com texto e imagens
2. As notícias ficam pendentes para aprovação
3. Aprovadores ou administradores revisam e publicam
4. O conteúdo aprovado aparece no site para o público

## Recursos Especiais

- **Extração automática de imagens**: O sistema extrai automaticamente imagens do conteúdo e as organiza na galeria
- **Interações entre usuários**: Sistema para comentários e curtidas em perfis
- **Galeria de imagens**: Mostra imagens de notícias organizadas por tipo (capa ou conteúdo)

## Equipe de Repórteres

O sistema foi projetado para jovens repórteres de diferentes níveis educacionais (Ensino Fundamental e Médio) da comunidade de Assaí, proporcionando uma experiência jornalística prática e educativa.

---

Este projeto tem como objetivo dar voz aos jovens assaienses, oferecendo uma plataforma moderna para compartilhamento de histórias locais, desenvolvimento de habilidades jornalísticas e engajamento comunitário.
