document.addEventListener("DOMContentLoaded", () => {
    const btnVoltarTopo = document.getElementById("btnVoltarTopo");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            btnVoltarTopo.classList.add("show");
        } else {
            btnVoltarTopo.classList.remove("show");
        }
    });

    btnVoltarTopo.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

    const linksCards = document.querySelectorAll('.topicos-grid a[href^="#"]');
    linksCards.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const targetId = this.getAttribute("href");
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });
});