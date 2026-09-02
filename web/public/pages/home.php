<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Código 42</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/produto.css">
</head>

<body>
    <?php require_once __DIR__ . '/../../components/header.php'; ?>

    <main>

        <section class="container-fluid px-3">

            <div class="d-flex justify-content-between align-items-center py-3">

                <h2 class="display-5 fw-normal m-0">
                    PRODUTOS
                </h2>

                <div class="d-flex gap-3">

                    <button class="btn btn-outline-dark px-3">
                        TUDO
                    </button>

                    <button class="btn btn-outline-dark px-3">
                        CAMISETAS
                    </button>

                    <button class="btn btn-outline-dark px-3">
                        CANECAS
                    </button>

                    <button class="btn btn-outline-dark px-3">
                        ADESIVOS
                    </button>

                </div>

            </div>


            <div id="lista-produtos" class="row g-3 mb-3">


            </div>

        </section>

    </main>

    <?php require_once __DIR__ . '/../../components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/produtos.js"></script>

</body>

</html>