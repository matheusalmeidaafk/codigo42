<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre e Guia do Site - E-commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <link rel="stylesheet" href="/public/assets/css/sobre.css">
</head>
<body class="bg-light">

<?php include_once(__DIR__ . '/../../components/header.php'); ?>

    <main class="container my-5" style="max-width: 1100px;">
        
        <section class="p-5 mb-5 bg-white rounded-3 shadow-sm text-center border">
            <h1 class="fw-bold fs-2 mb-3 text-dark">Sobre a Plataforma & Guia de Ajuda</h1>
            <p class="text-secondary mx-auto lh-base" style="max-width: 800px;">
                Bem-vindo à nossa página central de orientações! Este espaço foi desenvolvido para oferecer 
                uma visão completa sobre o funcionamento da aplicação, detalhando desde a navegação inicial 
                pela vitrine até os recursos avançados de conta, carrinho persistente e segurança.
            </p>
        </section>

        <section class="mb-5">
            <h2 class="fw-semibold fs-4 mb-4 text-center text-dark">Navegue pelos Tópicos</h2>
            
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#navegacao" class="card h-100 text-decoration-none shadow-sm topico-card p-3">
                        <div class="card-body">
                            <h3 class="fw-bold fs-6 text-primary mb-2">1. Navegação e Vitrine</h3>
                            <p class="text-muted small m-0">Como explorar os banners, destaques e categorias principais na página inicial.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#busca-filtros" class="card h-100 text-decoration-none shadow-sm topico-card p-3">
                        <div class="card-body">
                            <h3 class="fw-bold fs-6 text-primary mb-2">2. Busca e Filtros</h3>
                            <p class="text-muted small m-0">Aprenda a utilizar a barra de pesquisa e refinar os resultados por filtros dinâmicos.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#detalhes-produtos" class="card h-100 text-decoration-none shadow-sm topico-card p-3">
                        <div class="card-body">
                            <h3 class="fw-bold fs-6 text-primary mb-2">3. Produtos e Variações</h3>
                            <p class="text-muted small m-0">Como verificar detalhes, selecionar tamanhos, cores e consultar o estoque.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#carrinho-sidebar" class="card h-100 text-decoration-none shadow-sm topico-card p-3">
                        <div class="card-body">
                            <h3 class="fw-bold fs-6 text-primary mb-2">4. Carrinho de Compras</h3>
                            <p class="text-muted small m-0">Entenda o funcionamento da barra lateral, persistência de dados e cálculo de totais.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#conta-seguranca" class="card h-100 text-decoration-none shadow-sm topico-card p-3">
                        <div class="card-body">
                            <h3 class="fw-bold fs-6 text-primary mb-2">5. Conta e Autenticação</h3>
                            <p class="text-muted small m-0">Informações sobre o sistema unificado de login, cadastro e sessões seguras.</p>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <a href="#suporte-ajuda" class="card h-100 text-decoration-none shadow-sm topico-card p-3">
                        <div class="card-body">
                            <h3 class="fw-bold fs-6 text-primary mb-2">6. Suporte e Canais</h3>
                            <p class="text-muted small m-0">Como entrar em contato com a equipe de suporte técnico e atendimento ao cliente.</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="d-flex flex-column gap-4 mb-5">
            
            <article id="navegacao" class="card p-4 border-0 border-start border-primary border-4 shadow-sm topico-detalhe">
                <h2 class="fw-bold fs-5 text-dark mb-2">1. Navegação e Vitrine</h2>
                <p class="text-secondary m-0 lh-base">A página inicial foi estruturada para proporcionar um acesso rápido e intuitivo aos principais destaques do e-commerce. Nela, você encontra um sistema de banners rotativos dinâmicos com campanhas promocionais ativas, além de seções dedicadas aos produtos mais populares e recomendados do catálogo. Utilize o menu superior fixo para transitar de forma fluida entre as páginas principais.</p>
            </article>

            <article id="busca-filtros" class="card p-4 border-0 border-start border-primary border-4 shadow-sm topico-detalhe">
                <h2 class="fw-bold fs-5 text-dark mb-2">2. Busca e Filtros</h2>
                <p class="text-secondary m-0 lh-base">Para localizar itens específicos com agilidade, disponibilizamos uma barra de pesquisa integrada no cabeçalho de todas as páginas. Ao digitar termos relacionados, o sistema consulta a base de dados em tempo real. Adicionalmente, as páginas de listagem contam com filtros dinâmicos por categorias, faixa de preço e relevância para refinar os seus resultados.</p>
            </article>

            <article id="detalhes-produtos" class="card p-4 border-0 border-start border-primary border-4 shadow-sm topico-detalhe">
                <h2 class="fw-bold fs-5 text-dark mb-2">3. Produtos e Variações</h2>
                <p class="text-secondary m-0 lh-base">Ao selecionar qualquer item na vitrine, você será direcionado à página de detalhes do produto. Nela é possível inspecionar múltiplas imagens em alta resolução, ler descrições técnicas aprofundadas e escolher variações fundamentais como tamanhos, cores e acabamentos disponíveis, garantindo que o produto atenda exatamente às suas expectativas antes de adicioná-lo à compra.</p>
            </article>

            <article id="carrinho-sidebar" class="card p-4 border-0 border-start border-primary border-4 shadow-sm topico-detalhe">
                <h2 class="fw-bold fs-5 text-dark mb-2">4. Carrinho de Compras (carrinhoSideBar)</h2>
                <p class="text-secondary m-0 lh-base">O nosso carrinho foi projetado no formato de barra lateral deslizante (sidebar) para otimizar a experiência do usuário. Ele conta com persistência local via <code>localStorage</code>, assegurando que os itens selecionados permaneçam salvos mesmo se você atualizar a página. O componente realiza atualizações dinâmicas de quantidade por item e calcula o subtotal e o valor total instantaneamente a cada alteração.</p>
            </article>

            <article id="conta-seguranca" class="card p-4 border-0 border-start border-primary border-4 shadow-sm topico-detalhe">
                <h2 class="fw-bold fs-5 text-dark mb-2">5. Conta e Autenticação</h2>
                <p class="text-secondary m-0 lh-base">Centralizamos o acesso dos usuários em uma página unificada, permitindo alternar facilmente entre o fluxo de login e o de registro de nova conta. O sistema utiliza padrões seguros de criptografia e gerenciamento de sessões via tokens (como JWT no backend), garantindo que suas informações pessoais e dados de navegação permaneçam estritamente protegidos.</p>
            </article>

            <article id="suporte-ajuda" class="card p-4 border-0 border-start border-primary border-4 shadow-sm topico-detalhe">
                <h2 class="fw-bold fs-5 text-dark mb-2">6. Suporte e Canais</h2>
                <p class="text-secondary m-0 lh-base">Caso encontre qualquer inconsistência técnica, instabilidade ou tenha dúvidas comerciais sobre pedidos e entregas, nossa equipe de suporte operacional está preparada para auxiliar. Utilize os rodapés das páginas para acessar links rápidos de ajuda ou consulte os canais oficiais de atendimento listados no sistema.</p>
            </article>

        </section>
    </main>

    <button id="btnVoltarTopo" class="btn-voltar-topo" title="Retornar ao topo">
        ↑
    </button>

    <?php include_once(__DIR__ . '/../../components/footerHome.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sobre.js"></script>
</body>
</html>