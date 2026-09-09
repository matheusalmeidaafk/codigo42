<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">

    <div id="carouselBanners" class="carousel-inner">
    </div>

    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="prev">

        <img src="./assets/images/banner/arrow.png"
             class="seta"
             alt="Previous">

        <span class="visually-hidden">Previous</span>
    </button>

    <button class="carousel-control-next"
            type="button"
            data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="next">

        <img src="./assets/images/banner/arrow.png"
             class="seta proximo"
             alt="Next">

        <span class="visually-hidden">Next</span>
    </button>

</div>

<script>
    async function carregarBanners() {
        try {

            const response = await fetch("http://localhost:8080/banners");

            if (!response.ok) {
                throw new Error("Erro ao buscar banners.");
            }

            const banners = await response.json();

            const carousel = document.getElementById("carouselBanners");

            carousel.innerHTML = "";

            if (banners.length === 0) {
                carousel.innerHTML = `
                    <div class="carousel-item active">
                        <p class="text-center">
                            Nenhum banner disponível.
                        </p>
                    </div>
                `;

                return;
            }

            banners.forEach((banner, index) => {

                const item = document.createElement("div");

                item.classList.add("carousel-item");

                if (index === 0) {
                    item.classList.add("active");
                }

                const imagem = document.createElement("img");

                imagem.src = banner.bannerUrl;
                imagem.classList.add("d-block", "mx-auto", "w-75");
                imagem.alt = "Banner";

                item.appendChild(imagem);

                carousel.appendChild(item);
            });

        } catch (error) {

            console.error("Erro ao carregar banners:", error);

        }
    }

    carregarBanners();
</script>
