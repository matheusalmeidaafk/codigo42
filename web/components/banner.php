<containerTeste>
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./assets/images/banner/banner 1.png" class="d-block mx-auto" alt="banner 1">
            </div>

            <div class="carousel-item">
                <img src="./assets/images/banner/banner 2.png" class="d-block mx-auto" alt="banner 2">
            </div>

            <div class="carousel-item">
                <img src="./assets/images/banner/banner 3.png" class="d-block mx-auto" alt="banner 3">
            </div>
        </div>

        <!-- INDICADORES ABAIXO DO BANNER -->
        <div class="carousel-indicators indicadores-banner">
            <button
                type="button"
                data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide-to="0"
                class="active"
                aria-current="true"
                aria-label="Slide 1">
            </button>

            <button
                type="button"
                data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide-to="1"
                aria-label="Slide 2">
            </button>

            <button
                type="button"
                data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide-to="2"
                aria-label="Slide 3">
            </button>
        </div>

        <button
            class="carousel-control-prev"
            type="button"
            data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="prev">

            <img
                src="./assets/images/banner/arrow.png"
                class="seta"
                alt="Previous">

            <span class="visually-hidden">Previous</span>
        </button>

        <button
            class="carousel-control-next"
            type="button"
            data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="next">

            <img
                src="./assets/images/banner/arrow.png"
                class="seta proximo"
                alt="Next">

            <span class="visually-hidden">Next</span>
        </button>

    </div>
</containerTeste>